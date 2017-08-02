<?php

require_once "db_settings.php";

define("DEPOSIT_TRANS",  1);
define("RENTAL_TRANS",   2);
define("LATE_FEE_TRANS", 3);


// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->select_db("bookclub");



function GetTransactions() {
    global $conn;

    $sql = "
        SELECT  Transaction.Transaction_ID
               ,Transaction.Amount
               ,Transaction.Transaction_Date
               ,Transaction.Copy_No
               ,Transaction.Book_ID
               ,Transaction.Student_ID
               ,Student.Name AS Student_Name
               ,Book.Book_ID
               ,Book.Title AS Book_Title
               ,Book_Copy.Copy_No AS Book_Copy_No
               ,Transaction_Type.Name AS Type_Name
        FROM Transaction
        LEFT JOIN Book ON Book.Book_ID = Transaction.Book_ID
        LEFT JOIN Book_Copy ON Book_Copy.Book_ID = Transaction.Book_ID AND Book_Copy.Copy_No = Transaction.Copy_No 
        JOIN Student ON Student.Student_ID = Transaction.Student_ID
        JOIN Transaction_Type ON Transaction_Type.Transaction_Type_ID = Transaction.Transaction_Type_ID
    ";

    $results = $conn->query($sql);

    if (!$results) {
        throw new Exception("Database Error [{$conn->errno}] {$conn->error}");
    }

    if($results->num_rows > 0) {
        return $results->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

function GetBooks() {
    global $conn;

    $sql = "SELECT Book_ID, ISBN, Title, Author, Edition FROM Book";

    $results = $conn->query($sql);

    if (!$results) {
        throw new Exception("Database Error [{$conn->errno}] {$conn->error}");
    }

    $rows = array(); // If no books, return empty array.

    if($results->num_rows > 0) {
        foreach($results->fetch_all(MYSQLI_ASSOC) as $row) {
            $row['Copies'] = count(GetBookCopies($row['Book_ID'], true));
            $row['Available'] = count(GetBookCopies($row['Book_ID'], false));
            $rows[] = $row;
        }
    }

    return $rows;
}

function GetBook($book_id) {
    global $conn;

    $sql = "SELECT Book_ID, ISBN, Title, Author, Edition FROM Book WHERE Book_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    if ($stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
    }

    return $stmt->get_result()->fetch_assoc();
}

/**
 * @param $book_id
 * @param $all_copies When true, return all copies, else return only available copies (not checked out)
 */
function GetBookCopies($book_id, $all_copies) {
    global $conn;

    $sql = "SELECT Book_ID, Copy_No, `Condition`, Rental_Fee FROM Book_Copy WHERE Book_ID = ?";

    if (!$all_copies)
        $sql .= " AND Copy_No NOT IN (SELECT Copy_No FROM Student_Book_Copy_Check_Out WHERE Book_ID = ? AND Returned_Date IS NULL)";

    $stmt = $conn->prepare($sql);
    if ($all_copies)
        $stmt->bind_param("i", $book_id);
    else
        $stmt->bind_param("ii", $book_id, $book_id);

    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        return $results->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

function GetCheckedOutBooks() {
    global $conn;

    $sql = "
        SELECT	 Book_Copy.Book_ID
                ,Book_Copy.Copy_No
                ,Book_Copy.`Condition`
                ,Book_Copy.Rental_Fee 
                ,co.Check_Out_Date
                ,co.Rental_Periods
                ,DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH) AS Due_Date
                ,GREATEST(0,DATEDIFF(NOW(), DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH))) as Days_Late
                ,Student.Student_ID
                ,Student.Name
        FROM Book_Copy 
        JOIN Student_Book_Copy_Check_Out co ON co.Book_ID = Book_Copy.Book_ID AND co.Copy_No = Book_Copy.Copy_No AND co.Returned_Date IS NULL
        JOIN Student ON Student.Student_ID = co.Student_ID
        ORDER BY Book_Copy.Book_ID, Book_Copy.Copy_No
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $book_id);

    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        return $results->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

function GetCheckedOutCopies($book_id) {
    global $conn;

    $sql = "
        SELECT	 Book_Copy.Book_ID
                ,Book_Copy.Copy_No
                ,Book_Copy.`Condition`
                ,Book_Copy.Rental_Fee 
                ,co.Check_Out_Date
                ,co.Rental_Periods
                ,DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH) AS Due_Date
                ,GREATEST(0,DATEDIFF(NOW(), DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH))) as Days_Late
                ,Student.Student_ID
                ,Student.Name
        FROM Book_Copy 
        JOIN Student_Book_Copy_Check_Out co ON co.Book_ID = Book_Copy.Book_ID AND co.Copy_No = Book_Copy.Copy_No AND co.Returned_Date IS NULL
        JOIN Student ON Student.Student_ID = co.Student_ID
        WHERE Book_Copy.Book_ID = ?
        ORDER BY Copy_No
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $book_id);

    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        return $results->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

function GetLateCopies() {
    global $conn;

    $sql = "
        SELECT	 Book_Copy.Book_ID
                ,Book_Copy.Copy_No
                ,Book_Copy.`Condition`
                ,Book_Copy.Rental_Fee 
                ,co.Check_Out_Date
                ,co.Rental_Periods
                ,DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH) AS Due_Date
                ,GREATEST(0,DATEDIFF(NOW(), DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH))) as Days_Late
                ,Student.Student_ID
                ,Student.Name
                ,Book.Title
                ,Book.ISBN
        FROM Book_Copy 
        JOIN Student_Book_Copy_Check_Out co ON co.Book_ID = Book_Copy.Book_ID AND co.Copy_No = Book_Copy.Copy_No AND co.Returned_Date IS NULL
        JOIN Student ON Student.Student_ID = co.Student_ID
        JOIN Book ON Book.Book_ID = Book_Copy.Book_ID
        WHERE GREATEST(0,DATEDIFF(NOW(), DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH))) > 0
        ORDER BY Book_Copy.Book_ID, Copy_No
    ";

    $stmt = $conn->prepare($sql);


    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        return $results->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

function GetStudentCheckedOutBooks($student_id) {
    global $conn;

    $sql = "
        SELECT	 Book_Copy.Book_ID
                ,Book_Copy.Copy_No
                ,Book_Copy.`Condition`
                ,Book_Copy.Rental_Fee 
                ,co.Check_Out_Date
                ,co.Rental_Periods
                ,DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH) AS Due_Date
                ,GREATEST(0,DATEDIFF(NOW(), DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH))) as Days_Late
                ,Student.Student_ID
                ,Student.Name
        FROM Book_Copy 
        JOIN Student_Book_Copy_Check_Out co ON co.Book_ID = Book_Copy.Book_ID AND co.Copy_No = Book_Copy.Copy_No AND co.Returned_Date IS NULL
        JOIN Student ON Student.Student_ID = co.Student_ID
        WHERE Student.Student_ID = ?
        ORDER BY Book_Copy.Book_ID, Copy_No
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);

    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        return $results->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

function GetBookCopy($book_id, $copy_no) {
    global $conn;

    $sql = "SELECT Book_ID, Copy_No, `Condition`, Rental_Fee FROM Book_Copy WHERE Book_ID = ? AND Copy_No = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $book_id, $copy_no);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    return $stmt->get_result()->fetch_assoc();
}

function GetStudents() {
    global $conn;

    $sql = "SELECT Student_ID, Deposit, Balance, Name, Address, Phone_Number FROM Student";

    $results = $conn->query($sql);

    if (!$results) {
        throw new Exception("Database Error [{$conn->errno}] {$conn->error}");
    }

    $rows = array(); // If no books, return empty array.

    if($results->num_rows > 0) {
        foreach($results->fetch_all(MYSQLI_ASSOC) as $row) {
            $row['Books_Out'] = count(GetStudentCheckedOutBooks($row['Student_ID']));
            $rows[] = $row;
        }
    }

    return $rows;
}

function AddBook($title, $author, $isbn, $edition, $condition, $rental_fee) {
    global $conn;

    $sql = "INSERT INTO Book(ISBN, Title, Author, Edition) VALUES(?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $isbn, $title, $author, $edition);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $book_id = $conn->insert_id;
    AddBookCopy($book_id, $condition, $rental_fee);
}

function AddBookCopy($book_id, $condition, $rental_fee) {
    global $conn;

    $sql = "INSERT INTO Book_Copy(Book_ID, `Condition`, Rental_Fee) 
                        VALUES(?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $book_id, $condition, $rental_fee);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }
}


function AddStudent($name, $phone_number, $address) {
    global $conn;

    $amount = 300.00;
    $deposit = true;

    $sql = "INSERT INTO Student(Name, Phone_Number, Address, Balance, Deposit) VALUES(?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $name, $phone_number, $address, $amount, $deposit);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $student_id = $conn->insert_id;

    CreateTransaction($student_id, $amount, DEPOSIT_TRANS);
}

function StudentQuit($student_id) {
    global $conn;

    $amount = -300.00;

    $sql = "UPDATE Student SET Balance = 0, Deposit = 0 WHERE Student_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    CreateTransaction($student_id, $amount, DEPOSIT_TRANS);
}

function StudentRejoin($student_id) {
    global $conn;

    $amount = 300.00;

    $sql = "UPDATE Student SET Balance = 300, Deposit = 1 WHERE Student_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    CreateTransaction($student_id, $amount, DEPOSIT_TRANS);
}

function CheckoutBook($book_id, $copy_no, $student_id, $rental_periods) {
    global $conn;

    $sql = "INSERT INTO Student_Book_Copy_Check_Out(Book_ID, Copy_No, Student_ID, Rental_Periods, Check_Out_Date) 
                        VALUES(?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dddd", $book_id, $copy_no, $student_id, $rental_periods);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $copy = GetBookCopy($book_id, $copy_no);

    CreateTransaction($student_id, $copy['Rental_Fee'] * $rental_periods, RENTAL_TRANS, $book_id, $copy_no);
}

function Checkin_Book($book_id, $copy_no) {
    global $conn;

    $sql = "
        SELECT	 Book_Copy.Book_ID
                ,Book_Copy.Copy_No
                ,Book_Copy.`Condition`
                ,Book_Copy.Rental_Fee 
                ,co.Check_Out_Date
                ,co.Rental_Periods
                ,DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH) AS Due_Date
                ,GREATEST(0,DATEDIFF(NOW(), DATE_ADD(co.Check_Out_Date, INTERVAL co.Rental_Periods MONTH))) as Days_Late
                ,Student.Student_ID
                ,Student.Name
        FROM Book_Copy 
        JOIN Student_Book_Copy_Check_Out co ON co.Book_ID = Book_Copy.Book_ID AND co.Copy_No = Book_Copy.Copy_No AND co.Returned_Date IS NULL
        JOIN Student ON Student.Student_ID = co.Student_ID
        WHERE Book_Copy.Book_ID = ? AND Book_Copy.Copy_No = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dd", $book_id, $copy_no);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    $copy = $stmt->get_result()->fetch_assoc();

    $sql = "
        UPDATE Student_Book_Copy_Check_Out SET Returned_Date = NOW() 
        WHERE Book_ID = ? AND Copy_No = ? AND Returned_Date IS NULL
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dd", $book_id, $copy_no);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }

    if($copy['Days_Late'] > 0) // Process Late Fees Paid
        CreateTransaction($copy['Student_ID'], $copy['Days_Late'], LATE_FEE_TRANS, $book_id, $copy_no);
}

function CreateTransaction($student_id, $amount, $transaction_type, $book_id = null, $copy_no = null) {
    global $conn;


    $sql = "INSERT INTO Transaction(Student_ID, Amount, Transaction_Type_ID, Transaction_Date, Book_ID, Copy_No) 
                        VALUES(?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idiii", $student_id, $amount, $transaction_type, $book_id, $copy_no);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
        die($conn->error);
    }
}
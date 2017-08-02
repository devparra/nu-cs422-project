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

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    if ($stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
    }

    $results = $stmt->get_result();

    if($results->num_rows > 0) {
        return $results->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
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
            $row['Books_Out'] = 0; // TODO Function here to calculate
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
    }
}


function AddStudent($name, $phone_number, $address) {
    global $conn;

    $amount = 300.00;
    $type = DEPOSIT_TRANS;
    $deposit = true;

    $sql = "INSERT INTO Student(Name, Phone_Number, Address, Balance, Deposit) VALUES(?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $name, $phone_number, $address, $amount, $deposit);
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
    }

    $student_id = $conn->insert_id;


    $sql = "INSERT INTO Transaction(Student_ID, Amount, Transaction_Type_ID, Transaction_Date) 
                        VALUES(?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idi", $student_id, $amount, $type); // $300 Deposit Paid
    if (!$stmt->execute()){
        error_log ("MySQLi Error Message: ". $conn->error);
    }
}

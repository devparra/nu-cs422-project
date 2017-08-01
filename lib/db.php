<?php

require_once "db_settings.php";

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

    $ret = array();

    $results = $conn->query($sql);

    if (!$results) {
        throw new Exception("Database Error [{$conn->errno}] {$conn->error}");
    }

    $rows = array(); // If no books, return empty array.

    if($results->num_rows > 0) {
        foreach($results->fetch_all(MYSQLI_ASSOC) as $row) {
            $row['Copies'] = 0; // TODO Function here to calculate
            $row['Available'] = 0; // TODO Function here to calculate
            $rows[] = $row;
        }
    }

    return $rows;
}

function GetStudents() {
    global $conn;

    $sql = "SELECT Student_ID, Deposit, Balance, Name, Address, Phone_Number FROM Student";

    $ret = array();

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

function AddBook($title, $author, $isbn, $edition) {
    global $conn;

    $sql = "INSERT INTO Book(ISBN, Title, Author, Edition) VALUES(?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $isbn, $title, $author, $edition);
    $result = $stmt->execute();
}


function AddStudent($name, $phone_number, $address) {
    global $conn;

    $sql = "INSERT INTO Student(Name, Phone_Number, Address) VALUES(?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $phone_number, $address);
    $result = $stmt->execute();
}

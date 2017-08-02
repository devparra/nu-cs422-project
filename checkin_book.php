<?php
/**
 * CS442 Class Project
 * Authors: Vincent Castellano, Matthew Para, Rudy Garcia, Daniel LaVergne, Lukas Simonis
 * Created: 07/24/2017
 *
 * Text Book Rental Application
 **/

/* Page Configuration */
$page = Array();
$page['subtitle'] = "Add Book";

require_once "lib/db.php";

if(isset($_POST['book_id'])) {
    CheckinBook(
        $_POST['book_id'],
        $_POST['copy_no'],
        $_POST['student_id']);
    header("Location: /books.php");
} else {
    $book = GetBook($_GET['book_id']);
    $book_copies = GetCheckedOutCopies($_GET['book_id']);

    require "header.php"; ?>

<div class="row">
    <div class="medium-8 column">
        <h2><?=$book['Title']?></h2>
    </div>
    <div class="medium-4 column">
        <h4><?=$book['Author']?></h4>
    </div>
</div>
<div class="row">
    <div class="medium-8 column">

    </div>
    <div class="medium-4 column">
        <h5>ISBN13:<?=$book['ISBN']?></h5>
    </div>
</div>

<div class="row callout">
    <div class="large-12 columns">
        <div class="row">
            <div class="large-12 columns">
                <h1>Check-in Book</h1>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <table>
                    <thead>
                        <th>Copy Number</th>
                        <th>Student</th>
                        <th>Checkout Date</th>
                        <th>Due Date</th>
                        <th>Late Fees</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php foreach($book_copies as $copy) { ?>
                            <tr>
                                <td><?=$copy['Copy_No']?></td>
                                <td><?=$copy['Name']?></td>
                                <td><?=$copy['Check_Out_Date']?></td>
                                <td><?=$copy['Due_Date']?></td>
                                <td>$<?=$copy['Days_Late']?></td>
                                <td><a href="/checkin_book_process.php?book_id=<?=$copy['Book_ID']?>&cpy_no=<?=$copy['Copy_No']?>">Check-In</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once "footer.php";
}


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
    CheckoutBook(
        $_POST['book_id'],
        $_POST['copy_no'],
        $_POST['student_id'],
        $_POST['rental_periods']);
    header("Location: /books.php");
} else {
    $students = GetStudents();
    $book = GetBook($_GET['book_id']);
    $book_copies = GetBookCopies($_GET['book_id'], false);

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
                <h1>Checkout Book</h1>
            </div>
        </div>
        <form action="checkout_book.php" method="post">
            <div class="row">
                <div class="large-12 columns">
                    <label>Book Copy To Checkout
                        <select name="copy_no" required>
                            <?php foreach($book_copies as $copy) { ?>
                                <option value="<?=$copy['Copy_No']?>">$<?=$copy['Rental_Fee']?>: <?=$copy['Condition']?></option>
                            <?php } ?>
                        </select>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <label>Student Checking Out
                        <select name="student_id" required>
                            <?php foreach($students as $student) { if(!$student['Deposit']) continue; ?>
                                <option value="<?=$student['Student_ID']?>"><?=$student['Student_ID']?>: <?=$student['Name']?></option>
                            <?php } ?>
                        </select>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-4 columns">
                    <label>Months to Rent
                        <input type="number" min="1" max="12" placeholder="# Months" id="rental_periods" name="rental_periods" required value="1">
                    </label>
                </div>
<!--		
		<div class="large-2 columns">
                	<label>Rental Fee<h4 id="rental_fee">x $<?=$copy['Rental_Fee']?></h4></label>
		</div>
		<div class="large-2 columns">
                	<label>Total Due<h4 id="total_due">$<?=$copy['Rental_Fee']?></h4></label>
		</div>
-->
                <div class="large-4 columns">
                    <input type="hidden" name="book_id" value="<?=$book['Book_ID']?>">
                    <button class="button float-right" name="Send" type="submit">Checkout Book</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once "footer.php";
}


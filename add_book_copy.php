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

if(isset($_POST['condition'])) {
    AddBookCopy(
        $_POST['book_id'],
        $_POST['condition'],
        $_POST['rental_fee']);
    header("Location: /books.php");
} else {
    $book = GetBook($_GET['book_id']);
    require "header.php"; ?>


<div class="row callout">
    <div class="large-12 columns">
        <div class="row">
            <div class="large-12 columns">
                <h1>Add Book Copy</h1>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <h5><?=$book['Title']?></h5>
            </div>
        </div>
        <form action="add_book_copy.php" method="post">
            <div class="row">
                <div class="large-10 columns">
                    <label>Condition
                        <input type="text" placeholder="New, Used, Damaged, Marked, Etc" name="condition" required/>
                    </label>
                </div>
                <div class="large-2 columns">
                    <label>Rental Fee
                        <input type="text" placeholder="0.00"  name="rental_fee" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <input type="hidden" name="book_id" value="<?=$_GET['book_id']?>">
                    <button class="button float-right"name="Send" type="submit">Add Book Copy</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once "footer.php"; } ?>

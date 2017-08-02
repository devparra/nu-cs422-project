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

if(isset($_POST['title'])) {
    AddBook($_POST['title'],
        $_POST['author'],
        $_POST['isbn'],
        $_POST['edition'],
        $_POST['condition'],
        $_POST['rental_fee']);
    header("Location: /books.php");
} else {

    require "header.php"; ?>


<div class="row callout">
    <div class="large-12 columns">
        <div class="row">
            <div class="large-12 columns">
                <h1>Add Book</h1>
            </div>
        </div>
        <form action="add_book.php" method="post">
            <div class="row">
                <div class="large-12 columns">
                    <label>Title
                        <input type="text" placeholder="Book Title" name="title" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <label>Author(s)
                        <input type="text" placeholder="Author(s), comma separated" name="author" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-8 columns">
                    <label>Edition
                        <input type="text" placeholder="Edition and Region" name="edition" required/>
                    </label>
                </div>
                <div class="large-4 columns">
                    <label>ISBN13
                        <input type="text" placeholder="ISBN 13" maxlength="13" name="isbn" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <h4>Book Copy Details</h4>
                </div>
            </div>
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
                    <button class="button float-right"name="Send" type="submit">Add Book</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once "footer.php"; } ?>

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
$page['subtitle'] = "Home Page"
?>

<?php require "header.php"; ?>

<?php if (!$page['logged_in']){ ?>

    <div class="row">
        <div class="large-12 small-12 column large-centered small-centered large-offset-1 small-offset-1">
            <div class="callout secondary">
                <h2>Please Login to Administer Textbook System</h2>
                <p>You can login from the upper right hand link in the menu bar.</p>
            </div>
        </div>
    </div>

<?php } else {
    header("Location: /students.php"); // no homepage needed, just send to students.
} ?>


<?php require_once "footer.php"; ?>
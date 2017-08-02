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
$page['subtitle'] = "Add Student";

require_once("lib/db.php");

if(isset($_POST['name'])) {
    AddStudent($_POST['name'],
        $_POST['phone_number'],
        $_POST['address']);
    header("Location: /students.php");
} else {

    require "header.php"; ?>


<div class="row callout">
    <div class="large-12 columns">
        <div class="row">
            <div class="large-12 columns">
                <h1>Add Student</h1>
            </div>
        </div>
        <form action="add_student.php" method="post">
            <div class="row">
                <div class="large-12 columns">
                    <label>Name
                        <input type="text" placeholder="Student Full Name" name="name" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-8 columns">
                    <label>Address
                        <input type="text" placeholder="Address" name="address" required/>
                    </label>
                </div>
                <div class="large-4 columns">
                    <label>Phone Number
                        <input type="tel" placeholder="Phone Number" name="phone_number" required/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-10 columns">
                    <p><small><strong>By adding this student, you confirm you have collected a $300 deposit from the student.</strong></small></p>
                </div>
                <div class="large-2 columns">
                    <button class="button float-right"name="Send" type="submit">Add Student</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php 

require_once "footer.php"; }
?>

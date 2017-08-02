<?php
/**
 * CS442 Class Project
 * Authors: Vincent Castellano, Matthew Para, Rudy Garcia, Daniel LaVergne, Lukas Simonis
 * Created: 07/24/2017
 *
 * Text Book Rental Application
 **/

require_once 'lib/db.php';


session_start();

/* Page Configuration */
$page['logged_in'] = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];


?>
<html class="no-js" lang="en">
<head>
    <title>Textbook Rental: <?=$page['subtitle']?></title>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if(isset($page['redirect'])) echo '<META HTTP-EQUIV=REFRESH CONTENT="3; index.php">'; ?>

    <!-- Foundation -->
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/vendor/jquery.dataTables.min.js"></script>
    <script src="js/vendor/dataTables.foundation.min.js"></script>

    <link rel="stylesheet" href="css/app.css" />
    <script src="js/app.js"></script>
</head>

<body>

<div class="top-bar">

    <div class="top-bar-left">
        <ul class="dropdown menu" data-dropdown-menu>
            <li class="menu-text">CS422 Textbook Club</li>
            <?php if($page['logged_in']) { ?>
            <li>
                <a href="students.php">Student Management</a>
            </li>
            <li><a href="books.php">Book Management</a>
            </li>
            <li><a href="#">Reports</a>
                <ul class="menu vertical">
                    <li><a href="late_book_report.php">Late Book Report</a></li>
                    <li><a href="transactions_report.php">Transaction Report</a></li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="top-bar-right">
        <ul class="menu">
            <?php
                if($page['logged_in'])
                    echo "<a href='logout.php'>Logout</a>";
                else
                    echo "<a href='login.php'>Login</a>";
            ?>
        </ul>
    </div>
</div>

<div class="row"></div> <!-- Empty row -->

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
$page['subtitle'] = "Login";
$page['redirect'] = true;

require_once "header.php";

$_SESSION['logged_in'] = true;
?>
<div class="row">
    <div class="large-12 small-12 column large-centered small-centered large-offset-1 small-offset-1">
        <div class="callout secondary">
            <h2>Logging In</h2>
            <p>This is a simulation of a user account system, which is out of scope of this project.</p>
            <p>You will be automatically logged in.</p>
        </div>
    </div>
</div>
<?php
require_once "footer.php";
?>

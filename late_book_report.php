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
$page['subtitle'] = "Transaction List"
?>

<?php require "header.php"; ?>


<?php
    $book_copies = GetLateCopies();
?>
    <div class="row">
        <div class="large-12 small-12 column">
            <h1>Late Book Report</h1>
        </div>
    </div>
    <div class="row">
        <div class="large-12 small-12 column">
            <table id="late">
                <thead>
                <th>Title</th>
                <th>ISBN</th>
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
                        <td><?=$copy['Title']?></td>
                        <td><?=$copy['ISBN']?></td>
                        <td><?=$copy['Copy_No']?></td>
                        <td><?=$copy['Name']?></td>
                        <td><?=$copy['Check_Out_Date']?></td>
                        <td><?=$copy['Due_Date']?></td>
                        <td>$<?=$copy['Days_Late']?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<script type="application/javascript">
    $(document).ready(function() {
        $('#late').DataTable();
    } );
</script>

<?php require_once "footer.php"; ?>
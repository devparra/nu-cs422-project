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
    $transactions = GetTransactions();
?>
    <div class="row">
        <div class="large-12 small-12 column">
            <h1>Transactions</h1>
        </div>
    </div>
    <div class="row">
        <div class="large-12 small-12 column">
            <table id="transactions">
                <thead>
                    <th>Transaction ID</th>
                    <th>Student Name</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Transaction Date</th>
                    <th>Book</th>
                    <th>Copy Number</th>
                </thead>
                <tbody>
                    <?php foreach($transactions as $trans) { ?>
                        <tr>
                            <td><?=$trans['Transaction_ID']?></td>
                            <td><?=$trans['Student_Name']?></td>
                            <td><?=$trans['Type_Name']?></td>
                            <td><?=$trans['Amount']?></td>
                            <td><?=$trans['Transaction_Date']?></td>
                            <td><a href="book_copies.php?book_id=<?=$trans['Book_ID']?>"><?=$trans['Book_Title']?></a></td>
                            <td><?=$trans['Book_Copy_No']?></td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>

<script type="application/javascript">
    $(document).ready(function() {
        $('#transactions').DataTable();
    } );
</script>

<?php require_once "footer.php"; ?>
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
$page['subtitle'] = "Book List"
?>

<?php require "header.php"; ?>


<?php
    $book_copies = GetBookCopies($_GET['book_id'], true);
    $book = GetBook($_GET['book_id']);
?>

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
    <div class="row">
        <div class="large-12 small-12 column">
            <table>
                <thead>
                    <th>Copy Number</th>
                    <th>Condition</th>
                    <th>Rental Fee</th>
                    <?php /* <!-- Would be great to add this but it's beyond the scope/spec and we don't have time -->
                    <th>Times Rented</th>
                    <th>Currently Renting</th>
                    <th>Due Back</th> */ ?>
                </thead>
                <tbody>
                    <?php foreach($book_copies as $copy) { ?>
                        <tr>
                            <td><?=$copy['Copy_No']?></td>
                            <td><?=$copy['Condition']?></td>
                            <td><?=$copy['Rental_Fee']?></td>
                            <?php /*
                            <td><?=$copy['Times_Rented']?></td>
                            <td><?=$copy['Currently_Renting']?'Y':'N'?></td>
                            <td><?=$copy['Due_Date']?></td>>
                            */ ?>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="large-12 small-12 column float-right text-right">
            <a href="add_book_copy.php?book_id=<?=$book['Book_ID']?>" class="button">Add Copy</a>
        </div>
    </div>



<?php require_once "footer.php"; ?>


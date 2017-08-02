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
    $books = GetBooks();
?>

    <div class="row expanded ">
        <div class="large-12 small-12 column">
            <table>
                <thead>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Edition</th>
                    <th>Copies</th>
                    <th>Available</th>
                    <th></th>
                </thead>
                <tbody>
                    <?php foreach($books as $book) { ?>
                        <tr>
                            <td><?=$book['Title']?></td>
                            <td><?=$book['Author']?></td>
                            <td><?=$book['ISBN']?></td>
                            <td><?=$book['Edition']?></td>
                            <td><a href="book_copies.php?book_id=<?=$book['Book_ID']?>"><?=$book['Copies']?></a></td>
                            <td><?=$book['Available']?></td>
                            <td>
                                <?php if($book['Available'] > 0) { ?><a href="checkout_book.php?book_id=<?=$book['Book_ID']?>" class="button small">Checkout</a><?php } ?>
                                <?php if($book['Available'] < $book['Copies']) {?><a href="checkin_book.php?book_id=<?=$book['Book_ID']?>" class="alert button small">Check-in</a><?php } ?>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row expanded">
        <div class="large-12 small-12 column float-right text-right">
            <a href="add_book.php" class="button">Add Book</a>
        </div>
    </div>



<?php require_once "footer.php"; ?>
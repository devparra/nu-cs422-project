<?php
/**
 * CS442 Class Project
 * Authors: Vincent Castellano, Matthew Para, Rudy Garcia, Daniel LaVergne, Lukas Simonis
 */

require_once 'lib/db.php';

Checkin_Book($_GET['book_id'], $_GET['cpy_no']);
header("Location: /books.php");

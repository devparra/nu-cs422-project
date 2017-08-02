<?php
/**
 * CS442 Class Project
 * Authors: Vincent Castellano, Matthew Para, Rudy Garcia, Daniel LaVergne, Lukas Simonis
 */

require_once 'lib/db.php';

StudentQuit($_GET['student_id']);
header("Location: /students.php");

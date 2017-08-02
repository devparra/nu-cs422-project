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
    $students = GetStudents();
?>

    <div class="row">
        <div class="large-12 small-12 column">
            <table>
                <thead>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Num</th>
                    <th>Balance</th>
                    <th>Deposit Paid?</th>
                    <th>Books Checked Out</th>
                    <th></th>
                </thead>
                <tbody>
                    <?php foreach($students as $student) { ?>
                        <tr>
                            <td><?=$student['Name']?></td>
                            <td><?=$student['Address']?></td>
                            <td><?=$student['Phone_Number']?></td>
                            <td><?=$student['Balance']?></td>
                            <td><?=$student['Deposit']?"Y":"N"?></td>
                            <td><?=$student['Books_Out']?></td>
                            <td>
                                <?php if($student['Books_Out'] == 0 && $student['Deposit']) { ?>
                                    <a href="student_quit.php?student_id=<?=$student['Student_ID']?>">Remove Student</a>
                                <?php } else if(!$student['Deposit']) {?>
                                    <a href="student_rejoin.php?student_id=<?=$student['Student_ID']?>">Rejoin Student</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="large-12 small-12 column float-right text-right">
            <a href="add_student.php" class="button">Add Student</a>
        </div>
    </div>



<?php require_once "footer.php"; ?>

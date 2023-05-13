<?php

include_once('../include/entete_front.inc.php');

$bookList = new BookList("localhost", "bibliodb", "root", "");

if (isset($_GET['book_id'])) {
    $students = $bookList->getStudentsWhoBorrowedBook($_GET['book_id']);
    echo json_encode($students);
    exit();
}

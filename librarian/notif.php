<?php
include("listingretard.php");

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];
    $book_title = $_POST['book_title'];


    $studentManager = new StudentManager("localhost", "bibliodb", "root", "");

    $studentManager->insertNotifications($book_title, $user_id, $book_id);

    echo "Notification sent successfully!";
    exit();
}
?>
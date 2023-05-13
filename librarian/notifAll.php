<?php
include("listingretard.php");
if (isset($_POST)) {
    $studentManager = new StudentManager("localhost", "bibliodb", "root", "");
    $studentManager->insertNotificationsAll();
}

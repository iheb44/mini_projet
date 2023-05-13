<?php

include("listingstudent.php");


if (isset($_POST['id'])) {
  $id = $_POST['id'];

  $studentManager = new StudentManager("localhost", "bibliodb", "root", "");

  $studentManager->deleteStudent($id);

  exit();
}

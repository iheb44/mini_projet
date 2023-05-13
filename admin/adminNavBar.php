<?php
session_start();

include_once('../include/entete_front.inc.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['disconnect'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['role']);
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <link rel="stylesheet" href="../assets/css/style/navbar.css">
    
</head>

<body>
    <div class="container mb-4 mt-4">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-12">
                <nav class="navbar navbar-expand-lg navbar-dark bg-danger rounded">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link text-light" href="/projet/admin/listingbilio.php">Liste des bibliothèques</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="/projet/admin/listingstudent.php">Liste des étudiants</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link text-light" href="?disconnect">Disconnect</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
</body>

</html>
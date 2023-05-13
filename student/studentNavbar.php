<?php
include_once('../include/entete_front.inc.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../login.php");
    exit();
}
$navBookList = new NavBookList("localhost", "bibliodb", "root", "");
$notifs = $navBookList->getNotif();
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
    <title>Titre de votre page</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>
    <link rel="stylesheet" href="../assets/css/style/navbar.css">
</head>
<body>
<div class="container mb-4 mt-2">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-12">
            <nav class="navbar navbar-expand-lg navbar-dark bg-info rounded">
                <a class="navbar-brand" href="#">Biblio</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="/projet/student/listinglivre.php">Livres</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/projet/student/profile.php">Profil</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell"></i> Notifications <?php echo count($notifs) ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="width: 700px">
                                <div class="dropdown-header">Notifications <?php echo count($notifs) ?></div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Tout marquer comme lu</a>
                                <div class="dropdown-divider"></div>
                                <?php foreach ($notifs as $notif) { ?>
                                    <a class="dropdown-item" href="#"><?php echo $notif['message'] ?></a>
                                <?php } ?>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link text-light" href="?disconnect">Disconnect</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/popper.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>
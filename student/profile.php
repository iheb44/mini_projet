<?php
include_once('../include/entete_front.inc.php');

$userProfile = new UserProfile("localhost", "bibliodb", "root", "");

if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $carte = $_POST['carte'];
    $passwordo = $_POST['passwordo'];
    $new_password = $_POST['newpassword'];
    $confirm_password = $_POST['confirm_password'];

    if ($userProfile->updateProfile($nom, $prenom, $email, $carte, $passwordo, $new_password, $confirm_password)) {
        header("Location: profile.php");
        exit();
    }
}
?>
<html>

<head>
    <title>Biblio - Livres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/style/profile.css">
</head>

<body>
    <?php include('studentNavbar.php'); ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>User Info</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($userProfile->getErrors())) : ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($userProfile->getErrors() as $error) : ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($userProfile->getSuccess())) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $userProfile->getSuccess(); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-8">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom:</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $userProfile->getProfile()['nom']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom:</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $userProfile->getProfile()['prenom']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $userProfile->getProfile()['email']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="carte" class="form-label">Numéro de carte:</label>
                                <input type="text" class="form-control" id="carte" name="carte" value="<?php echo $userProfile->getProfile()['carte']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="passwordo" class="form-label">Mot de passe actuel:</label>
                                <input type="password" class="form-control" id="passwordo" name="passwordo">
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#passwordCollapse" aria-expanded="false" aria-controls="passwordCollapse">
                                    Changer le Mot de passe <i class="fa fa-chevron-down"></i>
                                </button>
                            </div>
                            <div class="collapse mb-4" id="passwordCollapse">
                                <div class="card card-body">
                                    <div class="mb-3">
                                        <label for="newpassword" class="form-label">Nouveau mot de passe:</label>
                                        <input type="password" class="form-control" id="newpassword" name="newpassword">
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirmer nouveau mot de
                                            passe:</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary">Mise a jour</button>
                        </form>
                    </div>
                </div>
</body>

</html>
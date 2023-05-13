<?php
include_once('include/entete_front.inc.php');

$userRegistration = new UserRegistration("localhost", "bibliodb", "root", "");
$userRegistration->register();
$errors = $userRegistration->getErrors();

?>
<html>

<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style/signup.css">

</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>User Registration</h2>
            </div>
            <div class="card-body">

                <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($errors as $error) : ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success" role="alert">
                        <ul>
                            <li><?php echo $success; ?></li>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom:</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom:</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="carte" class="form-label">Carte:</label>
                        <input type="text" class="form-control" id="carte" name="carte">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"></script>
</body>

</html>
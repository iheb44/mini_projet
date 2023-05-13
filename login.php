<?php

include_once('include/entete_front.inc.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $login = new Login("localhost", "bibliodb", "root", "");
  $error = $login->authenticate($email, $password);
}
?>

<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style/login.css">
</head>

<body>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h2>Login</h2>
      </div>
      <div class="card-body">
        <?php if (isset($error)) { ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="post">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
    <script src="assets/js/jquery.slim.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
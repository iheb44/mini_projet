<?php
$host = "localhost";
$db = "bibliodb";
$user = "root"; 
$password = "";  
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($host);

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
  
  
    $sql = "UPDATE users SET nom = ?, prenom  = ?, email = ? WHERE id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $lastName, $email, $user_id]);

    echo "update with sucess";
    exit();

}


<?php

class NavBookList
{
    private $pdo;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
        if (!isset($_SESSION['user_id'] )) {
            header("Location: ../login.php");
            exit();
        }
    }

    public function getNotif()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notification WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
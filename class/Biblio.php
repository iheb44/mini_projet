<?php

class Biblio
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
    }

    public function getUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users where  role = ? ");
        $role = "bibliothecaire";
        $stmt->execute([$role]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }
}
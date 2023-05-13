<?php

class Login
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

    public function authenticate($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT id, role, password FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                session_regenerate_id();

                if ($_SESSION['role'] == 'etudiant') {
                    header("location:student/listinglivre.php");
                } elseif ($_SESSION['role'] == 'bibliothecaire') {
                    header("location:librarian/listingretard.php");
                } elseif ($_SESSION['role'] == 'administrateur') {
                    header("location:admin/listingbilio.php");
                }
            } else {
                return "Votre mot de passe n'est pas valide";
            }
        } else {
            return "Votre email n'est pas valide";
        }
    }
}
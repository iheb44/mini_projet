<?php

class UserRegistration
{
    private $pdo;
    private $errors = [];
    private $success = '';

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

    public function register()
    {
        if (isset($_POST['register'])) {
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
            $carte = trim($_POST['carte']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($nom)) {
                $this->errors[] = "Le nom est obligatoire";
            }

            if (empty($prenom)) {
                $this->errors[] = "Le prenom est obligatoire";
            }

            if (empty($email)) {
                $this->errors[] = "L'email est obligatoire";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "L'email n'est pas valide";
            }

            if (empty($carte)) {
                $this->errors[] = "Le numero de carte est obligatoire";
            }

            if (empty($password)) {
                $this->errors[] = "Le mot de passe est obligatoire";
            } elseif (strlen($password) < 8) {
                $this->errors[] = "Le mot de passe doit comporter au moins 8 caractères";
            } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/", $password)) {
                $this->errors[] = "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre, et un caractère spécial";
            }

            if ($password != $confirm_password) {
                $this->errors[] = "Les mots de passe ne correspondent pas";
            }

            $nom = htmlspecialchars($nom);
            $prenom = htmlspecialchars($prenom);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $carte = htmlspecialchars($carte);

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $this->errors[] = "L'email existe déjà";
            }

            if (empty($this->errors)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare("INSERT INTO users (nom, prenom, email, password, numCarte) VALUES (? , ? , ? , ? , ?)");
                $stmt->execute([$nom, $prenom, $email, $password, $carte]);

                $this->success = "L'utilisateur s'est enregistré avec succès";
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccess()
    {
        return $this->success;
    }
}
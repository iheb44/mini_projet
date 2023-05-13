<?php

class UserProfile
{
    private $pdo;
    private $errors = [];
    private $success = "";

    public function __construct($host, $dbname, $username, $password)
    {
        $this->connect($host, $dbname, $username, $password);
        if (!isset($_SESSION['user_id']) ) {
            header("Location: ../login.php");
            exit();
        }
    }

    private function connect($host, $dbname, $username, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
    }

    public function updateProfile($nom, $prenom, $email, $carte, $passwordo, $new_password = null, $confirm_password = null)
{
    $nom = trim($nom);
    $prenom = trim($prenom);
    $email = trim($email);
    $carte = trim($carte);

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

    $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($passwordo, $row['password']) && ($new_password === null || $new_password === $confirm_password)) {
        if ($new_password !== null) {
            $password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE users SET nom = ?, prenom = ?, email = ?, numCarte = ?,  password = ? WHERE id = ?");
            $stmt->execute([$nom, $prenom, $email, $carte, $password, $_SESSION['user_id']]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE users SET nom = ?, prenom = ?, email = ?, numCarte = ? WHERE id = ?");
            $stmt->execute([$nom, $prenom, $email, $carte, $_SESSION['user_id']]);
        }
        if ($stmt->rowCount() > 0) {
            $this->success = "Your information has been updated.";
        } else {
            $this->errors[] = "Failed to update your information.";
        }
    } else {
        $this->errors[] = "Invalid password or passwords do not match";
    }

    return empty($this->errors);
}

    public function getProfile()
    {
        $stmt = $this->pdo->prepare("SELECT nom, prenom, email, numCarte, password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'carte' => $row['numCarte'],
        ];
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
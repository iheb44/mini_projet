<?php
class AddLibrarian
{
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $errors = array();
    private $success;
    private $pdo;

    public function __construct($host, $dbname, $username, $pwd, $nom, $prenom, $email, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->validate($this->pdo);
    }

    private function validate($pdo)
    {
        if (empty($this->nom)) {
            $this->errors[] = "Le nom est obligatoire";
        }

        if (empty($this->prenom)) {
            $this->errors[] = "Le prenom est obligatoire";
        }

        if (empty($this->email)) {
            $this->errors[] = "L'email est obligatoire";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email n'est pas valide";
        }

        if (empty($this->password)) {
            $this->errors[] = "Le mot de passe est obligatoire";
        } elseif (strlen($this->password) < 8) {
            $this->errors[] = "Le mot de passe doit comporter au moins 8 caractères";
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$this->email]);

        if ($stmt->rowCount() > 0) {
            $this->errors[] = "L'email existe déjà";
        }

        if (empty($this->errors)) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $role = "bibliothecaire";
            $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role) VALUES (? , ? , ? , ?, ? )");
            $stmt->execute([$this->nom, $this->prenom, $this->email, $this->password, $role]);

            $this->success = "L'utilisateur s'est enregistré avec succès";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $add_librarian = new AddLibrarian("localhost", "bibliodb", "root", "", $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password']);
}

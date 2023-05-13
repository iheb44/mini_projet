<?php

class StudentManager
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

    public function getStudents()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = ?");
        $role = "etudiant";
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteStudent($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

    function getUsersWithLateBooks()
    {
        $query = "SELECT emprunt.user_id, users.email , livres.id AS livre_id,livres.titre AS livre_titre   FROM emprunt
              INNER JOIN users ON emprunt.user_id = users.id
              INNER JOIN livres ON emprunt.livre_id = livres.id
              WHERE emprunt.date_retour <  DATE_SUB(NOW(), INTERVAL 10 DAY) AND emprunt.date_retour IS NOT NULL ";
        $statement = $this->pdo->query($query);
        $users = array();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $users[] = array(
                'user_id' => $row['user_id'],
                'book_id' => $row['livre_id'],
                'book_title' => $row['livre_titre'],

            );
        }

        return $users;
    }

    function insertNotificationsAll()
    {
        $users = $this->getUsersWithLateBooks();

        foreach ($users as $user) {
            $message = "Vous avez emprunté le livre \"" . $user['book_title'] . "\" et il est maintenant en retard. Veuillez le retourner.";

            $query = "INSERT INTO notification (user_id, message,livre_id) VALUES (:user_id, :message,:livre_id)";
            $statement = $this->pdo->prepare($query);
            $statement->execute(array(':user_id' => $user['user_id'], ':message' => $message, ":livre_id" => $user['book_id']));
        }
    }
    function insertNotifications($title, $user_id, $livre_id)
    {

        $message = "Vous avez emprunté le livre \"" . $title . "\" et il est maintenant en retard. Veuillez le retourner.";
        $query = "INSERT INTO notification (user_id, message,livre_id) VALUES (:user_id, :message,:livre_id)";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array(':user_id' => $user_id, ':message' => $message, ":livre_id" => $livre_id));
    }
}
<?php

class BookList
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

    public function getBooks()
    {
        $stmt = $this->pdo->query("SELECT * FROM livres ORDER BY genre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function emprunterStudent($user_id, $livre_id, $return_date)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt1 = $this->pdo->prepare("UPDATE livres SET copies_disponibles = copies_disponibles - 1 WHERE id = ?");
            $stmt1->execute([$livre_id]);

            $stmt2 = $this->pdo->prepare("INSERT INTO emprunt (user_id, livre_id, emprunt_date, date_retour) VALUES (?, ?, NOW(), ?)");
            $stmt2->execute([$user_id, $livre_id, $return_date]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
    }

    public function emprunterBiblio($user_id, $livre_id)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt1 = $this->pdo->prepare("UPDATE livres SET copies_disponibles = copies_disponibles + 1 WHERE id = ?");
            $stmt1->execute([$livre_id]);

            $stmt2 = $this->pdo->prepare("DELETE FROM emprunt WHERE user_id = ? AND livre_id = ?");
            $stmt2->execute([$user_id, $livre_id]);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
    public function addBook($titre, $auteur, $genre, $copies_disponibles, $image_url)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO livres (titre, auteur, genre, copies_disponibles, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$titre, $auteur, $genre, $copies_disponibles, $image_url]);
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
   
    public function getStudentsWhoBorrowedBook($book_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT u.id, u.email, e.emprunt_date, e.date_retour 
        FROM emprunt e
        INNER JOIN users u ON e.user_id = u.id 
        WHERE e.livre_id = ? AND e.returned = 0
    ");
        $stmt->execute([$book_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function returnBooks($studentIds)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE emprunt SET returned = 1, returned_at = CURDATE() WHERE user_id IN (" . implode(',', array_map('intval', $studentIds)) . ")");
            $stmt->execute();
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
}

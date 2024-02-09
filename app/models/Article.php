<?php
require_once __DIR__ . '/../core/Database.php';

class Article {
    public function getArticlesByPage($page, $perPage) {
        $pdo = Database::getInstance();
        $offset = ($page - 1) * $perPage;
        $stmt = $pdo->prepare('SELECT * FROM articles ORDER BY id DESC LIMIT :offset, :perPage');
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByPageById($page, $perPage, $articleIds) {
        $pdo = Database::getInstance();
        $offset = ($page - 1) * $perPage;
        
        // Convert the array of IDs into a comma-separated string
        $idsString = implode(',', array_map('intval', $articleIds));

        // Prepare the SQL query with the IDs condition
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id IN ($idsString) ORDER BY id DESC LIMIT :offset, :perPage");
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCount() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query('SELECT COUNT(*) FROM articles');
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $content) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('UPDATE articles SET name = ?, content = ? WHERE id = ?');
        $stmt->execute([$name, $content, $id]);
    }

    public function delete($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function create($name, $content) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('INSERT INTO articles (name, content) VALUES (?, ?)');
        $stmt->execute([$name, $content]);
        return $pdo->lastInsertId();
    }
}

<?php
require_once __DIR__ . '/../core/Database.php';

class Tag {
    public function create($name) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?) ON DUPLICATE KEY UPDATE name = VALUES(name)");
        $stmt->execute([$name]);
        return $pdo->lastInsertId();
    }

    public function getByName($name) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM tags WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addTagForArticle($name, $articleId) {
        $pdo = Database::getInstance();
        $this->create($name);
        $tagId = $this->getByName($name)['id'];
        $stmt = $pdo->prepare("INSERT IGNORE INTO article_tags (article_id, tag_id) VALUES (?, ?)");
        $stmt->execute([$articleId, $tagId]);
    }

    public function getTagsForArticle($articleId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT t.* FROM tags t INNER JOIN article_tags at ON t.id = at.tag_id WHERE at.article_id = ?");
        $stmt->execute([$articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesForTag($tagId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT a.* FROM articles a INNER JOIN article_tags at ON a.id = at.article_id WHERE at.tag_id = ?");
        $stmt->execute([$tagId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleIdsForTag($tagId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT article_id FROM article_tags WHERE tag_id = ?");
        $stmt->execute([$tagId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getAllTags() {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM tags ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

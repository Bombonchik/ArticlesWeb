<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Tag.php';

class TagsController extends BaseController {

    public function getTagsForArticle($articleId) {
        $tagModel = new Tag();
        $tags = $tagModel->getTagsForArticle($articleId);
        return $tags;
    }

    public function updateTagsForArticle($articleId) {
        $tagModel = new Tag();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tags'])) {
            $tagNames = explode(',', $_POST['tags']);
            
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if (!empty($tagName)) {
                    $tagModel->addTagForArticle($tagName, $articleId);
                }
            }
        }
    }

    public function getArticleIdsForTag($tagId) {
        $tagModel = new Tag();
        $articleIds = $tagModel->getArticleIdsForTag($tagId);
        return $articleIds;
    }

    public function getAllTags() {
        $tagModel = new Tag();
        $tags = $tagModel->getAllTags();
        return $tags;
    }

}

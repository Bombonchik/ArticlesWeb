<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Article.php';

class ArticlesController extends BaseController {

    private function getCurrentPage($pagesCount) {
        $currentPage = filter_var($_GET['p'] ?? 1, FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 1, // Default value if validation fails
                'min_range' => 1, // Minimum value
            ]
        ]);
    
        // Check if the current page is within the range of available pages
        if ($currentPage > $pagesCount) {
            header('HTTP/1.0 404 Not Found');
            echo 'Page not found.';
            exit;
        }
    
        return $currentPage;
    }

    public function detail($id, TagsController $tagsController) {
        $baseUrl = $this->getBaseUrl();
        $article = (new Article())->getById($id);
        $articleTags = $tagsController->getTagsForArticle($id);
        $this->render('article_detail', ['article' => $article, 'baseUrl' => $baseUrl, 
                                        'id' => $id, 'articleTags' => $articleTags]);
    }

    public function edit($id, TagsController $tagsController) {

        $articleModel = new Article();
        $article = $articleModel->getById($id);
        $baseUrl = $this->getBaseUrl();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedName = $_POST['name'];
            $updatedContent = $_POST['content'];
            if (!empty($updatedName)) {
                $articleModel->update($id, $updatedName, $updatedContent);
                $tagsController->updateTagsForArticle($id);
                header("Location: ".$baseUrl."articles");
                exit;
            }
            echo "Article name cannot be empty.";
        }
        else {
            $articleTags = $tagsController->getTagsForArticle($id);
            $this->render('article_edit', ['article' => $article, 'baseUrl' => $baseUrl, 'articleTags' => $articleTags]);
        }
    }

    public function delete($id) {
        $result = (new Article())->delete($id);
        if ($result) {
            // Send a success response
            http_response_code(200); // OK
            echo json_encode(['message' => 'Article deleted']);
        } else {
            // Send an error response
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to delete article']);
        }
    }

    public function index(TagsController $tagsController) {
        $perPage = 10; // Number of articles per page
        $articleModel = new Article();
        $articlesCount = $articleModel->getCount();
        $allTags = $tagsController->getAllTags();
        $pagesCount = max(ceil($articlesCount / $perPage), 1);
        $currentPage = $this->getCurrentPage($pagesCount);
        $articles = $articleModel->getArticlesByPage($currentPage, $perPage);
        $this->render('articles', ['articles' => $articles,
                                    'baseUrl' => $this->getBaseUrl(),
                                    'pagesCount' => $pagesCount, 
                                    'currentPage' => $currentPage,
                                    'allTags' => $allTags,]);
    }
    
    public function indexWithTag($tag, TagsController $tagsController){
        $perPage = 10; // Number of articles per page
        $articleModel = new Article();
        $articleIds = $tagsController->getArticleIdsForTag($tag);
        $allTags = $tagsController->getAllTags();
        $articlesCount = count($articleIds);
        $pagesCount = max(ceil($articlesCount / $perPage), 1);
        $currentPage = $this->getCurrentPage($pagesCount);
        $articles = ($articlesCount > 0) ? $articleModel->getArticlesByPageById($currentPage, $perPage, $articleIds) : [];
        $this->render('articles', ['articles' => $articles,
                                    'baseUrl' => $this->getBaseUrl(),
                                    'pagesCount' => $pagesCount, 
                                    'currentPage' => $currentPage,
                                    'allTags' => $allTags,
                                    ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $baseUrl = $this->getBaseUrl();
            $input = json_decode(file_get_contents('php://input'), true);
            if (!empty($input['articleName'])) {
                $newArticleId = (new Article())->create($input['articleName'], '');
                echo json_encode(['id' => $newArticleId]);
                header("Location: $baseUrl./article-edit/$newArticleId");
            } else {
                echo json_encode(['error' => 'Article name is required']);
            }
        }
    }
}

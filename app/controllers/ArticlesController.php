<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Article.php';

class ArticlesController extends BaseController {
    public function index() {
        $perPage = 10; // Number of articles per page
        $articleModel = new Article();
        $articlesCount = $articleModel->getCount();
        $pagesCount = max(ceil($articlesCount / $perPage), 1);
        $currentPage = $this->getCurrentPage($pagesCount);
        $articles = $articleModel->getArticlesByPage($currentPage, $perPage);
        $this->render('articles', ['articles' => $articles,
                                    'baseUrl' => $this->getBaseUrl(),
                                    'pagesCount' => $pagesCount, 
                                    'currentPage' => $currentPage]);
    }

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

    public function detail($id) {
        $baseUrl = $this->getBaseUrl();
        $article = (new Article())->getById($id);
        $this->render('article_detail', ['article' => $article, 'baseUrl' => $baseUrl, 'id' => $id]);
    }

    public function edit($id) {
        $article = (new Article())->getById($id);
        $baseUrl = $this->getBaseUrl();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedName = $_POST['name'];
            $updatedContent = $_POST['content'];
            if (!empty($updatedName)) {
                (new Article())->update($id, $updatedName, $updatedContent);
                header("Location: ".$baseUrl."articles");
                exit;
            }
            echo "Article name cannot be empty.";
        }
        $this->render('article_edit', ['article' => $article, 'baseUrl' => $baseUrl]);
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

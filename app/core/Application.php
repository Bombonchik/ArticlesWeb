<?php
require_once 'Database.php';
require_once __DIR__ . '/../controllers/ArticlesController.php';
require_once __DIR__ . '/../controllers/TagsController.php';

class Application {
    public function __construct() {
        $this->routeRequest();
    }

    private function parseUrl() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('/index.php', '', $scriptName);
        $relativePath = str_replace($basePath, '', $requestUri);
        $urlPath = parse_url($relativePath, PHP_URL_PATH);
        return explode('/', trim($urlPath, '/'));
    }

    private function routeRequest() {
        $pathComponents = $this->parseUrl();
        
        $request = $pathComponents[0] ?? ''; 
        $id = $pathComponents[1] ?? null;
        $selectedTag = (isset($_GET['tag']) && strlen($_GET['tag']) <= 32) ? $_GET['tag'] : null;

        $this->routeToController($request, $id, $selectedTag);
    }

    private function routeToController($request, $id, $selectedTag) {
        switch ($request) {
            case 'article':
                (new ArticlesController())->detail($id, new TagsController());
                break;
            case 'article-edit':
                (new ArticlesController())->edit($id, new TagsController());
                break;
            case 'article-delete':
                (new ArticlesController())->delete($id);
                break;
            case 'article-create':
                (new ArticlesController())->create();
                break;
            default:
                switch ($selectedTag) {
                    case null:
                        (new ArticlesController())->index(new TagsController());
                        break;
                    default:
                        (new ArticlesController())->indexWithTag($selectedTag, new TagsController());
                            break;
                }
        }
    }

}

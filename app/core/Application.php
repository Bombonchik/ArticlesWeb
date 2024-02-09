<?php
require_once 'Database.php';
require_once __DIR__ . '/../controllers/ArticlesController.php';

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

        $this->routeToController($request, $id);
    }

    private function routeToController($request, $id) {
        switch ($request) {
            case 'article':
                (new ArticlesController())->detail($id);
                break;
            case 'article-edit':
                (new ArticlesController())->edit($id);
                break;
            case 'article-delete':
                (new ArticlesController())->delete($id);
                break;
            case 'article-create':
                (new ArticlesController())->create();
                break;
            default:
                (new ArticlesController())->index();
                break;
        }
    }

}

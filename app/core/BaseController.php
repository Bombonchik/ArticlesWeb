<?php
class BaseController {
    protected function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        return $protocol . "://" . $host . $scriptName . '/';
    }
    
    protected function render($viewName, $data = []) {
        extract($data);
        include __DIR__ . '/../views/' . $viewName . '.php';
    }
}
<?php

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Article List</title>';
echo '<link rel="stylesheet" href="' . $baseUrl . 'public/css/articles_styles.css">';
echo '</head>';
echo '<body>';
echo '<div class="article-list-container">';
echo '<h1>Article list</h1>';
echo '<ul class="article-list">';


foreach ($articles as $article) {
    echo '<li data-article-id="' . htmlspecialchars($article['id']) . '">';
    echo '<span class="article-name">' . htmlspecialchars($article['name']) . '</span>';
    echo '<div class="article-actions">';
    echo '<a class="article-action" href="' . $baseUrl . 'article/' . htmlspecialchars($article['id']) . '">Show</a>';
    echo '<a class="article-action" href="' . $baseUrl . 'article-edit/' . htmlspecialchars($article['id']) . '">Edit</a>';
    echo '<button class="article-action delete" onclick="deleteArticle(' . htmlspecialchars($article['id']) . ')">Delete</button>';
    echo '</div>';
    echo '</li>';
}

echo '</ul>';
echo '<div class="pagination-container">';
echo '<div class="pagination">';

if ($currentPage > 1) {
    echo '<a href="'. $baseUrl .'articles?p=' . ($currentPage - 1) . '">Previous</a>';
}

if ($currentPage < $pagesCount) {
    echo '<a href="'. $baseUrl .'articles?p=' . ($currentPage + 1) . '">Next</a>';
}

echo '</div>'; 


echo '<div class="page-count-create">';
echo '<span class="page-count">Page ' . $currentPage . ' of ' . $pagesCount . '</span>';
echo '<button class="create-article-button" onclick="showCreateArticleForm()">Create article</button>';
echo '</div>'; 

echo '</div>'; 

echo '<div id="createArticleDialog" class="modal" style="display:none;">';
echo '<form id="createArticleForm">';
echo '<label for="articleName">Article name</label><br>';
echo '<input type="text" id="articleName" name="name" maxlength="32" required oninput="checkArticleName()"><br>';
echo '<input type="button" id="createButton" value="Create" onclick="createArticle()" disabled>';
echo '<input type="button" id="cancelButton" value="Cancel" onclick="hideCreateArticleForm()">';
echo '</form>';
echo '</div>';

echo '<script src="' . $baseUrl . 'public/js/script.js"></script>';
echo '</body>';
echo '</html>';

<?php

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Article Detail</title>';
echo '<link rel="stylesheet" href="' . htmlspecialchars($baseUrl) . 'public/css/article_detail_styles.css">';
echo '</head>';
echo '<body>';
echo '<div class="article-detail-container">';

if ($article) {
    echo "<h1 class='article-title'>" . htmlspecialchars($article['name']) . "</h1>";

    if (!empty($articleTags)) {
    echo "<div class='tags-container'>";
    echo "<label id='tags-label'>Tags: </label>";
    foreach ($articleTags as $tag) {
        echo "<span class='tag'>" . htmlspecialchars($tag['name']) . "</span>";
    }
    echo "</div>";
    }
    echo "<div class='article-content'>" . htmlspecialchars($article['content']) . "</div>";
    echo "<div class='article-actions'>";

    echo "<a href='" . htmlspecialchars($baseUrl) . "article-edit/{$id}' class='article-edit-button'>Edit</a>"; 

    echo "<a href='" . htmlspecialchars($baseUrl) . "articles' class='back-to-articles-button'>Back to articles</a>";  
    echo "</div>";
}  else {
    http_response_code(404);
    echo "<p>Article not found</p>";
}

echo '</div>'; 
echo '</body>';
echo '</html>';
?>

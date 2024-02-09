<?php

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Edit Article</title>';
echo '<link rel="stylesheet" href="' . $baseUrl . 'public/css/article_edit_styles.css">';
echo '</head>';
echo '<body>';
echo '<div class="article-edit-container">';

if ($article) {

    echo "<form class='article-edit-form' method='post'>";
    echo "<label for='name'>Name</label>";
    echo "<input type='text' id='name' name='name' value='{$article['name']}' maxlength='32' required />";
    echo "<label for='content'>Content</label>";
    echo "<textarea id='content' name='content' maxlength='1024'>{$article['content']}</textarea>";
    echo "<div class='form-actions'>";
    echo "<input type='submit' class='save-button' value='Save' />";
    echo "<a href='" . $baseUrl . "articles' class='back-button'>Back to articles</a>";
    echo "</div>";
    echo "</form>";
} else {

    http_response_code(404);
    echo "<p>Article not found</p>";
}

echo '</div>';
echo '</body>';
echo '</html>';
?>
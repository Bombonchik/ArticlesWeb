<?php

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Edit Article</title>';
echo '<link rel="stylesheet" href="' . htmlspecialchars($baseUrl) . 'public/css/article_edit_styles.css">';
echo '</head>';
echo '<body>';
echo '<div class="article-edit-container">';

if ($article) {

    echo "<form class='article-edit-form' method='post'>";
    echo "<label for='name'>Name</label>";
    echo "<input type='text' id='name' name='name' value='" . htmlspecialchars($article['name']) . "' maxlength='32' required />";
    echo "<label for='content'>Content</label>";
    echo "<textarea id='content' name='content' maxlength='1024'>" . htmlspecialchars($article['content']) . "</textarea>";

    echo "<div class='tags-section'>";
    echo "<label for='tags'>Tags</label>";
    echo "<span id='tags-area' class='tags-area'>";
    echo "<div id='tags-container' class='tags-container'>";
    // list the existing tags.
    foreach ($articleTags as $tag) {
        echo "<span class='tag'>" . htmlspecialchars($tag['name']) . "</span>";
    }
    echo "</div>"; 
    echo "<div id='add-tag-btn-area' class='add-tag-btn-area'>";
    echo '<button type="button" class="add-tag-btn" onclick="showCreateTagForm()">Add Tag</button>';
    echo "</div>"; 
    echo "</span>"; 
    // Hidden input to store all tags in a comma-separated list.
    $tagNames = array_map(function($tag) {
        return $tag['name']; 
    }, $articleTags);
    echo "<input type='hidden' name='tags' id='hidden-tags' value='" . implode(",", $tagNames) . "'>";
    echo "</div>";
    
    // Form actions (save and back)
    echo "<div class='form-actions'>";
    echo "<input type='submit' class='save-button' value='Save' />";
    echo "<a href='" . htmlspecialchars($baseUrl) . "articles' class='back-button'>Back to articles</a>";
    echo "</div>";
    echo "</form>";

    // Create Tag Dialog
    echo '<div id="createTagDialog" class="modal" style="display:none;">';
    echo '<form id="createTagForm">';
    echo '<label for="tagName">Tag name</label><br>';
    echo '<input type="text" id="tagName" name="name" maxlength="32" required oninput="checkTagName()"><br>';
    echo '<input type="button" id="addButton" value="Add" onclick="addTag()" disabled>';
    echo '<input type="button" id="cancelButton" value="Cancel" onclick="hideCreateTagForm()">';
    echo '</form>';
    echo '</div>';
} else {

    http_response_code(404);
    echo "<p>Article not found</p>";
}

echo '</div>';
echo '<script src="' . htmlspecialchars($baseUrl) . 'public/js/script.js"></script>';
echo '</body>';
echo '</html>';
?>
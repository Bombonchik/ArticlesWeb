header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//for all pages
function getBaseUrl() {
    return window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/');
}

//for articles.php
document.addEventListener('DOMContentLoaded', function () {
    var nameInput = document.querySelector('input[name="name"]');
    var saveButton = document.querySelector('input[type="submit"]');

    function toggleSaveButton() {
        if (nameInput && saveButton) {
            saveButton.disabled = !nameInput.value.trim();
        }
    }

    if (nameInput) {
        nameInput.addEventListener('input', toggleSaveButton);
        toggleSaveButton(); 
    }
});

//for articles.php
function createArticle() {

    const baseUrl = getBaseUrl();

    var articleName = document.getElementById('articleName').value.trim();

    if (articleName) {

        fetch(baseUrl + '/article-create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ articleName: articleName })
        }).then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            }
        })
    }
}

//for articles.php
function deleteArticle(articleId) {

    const baseUrl = getBaseUrl();

    if (confirm('Are you sure you want to delete this article?')) {
        fetch(baseUrl + '/article-delete/' + articleId, { 
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                var articleElement = document.querySelector('li[data-article-id="' + articleId + '"]');
                if (articleElement) {
                    articleElement.remove();
                }
                var remainingArticles = document.querySelectorAll('ul.article-list li').length;
                if (remainingArticles === 0) {
                    var currentPage = parseInt(new URLSearchParams(window.location.search).get('p')) || 1;
                    var previousPage = Math.max(currentPage - 1, 1);
                    window.location.href = `${baseUrl}/articles?p=${previousPage}`;
                }
            } else {
                alert('Failed to delete the article.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error occurred while deleting the article.');
        });
    }
}

//for articles.php
function showCreateArticleForm() {
    document.getElementById('createArticleDialog').style.display = 'block';
}

//for articles.php
function hideCreateArticleForm() {
    document.getElementById('createArticleDialog').style.display = 'none';
}

//for articles.php
function checkArticleName() {
    var articleName = document.getElementById('articleName');
    var createArticleButton = document.getElementById('createButton');
    createArticleButton.disabled = !articleName.value.trim();
}

//for articles.php
function filterByTag() {
    var tagId = document.getElementById("tagFilter").value;
    const baseUrl = getBaseUrl();
    window.location.href = `${baseUrl}/articles?tag=${tagId}`;
}


//for article_edit.php
function addTag() {
    var tagName = document.getElementById('tagName').value.trim();
    if (tagName) {
        // Add the tag to the tags container
        var container = document.getElementById('tags-container');
        var newTag = document.createElement('span');
        newTag.textContent = tagName;
        newTag.className = 'tag';
        container.appendChild(newTag);

        // Add the tag to the hidden input
        var hiddenInput = document.getElementById('hidden-tags');
        var tags = hiddenInput.value ? hiddenInput.value.split(',') : [];
        tags.push(tagName);
        hiddenInput.value = tags.join(',');

        // Clear the input and hide the form
        document.getElementById('tagName').value = '';
        hideCreateTagForm();
    }
}

//for article_edit.php
function showCreateTagForm() {
    document.getElementById('createTagDialog').style.display = 'block';
}

//for article_edit.php
function hideCreateTagForm() {
    document.getElementById('createTagDialog').style.display = 'none';
    document.getElementById('tagName').value = ''; // Clear the input
    document.getElementById('addButton').disabled = true; // Reset the button to disabled
}

//for article_edit.php
function checkTagName() {
    var tagName = document.getElementById('tagName');
    var createTagButton = document.getElementById('addButton');
    createTagButton.disabled = !tagName.value.trim(); 
}

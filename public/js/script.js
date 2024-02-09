header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

function hideCreateArticleForm() {
    var form = document.getElementById('createArticleForm');
    form.style.display = 'none';
}

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

function getBaseUrl() {
    return window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/');
}

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

function showCreateArticleForm() {
    document.getElementById('createArticleDialog').style.display = 'block';
}

function hideCreateArticleForm() {
    document.getElementById('createArticleDialog').style.display = 'none';
}

function checkArticleName() {
    var articleName = document.getElementById('articleName');
    var createButton = document.getElementById('createButton');
    createButton.disabled = !articleName.value.trim();
}


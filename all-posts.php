<?php
require_once './config/config.php';
require_once './providers/posts.php';
require_once './providers/database.php';

use App\Pdo\Database;

session_start();

if (isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
}

include './include/header.php';
?>

<h2 class="mt-5 p-5">
    All posts:
</h2>

<?php
if (isset($_SESSION['userId'])) {
    echo <<<ADDPOST
     <div class="p-4">
       <a href="add-post.php" class="btn btn-primary">
          Add Post
       </a>
     </div>
  ADDPOST;
} else {
    echo <<<LOGIN
      <a href="login.php" class="btn btn-primary mb-3">Login to add posts</a>
    LOGIN;
};
?>

<?php
$query = 'SELECT posts.id, posts.title, posts.user_id, posts.last_update, posts.body, posts.image_url, posts.image_alt, users.name FROM posts LEFT JOIN users ON posts.user_id = users.id ORDER BY posts.last_update DESC';

$conn = new Database();
$result = $conn->dbQuery($query, []);
$posts = [];

foreach ($result as $item) {
    array_push($posts, new TextPost(
        $item['id'],
        $item['title'],
        $item['last_update'],
        $item['name'],
        $item['user_id'],
        $item['body']

    ));
}


foreach ($posts as $post) {
    $queryUserImage = 'SELECT users.image FROM users WHERE users.id=?';
    $userImage = $conn->dbQuery($queryUserImage, [$post->get('user_id')]);
    $resultUserImage = implode(" ", $userImage[0]);
    $resultUserImage = substr($resultUserImage, 24);
    echo <<<CARD
        <div class="card text-start mb-3">
            <div class="card-header">
            <img  class="avatar" src="{$resultUserImage}" alt="user-image">
                {$post->get('author')}
            </div>
            <div class="card-body">
                <h5 class="card-title">
                    {$post->get('title')}
                </h5>
                <p class="card-text">
                    {$post->get('post_body')}
                </p>

                <small class="text-muted">
                    updated:
                    {$post->formatDate()}
                </small>
        CARD;

    if (isset($_SESSION['userId']) && $post->get('user_id') === $user_id) {
        echo <<<CARD2
                 <div class="card-text text-end">
                    <a href="update-post.php?id={$post->get('id')}" class="btn btn-info">
                         <i class="bi bi-pen"></i>
                    </a>

                    <a href="delete-post.php?id={$post->get('id')}" class="btn btn-danger">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
                CARD2;
    }
    echo ('</div>');
    echo ('</div>');
}

?>


<?php include './include/footer.php' ?>
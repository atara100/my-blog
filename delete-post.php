<?php
require_once './config/config.php';
require_once './providers/database.php';
require_once './providers/utils.php';

use App\Pdo\Database;

session_start();

if (!isset($_SESSION['userId'])) {
    header('location: login.php');
}

$error = '';
$post_id = $_GET['id'];
$posts = [];

try {
    $posts = post_by_id($post_id);
} catch (Exception $err) {
    $error = $err->getMessage();
}

$_SESSION['token'] = sha1('Aa$124$!re');

if (isset($_POST['submit']) && !empty($_SESSION['token'])) {
    $pid = $_POST['post_id'];

    try {
        if (empty($pid)) {
            throw new Exception("Error: no post id.");
        }

        $conn = new Database();
        $result = $conn->dbQuery(
            "DELETE FROM posts WHERE posts.id = ?",
            [$pid]
        );

        header('location: all-posts.php');
    } catch (Exception $err) {
        $error = $err->getMessage();
    }
}

include './include/header.php';
?>

<h2 class="my-3">Delete Post</h2>

<?php
if (count($posts) > 0) {
?>
    <div class="card text-bg-danger mb-3">
        <div class="card-header">Are you sure you want to delete this post?</div>
        <div class="card-body">
            <h5 class="card-title">
                <?= $posts['title'] ?>
            </h5>
            <p class="card-text">
                <?= $posts['body'] ?>
            </p>
            <p class="card-text">
            <form action="delete-post.php" method="post" accept-charset="UTF-8" class="d-inline-block">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <input type="submit" name="submit" value="Delete" class="btn btn-light me-3">
            </form>
            <a href="all-posts.php" class="btn btn-secondary">Cancel</a>
            </p>
        </div>
    </div>
<?php
}
?>

<p class="text-danger">
    <?= $error ?>
</p>

<?php
include './include/footer.php';

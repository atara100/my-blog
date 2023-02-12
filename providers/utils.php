<?php

require_once './config/config.php';
require_once './providers/database.php';

use App\Pdo\Database;

function post_by_id($post_id)
{
    $posts = [];

    if (!isset($post_id) || empty($post_id)) {
        throw new Exception("ERROR: post id is required");
    }

    $conn = new Database();
    $posts = $conn->dbQuery(
        "SELECT * FROM posts WHERE id = ?",
        [$post_id]
    );

    return (count($posts) > 0) ? $posts[0] : [];
}

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $post_id = (int) $_GET['id'];

    $sql = "DELETE FROM posts WHERE id = $post_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?deleted=1");
        exit();
    } else {
        echo "❌ Error deleting post: " . mysqli_error($conn);
    }
} else {
    echo "No post ID given.";
}

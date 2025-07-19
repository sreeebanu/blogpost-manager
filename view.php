<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Post ID missing.");
}

$id = (int)$_GET['id'];
$sql = "SELECT * FROM posts WHERE id = $id";
$result = mysqli_query($conn, $sql);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    die("Post not found.");
}

echo "<h1>" . htmlspecialchars($post['title']) . "</h1>";
echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
?>

</html>

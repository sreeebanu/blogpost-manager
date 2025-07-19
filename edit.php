<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get post ID from URL
if (!isset($_GET['id'])) {
    die("Post ID missing.");
}
$post_id = (int)$_GET['id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category_id = (int)$_POST['category'];

    $sql = "UPDATE posts SET title='$title', content='$content', category_id=$category_id WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?updated=1");
        exit();
    } else {
        echo "Error updating post.";
    }
}

// Fetch post data
$post_sql = "SELECT * FROM posts WHERE id = $post_id";
$post_result = mysqli_query($conn, $post_sql);
$post = mysqli_fetch_assoc($post_result);

if (!$post) {
    die("Post not found.");
}

// Fetch categories
$cat_result = mysqli_query($conn, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fef6fb;
            padding: 30px;
        }

        .form-box {
            background-color: white;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #d63384;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 5px;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        textarea {
            height: 120px;
        }

        .button {
            margin-top: 20px;
            padding: 10px 18px;
            background-color: #a0d8ef;
            color: black;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #8fcbe2;
        }

        .back {
            background-color: #ffb3c1;
            margin-left: 10px;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Edit Post</h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

        <label>Content:</label>
        <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>

        <label>Category:</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                <option value="<?= $cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="button">Update Post</button>
        <a href="index.php" class="button back">← Cancel</a>
    </form>
</div>

</body>
</html>

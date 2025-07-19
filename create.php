<?php
session_start();

// Connect to DB
$conn = mysqli_connect("localhost", "root", "", "blogpostdb");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    $author_id = $_SESSION['user_id']; // Must be set during login

    // Validate
    if (empty($title) || empty($content) || $category_id <= 0) {
        echo "<p style='color:red'>Please fill all fields and select a valid category.</p>";
    } else {
        // Insert query
        $sql = "INSERT INTO posts (title, content, author_id, category_id)
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $title, $content, $author_id, $category_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color:green'>✅ Post published successfully!</p>";
        } else {
            echo "<p style='color:red'>❌ Error: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #fef6f9;
        color: #333;
        padding: 20px;
    }

    h2 {
        color: #a25c8c;
    }

    form {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        max-width: 500px;
        box-shadow: 0 4px 8px rgba(160, 120, 160, 0.1);
    }

    label {
        font-weight: bold;
        color: #a25c8c;
    }

    input[type="text"], textarea, select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fafafa;
    }

    button {
        background-color: #a25c8c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button:hover {
        background-color: #8b3e6b;
    }

    .success {
        background-color: #d6f5e3;
        border-left: 5px solid #32a852;
        padding: 10px;
        margin-bottom: 15px;
    }

    .error {
        background-color: #fddcdc;
        border-left: 5px solid #e74c3c;
        padding: 10px;
        margin-bottom: 15px;
    }
</style>

</head>
<body>
    <h2>Create a New Blog Post</h2>

    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="5" cols="40" required></textarea><br><br>

        <label>Category:</label><br>
        <select name="category_id" required>
            <option value="">-- Choose Category --</option>
            <?php
            // Load categories from DB
            $result = mysqli_query($conn, "SELECT * FROM categories");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">📢 Publish</button>
    </form>
</body>
</html>


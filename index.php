<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fcefef;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #ffb3c1;
            color: #333;
            padding: 20px;
            margin: 0;
        }

        .container {
            padding: 20px;
        }

        .button {
            background-color: #a0d8ef;
            padding: 10px 15px;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            display: inline-block;
            font-size: 14px;
        }

        .button:hover {
            background-color: #92cfe7;
        }

        .logout {
            background-color: #ff9999;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #ffe0e9;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .actions a {
            margin-right: 6px;
        }

        .success-msg {
            background-color: #d4f8e8;
            border-left: 5px solid #2ecc71;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h1>Welcome, <?php echo $name; ?> (<?php echo $role; ?>)</h1>

<div class="container">
    <a href="create.php" class="button">+ Add New Post</a>
    <a href="logout.php" class="button logout">Logout</a>

    <!-- Flash message after post publish -->
    <?php if (isset($_GET['published']) && $_GET['published'] == 1): ?>
        <div class="success-msg">✅ Your post has been published!</div>
    <?php endif; ?>

    <h2>All Blog Posts</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Author ID</th>
            <th>Actions</th>
        </tr>

        <?php
        $query = "SELECT posts.*, categories.name AS category_name 
                  FROM posts 
                  LEFT JOIN categories ON posts.category_id = categories.id 
                  ORDER BY posts.id DESC";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                echo "<td>" . $row['author_id'] . "</td>";
                echo "<td class='actions'>
                        <a class='button' href='view.php?id=" . $row['id'] . "'>👁 View</a>
                        <a class='button' href='edit.php?id=" . $row['id'] . "'>✏️ Edit</a>
                        <a class='button logout' href='delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Delete this post?\")'>🗑 Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No posts found.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>


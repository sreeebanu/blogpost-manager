<?php
// Include database connection
include 'db.php';

// Initialize a message variable for feedback
$message = "";

// Run this block only when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from form and trim whitespace
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashing for security
    $role = $_POST["role"]; // role selected from dropdown (admin/author/subs)

    // Check if the email is already registered
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email); // 's' = string
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // User already exists with this email
        $message = "⚠️ This email is already registered.";
    } else {
        // New user: insert into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role); // 4 strings

        if ($stmt->execute()) {
            $message = "✅ Registration successful! <a href='login.php'>Click here to log in</a>.";
        } else {
            $message = "❌ Something went wrong: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css"> <!-- Link your existing CSS -->
</head>
<body>
    <h1>User Registration</h1>

    <!-- Show success/error message -->
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <!-- Registration Form -->
    <form method="POST">
        <input type="text" name="name" placeholder="Enter your full name" required><br>
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <input type="password" name="password" placeholder="Choose a password" required><br>

        <!-- Role dropdown with 3 options -->
        <select name="role" required>
            <option value="subs">Subscriber</option>
            <option value="author">Author</option>
            <option value="admin">Admin</option>
        </select><br>

        <button type="submit" class="btn">Register</button>
    </form>

    <a href="index.php">← Back to Home</a>
</body>
</html>

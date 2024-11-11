<?php
// Start the session
session_start();

// Include the database connection code
require 'db_connection.php'; // Assume you have this file that contains the connect_to_database function.

// Initialize a variable to hold error messages
$registerErrorMessage = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validate input and check if password matches confirmation
    if (empty($email) || empty($password) || $password !== $confirm_password) {
        $registerErrorMessage = 'Please fill all the fields and make sure passwords match.';
    } else {
        // Establish database connection
        $db = connect_to_database($servername, $username, $password, $dbname, $port);

        // Check if email already exists
        $stmt = $db->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $registerErrorMessage = 'Email is already registered.';
        } else {
            // Hash the password and insert the new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            if ($insert_stmt->execute([$email, $hashed_password])) {
                $_SESSION['user'] = $email; // Optionally log the user in
                header('Location: dashboard.php'); // Redirect to dashboard or login page
                exit;
            } else {
                $registerErrorMessage = 'There was an error registering your account.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="register-container">
        <!-- Your brand here -->
        <h2>CHUDDREY - Register</h2>

        <!-- Show error message if registration fails -->
        <?php if ($registerErrorMessage): ?>
            <div class="error"><?php echo $registerErrorMessage; ?></div>
        <?php endif; ?>

        <!-- Registration form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
            <p>Already have an account? <a href="FinalASsignment.php">Login here.</a></p>
        </form>
    </div>
</body>
</html>

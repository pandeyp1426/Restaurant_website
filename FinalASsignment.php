<?php
// Start the session
session_start();

// Database credentials
$servername = "sql3.freemysqlhosting.net";
$username = "sql3702282";
$password = "McNK7bPtAq";
$dbname = "sql3702282";
$port = 3306;

// Function to establish a database connection
function connect_to_database($servername, $username, $password, $dbname, $port) {
    try {
        $dsn = "mysql:host=$servername;dbname=$dbname;port=$port";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Could not connect to the database $dbname :" . $e->getMessage());
    }
}

// Establish database connection
$db = connect_to_database($servername, $username, $password, $dbname, $port);

// Initialize a variable to hold error messages
$errorMessage = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate credentials using the database
    try {
        $stmt = $db->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // If a user with the email was found and the passwords match
        if ($user && password_verify($password, $user['password'])) {
            // Set session variable
            $_SESSION['user'] = $email;

            // Redirect to a new page or dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            // If credentials are wrong, show an error message
            $errorMessage = 'Invalid email or password.';
        }
    } catch (PDOException $e) {
        $errorMessage = 'Login failed: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="login-container">
        <!-- Your brand here -->
        <h2>CHUDDREY - your shopping place</h2>

        <!-- Show error message if login fails -->
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Login form -->
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
                <button type="submit">Login</button>
            </div>
            <p>Don't have an account? <a href="register.php">Register here.</a></p>
        </form>
    </div>
</body>
</html>

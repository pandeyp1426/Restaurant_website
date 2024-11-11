<?php
// Initialize an array to hold any validation errors
$errors = [];

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
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data or set defaults
    $selectedItems = isset($_POST['selectedItems']) ? $_POST['selectedItems'] : [];
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $street = isset($_POST['street']) ? trim($_POST['street']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $state = isset($_POST['state']) ? trim($_POST['state']) : '';
    $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
	$country = isset($_POST['country']) ? trim($_POST['country']) : '';

    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';

    // Associative array to hold item prices for reference
    $itemPrices = [
        "Brownie" => 5.69,
        "Projector" => 7.69,
        "Lasagna" => 5.69,
        "Cheese Snack" => 20.49,
		"Hamburger" => 5.59
    ];

    // Validation checks
    if (empty($selectedItems)) {
        $errors[] = "Please select at least one item.";
    }
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }
     if (empty($street)) {
        $errors[] = "Street is required.";
    }
    if (empty($city)) {
        $errors[] = "City is required.";
    }
    if (empty($state)) {
        $errors[] = "State is required.";
    }
    if (empty($zip)) {
        $errors[] = "ZIP code is required.";
    }
	if (empty($country)) {
        $errors[] = "Country code is required.";
    }
    if (empty($paymentMethod)) {
        $errors[] = "Payment method is required.";
    }
    // If no errors, process the order
    if (empty($errors)) {
		// Establish database connection
        $db = connect_to_database($servername, $username, $password, $dbname, $port);

        $totalPrice = 0;
        $deliveryCharge = 10; // fixed shipping cost

    try {
        $stmt = $db->prepare("INSERT INTO orders (firstName, lastName, street, city, state, zip, country, paymentMethod, totalPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $street, $city, $state, $zip, $country, $paymentMethod, $totalPrice]);
        // Inform the user that the order has been placed
        echo "<p>Order has been recorded. Thank you!</p>";
    } catch (PDOException $e) {
        echo "Order could not be processed: " . $e->getMessage();
    }
        // Start of HTML output
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Order Summary</title></head><body>";
        echo "<h1>Thank you for your order, " . htmlspecialchars($firstName) . " " . htmlspecialchars($lastName) . "!</h1>";
        echo "<p>Your food will be shipped to: " . htmlspecialchars($street) . ", " . htmlspecialchars($city) . ", " . htmlspecialchars($state) . "," . htmlspecialchars($zip) . ".</p>";

        // Display for selected items and prices
        echo "<table border='1'>";
        echo "<tr><th>Item</th><th>Price</th></tr>";
        foreach ($selectedItems as $itemValue) {
            // The selectedItems are in the form of "ItemName-Price"
            list($itemName, $itemPrice) = explode('-', $itemValue);
            $itemPrice = floatval($itemPrice); // Convert the price to a float
            $totalPrice += $itemPrice; // Add to the total

            // Output each item as a row in the table
            echo "<tr><td>" . htmlspecialchars($itemName) . "</td><td>$" . number_format($itemPrice, 2) . "</td></tr>";
        }
        echo "</table>";

        // Add shipping cost and display total cost
        $totalPrice += $deliveryCharge;
        echo "<p>Delivery Charge: $" . number_format($deliveryCharge, 2) . "</p>";
        echo "<p>Total Cost: $" . number_format($totalPrice, 2) . "</p>";
        echo "</body></html>";
        exit;
    }
}

// If there are errors, redirect back to the form with error messages
if (!empty($errors)) {
    // Redirect back to form page
    header('Location: order_form.php'); // You may want to pass errors back via GET or SESSION
    exit;
}
// If the script reaches this point without exiting, redirect to the form
header('Location: order_form.php');
exit;
?>

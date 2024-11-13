<?php
require_once('db.php'); 

$productID = $_GET['id'] ?? null; // Retrieve productID from the URL, default is null

// Handle form submission to modify the drink
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $productName = $_POST['productName'];
    $listPrice = $_POST['listPrice'];

    // Update the drink in the database
    $query = "UPDATE PRODUCTS SET productName = :productName, listPrice = :listPrice WHERE productID = :productID";
    $stmt = $db->prepare($query); // Prepare the SQL statement
    $stmt->bindParam(':productName', $productName); // Bind productName parameter
    $stmt->bindParam(':listPrice', $listPrice); // Bind listPrice parameter
    $stmt->bindParam(':productID', $productID); // Specify the productID to update

    try {
        $stmt->execute(); // Execute the prepared statement
        header("Location: index.php"); // Redirect to the main page after modification
        exit(); // Prevent further execution after redirection
    } catch (PDOException $e) {
        // Display error message if query execution fails
        echo "Error executing query: " . $e->getMessage();
    }
}

// Retrieve existing data for the drink to be modified
$query = "SELECT * FROM PRODUCTS WHERE productID = :productID";
$stmt = $db->prepare($query); // Prepare the SQL statement
$stmt->bindParam(':productID', $productID); // Bind productID parameter
$stmt->execute(); // Execute the statement
$product = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the product data as an associative array

// Handle case where the product does not exist
if (!$product) {
    echo "Product not found."; // Display message if product is not found
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/add.css">
    <title>Cold Drink Cafe - Modify Drink</title>
</head>
<body>
    <main>
        <div class="header">
            <h2>Cold Drink Cafe</h2> <hr>
            <h3>Modify Drink</h3>
        </div>

        <!-- Form to modify the drink -->
        <form method="POST" action="">
            <label for="productName">Drink Name:</label>
            <input type="text" name="productName" id="productName" value="<?php echo htmlspecialchars($product['productName']); ?>" required><br>

            <label for="listPrice">Price:</label>
            <input type="number" step="0.01" name="listPrice" id="listPrice" value="<?php echo htmlspecialchars($product['listPrice']); ?>" required><br>

            <button type="submit">Modify Drink</button>
        </form>

        <br>
        <div class="buttons">
            <a href="index.php">View Drink List</a>
        </div> 
    </main>
    
    <br>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Cold Drink Cafe, Inc.</p>
    </footer>
</body>
</html>

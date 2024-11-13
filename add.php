<?php
require_once('db.php');  // Include the database connection file

// Check if the form has been submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['productName'];  // Get the product name from the form
    $listPrice = $_POST['listPrice'];  // Get the product price from the form
    $productCode = $_POST['productCode'];  // Get the product code from the form
    $categoryID = $_POST['productCategory'];  // Get the selected category ID

    // SQL query to insert a new product into the database
    $query = "INSERT INTO PRODUCTS (productName, listPrice, categoryID, productCode) 
              VALUES (:productName, :listPrice, :categoryID, :productCode)";
    $statement = $db->prepare($query);  // Prepare the SQL query
    $statement->bindParam(':categoryID', $categoryID);  // Bind the category ID to the query
    $statement->bindParam(':productName', $productName);  // Bind the product name to the query
    $statement->bindParam(':listPrice', $listPrice);  // Bind the price to the query
    $statement->bindParam(':productCode', $productCode);  // Bind the product code to the query

    try {
        $statement->execute();  // Execute the query to insert the product
        header("Location: index.php");  // Redirect to the main page after successful insertion
        exit;  // Stop the script after redirection
    } catch (PDOException $e) {
        echo "Error adding drink: " . $e->getMessage();  // Display error message if something goes wrong
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/add.css">
    <title>Cold Drink Cafe</title>
</head>
<body>
    <main>
        <div class="header">
            <h2>Cold Drink Cafe</h2> <hr>
            <h3>Add Drink</h3>
        </div>

        <!-- Form to add a new drink -->
        <form method="POST" action="">
            <!-- Product category dropdown -->
            <label for="productCategory">Product Category:</label>
            <select name="productCategory" id="productCategory">
                <option value="1">Regular</option>  <!-- Option for Regular drink category -->
                <option value="2">Zero Sugar</option>  <!-- Option for Zero Sugar drink category -->
                <option value="3">Energy</option>  <!-- Option for Energy drink category -->
            </select><br>

            <label for="productCode">Code:</label>
            <input type="text" name="productCode" id="productCode" required><br>
                
            <label for="productName">Drink Name:</label>
            <input type="text" name="productName" id="productName" required><br>

            <label for="listPrice">Price:</label>
            <input type="number" step="0.01" name="listPrice" id="listPrice" required><br>

            <button type="submit">Add Drink</button>
        </form>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Cold Drink Cafe, Inc.</p> 
    </footer>
</body>
</html>

<?php
require_once('db.php');  // Include the database connection file

/* Fetch drinks from the database */
$query = "SELECT * FROM PRODUCTS";  // SQL query to get all products from the database
$drinks = [];  // Initialize an empty array to store the drinks

try {
    /* Fetch all products */
    $result = $db->query($query);  // Run the query on the database
    $drinks = $result->fetchAll(PDO::FETCH_ASSOC);  // Store the result as an associative array

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();  // If an error occurs, display the error message
}

// Handle delete action
if (isset($_GET['delete_id'])) {  // Check if a delete request has been made
    $delete_id = $_GET['delete_id'];  // Get the ID of the product to delete
    $delete_query = "DELETE FROM PRODUCTS WHERE productID = :productID";  // SQL query to delete the product
    $statement = $db->prepare($delete_query);  // Prepare the delete query
    $statement->bindParam(':productID', $delete_id);  // Bind the product ID to the query
    $statement->execute();  // Execute the delete query
    header("Location: index.php");  // Redirect to the homepage after deletion
}

// Get category ID
if (!isset($categoryID)) {  
    $categoryID = filter_input(INPUT_GET, 'categoryID', FILTER_VALIDATE_INT);  // Get the category ID from the URL
    if ($categoryID == NULL || $categoryID == FALSE) {  
        $categoryID = 1; // Set default category to 1 if no category is selected
    }
}

// Get name for selected category
$queryCategory = 'SELECT * FROM categories WHERE categoryID = :categoryID';  // Query to get category details
$statement1 = $db->prepare($queryCategory);  // Prepare the SQL statement
$statement1->bindValue(':categoryID', $categoryID);  // Bind the category ID
$statement1->execute();  // Execute the query
$category = $statement1->fetch();  // Fetch the category data
$category_name = $category['categoryName'];  // Get the category name
$statement1->closeCursor();  // Close the statement

// Get all categories
$query = 'SELECT * FROM categories ORDER BY categoryID';  // Query to get all categories from the database
$statement = $db->prepare($query);  // Prepare the SQL query
$statement->execute();  // Execute the query
$categories = $statement->fetchAll();  // Fetch all categories
$statement->closeCursor();  // Close the statement

// Get products for selected category
$queryProducts = 'SELECT * FROM products WHERE categoryID = :categoryID ORDER BY productID';  // Query to get products for a specific category
$statement3 = $db->prepare($queryProducts);  // Prepare the SQL query
$statement3->bindValue(':categoryID', $categoryID);  // Bind the category ID
$statement3->execute();  // Execute the query
$products = $statement3->fetchAll();  // Fetch the products for the selected category
$statement3->closeCursor();  // Close the statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/index.css">
    <title>Cold Drink Cafe</title>
</head>
<body>
    <main>
        <div class="header">
            <h2>Cold Drink Cafe</h2> <hr>
            <h3>Drink List</h3>
        </div>
        
        <div class="container">
            <div class="categories">
                <span id="categories">Categories</span> <br>
                <aside>
                    <!-- Display a list of categories -->
                    <nav>
                    <ul>
                        <?php foreach ($categories as $category) : ?>
                        <li><a href=".?categoryID=<?php echo $category['categoryID']; ?>">
                                <?php echo $category['categoryName']; ?> 
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    </nav>          
                </aside>
            </div>

            <div class="product-list">
                <span id="table-title"><?php echo htmlspecialchars($category_name); ?></span> 
                <table>
                    <thead>
                        <tr>
                            <th>Name</th> 
                            <th>Price</th> 
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Display each product
                    foreach ($products as $product) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($product['productName']) . "</td>";  // Display product name
                        echo "<td>$" . number_format($product['listPrice'], 2) . "</td>";  // Display product price
                        echo "<td>
                            <a href='?delete_id=" . $product['productID'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>  <!-- Delete link with confirmation -->
                        </td>";
                        echo "<td>
                            <a href='modify.php?id=" . $product['productID'] . "'>Modify</a>  <!-- Link to modify the product -->
                        </td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="buttons">
                <a href="add.php" class="button-link">Add Drink</a> <br>  <!-- Link to add a new drink -->
                <a href="asscending.php" class="button-link">Sort Drink in Ascending Order</a> <br>  <!-- Link to sort drinks in ascending order -->
                <a href="descending.php" class="button-link">Sort Drink in Descending Order</a>  <!-- Link to sort drinks in descending order -->
            </div>  
        </div>             
    </main>
    <br>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Cold Drink Cafe, Inc.</p>
    </footer>
</body>
</html>

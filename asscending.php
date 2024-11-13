<?php
require_once('db.php');  // Include the database connection file

/* Fetch drinks from the database */
$query = "SELECT * FROM PRODUCTS";  // SQL query to get all products from the database
$drinks = [];  // Initialize an empty array to store the drinks

// Fetch drinks from the database in ascending order by productName
$query = "SELECT * FROM PRODUCTS ORDER BY productName ASC";
$drinks = [];  // Initialize an empty array to store the drinks

try {
    // Fetch all products sorted by name in ascending order
    $result = $db->query($query);
    $drinks = $result->fetchAll(PDO::FETCH_ASSOC);  // Store the fetched results in the $drinks array
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();  // Display an error message if the query fails
}

// Handle delete action when a delete request is made via GET method
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];  // Get the product ID to delete
    $delete_query = "DELETE FROM PRODUCTS WHERE productID = :productID";  // Prepare the SQL delete query
    $statement = $db->prepare($delete_query);  // Prepare the query for execution
    $statement->bindParam(':productID', $delete_id);  // Bind the product ID parameter to the query
    $statement->execute();  // Execute the query to delete the product
    header("Location: index.php");  // Redirect back to the main page after deletion
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
$queryProducts = 'SELECT * FROM products WHERE categoryID = :categoryID ORDER BY productName DESC';  // Query to get products for a specific category
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
                <span id="table-title">Regular</span>
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
                        // Loop through the fetched drinks and display each one
                        foreach ($drinks as $drink) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($drink['productName']) . "</td>";  // Display drink name
                            echo "<td>$" . number_format($drink['listPrice'], 2) . "</td>";  // Display price with 2 decimal places
                            echo "<td>
                                <a href='?delete_id=" . $drink['productID'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>";  // Link to delete the product with a confirmation prompt
                            echo "<td>
                                <a href='modify.php?id=" . $drink['productID'] . "'>Modify</a>
                            </td>";  // Link to modify the product details
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="buttons">
                <!-- Link to add a new drink or sort in descending order -->
                <a href="add.php" class="button-link"   >Add Drink</a> <br>
                <a href="descending.php" class="button-link">Sort Drink in Descending Order</a>
            </div>  
        </div>             
    </main>
    <br>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Cold Drink Cafe, Inc.</p>
    </footer>
</body>
</html>

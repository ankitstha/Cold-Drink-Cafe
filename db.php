<?php
    // Database connection details
    $dsn = 'mysql:host=localhost;dbname=cafe';  // Data Source Name, specifying the database location (localhost) and database name (cafe1)
    $username = 'root';  // Username for the database connection
    $password = '';      // Password for the database connection (empty for local development)

    try {
        // Try establishing a connection to the database using PDO
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        // If the connection fails, catch the exception and store the error message
        $error_message = $e->getMessage();
        
        // Include the error page to display the message to the user
        include('db_error.php');
        
        // Stop further execution of the script after the error
        exit();
    }
?>

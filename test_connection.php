<?php
// test_connection.php
require_once 'config/database.php';

echo "Testing database connection...<br>";

if (testDatabaseConnection()) {
    echo "Successfully connected to database!";
} else {
    echo "Failed to connect to database. Please check your configuration.";
}

// Tampilkan informasi PHP dan MySQL untuk debugging
echo "<br><br>PHP Version: " . phpversion();
echo "<br>PHP PDO Extensions: ";
print_r(PDO::getAvailableDrivers());
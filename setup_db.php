```php
<?php
// Azure MySQL connection details (replace with your values)
$host = 'agzone-mysql.mysql.database.azure.com';
$dbname = 'agzone_db';
$username = 'adminuser@agzone-mysql';
$password = 'YOUR_MYSQL_PASSWORD'; // Replace with your MySQL admin password

// Connect to MySQL database
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create membership table
    $db->exec("CREATE TABLE IF NOT EXISTS membership (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL,
        mobileNo VARCHAR(15) NOT NULL,
        password VARCHAR(255) NOT NULL
    )");

    // Insert a sample user (password: "password123")
    $db->exec("INSERT IGNORE INTO membership (username, email, mobileNo, password) VALUES (
        'testuser',
        'testuser@example.com',
        '1234567890',
        '" . password_hash('password123', PASSWORD_DEFAULT) . "'
    )");

    echo "Database table created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
```
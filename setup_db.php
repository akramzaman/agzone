<?php
// Azure MySQL connection details
$host = 'agzone-mysql.mysql.database.azure.com';
$dbname = 'agzone_db';
$username = 'adminuser@agzone-mysql';
$password = 'MySecurePass123!'; // Replace with your MySQL admin password

try {
    // Use utf8mb4 for MySQL 8.0
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Create membership table if it doesn't exist
    $db->exec("
        CREATE TABLE IF NOT EXISTS membership (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL,
            mobileNo VARCHAR(15) NOT NULL,
            password VARCHAR(255) NOT NULL
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ");

    // Insert a sample user (password: "password123")
    $samplePassword = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("
        INSERT INTO membership (username, email, mobileNo, password)
        VALUES (:username, :email, :mobileNo, :password)
        ON DUPLICATE KEY UPDATE username=username
    ");
    $stmt->execute([
        'username' => 'testuser',
        'email' => 'testuser@example.com',
        'mobileNo' => '1234567890',
        'password' => $samplePassword
    ]);

    echo "Database table created successfully and sample user added.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

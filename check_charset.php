<?php
$host = 'agzone-mysql.mysql.database.azure.com';
$dbname = 'agzone_db';
$username = 'adminuser@agzone-mysql';
$password = 'MySecurePass123!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // 1️⃣ Database charset
    $dbCharset = $pdo->query("
        SELECT DEFAULT_CHARACTER_SET_NAME AS charset, DEFAULT_COLLATION_NAME AS collation
        FROM information_schema.SCHEMATA
        WHERE SCHEMA_NAME = '$dbname';
    ")->fetch();
    echo "Database charset: {$dbCharset['charset']}, collation: {$dbCharset['collation']}<br>";

    // 2️⃣ Table charset
    $tables = $pdo->query("
        SELECT TABLE_NAME, TABLE_COLLATION
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = '$dbname';
    ")->fetchAll();
    echo "<h3>Tables:</h3>";
    foreach ($tables as $table) {
        echo "Table: {$table['TABLE_NAME']}, collation: {$table['TABLE_COLLATION']}<br>";
    }

    // 3️⃣ Column charset
    echo "<h3>Columns in 'membership' table:</h3>";
    $columns = $pdo->query("
        SELECT COLUMN_NAME, CHARACTER_SET_NAME, COLLATION_NAME
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = '$dbname'
          AND TABLE_NAME = 'membership';
    ")->fetchAll();
    foreach ($columns as $col) {
        echo "Column: {$col['COLUMN_NAME']}, charset: {$col['CHARACTER_SET_NAME']}, collation: {$col['COLLATION_NAME']}<br>";
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

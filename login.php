<?php
// Azure MySQL connection details (replace with your values)
$host = 'agzone-mysql.mysql.database.azure.com';
$dbname = 'agzone_db';
$username = 'adminuser@agzone-mysql';
$password = 'MyNewSecurePass123!'; // Replace with your MySQL admin password

// Connect to MySQL database
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utfmb3", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Query the membership table
        $stmt = $db->prepare("SELECT * FROM membership WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user and password
        if ($user && password_verify($password, $user['password'])) {
            // Successful login - display user details
            $message = "
                <h2 style='color: #2e7d32; text-align: center;'>Login Successful</h2>
                <p style='text-align: center; font-size: 16px; color: #333;'>Welcome, {$user['username']}!</p>
                <p style='text-align: center; font-size: 14px; color: #333;'>Email: {$user['email']}</p>
                <p style='text-align: center; font-size: 14px; color: #333;'>Mobile No: {$user['mobileNo']}</p>
                <p style='text-align: center;'><a href='contactUs.php' style='color: #f4b400; text-decoration: none;'>Back to Login</a></p>
            ";
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result</title>
</head>
<body style="margin: 0; font-family: Arial, sans-serif; background-color: #f9f9f9; min-height: 100vh; display: flex; flex-direction: column;">
    <div style="flex-grow: 1; max-width: 1200px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
        <?php
        if (isset($error)) {
            echo "<p style='color: red; font-size: 16px;'>$error</p>";
            echo "<p style='text-align: center;'><a href='contactUs.php' style='color: #f4b400; text-decoration: none;'>Back to Login</a></p>";
        } elseif (isset($message)) {
            echo $message;
        }
        ?>
    </div>
    <section class="footer" style="background-color: #2e7d32; color: #fff; padding: 20px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div class="left-side-footer" style="flex: 1; text-align: center;">
            <p style="margin: 0; font-size: 14px;">&copy; 2025 All Rights Reserved. AgZone.</p>
        </div>
        <div class="right-side-footer" style="flex: 1; text-align: center;">
            <p style="margin: 0; font-size: 14px;">Web Design and Development by <a href="index.html" style="color: #f4b400; text-decoration: none;">AgZone Team</a></p>
        </div>
    </section>
</body>
</html>


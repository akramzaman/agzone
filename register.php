```php
<?php
// Azure MySQL connection details (replace with your values)
$host = 'agzone-mysql.mysql.database.azure.com';
$dbname = 'agzone_db';
$username = 'adminuser@agzone-mysql';
$password = 'MySecurePass123!'; // Replace with your MySQL admin password

// Connect to MySQL database
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $mobileNo = trim($_POST['mobileNo']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($form_username) || empty($email) || empty($mobileNo) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username already exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM membership WHERE username = :username");
        $stmt->execute(['username' => $form_username]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO membership (username, email, mobileNo, password) VALUES (:username, :email, :mobileNo, :password)");
            try {
                $stmt->execute([
                    'username' => $form_username,
                    'email' => $email,
                    'mobileNo' => $mobileNo,
                    'password' => $hashed_password
                ]);
                $message = "Registration successful! <a href='contactUs.php' style='color: #f4b400; text-decoration: none;'>Log in here</a>.";
            } catch (PDOException $e) {
                $error = "Registration failed: " . $e->getMessage();
            }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script defer src="script.js"></script>
    <title>Register</title>
</head>
<body style="margin: 0; font-family: Arial, sans-serif; background-color: #f9f9f9; min-height: 100vh; display: flex; flex-direction: column;">
    <div class="contactUs" style="flex-grow: 1;">
        <div class="nav-bar" style="background-color: #2e7d32; padding: 10px 20px; max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
            <div class="left-side" style="flex: 1;">
                <div class="logo">
                    <img src="Images/agzone-logo.png" alt="AgZone-Logo" title="AgZone" style="height: 50px;">
                </div>
            </div>
            <div class="right-side" style="display: flex; align-items: center;">
                <ul id="nav-links" style="list-style: none; display: flex; gap: 20px; margin: 0; padding: 0;">
                    <li><a href="index.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-fw fa-home"></i> Home</a></li>
                    <li><a href="fertilizers.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-leaf"></i> Crops</a></li>
                    <li><a href="rent_machine.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-bus"></i> Rent machinery</a></li>
                    <li><a href="cultivation.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-asl-interpreting"></i> Cultivation & Protection</a></li>
                    <li><a href="contactUs.php" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-fw fa-user"></i> Login</a></li>
                    <li><a href="register.php" style="color: #f4b400; text-decoration: none; font-size: 16px;"><i class="fa fa-fw fa-user-plus"></i> Register</a></li>
                </ul>
            </div>
            <button class="right-bar" style="display: none; background: none; border: none; cursor: pointer; font-size: 24px; color: #fff;">
                <span class="bar" style="display: block; width: 25px; height: 3px; background-color: #fff; margin: 5px 0;"></span>
                <span class="bar" style="display: block; width: 25px; height: 3px; background-color: #fff; margin: 5px 0;"></span>
                <span class="bar" style="display: block; width: 25px; height: 3px; background-color: #fff; margin: 5px 0;"></span>
            </button>
            <div class="mobile_nav" style="display: none; position: fixed; top: 0; right: 0; width: 250px; height: 100%; background-color: #2e7d32; padding: 20px; z-index: 1000;">
                <ul id="mobile_nav_links" style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 15px;"><a href="index.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-fw fa-home"></i> Home</a></li>
                    <li style="margin-bottom: 15px;"><a href="fertilizers.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-leaf"></i> Crops</a></li>
                    <li style="margin-bottom: 15px;"><a href="rent_machine.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-bus"></i> Rent machinery</a></li>
                    <li style="margin-bottom: 15px;"><a href="cultivation.html" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-asl-interpreting"></i> Cultivation & Protection</a></li>
                    <li style="margin-bottom: 15px;"><a href="contactUs.php" style="color: #fff; text-decoration: none; font-size: 16px;"><i class="fa fa-fw fa-user"></i> Login</a></li>
                    <li style="margin-bottom: 15px;"><a href="register.php" style="color: #f4b400; text-decoration: none; font-size: 16px;"><i class="fa fa-fw fa-user-plus"></i> Register</a></li>
                </ul>
                <div class="mobile_footer" style="position: absolute; bottom: 20px; text-align: center; color: #fff; font-size: 12px;">
                    <p>Copyright &copy; 2025 AgZone. All Rights Reserved</p>
                </div>
            </div>
        </div>
        <div class="contact_container" style="max-width: 1200px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="heading" style="text-align: center; margin-bottom: 20px;">
                <p style="font-size: 28px; color: #2e7d32; margin: 0;">Register</p>
            </div>
            <div class="sub-para" style="text-align: center; margin-bottom: 20px;">
                <p style="font-size: 16px; color: #333; line-height: 1.6;">Create your AgZone account to get started.</p>
            </div>
            <div class="form-container" style="max-width: 400px; margin: 0 auto;">
                <?php if (isset($error)): ?>
                    <p style="color: red; font-size: 16px; text-align: center;"><?php echo $error; ?></p>
                <?php elseif (isset($message)): ?>
                    <p style="color: #2e7d32; font-size: 16px; text-align: center;"><?php echo $message; ?></p>
                <?php endif; ?>
                <form action="register.php" method="post" style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="input-group" style="width: 100%;">
                        <label for="username" style="font-size: 14px; color: #333; display: block; margin-bottom: 5px;">Username</label>
                        <input type="text" name="username" id="username" placeholder="Your Username" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;" required>
                    </div>
                    <div class="input-group" style="width: 100%;">
                        <label for="email" style="font-size: 14px; color: #333; display: block; margin-bottom: 5px;">Email</label>
                        <input type="email" name="email" id="email" placeholder="Your Email" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;" required>
                    </div>
                    <div class="input-group" style="width: 100%;">
                        <label for="mobileNo" style="font-size: 14px; color: #333; display: block; margin-bottom: 5px;">Mobile Number</label>
                        <input type="tel" name="mobileNo" id="mobileNo" placeholder="Your Mobile Number" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;" required>
                    </div>
                    <div class="input-group" style="width: 100%;">
                        <label for="password" style="font-size: 14px; color: #333; display: block; margin-bottom: 5px;">Password</label>
                        <input type="password" name="password" id="password" placeholder="Your Password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;" required>
                    </div>
                    <button type="submit" style="background-color: #2e7d32; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; max-width: 200px; margin: 10px auto;">Register</button>
                </form>
                <p style="text-align: center; font-size: 14px; color: #333;">Already have an account? <a href="contactUs.php" style="color: #f4b400; text-decoration: none;">Log in here</a>.</p>
            </div>
        </div>
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
```
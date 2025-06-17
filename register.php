<?php
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if (empty($name) || empty($email) || empty($role) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } else {
        try {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = "This email is already registered";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Prepare an insert statement
                $sql = "INSERT INTO users (name, email, role, password) VALUES (:name, :email, :role, :password)";
                $stmt = $conn->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':role', $role, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                
                // Execute the statement
                if ($stmt->execute()) {
                    $success = "Registration successful! You can now <a href='login.php' class='text-blue-300'>login</a>";
                } else {
                    $error = "Something went wrong. Please try again later.";
                }
            }
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GottaWork - Register</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: lora;
    }
    .container {
      position: relative;
      width: 100%;
      height: 100vh;
    }
    .bg-image {
      position: absolute;
      width: 100%;
      height: 100%;
      background-image: url('assets/loginregister.jpg');
      background-size: cover;
      background-position: center;
    }
    .form-overlay {
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .auth-form {
      background-color: rgba(0, 0, 0, 0.85);
      color: gray;
      padding: 2rem;
      width: 400px;
    }
    .back-button {
      position: absolute;
      top: 1rem;
      left: 1rem;
      background-color: white;
      color: black;
      padding: 0.5rem 1rem;
      font-weight: bold;
      text-decoration: none;
    }
    .error-message {
      color: #f56565;
      margin-bottom: 1rem;
      text-align: center;
    }
    .success-message {
      color: #48bb78;
      margin-bottom: 1rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="bg-image"></div>
    <div class="form-overlay">
      <div class="auth-form">
        <h2 class="text-center text-xl font-bold mb-6">REGISTER</h2>
        
        <?php if (!empty($error)): ?>
          <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
          <div class="success-message"><?php echo $success; ?></div>
        <?php else: ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <label>Name:</label>
          <input type="text" name="name" class="w-full p-2 mb-4" required>
          
          <label>Email:</label>
          <input type="email" name="email" class="w-full p-2 mb-4" required>
          
          <label>Role:</label>
          <select name="role" class="w-full p-2 mb-4">
            <option value="Customer">Customer</option>
          </select>
          
          <label>Password:</label>
          <input type="password" name="password" class="w-full p-2 mb-4" required>
          
          <label>Confirm Password:</label>
          <input type="password" name="confirm_password" class="w-full p-2 mb-4" required>
          
          <button type="submit" class="w-full p-2 bg-green-600 text-white font-bold">SUBMIT</button>
        </form>
        
        <p class="text-center text-sm mt-4">Already have an account? <a href="login.php" class="text-blue-300">Login</a></p>
        
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
<?php
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if (empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required";
    } else {
        try {
            // Prepare a select statement
            $sql = "SELECT id, name, email, role, password FROM users WHERE email = :email AND role = :role";
            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            
            // Execute the statement
            $stmt->execute();
            
            // Check if email exists
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify the password
                if (password_verify($password, $row['password'])) {
                    // Password is correct, start a new session
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];
                    
                    // Redirect based on role
                    switch ($row['role']) {
                        case 'Manager':
                            header("location: manager_homepage.php");
                            break;
                        case 'Staff':
                            header("location: staff_homepage.php");
                            break;
                        case 'Customer':
                            header("location: homepage.php");
                            break;
                        default:
                            header("location: index.php");
                            break;
                    }
                    exit;
                } else {
                    // Password is not valid
                    $error = "Invalid password";
                }
            } else {
                // Email doesn't exist
                $error = "No account found with that email and role";
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
  <title>GottaWork - Login</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <style>body { font-family: 'Lora', serif; }
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Lora;
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
      background-image: url('Gambar/loginregister.jpg');
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
  </style>
</head>
<body>
  <div class="container">
    <div class="bg-image"></div>
    <div class="form-overlay">
      <a href="index.html" class="back-button">← Back</a>
      <div class="auth-form">
        <h2 class="text-center text-xl font-bold mb-6">LOGIN</h2>
        
        <?php if (!empty($error)): ?>
          <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <label>Email:</label>
          <input type="email" name="email" class="w-full p-2 mb-4" required>
          
          <label>Role:</label>
          <select name="role" class="w-full p-2 mb-4" required>
            <option value="Customer">Customer</option>
            <option value="Manager">Manager</option>
            <option value="Staff">Staff</option>
          </select>
          
          <label>Password:</label>
          <input type="password" name="password" class="w-full p-2 mb-4" required>
          
          <button type="submit" class="w-full p-2 bg-green-600 text-white font-bold">SUBMIT</button>
        </form>
        
        <p class="text-center text-sm mt-4">Don't have an account? <a href="register.php" class="text-blue-300">Sign up</a></p>
      </div>
    </div>
  </div>
</body>
</html>

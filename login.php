<?php
session_start();
include("db.php");

// Initialize error array
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    
    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Proceed with login if no validation errors
    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('anime-art-fon-tekstura-pokemon-4500.jpg'); /* Pokémon background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent for readability */
            border: 2px solid #ffcb05; /* Pokémon yellow border */
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
        }
        .form-label {
            font-weight: bold;
            color: #2a75bb; /* Pokémon blue */
        }
        .btn-primary {
            background-color: #ffcb05; /* Pokémon yellow */
            border-color: #ffcb05;
            color: #2a75bb; /* Pokémon blue */
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #2a75bb;
            border-color: #2a75bb;
            color: #ffcb05;
        }
        .btn-secondary {
            background-color: #3c5aa6; /* Pokémon darker blue */
            border-color: #3c5aa6;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background-color: #ffcb05;
            border-color: #ffcb05;
            color: #2a75bb;
        }
        .register-link {
            color: #2a75bb;
            font-weight: bold;
        }
        .register-link:hover {
            color: #ffcb05;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4" style="color: #2a75bb;">Login</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
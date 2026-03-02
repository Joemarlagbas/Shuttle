<?php
session_start();
$conn = new mysqli("localhost", "root", "", "shuttle_db");
$conn->set_charset("utf8mb4");

$error = "";
$message = "";

if (isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Invalid email format!";
    } elseif ($password !== $confirm) {
        $error = "❌ Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "❌ Password must be at least 6 characters!";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM admins WHERE username=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "❌ Email already exists!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hash);

            if ($stmt->execute()) {
                $message = "✅ Registered successfully!";
            } else {
                $error = "❌ Registration failed!";
            }
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
        body {
            height:100vh; display:flex; justify-content:center; align-items:center;
            background: linear-gradient(135deg, #141e30, #243b55);
        }
        .register-card {
            width: 380px; padding:40px; background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px); border-radius:20px; box-shadow:0 20px 40px rgba(0,0,0,0.5);
            color:white; animation: fade 0.8s ease;
        }
        @keyframes fade {
            from {opacity:0; transform:translateY(20px);}
            to {opacity:1; transform:translateY(0);}
        }
        h2 { text-align:center; margin-bottom:25px; }
        .input-group { margin-bottom:20px; }
        .input-group input {
            width:100%; padding:12px; border:none; border-radius:10px;
            background: rgba(255,255,255,0.2); color:white; outline:none; transition:0.3s;
        }
        .input-group input:focus {
            background: rgba(255,255,255,0.3); box-shadow:0 0 10px #00f2fe;
        }
        button {
            width:100%; padding:12px; border:none; border-radius:10px;
            background: linear-gradient(90deg, #00f2fe, #4facfe); color:white;
            font-weight:bold; cursor:pointer; transition:0.3s;
        }
        button:hover { transform:scale(1.05); box-shadow:0 10px 20px rgba(0,0,0,0.4); }
        .error, .success { padding:10px; border-radius:8px; margin-bottom:15px; text-align:center; font-size:14px; }
        .error { background:#ff4444; }
        .success { background:#00c851; }
        .link { text-align:center; margin-top:15px; font-size:14px; }
        .link a { color:#00f2fe; text-decoration:none; font-weight:bold; }
        .link a:hover { text-decoration:underline; }
    </style>
</head>
<body>

<div class="register-card">
    <h2>🛡️ Admin Registration</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>

        <button name="register">Register</button>
    </form>

    <div class="link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

</body>
</html>
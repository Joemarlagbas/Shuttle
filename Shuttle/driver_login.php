<?php
session_start();
$conn = new mysqli("localhost","root","","shuttle_db");

$error = "";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM driver_accounts WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user && password_verify($password,$user['password'])){

        if($user['approved'] == 1){
            $_SESSION['driver_id'] = $user['id'];
            header("Location:driver_dashboard.php");
            exit();
        } else {
            $error = "Account not yet approved by admin.";
        }

    } else {
        $error = "Invalid login credentials.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Driver Login Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    overflow:hidden;
}

/* Floating animated circles */
body::before, body::after{
    content:'';
    position:absolute;
    border-radius:50%;
    background:rgba(255,255,255,0.05);
    animation: float 8s infinite ease-in-out alternate;
}
body::before{
    width:300px;
    height:300px;
    top:-80px;
    left:-80px;
}
body::after{
    width:400px;
    height:400px;
    bottom:-120px;
    right:-120px;
}

@keyframes float{
    from{ transform:translateY(0px);}
    to{ transform:translateY(40px);}
}

/* Glass Card */
.login-card{
    width:350px;
    padding:40px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.4);
    color:white;
    animation: slideIn 0.8s ease;
}

@keyframes slideIn{
    from{ opacity:0; transform:translateY(30px);}
    to{ opacity:1; transform:translateY(0);}
}

.login-card h2{
    text-align:center;
    margin-bottom:25px;
    font-weight:600;
}

.input-group{
    position:relative;
    margin-bottom:25px;
}

.input-group input{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    outline:none;
    background:rgba(255,255,255,0.2);
    color:white;
    font-size:14px;
    transition:0.3s;
}

.input-group input:focus{
    background:rgba(255,255,255,0.3);
    box-shadow:0 0 10px rgba(0,255,255,0.6);
}

/* Button */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:linear-gradient(90deg,#00c6ff,#0072ff);
    color:white;
    font-size:15px;
    font-weight:bold;
    cursor:pointer;
    transition:0.4s;
    position:relative;
    overflow:hidden;
}

button:hover{
    transform:scale(1.05);
    box-shadow:0 10px 20px rgba(0,0,0,0.4);
}

/* Loading animation */
button.loading{
    pointer-events:none;
    opacity:0.8;
}

button.loading::after{
    content:'';
    position:absolute;
    width:18px;
    height:18px;
    border:3px solid white;
    border-top:3px solid transparent;
    border-radius:50%;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    animation:spin 1s linear infinite;
}
.divider{
    margin:20px 0;
    text-align:center;
    position:relative;
    color:#ddd;
    font-size:13px;
}

.divider::before,
.divider::after{
    content:"";
    position:absolute;
    top:50%;
    width:40%;
    height:1px;
    background:rgba(255,255,255,0.3);
}

.divider::before{ left:0; }
.divider::after{ right:0; }

.register-link{
    text-align:center;
    font-size:14px;
    color:#ddd;
}

.register-link a{
    color:#00f2fe;
    text-decoration:none;
    font-weight:bold;
    position:relative;
    transition:0.3s;
}

.register-link a::after{
    content:"";
    position:absolute;
    width:0%;
    height:2px;
    left:0;
    bottom:-3px;
    background:#00f2fe;
    transition:0.3s;
}

.register-link a:hover{
    color:#ffffff;
}

.register-link a:hover::after{
    width:100%;
}

@keyframes spin{
    from{transform:translateY(-50%) rotate(0deg);}
    to{transform:translateY(-50%) rotate(360deg);}
}

/* Error Message */
.error{
    background:#ff4d4d;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
    font-size:13px;
    animation: shake 0.4s ease;
}

@keyframes shake{
    0%{transform:translateX(0);}
    25%{transform:translateX(-5px);}
    50%{transform:translateX(5px);}
    75%{transform:translateX(-5px);}
    100%{transform:translateX(0);}
}

@media(max-width:400px){
    .login-card{
        width:90%;
        padding:25px;
    }
}
</style>
</head>
<body>
<div class="login-card">
    <h2>🚐 Driver Portal</h2>

    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return showLoading(this);">
        <div class="input-group">
            <input name="username" required placeholder="Username">
        </div>

        <div class="input-group">
            <input name="password" type="password" required placeholder="Password">
        </div>

        <button name="login">Login</button>
    </form>

    <!-- Divider -->
    <div class="divider">
        <span>or</span>
    </div>

    <!-- Register Link -->
    <div class="register-link">
        Don’t have an account? 
        <a href="driver_register.php">Create Account</a>
    </div>
</div>
<script>
function showLoading(form){
    const btn = form.querySelector("button");
    btn.classList.add("loading");
    btn.innerHTML = "Signing in...";
    return true;
}
</script>

</body>
</html>
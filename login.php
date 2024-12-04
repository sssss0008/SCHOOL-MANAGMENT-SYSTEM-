<?php
session_start();

// Simulating database
$users = [
    'Teacher' => [],
    'Student' => [],
    'Parent' => []
];

$adminAccounts = [
    'admin@system.com' => 'admin123'
];

$otp = '1234'; // A static OTP for simplicity

// Initialize variables
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle login
    if (isset($_POST['login'])) {
        $message = handleLogin($_POST);
    } 
    // Handle registration
    elseif (isset($_POST['register'])) {
        $message = handleRegistration($_POST);
    } 
    // Handle password reset
    elseif (isset($_POST['reset_password'])) {
        $message = handlePasswordReset($_POST['email']);
    }
    // Handle OTP verification
    elseif (isset($_POST['verify_otp'])) {
        $message = verifyOtp($_POST['otp']);
    }
}

// Handle Login
function handleLogin($data) {
    global $users, $adminAccounts, $otp;

    $role = $data['role'];
    $email = $data['email'];
    $password = $data['password'];

    // Check if Admin
    if ($role == 'Admin' && isset($adminAccounts[$email]) && $adminAccounts[$email] == $password) {
        $_SESSION['role'] = 'Admin';
        $_SESSION['email'] = $email;
        return "Admin Login Successful! 🎉";
    }

    // Check user role login
    if (isset($users[$role]) && in_array(['email' => $email, 'password' => $password], $users[$role])) {
        $_SESSION['role'] = $role;
        $_SESSION['email'] = $email;
        return "Login Successful for $role! 🎉";
    }

    return "Invalid Credentials! ❌";
}

// Handle Registration
function handleRegistration($data) {
    global $users;

    $role = $data['regRole'];
    $email = $data['regEmail'];
    $password = $data['regPassword'];

    if ($role && $email && $password) {
        $users[$role][] = ['email' => $email, 'password' => $password];
        return "Registration Successful! 🎉";
    }

    return "Please fill in all fields! ⚠️";
}

// Handle Password Reset
function handlePasswordReset($email) {
    return "Password reset link sent to $email! 📩";
}

// Verify OTP
function verifyOtp($otpInput) {
    global $otp;
    if ($otpInput == $otp) {
        return "OTP Verified! ✅";
    }
    return "Invalid OTP! ❌";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role-Based Login & Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #000;
            color: #fff;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 400px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h1, h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin: 15px 0;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #555;
            background: #333;
            color: #fff;
            font-size: 1rem;
        }

        .btn {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #0078ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #0056d6;
            transform: scale(1.05);
        }

        .msg {
            margin-top: 20px;
            color: #ff6347;
        }
    </style>
</head>
<body>
    <div class="container" id="loginContainer">
        <h1>🎓 Role-Based Login System</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="role">Select Role:</label>
                <select name="role" id="role" required>
                    <option value="">--Select Role--</option>
                    <option value="Teacher">👩‍🏫 Teacher</option>
                    <option value="Student">📚 Student</option>
                    <option value="Parent">👨‍👩‍👧 Parent</option>
                    <option value="Admin">⚙️ Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">📧 Email:</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">🔒 Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
            <button type="submit" name="registerForm" class="btn" onclick="showRegisterForm()">Register</button>
            <button type="button" class="btn" onclick="showResetForm()">Forgot Password?</button>
        </form>
    </div>

    <div class="container" id="registerForm" style="display:none;">
        <h2>👤 Register</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="regRole">Select Role:</label>
                <select name="regRole" id="regRole" required>
                    <option value="">--Select Role--</option>
                    <option value="Teacher">👩‍🏫 Teacher</option>
                    <option value="Student">📚 Student</option>
                    <option value="Parent">👨‍👩‍👧 Parent</option>
                </select>
            </div>
            <div class="form-group">
                <label for="regEmail">📧 Email:</label>
                <input type="email" name="regEmail" id="regEmail" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="regPassword">🔒 Password:</label>
                <input type="password" name="regPassword" id="regPassword" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="register" class="btn">Register</button>
            <button type="button" class="btn" onclick="backToLogin()">Back to Login</button>
        </form>
    </div>

    <div class="container" id="otpForm" style="display:none;">
        <h2>🔑 Enter OTP</h2>
        <form method="POST" action="">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp" class="btn">Verify</button>
        </form>
    </div>

    <div class="msg"><?php echo $message; ?></div>

    <script>
        function showRegisterForm() {
            document.getElementById('loginContainer').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
        }

        function showResetForm() {
            document.getElementById('loginContainer').style.display = 'none';
            document.getElementById('otpForm').style.display = 'block';
        }

        function backToLogin() {
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('loginContainer').style.display = 'block';
        }
    </script>
</body>
</html>

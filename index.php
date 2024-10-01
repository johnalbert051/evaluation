<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    include "connection/conn.php";
    
    error_log("Login attempt for username: " . $username . ", role: " . $role);
    
    $table = '';
    $redirect = '';
    
    switch ($role) {
        case 'admin':
            $table = 'admin_table';
            $redirect = 'admin/maindashboard.php';
            break;
        case 'teacher':
            $table = 'teachers';
            $redirect = 'teacher/teacher_home.php';
            break;
        case 'student':
            $table = 'student_table';
            $redirect = 'student/student_home.php';
            break;
        default:
            $_SESSION['error'] = "Invalid role selected";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
    }
    
    $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password']) || $password === $user['password']) { // Check both hashed and unhashed
            // Password is correct, set up the session
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'] ?? $user['admin_name'] ?? '';
            $_SESSION['user_type'] = $role;
            
            // If the password was unhashed, update it to a hashed version
            if ($password === $user['password']) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE $table SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed_password, $user['id']);
                $update_stmt->execute();
                $update_stmt->close();
            }
            
            error_log("Login successful for user: " . $username . ", role: " . $role);
            error_log("Session data: " . print_r($_SESSION, true));
            
            header("Location: $redirect");
            exit();
        } else {
            // Password is incorrect
            $_SESSION['error'] = "Invalid username or password";
        }
    } else {
        // No user found with that username
        $_SESSION['error'] = "Invalid username or password";
    }
    
    $stmt->close();
    $conn->close();
    
    // If we reach here, login was unsuccessful
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Evaluation System</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-image: url('img/Picture.jpg');
    background-size: cover;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    width: 350px;
}

h2 {
    text-align: center;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    color: #666;
}

input[type="text"],
input[type="password"] {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

input[type="submit"] {
    background-color: #6f329a;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #4a1c6b;
}

.error {
    color: #ff0000;
    margin-bottom: 15px;
}

.input-shadow {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

.input-shadow:focus {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.select-wrapper {
    position: relative;
}

.select-wrapper::after {
    content: "\25BC";
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    pointer-events: none;
    color: #6f329a;
}
.form-select {
    display: block;
    width: 100%;
    padding: .575rem 2.25rem .575rem .75rem !important;
    -moz-padding-start: calc(0.75rem - 3px);
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-image: none !;
    background-repeat: no-repeat;
    background-position: right .75rem center;
    background-size: 16px 12px;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
.form-select {
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    width: 100%;
    padding: 10px 15px;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <div id="alert-container">
            <?php if($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="role" class="form-label">Select Role:</label>
                <div class="select-wrapper">
                    <select id="role" name="role" class="form-select" required>
                        <option value="" disabled selected>Choose your role</option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="input-shadow" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="input-shadow" required>
            <br>
            <input type="submit" value="Login" class="input-shadow">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        var alertContainer = document.getElementById('alert-container');
        
        if (username.trim() === '' || password.trim() === '') {
            e.preventDefault();
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Please enter both username and password.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
        }
    });
    </script>
</body>
</html>
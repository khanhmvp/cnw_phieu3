<?php
session_start();
if (isset($_SESSION['lauth']) && $_SESSION['auth'] === true) {
    // Nếu người dùng đã đăng nhập, chuyển hướng đến dashboard.php
    header("Location: dashboard.php");
    exit();
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Kiểm tra thông tin đăng nhập (ví dụ: username là "admin" và password là "123456")
    if ($username === 'admin' && $password === '123456') {
        $_SESSION['auth'] = true;
        $_SESSION['username'] = 'admin ';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu.';
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dang nhap login</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                .error {
                    color: red;
                }
                .login-card {
                    border: 1px solid #ddd;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                .form-group {
                    margin-bottom: 15px;
                }
                .form-group label {
                    display: block;
                    margin-bottom: 5px;
                }
                .form-group input {
                    width: 100%;
                    padding: 8px;
                    box-sizing: border-box;
                }
                .btn {
                    width: 100%;
                    padding: 10px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .btn:hover {
                    background-color: #45a049;
                }
               </style>
        </head>
        <body>
            <div class="login-card">
                <h2>Dang nhap</h2>
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="POST" action="login.php">
                    <div class="form-group 
}">
                        <label for="username">Ten dang nhap:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mat khau:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn">Dang nhap</button>
                </form>
            </div>
        </body>
    </html>
    <?php
}
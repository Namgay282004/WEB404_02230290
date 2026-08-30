<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SecureBank | Login</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .card {
    background: #ffffff;
    padding: 40px 36px;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    width: 360px;
    text-align: center;
  }
  .logo {
    font-size: 24px;
    font-weight: 700;
    color: #1e3c72;
    margin-bottom: 6px;
  }
  .subtitle {
    color: #777;
    font-size: 13px;
    margin-bottom: 28px;
  }
  input {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: border 0.2s;
  }
  input:focus { border-color: #2a5298; }
  button {
    width: 100%;
    padding: 12px;
    background: #1e3c72;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }
  button:hover { background: #2a5298; }
  .error {
    background: #fdecea;
    color: #c0392b;
    padding: 10px;
    border-radius: 8px;
    font-size: 13px;
    margin-bottom: 16px;
  }
  .hint {
    margin-top: 20px;
    font-size: 12px;
    color: #999;
  }
</style>
</head>
<body>
  <div class="card">
    <div class="logo">🏦 SecureBank</div>
    <div class="subtitle">Sign in to manage your account</div>

    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Log In</button>
    </form>

    <div class="hint">Demo login: testuser / password123</div>
  </div>
</body>
</html>

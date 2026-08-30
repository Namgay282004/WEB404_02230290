<?php
session_start();
include 'db.php';

// Generate a CSRF token if one doesn't already exist for this session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// FIXED: validate CSRF token before processing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_email'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "⚠️ Request blocked: Invalid or missing CSRF token.";
    } else {
        $new_email = $_POST['new_email'];
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $new_email, $user_id);
        $stmt->execute();

        $message = "✅ Email updated successfully!";
    }
}

// fetch current email to display
$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SecureBank | Dashboard</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #f4f6fb;
    min-height: 100vh;
  }
  nav {
    background: #1e3c72;
    color: white;
    padding: 16px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  nav .logo { font-weight: 700; font-size: 18px; }
  nav a {
    color: #cdd8f0;
    text-decoration: none;
    font-size: 14px;
  }
  nav a:hover { color: white; }
  .container {
    max-width: 500px;
    margin: 60px auto;
    padding: 0 20px;
  }
  .welcome {
    font-size: 22px;
    font-weight: 600;
    color: #1e3c72;
    margin-bottom: 24px;
  }
  .card {
    background: white;
    padding: 28px;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    margin-bottom: 20px;
  }
  .card h3 {
    font-size: 16px;
    color: #333;
    margin-bottom: 4px;
  }
  .card .current {
    color: #777;
    font-size: 13px;
    margin-bottom: 20px;
  }
  .current span {
    color: #1e3c72;
    font-weight: 600;
  }
  input[type=email] {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 14px;
    outline: none;
  }
  input[type=email]:focus { border-color: #2a5298; }
  button {
    padding: 10px 22px;
    background: #1e3c72;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
  }
  button:hover { background: #2a5298; }
  .success {
    background: #eafaf1;
    color: #1e8449;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 13px;
    margin-bottom: 16px;
  }
  .badge {
    display: inline-block;
    background: #fdecea;
    color: #c0392b;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 5px;
    margin-left: 8px;
    vertical-align: middle;
  }
</style>
</head>
<body>
  <nav>
    <div class="logo">🏦 SecureBank</div>
    <a href="logout.php">Logout</a>
  </nav>

  <div class="container">
    <div class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</div>

    <?php if ($message): ?>
      <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="card">
      <h3>Account Email <span class="badge" style="background:#eafaf1;color:#1e8449;">CSRF PROTECTED</span></h3>
      <div class="current">Current email: <span><?php echo htmlspecialchars($user['email']); ?></span></div>
      <form method="POST" action="dashboard.php">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="email" name="new_email" placeholder="Enter new email" required>
        <button type="submit">Update Email</button>
      </form>
    </div>
  </div>
</body>
</html>

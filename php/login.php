<?php
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user = $_POST['username'] ?? '';
  $pass = $_POST['password'] ?? '';

 $conn = new mysqli(
  getenv("DB_HOST"),
  getenv("DB_USER"),
  getenv("DB_PASS"),
  getenv("DB_NAME"),
  getenv("DB_PORT")
);


  if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
      try {
        $redis = new Predis\Client();
        $redis->set("session_" . $user, "logged_in");
        echo "success"; // ✅ must be exactly this, no extra output
      } catch (Exception $e) {
        echo "Redis error: " . $e->getMessage();
      }
    } else {
      echo "Invalid username or password";
    }
  } else {
    echo "Invalid username or password";
  }

  $stmt->close();
  $conn->close();
}
?>

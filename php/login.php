<?php
require '../vendor/autoload.php';

header("Access-Control-Allow-Origin: https://pravinviswa.github.io");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $usr = $_POST['username'] ?? '';
  $pwd = $_POST['password'] ?? '';

  $host = getenv("MYSQL_HOST");
  $port = getenv("MYSQL_PORT");
  $dbname = getenv("MYSQL_DB");
  $user = getenv("MYSQL_USER");
  $pass = getenv("MYSQL_PASS");

  $conn = new mysqli($host, $user, $pass, $dbname, $port);

  if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $usr);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($pwd, $row['password'])) {

      $redisHost = getenv("REDIS_HOST");
      $redisPort = getenv("REDIS_PORT");
      $redisUser = getenv("REDIS_USER");
      $redisPass = getenv("REDIS_PASS");

      try {
        $redis = new Predis\Client([
          'scheme'   => 'tcp',
          'host'     => $redisHost,
          'port'     => $redisPort,
          'password' => $redisPass
        ]);

        $redis->set("session_$usr", "logged_in");
        echo "success";
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

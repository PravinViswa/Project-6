<?php

header("Access-Control-Allow-Origin: https://pravinviswa.github.io");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// MySQL connection
$conn = new mysqli(
  getenv("DB_HOST"),
  getenv("DB_USER"),
  getenv("DB_PASS"),
  getenv("DB_NAME"),
  getenv("DB_PORT")
);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user     = $_POST['username'];
$email    = $_POST['email'];
$raw_pass = $_POST['password'];
$name     = $_POST['name'];
$age      = $_POST['age'];
$dob      = $_POST['dob'];
$gender   = $_POST['gender'];
$contact  = $_POST['contact'];
$about    = $_POST['about'];

// Hash the password
$pass = password_hash($raw_pass, PASSWORD_DEFAULT);

// Check if username exists
$check = $conn->prepare("SELECT * FROM users WHERE username = ?");
$check->bind_param("s", $user);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  echo "Username already taken";
  exit;
}

// Insert into MySQL
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $user, $email, $pass);

if ($stmt->execute()) {
  // Add profile to MongoDB
  require '../vendor/autoload.php';
  try {
    $mongoClient = new MongoDB\Client(getenv("MONGO_URI"));
    $collection = $mongoClient->project6->profiles;

    $profileData = [
      "username" => $user,
      "email"    => $email,
      "name"     => $name,
      "age"      => $age,
      "dob"      => $dob,
      "gender"   => $gender,
      "contact"  => $contact,
      "about"    => $about
    ];

    $collection->insertOne($profileData);
    echo "success";

  } catch (Exception $e) {
    echo "MongoDB Error: " . $e->getMessage();
  }
} else {
  echo "Something went wrong";
}

$stmt->close();
$conn->close();
?>

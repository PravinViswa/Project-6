<?php
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mongoClient = new MongoDB\Client(getenv("MONGO_URI"));

  $collection = $mongoClient->project6->profiles;

  if (isset($_POST['update'])) {
    // 📝 Update logic
    $username = $_POST['username'];
    $updateData = [
      'name'    => $_POST['name'],
      'age'     => $_POST['age'],
      'dob'     => $_POST['dob'],
      'gender'  => $_POST['gender'],
      'about'   => $_POST['about'],
      'contact' => $_POST['contact']
    ];

    $result = $collection->updateOne(
      ['username' => $username],
      ['$set' => $updateData]
    );

    echo json_encode(["success" => $result->getModifiedCount() > 0]);
  } else {
    // 📤 Fetch logic
    $username = $_POST['username'];
    $user = $collection->findOne(['username' => $username]);

    if ($user) {
      echo json_encode([
        "username" => $user['username'],
        "email"    => $user['email'],
        "name"     => $user['name'],
        "age"      => $user['age'],
        "dob"      => $user['dob'],
        "gender"   => $user['gender'],
        "about"    => $user['about'],
        "contact"  => $user['contact']
      ]);
    } else {
      echo json_encode(["error" => "User not found"]);
    }
  }
}
?>

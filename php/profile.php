<?php
require '../vendor/autoload.php';

#Render Connection
header("Access-Control-Allow-Origin:https://pravinviswa.github.io");
header("Access-Control-Allow-Headers:Content-Type");
header("Access-Control-Allow-Methods:POST,GET,OPTIONS");

if($_SERVER['REQUEST_METHOD']==='OPTIONS'){
  http_response_code(200);
  exit();
}

#Mongo Atlas Cloud
$mongoURI=getenv("MONGODB_URI");

if($_SERVER['REQUEST_METHOD']==='POST'){
  
  $mongoClient=new MongoDB\Client($mongoURI);
  $collection=$mongoClient->project6->profiles;

  if(isset($_POST['update'])){
    //Update logic
    $username=$_POST['username'];
    $updateData=[
      'name'=>$_POST['name'],
      'age'=>$_POST['age'],
      'dob'=>$_POST['dob'],
      'gender'=>$_POST['gender'],
      'about'=>$_POST['about'],
      'contact'=>$_POST['contact']
    ];

    $result=$collection->updateOne(
      ['username'=>$username],
      ['$set'=>$updateData]
    );

    echo json_encode(["success"=>$result->getModifiedCount()>0]);
  }else{
    //Fetch logic
    $username=$_POST['username'];
    $user=$collection->findOne(['username'=>$username]);

    if($user){
      echo json_encode([
        "username"=>$user['username'],
        "email"=>$user['email'],
        "name"=>$user['name'],
        "age"=>$user['age'],
        "dob"=>$user['dob'],
        "gender"=>$user['gender'],
        "about"=>$user['about'],
        "contact"=>$user['contact']
      ]);
    }else{
      echo json_encode(["error"=>"User not found"]);
    }
  }
}
?>

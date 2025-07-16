$(document).ready(function(){
  $("#register-form").submit(function(e){
    e.preventDefault();

    //To Grab all form data
    const username=$("#username").val().trim();
    const email=$("#email").val().trim();
    const password=$("#password").val().trim();
    const name=$("#name").val().trim();
    const age=$("#age").val().trim();
    const dob=$("#dob").val();
    const gender=$("#gender").val();
    const contact=$("#contact").val().trim();
    const about=$("#about").val().trim();

    //required field check
    if(!username||!email||!password){
      $("#register-alert").removeClass("d-none").text("Username, email, and password are required.");
      return;
    }

    //To Send to PHP using AJAX
    $.ajax({
      url:"https://project-6-dbrj.onrender.com/php/register.php",
      type:"POST",
      data:{
        username,
        email,
        password,
        name,
        age,
        dob,
        gender,
        contact,
        about
      },
      success:function(res){
        if(res==="success"){
          alert("Registered successfully! Now you can login.");
          window.location.href="login.html";
        }else{
          $("#register-alert").removeClass("d-none").text(res);
        }
      },
      error:function(){
        $("#register-alert").removeClass("d-none").text("Something broke. Try again.");
      }
    });
  });
});

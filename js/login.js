$(document).ready(function () {
  $("#login-form").submit(function (e) {
    e.preventDefault(); // Stop form from submitting normally

    // Get inputs
    const username = $("#username").val().trim();
    const password = $("#password").val().trim();

    // Validate
    if (!username || !password) {
      $("#login-alert").removeClass("d-none").text("Please fill in both fields.");
      return;
    }

    // Send login data to PHP
    $.ajax({
      url: "https://project-6-dbrj.onrender.com/php/login.php",
      type: "POST",
      data: { username, password },
      success: function (res) {
        if (res === "success") {
          localStorage.setItem("username", username);
          window.location.href = "profile.html";
        } else {
          $("#login-alert").removeClass("d-none").text(res);
        }
      },
      error: function () {
        $("#login-alert").removeClass("d-none").text("Server error. Please try again.");
      }
    });
  });
});

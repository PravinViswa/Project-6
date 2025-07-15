$(document).ready(function () {
  const username = localStorage.getItem("username");

  if (!username) {
    alert("Login first!");
    window.location.href = "login.html";
    return;
  }

  // Fetch user profile details
  $.ajax({
    url: "https://project-6-dbrj.onrender.com/php/profile.php",
    type: "POST",
    data: { username },
    success: function (res) {
      try {
        const data = JSON.parse(res);
        if (data.error) {
          alert(data.error);
        } else {
          $("#username").val(data.username);
          $("#email").val(data.email);
          $("#name").val(data.name || "");
          $("#age").val(data.age || "");
          $("#dob").val(data.dob || "");
          $("#gender").val(data.gender || "");
          $("#contact").val(data.contact || "");
          $("#about").val(data.about || "");
        }
      } catch (e) {
        alert("Invalid response from server.");
      }
    },
    error: function () {
      alert("Failed to fetch profile.");
    }
  });

  // Save updated profile
  $("#profile-form").submit(function (e) {
    e.preventDefault();

    const profileData = {
      username: $("#username").val().trim(),
      name: $("#name").val().trim(),
      age: $("#age").val().trim(),
      dob: $("#dob").val().trim(),
      gender: $("#gender").val().trim(),
      contact: $("#contact").val().trim(),
      about: $("#about").val().trim(),
      update: true
    };

    $.ajax({
      url: "https://project-6-dbrj.onrender.com/php/profile.php",
      type: "POST",
      data: profileData,
      success: function (res) {
        try {
          const data = JSON.parse(res);
          if (data.success) {
            alert("Profile updated successfully!");
          } else {
            alert("Update failed. Please try again.");
          }
        } catch (e) {
          alert("Server error!");
        }
      },
      error: function () {
        alert("Something went wrong while updating.");
      }
    });
  });

  // Logout
  $("#logoutBtn").click(function () {
    localStorage.removeItem("username");
    window.location.href = "index.html";
  });
});

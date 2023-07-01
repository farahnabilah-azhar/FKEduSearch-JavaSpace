document.getElementById("login-form").addEventListener("submit", function(event) {
  event.preventDefault(); // Prevent form submission

  // Get the entered username and password
  var UserName = document.getElementById("UserName").value;
  var UserPassword = document.getElementById("UserPassword").value;

  // Send an AJAX request to validate the credentials
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "check_login.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Successful login, redirect to appropriate dashboard
          if (response.role === "admin") {
            window.location.href = "admin.php";
          } else if (response.role === "user") {
            window.location.href = "user.php";
          } else if (response.role === "expertise") {
            window.location.href = "expertise.php";
          }
        } else {
          // Invalid credentials, display error message
          alert(response.message);
        }
      } else {
        // Error occurred, display error message
        alert("An error occurred. Please try again later.");
      }
    }
  };
  var data = "UserName=" + encodeURIComponent(UserName) + "&UserPassword=" + encodeURIComponent(UserPassword);
  xhr.send(data);
});

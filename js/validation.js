// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', function() {
  // Get the login form element
  var loginForm = document.getElementById('login-form');

  // Add a submit event listener to the form
  loginForm.addEventListener('submit', function(event) {
    // Prevent the form from submitting
    event.preventDefault();

    // Get the username and password input values
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    // Perform validation checks
    if (username === '') {
      alert('Please enter your username.');
      return;
    }

    if (password === '') {
      alert('Please enter your password.');
      return;
    }

    // If all checks pass, submit the form
    loginForm.submit();
  });
});

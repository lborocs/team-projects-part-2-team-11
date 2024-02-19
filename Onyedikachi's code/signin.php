<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


//session_register('email');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="left-content"></div>
        <div class="left-container">
            <img src="sample-image.jpg" alt="Sample Image">
        </div>
        <div class="right-container">
            <img src="logo.png" alt="logo Image">
            <br>
            <br>
            <h1 class="typewriter">LOGIN</h1>
            
        <form id = "loginForm">
            <div class="form-group">
                    <!-- <label for="email">Email:</label> -->
                    <input type="email" placeholder="Email" class="textbox" id="email" name="email" required>
                </div>
                <div class="form-group">
                <!-- <label for="password">Password:</label> -->
                <div style="position: relative;">
                    <input type="password" name="password1" id="password" class="textbox"
                           placeholder="Enter your password" required>
                           
                    <i id="togglePassword" class="eye-icon" onclick="togglePasswordVisibility()">👁️</i>
                </div>
                <span id="passwordMessage" class="message"></span>
                <a href="forgotPassword.php" style="color: #007bff;">Forgotten password?</a>
            </div>
                <button type="submit" class="button" id="loginButton">Login</button>
                <!-- <a href="forgotPassword.php" style="color: #007bff;">Forgotten password?</a> -->
                <p>Don't have an account? <a href="registration.php" style="color: #007bff;">Register now</a></p>
            </form>

            <script> 
             function togglePasswordVisibility() {
                var passwordInput = document.getElementById("password");
                var togglePasswordIcon = document.getElementById("togglePassword");

                passwordInput.type = passwordInput.type === "password" ? "text" : "password";
                togglePasswordIcon.textContent = passwordInput.type === "password" ? "👁️" : "🔒";
               
            }
                     document.getElementById("loginForm").addEventListener("submit", function(event) {
                        event.preventDefault(); // Prevent the form from submitting normally

                        var email = document.getElementById("email").value;
                        var password = document.getElementById("password").value;

                        console.log(email);
                        console.log(password);

                        $.ajax({
                            url: 'login.php',
                            type: 'POST',
                            data: {
                                email: $('#email').val(),
                                password: $('#password').val()
                            },
                            success: function(response) {
                                // Parse the JSON response
                                var data = JSON.parse(response);

                                console.log(data);

                                // Check if login was successful
                                if (data.success) {
                                    // Display both the success message and the role information
                                    alert('Login successful. Role: ' + data.role);
                                    


                                    //console.log(data.role);
                                    //$_SESSION['email'] = $('#email').val()
                                    //console.log($_SESSION['email']);


                                    if (data.role == "Employee"){
                                        window.location.href = "/PKMS/PKMS(Productivity)/ProductivityLanding.php";
                                    } else if (data.role == "Manager"){
                                        window.location.href = "/PKMS/PKMS(Manager)/ManagerLanding.php";
                                    } else{
                                        window.location.href = "/PKMS/PKMS(Manager)/admin.php";
                                    }
                                } else {
                                    alert('Login failed: ' + data.message);
                                }

                            },
                            error: function() {
                                alert("Error");
                            }
                        });
});


                    
                    
               
            </script>
        </div>
    </div>
</body>
</html>

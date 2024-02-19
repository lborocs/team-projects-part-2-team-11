<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registration Page</title>
    <style>
    </style>
</head>

<body>

<div class="container">
    <div class="left-container">
        <img src="sample-image.jpg" alt="Left Container Image">
    </div>
    <div class="right-container">
        <h1 class="typewriter">REGISTER</h1>
        <form id="registrationForm" method="post" action="register.php" onsubmit = "return validateForm()"> <!-- Update the form action -->
            <div class="form-group">
                <label for="email"></label>
                <input type="text" name="email" id="email" class="textbox" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="username"></label>
                <input type="text" name="username" id="username" class="textbox" placeholder="Enter your username"
                       required>
            </div>

            <div class="form-group">
                <label for="password"></label>
                <div style="position: relative;">
                    <input type="password" name="password1" id="password" class="textbox"
                           placeholder="Enter your password" required>
                    <i id="togglePassword" class="eye-icon" onclick="togglePasswordVisibility()">👁️</i>
                </div>
                <span id="passwordMessage" class="message"></span>
            </div>

            <div class="form-group">
                <label for="confirmPassword"></label>
                <div style="position: relative;">
                <input type="password" name="confirmPassword" id="confirmPassword" class="textbox"
                       placeholder="Confirm your password" required>
                <i id="toggleconfirmPassword" class="eye-icon" onclick="toggleconfirmPasswordVisibility()">👁️</i>
                </div>
                <span id="confirmPasswordMessage" class="message"></span>
            </div>

            <div class="form-group">
                <label for="securityQuestion"></label>
                <select name="securityQuestion" id="securityQuestion" class="dropdown common-input" required>
                    <option value="" disabled selected>Select a security question</option>
                    <option value="question1">What is your favorite colour</option>
                    <option value="question2">What is the name of your first pet?</option>
                    <option value="question3">In which city were you born?</option>
                </select>
                <span id="securityQuestionMessage" class="message"></span>
            </div>

            <div class="form-group">
                <label for="securityAnswer"></label>
                <input type="text" name="securityAnswer" id="securityAnswer" class="textbox"
                       placeholder="Type your answer" required>
                <span id="securityAnswerMessage" class="message"></span>
            </div>

            <button type="submit" class="button" onsubmit="validateForm()">Register</button>
        </form>
        <br>
        <a href="signin.php" style="color: #007bff;">Back To Log In</a>
    </div>
</div>
<script>
    // Toggle password visibility
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var togglePasswordIcon = document.getElementById("togglePassword");

        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        togglePasswordIcon.textContent = passwordInput.type === "password" ? "👁️" : "🔒";
    }

    function toggleconfirmPasswordVisibility() {
        var passwordInput = document.getElementById("confirmPassword");
        var togglePasswordIcon = document.getElementById("toggleconfirmPassword");

        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        togglePasswordIcon.textContent = passwordInput.type === "password" ? "👁️" : "🔒";
    }
    function validateForm() {
    // Display password validation message
    var passwordMessage = document.getElementById("passwordMessage");
    passwordMessage.textContent = ""; 

    // Display confirm password validation message
    var confirmPasswordMessage = document.getElementById("confirmPasswordMessage");
    var confirmPasswordInput = document.getElementById("confirmPassword");
    confirmPasswordMessage.textContent = ""; 

    // Get password input
    var passwordInput = document.getElementById("password");

    // Check if passwords match
    if (passwordInput.value !== confirmPasswordInput.value) {
        confirmPasswordMessage.textContent = "Passwords do not match";
        return false; // Prevent form submission
    }

    // Check password complexity
    var password = passwordInput.value;
        if (!password.match(/[A-Z]/) || !password.match(/[0-9]/) || !password.match(/[^A-Za-z0-9]/) || password.length < 8) {
            passwordMessage.textContent = "Password must contain at least one capital letter, one number, one symbol, and be at least 8 characters long";
            return false; // Prevent form submission
}

    // If all checks pass, return true to allow form submission
    // alert("Registration successful!");
    return true;
}
 </script>




</body>
</html>

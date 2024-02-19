<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="left-container">
            <!-- Your logo or image -->
            <img src="logo.png" alt="Logo">
        </div>
        <div class="right-container">
            <form action="reset_password.php" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                <div style="position: relative;">
                    <label for="newPassword">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword" class="textbox" required>
                    <span id="passwordMessage" class="error-message"></span> <!-- Display password validation message -->
                    <i id="togglePassword" class="eye-icon" onclick="togglePasswordVisibility()">👁️</i>
                     <!-- <i id="togglePassword" class="eye-icon" onclick="togglePasswordVisibility()">👁️</i> -->
                </div>
                <div class="form-group">
                    <div style="position: relative;">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="textbox" required>
                    <span id="confirmPasswordMessage" class="error-message"></span> <!-- Display confirm password validation message -->
                    <i id="togglePassword" class="eye-icon" onclick="toggleconfirmPasswordVisibility()">👁️</i> <!-- Toggle confirm password visibility button -->
                </div>
                <!-- Add hidden input fields to store email and password -->
                <input type="hidden" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                <input type="hidden" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                <button type="submit" class="button">Reset Password</button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("newPassword");
            var togglePasswordIcon = document.getElementById("togglePassword");

            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
            togglePasswordIcon.textContent = passwordInput.type === "password" ? "👁️" : "🔒";
        }

        // Toggle confirm password visibility
        function toggleconfirmPasswordVisibility() {
            var confirmPasswordInput = document.getElementById("confirmPassword");
            var toggleconfirmPasswordIcon = document.getElementById("toggleconfirmPassword");

            confirmPasswordInput.type = confirmPasswordInput.type === "password" ? "text" : "password";
            toggleconfirmPasswordIcon.textContent = confirmPasswordInput.type === "password" ? "👁️" : "🔒";
        }

        // Form validation
        function validateForm() {
            // Display password validation message
            var passwordMessage = document.getElementById("passwordMessage");
            passwordMessage.textContent = "";

            // Display confirm password validation message
            var confirmPasswordMessage = document.getElementById("confirmPasswordMessage");
            confirmPasswordMessage.textContent = "";

            // Get password input
            var passwordInput = document.getElementById("newPassword");
            var confirmPasswordInput = document.getElementById("confirmPassword");

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
            return true;
        }
    </script>
</body>
</html>

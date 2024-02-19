<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <div class="left-content"></div>
        <div class="left-container">
            <img src="sample-image.jpg" alt="Sample Image">
        </div>
        <div class="right-container">
            <br>
            <br>
            <h1 class="typewriter">FORGOTTEN PASSWORD</h1>
            <br>
            <h2>Please enter your email and answer your security question to recover</h2>
            <br>
            <br>
            <form action="forgot_password.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" placeholder="Email" class="textbox" name="email" id="email" required>
                </div>
                <!-- Add a hidden input field to store the email value -->
                <input type="hidden" name="reset_email" id="reset_email">
                <div class="form-group">
                    <label for="security_answer">Security Answer:</label>
                    <input type="text" placeholder="Security Answer" class="textbox" name="security_answer" id="security_answer" required>
                </div>
                <button type="submit" class="button">Reset Password</button>
                <a href="signin.php" style="color: #007bff;">Back To Log In</a>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            // Get the value of the email input field
            var email = document.getElementById('email').value;
            // Set the value of the hidden input field
            document.getElementById('reset_email').value = email;
        });
    </script>
</body>
</html>

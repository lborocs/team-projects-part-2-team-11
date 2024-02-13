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
            <form action="reset_password.php" method="post">
                <div class="form-group">
                    <label for="newPassword">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword" class="textbox" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="textbox" required>
                </div>
                <button type="submit" class="button">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>

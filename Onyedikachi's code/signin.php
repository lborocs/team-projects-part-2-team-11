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
            
            <form id="loginForm" action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" placeholder="Email" class="textbox" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" placeholder="Password" class="textbox2" id="password" name="password" required>
                    <br>
                    <a href="forgot_password.html" style="color: #007bff;">Forgotten password?</a>
                </div>
                <button type="submit" class="button" id="loginButton">Login</button>
                <p>Don't have an account? <a href="registration.php" style="color: #007bff;">Register now</a></p>
            </form>

            <script>
                // document.getElementById("loginForm").addEventListener("submit", function(event) {
                //     event.preventDefault(); // Prevent the form from submitting normally
                    
                //     var email = document.getElementById("email").value;
                //     var password = document.getElementById("password").value;

                //     console.log(email);
                //     console.log(password);

                    // $.ajax({
                    //     url:'login.php',
                    //     type:'POST',

                    //     data :{
                    //         email : $('#email').val(),
                    //         password : $('#password').val()
                    //     },
                    //     success : function(data){
                    //         // Redirect the user to the dashboard or another page upon successful login
                    //         console.log(data.success);
                    //         if (data.success == true){
                    //             alert('yes');
                    //         }else {
                    //             alert('no')
                    //         }
                    //         //window.location.href = "registration.php";
                    //     },
                    //     error : function(){
                    //         alert("Error");
                    //     }
                    // });


                    // $.ajax({
                    //     url: 'login.php',
                    //     type: 'POST',
                    //     data: {
                    //         email: $('#email').val(),
                    //         password: $('#password').val()
                    //     },
                    //     success: function(data) {
                    //         // Parse the JSON response
                    //         var responseData = JSON.parse(data);

                    //         // Check if login was successful
                    //         if (responseData.success) {
                    //             alert('Login successful');
                    //             // Redirect to the dashboard or another page
                    //             window.location.href = "dashboard.php";
                    //         } else {
                    //             alert('Login failed: ' + responseData.message);
                    //         }
                    //     },
                    //     error: function() {
                    //         alert("Error");
                    //     }
                     
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
                                    alert('Login successful');
                                    // Redirect to the dashboard or another page
                                    window.location.href = "registration.php";
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

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="container">
    <div class="additions">
<h2>Add User</h2>

<form id="add_user_form" action="add_user.php" method="post">
    <label for="user_email">User Email:</label>
    <input type="email" name="user_email" id="user_email" required>
    <br><br>
    <label for="role">Role:</label>
    <select name="role" id="role">
        <option value="Manager">Manager</option>
        <option value="Employee">Employee</option>
    </select>
    <br><br>
    <input type="submit" value="Add User">
</form>
</div>
<div class="additions">


<h2>Update User Role</h2>

<form id="update_role_form" action="update_role.php" method="post">
    <label for="user_email_update">Select User:</label>
    <select name="user_email_update" id="user_email_update">
        <?php
        // Database connection details
        $servername = "localhost";
        $username = "team011";
        $password = "JAEWyfUXpzqank7scpWm";
        $dbname = "team011";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve list of user emails
        $sql = "SELECT user_email FROM Users_Details";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['user_email'] . "'>" . $row['user_email'] . "</option>";
            }
        }
        ?>
    </select>
    <br><br>
    <label for="role_update">New Role:</label>
    <select name="role_update" id="role_update">
        <option value="Manager">Manager</option>
        <option value="Employee">Employee</option>
    </select>
    <br><br>
    <input type="submit" value="Update Role">
</form>
    </div>
    </div>
    <div class="container">
        <div class="additions">
<h2>List of Users</h2>

<table border="1">
    <thead>
        <tr>
            <th>User Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody id="user_table_body">
        <?php
                include 'get_users.php';
    ?>
    </tbody>
</table>
    </div>
    </div>
<script>
    function updateUserTable() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_users.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('user_table_body').innerHTML = xhr.responseText;
                } else {
                    console.error('Error fetching user data: ' + xhr.status);
                }
            }
        };
        xhr.send();
    }

    // Function to handle AJAX requests
    function sendAjaxRequest(formId, successCallback) {
        var formData = new FormData(document.getElementById(formId));

        var xhr = new XMLHttpRequest();
        xhr.open('POST', document.getElementById(formId).getAttribute('action'), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    successCallback(xhr.responseText);
                } else {
                    alert('Error: ' + xhr.status);
                }
            }
        };
        xhr.send(formData);
    }

    // Event listener for add user form submission
    // document.getElementById('add_user_form').addEventListener('submit', function(event) {
    //     event.preventDefault();
    //     sendAjaxRequest('add_user_form', function(response) {
    //         if (response.trim() === 'success') { // Trim to remove any whitespace
    //         // if (response === 'success') {
    //             alert('User added successfully.');
    //             document.getElementById('add_user_form').reset();
    //             // Update user table after successful addition
    //             updateUserTable();
    //         } else {
    //             alert('Error adding user: ' + response);
    //         }
    //     });
    // });
    document.getElementById('add_user_form').addEventListener('submit', function(event) {
    event.preventDefault();
    sendAjaxRequest('add_user_form', function(response) {
        var users = JSON.parse(response);
        if (Array.isArray(users)) {
            // Update user selection options for changing roles
            var selectUser = document.getElementById('user_email_update');
            selectUser.innerHTML = ''; // Clear existing options
            users.forEach(function(user) {
                var option = document.createElement('option');
                option.value = user;
                option.textContent = user;
                selectUser.appendChild(option);
            });
            alert('User added successfully.');
            document.getElementById('add_user_form').reset();
            updateUserTable();
        } else {
            alert('Error adding user: ' + response);
        }
    });
});

    // Event listener for update role form submission
    document.getElementById('update_role_form').addEventListener('submit', function(event) {
        event.preventDefault();
        sendAjaxRequest('update_role_form', function(response) {
            if (response === 'success') {
                alert('User role updated successfully.');
                document.getElementById('update_role_form').reset();
                // Update user table after successful role update
                updateUserTable();
            } else {
                alert('Error updating user role: ' + response);
            }
        });
    });

    // Initial call to populate user table
    updateUserTable();

</script>

</body>
</html>

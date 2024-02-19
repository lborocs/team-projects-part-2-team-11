<?php
    //set up the connection to the data base
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $servername = "localhost";
    $dbname = "team011";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $User = $_POST['User'];
    } else {
        $User = "";
    }

    $User = 'frederick.umah@makeitall.org.uk';
    echo '<label for="assignedTo">Choose User:</label>
        <select id="assignedTo" name="assignedTo" class="input-field" required>
            <option value="">Select User</option>';
    if ($_POST['Project_ID'] != '0'){
        //get all the users
        $employeeQuery = "SELECT user_email, username 
        FROM `Users_Details` 
        WHERE role = 'Employee' and username IS NOT NULL and user_email IS NOT '$User';";
        $allEmployees = mysqli_query($conn, $employeeQuery);

        if (mysqli_num_rows($allEmployees) > 0){
            //output data of each row
            while ($row = mysqli_fetch_array($allEmployees)){
                echo "<option value='$row[0]'>".$row[1]."</option>";
            }
        }
    } else {
        echo '<option value="$User">Yourself</option>';
    }
    echo '</select><br><br>';
?>
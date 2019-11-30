<!DOCTYPE html>
<html lang="en">
<head>
    <title>Task Assignment</title>
    <link rel="stylesheet" type="text/css" href="Task.css">
    <style>
        .error {
            color: #FF0000;
        }
        table {
margin: 8px;
margin-left: auto;
    margin-right: auto;

}
th {
font-family: Arial, Helvetica, sans-serif;
font-size: 1.5em;
background: #666;
color: black;
padding: 2px 6px;
border-collapse: separate;
border: 1px solid black;
}

td {
font-family: Arial, Helvetica, sans-serif;
font-size: 1em;
border: 1px solid black;
color: black;
}

    </style>
</head>
<body>
    <header>
        <h1>
            Welcome to our Task Allocation app
        </h1>
    </header>

    <?php

    $dbHost = "localhost";
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'task_allocation';

    //Create connection and select DB
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if ($db->connect_error) {
        die("Unable to connect database: " . $db->connect_error);
    }
    $fnameErr = "";
    $fn = 0;
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    if (isset($_POST['add'])) {
         //$tfname = $_POST['name'];
         $tfname = test_input($_POST["name"]); {
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/", $tfname)) {
                $fnameErr = "Invalid name";
            } else {
                $fn = TRUE;
            }
        }
        $interest = $_POST['interest'];      
         $task = $_POST['tasks'];
         if($fn){
        $sql = "INSERT INTO employees (name, interest, Tasks) VALUES ('$tfname', '$interest','$task');";
        if ($db->query($sql) === TRUE) {
           echo "Record added succesfully";
        } else {
            echo "Error<br>" . $db->error;
        }
    }
}
if (isset($_POST['show'])) {

    $sql = "SELECT * FROM employees";

    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        echo "<table> <tr><th>ID</th><th>Name</th><th>Interest</th><th>Tasks Assigned</th> </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr onmouseover=\"hilite(this)\" onmouseout=\"lowlite(this)\"><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td> " . $row["interest"] . "</td><td> " . $row["Tasks"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results found";
    }
}
if (isset($_POST['delete'])) {
    $sql = "DELETE from employees";

if ($db->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $db->error;
}}
if (isset($_POST['deleteR'])) {
    $n = $_POST['n'];
    $sql = "DELETE from employees WHERE id='$n'";

if ($db->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $db->error;
}
}
    $db->close();
?>
    <div class="two">
    
        <form class="empl" method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
            <h2>Employees</h2>
            <input type="text" name="name" placeholder="Enter employees name" required>
            <span class="error">* <?php echo $fnameErr; ?></span>
            <input type="text" name="interest" required placeholder="Enter his/her interest"><br>
            <input type="text" name="tasks" required placeholder="Enter his/her Projects"><br>
            <br><br>
            <input type="submit" name = "add" value="Add">
            
    <br><br> </form>
    <form><div><img id="con" src="grp.jpg" /></div></form>
  
    <form class = "tsk">
            <h2>Task</h2>
            <input type="text" name="task" placeholder="Enter task name" required><br>
            <input type="text" name="interest" required placeholder="Enter field of interest"><br>
            <input type="text" name="parts" required placeholder="Enter modules of Project"><br>
            <br><br>
            <input type="submit" name = "result" value="Result">
        <br><br>
    </form>

</div>
<div class = "two">
<form method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
<input id="show" type="submit" name = "show" value="Show Record"></form>
<center><form class = "delete" method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
    <br><br>
    <input type="int" name="n" placeholder="Enter id of record" required><br> <br>
    <div id = "container">
    <input type="submit" name="deleteR" value = "Delete"><br>
</div> 
</form></center>
<form method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
    <input id="delete" type="submit" name = "delete" value="Delete Record">
</form>

</div>
</body>
</html>

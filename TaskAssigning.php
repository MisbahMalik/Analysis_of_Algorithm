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
            margin-left: auto;
            margin-right: auto; 
            margin-bottom: 20px;
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
    // a class containing the detail of an employee
    class employee
    {
        public $name;
        public $interests = array();
        public $tasks = array();

        function set_name($name)
        {
            $this->name = $name;
        }
        function set_interests($intrsts){
            $this->interests = $intrsts;
        }
        function set_tasks($tsks){
            $this->tasks = $tsks;
        }
        function display(){
            echo nl2br("\nName: " . $this->name);
            echo "Interests: ";
            for($i = 0; $i<count($this->interests); $i++){
                echo $this->interests[$i];
            }
            echo "Tasks: ";
            for($i = 0; $i<count($this->tasks); $i++){
                echo $this->tasks[$i];
            }
        }
    }
     //Insertion sorting, It will sort the employees on the basis of no. of projects on which they are currently working
     function sortEmployee($employee) {
        $item = $j = 0;
       for ($i = 1; $i < count($employee); ++$i) {
           $item = $employee[$i];
           $j = $i - 1;
           while (count($employee[$j]->tasks) > count($item->tasks)) {
               $employee[$j + 1] = $employee[$j];
               --$j;
               if ($j < 0) break;
           }
           $employee[$j + 1] = $item;
       }
       return $employee; //returns sorted list of employees
   }
   function find($Interest, $array){
       for($j = 0; $j < count($array); ++$j){
           if($Interest == $array[$j]){
               return true;
           }
       }
       return false;
   } 
   function TaskAssigning($employee, $Task, $Interest, $parts, mysqli $db) {
       $List = array();
       
       for ($k = 0; $k < $parts; $k++) { 
           for ($i = 0; $i < count($employee); ++$i) {
               
               if (find($Interest, $employee[$i]->interests) && count($employee[$i]->tasks) < 5) {
                   array_push($List, $employee[$i]->name);
                   array_push($employee[$i]->tasks, $Task);
                   $nm = $employee[$i]->name;
                   $ts = implode(", ", $employee[$i]->tasks) ;
                   $querry = "UPDATE employees SET Tasks = '$ts' WHERE name = '$nm'"; 
                   if ($db->query($querry) === TRUE) {
                       echo "";
                   } else {
                       echo "Error<br>" . $db->error;
                   }
                   break;
               }  
               
           } 
           $employee = sortEmployee($employee);
       } 
       $str ="The Desired list of employees to whom the project is assigned, is: \n" ;
       $next = "\n";
       echo nl2br($str);
       //document.write("Selected "+ List);
      for($n = 0; $n < $parts; ++$n){
          if(is_null($List[$n])){
           echo nl2br("Can't assign the project\n");
          }else{
           echo nl2br($List[$n]);
           echo nl2br($next);
       }
       }
       
   }
  
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
    
    //insert new record in table
    if (isset($_POST['add'])) {
        
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
        if ($fn) {
            $sql = "INSERT INTO employees (name, interest, Tasks) VALUES ('$tfname', '$interest','$task');";
            if ($db->query($sql) === TRUE) {
                echo "Record added succesfully";
            } else {
                echo "Error<br>" . $db->error;
            }
        }
    }

    if (isset($_POST['result'])) {
        
        $naam = $interests = $tasks = "";
        $ftask = $finterest =$Err=$p=  "";
        $fparts = 0;
        // $employee = array();
        $empl = array();
        $assignedlst = array();
        $arrintrsts = "";
        $fparts = test_input($_POST["parts"]); {
          if (!preg_match("/^[0-9]*$/", $fparts)) {
                $Err = "Only numbers are allowed\n";
            } else {
                $p = TRUE;
            } 
        }
        $empl = sortEmployee($empl);
        $ftask = $_POST['task'];   
        $finterest = $_POST['interest'];   
        $fparts = $_POST['parts'];  
        $sql = "SELECT * FROM employees";
        $result = $db->query($sql);
        if($p){
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $employe = new employee();
                $naam = $row["name"];
                $employe->name = $naam;
                $interests = $row["interest"];     // preg_split()
                $arrintrsts = explode(",", $interests);
                $employe->set_interests($arrintrsts);
                $tasks = $row["Tasks"];
                $taskk = explode(",", $tasks);
                $employe->set_tasks($taskk);
                array_push($empl, $employe);
              }
            TaskAssigning($empl, $ftask, $finterest, $fparts, $db);
           } else {
            echo "0 results found";
        }
    }
    }
    //delete whole record
    if (isset($_POST['delete'])) {
        $sql = "DELETE from employees";

        if ($db->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $db->error;
        }
    }
    //delete a specific record
    if (isset($_POST['deleteR'])) {
        $n = $_POST['n'];
        $sql = "DELETE from employees WHERE id='$n'";

        if ($db->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $db->error;
        }
    }
    //display all record
    if (isset($_POST['show'])) {
        $sql1 = "SELECT * FROM employees";
        $naam = "";       
        $result = $db->query($sql1);
        if ($result->num_rows > 0) {
            echo "<div class = 'table'>";
            echo "<table> <tr><th>ID</th><th>Name</th><th>Interest</th><th>Tasks Assigned</th> </tr>";
            while ($row = $result->fetch_assoc()) {
                $naam = $row["name"];
                echo "<tr onmouseover=\"hilite(this)\" onmouseout=\"lowlite(this)\"><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td>
                <td> " . $row["interest"] . "</td><td> " . $row["Tasks"] . "</td></tr>";
            }
            echo "</table>";
            echo "</div>";
            }
         else {
            echo "0 results found";
        }
    
    }
    $db->close();
    ?>
    <div class="two">

        <form class="empl" method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
            <h2>Employees</h2>
            <input type="text" name="name" placeholder="Enter employees name" required>
            <span class="error"> <?php echo $fnameErr; ?></span>
            <input type="text" name="interest" required placeholder="Enter his/her interest">
           
            <input type="text" name="tasks" required placeholder="Enter his/her Projects"><br>
            <br><br>
            <input type="submit" name="add" value="Add">

            <br><br>
        </form>
        <form>
            <div><img id="con" src="grp.jpg" /></div>
        </form>

        <form class="tsk" method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
            <h2>Task</h2>
            <input type="text" name="task" placeholder="Enter task name" required><br>
            <input type="text" name="interest" placeholder="Enter field of interest" required><br>
            <input type="text" name="parts" placeholder="Enter modules of Project" required><br>
            <br><br>
            <input type="submit" name="result" value="Result">
            <br><br>
        </form>

    </div>
    <div class="two">
        <form method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
            <input id="show" type="submit" name="show" value="Show Record"></form>
        <center>
            <form class="delete" method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
                <br><br>
                <input type="int" name="n" placeholder="Enter id of record" required><br> <br>
                <div id="container">
                    <input type="submit" name="deleteR" value="Delete"><br>
                </div>
            </form>
        </center>
        <form method="POST" action="http://localhost/TaskAssignment/TaskAssigning.php">
            <input id="delete" type="submit" name="delete" value="Delete Record">
        </form>

    </div>
</body>
</html>
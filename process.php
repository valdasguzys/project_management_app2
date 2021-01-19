<?php
function pre_r ($array) {
    print("<pre>");
    print_r($array);
    print("</pre>");
}

$servername = "localhost";
$username = "root";
$password = "mysql";
$db_name = "proj_man2";

$conn = mysqli_connect($servername, $username, $password, $db_name);

//default values for input fields
$first_name = '';
$last_name = ''; 
$project_title = ''; 

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
};

// SQL query for 'projects' tab
$sql_projects = 
        "SELECT 
            projects.project_title, projects.project_id, 
            GROUP_CONCAT(concat_ws(' ', employees.first_name, employees.last_name ) SEPARATOR ', ') as employees
        FROM projects
        LEFT JOIN employees
        ON employees.project = projects.project_id
        GROUP BY projects.project_title, projects.project_id
        ORDER BY projects.project_id;";

$result_projects = mysqli_query($conn, $sql_projects);

// function displays projects table
function display_projects ($result_projects) {
    $i=1;
    if (mysqli_num_rows($result_projects) > 0) {
        while($row = mysqli_fetch_assoc($result_projects)) {
            echo "<tr>";
            echo "<td>" . $i++ . "</td>
                  <td>" . $row["project_title"] . "</td>
                  <td>" . $row["employees"]. "</td>
                  <td><a href='process.php?delete_project=". $row['project_id'] ."'><button class='btn btn-danger' type='submit'>Delete</button></a>
                  <a href='index.php?action=projects&edit_project=". $row['project_id'] ."'><button class='btn btn-info' type='submit'>Edit</button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "0 results";
    }
}

// SQL query for 'employees' tab
$sql_employees = 
        "SELECT employees.employee_id, concat_ws(' ', employees.first_name, employees.last_name ) as employees, projects.project_title, employees.project
        FROM employees 
        LEFT JOIN projects 
        ON employees.project = projects.project_id
        ORDER BY employees.employee_id;";

$result_employees = mysqli_query($conn, $sql_employees);

// function displays employees 

function display_employees ($result_employees) {
    $i=1;
    if (mysqli_num_rows($result_employees) > 0) {
        while($row = mysqli_fetch_assoc($result_employees)) {
            echo "<tr>";
            echo "<td>" . $i++ . "</td>
                  <td>" . $row["employees"] . "</td>
                  <td>" . $row["project_title"] . "</td>
                  <td><a href='process.php?delete_employee=". $row['employee_id'] ."'><button class='btn btn-danger' type='submit'>Delete</button></a>
                      <a href='index.php?action=employees&edit_employee=". $row["employee_id"] ."'><button class='btn btn-info' type='submit'>Edit</button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "0 results";
    }
}

// delete project 
if(isset($_GET['delete_project'])){
    $id = $_GET['delete_project'];
    $sql_delete_project = "DELETE FROM projects WHERE project_id = $id";
    $delete_project = mysqli_query($conn, $sql_delete_project);

    if (!$delete_project) {
        die('Could not delete data: ' . mysqli_error($delete_project));
    } else {
        header('Location: ./?action=projects');
        exit;
    }
}

// delete employee 
if(isset($_GET['delete_employee'])){
    $id = $_GET['delete_employee'];
    $sql_delete_employee = "DELETE FROM employees WHERE employee_id = $id";
    $delete_employee = mysqli_query($conn, $sql_delete_employee);
 
    if (!$delete_employee) {
        die('Could not delete data: ' . mysqli_error($delete_employee));
    } else {
        header('Location: ./?action=employees');
        exit;
    }
  
}

//editing employee
if (isset($_GET['edit_employee'])) {
    $id = $_GET['edit_employee'];

    $sql_edit_employee = "SELECT first_name, last_name FROM employees WHERE employee_id= $id";
    $edit_employee = mysqli_query($conn, $sql_edit_employee);
    $row = mysqli_fetch_assoc($edit_employee);   
    if (mysqli_num_rows($edit_employee) > 0) {
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];    
    }
}

//dropdown projects list
// $result_projects values comes from SQL query from project table listing
function assign_project ($result_projects) {
    if (mysqli_num_rows($result_projects) > 0) {
        while($row = mysqli_fetch_assoc($result_projects)) {
            echo '<option value=' . $row['project_id'] . '>' . $row['project_title'] . '</option>';
        }
    } else {
        echo '0 results';
    }
}

// edit and assign project for employee
if (isset($_POST['update_employee']) AND isset($_POST['assign_project'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];   
    $last_name = $_POST['last_name']; 
    $project = $_POST['assign_project'];

    $sql = "UPDATE employees SET first_name='$first_name', last_name='$last_name'  WHERE employee_id= $id";
    mysqli_query($conn, $sql);
    $sql = "UPDATE employees SET project ='$project' WHERE employee_id= $id";
    mysqli_query($conn, $sql);

    header('Location: ./?action=employees');
}


//editing project
if (isset($_GET['edit_project'])) {
    $id = $_GET['edit_project'];
    $sql_edit_project = "SELECT project_title FROM projects WHERE project_id= $id";
    $edit_project = mysqli_query($conn, $sql_edit_project);
    $row = mysqli_fetch_assoc($edit_project);   
    if (mysqli_num_rows($edit_project) > 0) {
        $project_title = $row['project_title'];   
    }
}

if (isset($_POST['update_project'])) {
    $id = $_POST['id'];
    $project_title = $_POST['project_title'];    
    $sql = "UPDATE projects SET project_title='$project_title' WHERE project_id= $id";
    mysqli_query($conn, $sql);
    header('Location: ./?action=projects');
}



// add new project

if (isset($_POST['save_project'])) {
    $project_title = $_POST['project_title'];

    $sql_insert_project = "INSERT INTO projects (project_title)
                            VALUES ('$project_title');";

    $insert_project = mysqli_query ($conn, $sql_insert_project);    

    if (!$insert_project) {
        die('Could not enter data: ' . mysqli_error($insert_project));;
    } else {
        header('Location: ./?action=projects');
        exit;
    }
}

// add new emplyee

if (isset($_POST['save_employee'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
   
    $sql_insert_employee = "INSERT INTO employees (first_name, last_name)
                            VALUES 
                            ('$first_name', '$last_name');";
   
    $insert_employee = mysqli_query ($conn, $sql_insert_employee);
    if (!$insert_employee) {
        die('Could not enter data: ' . mysqli_error($insert_employee));;
    } else {
        header('Location: ./?action=employees');
        exit;
    }
}


    
mysqli_close($conn);



?>
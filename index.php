<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>Project Management System</title>
</head>
<body>

<?php require 'process.php'; ?>   

<div class="container mt-3">

<div class="justify-content-center">

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" href="?action=projects">Projects</a>
        </li>
        <li class="nav-item">
            <a  class="nav-link" href="?action=employees">Employees</a>
        </li>
    </ul>

    <table class="table">

        <!-- prints out projects into the table -->
        <?php 
        if(isset($_GET['action']) and $_GET['action'] == 'projects') { ?>
               <!-- new project form -->
            <form class="row " action="process.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="row">
                    <div class="col">
                        <label class="form-label" for="project_title">Add new project</label>
                        <input class="form-control " type="text" name="project_title" placeholder="Enter project title" value="<?php echo $project_title ?>">
                    </div>
                </div>
                <div class="g-3 p-2">
                    <?php if(isset($_GET['edit_project'])) { ?>
                        <button class="btn btn-info" type="submit" name="update_project">Update</button> 
                    <?php } else {  ?>
                        <button class="btn btn-primary" type="submit" name="save_project">Submit</button> 
                    <?php }; ?>
                </div>
            </form>
            
            
            <tr>
                <th>Number</th>
                <th>Project title</th>
                <th>Full name</th>
                <th>Action</th>
            </tr>

        <?php display_projects ($result_projects); 
            }; ?>

        <!-- prints out employees into the table -->

        <?php if(isset($_GET['action']) and $_GET['action'] == 'employees') { ?>
            <!-- new employee form -->
            <form class="row " action="process.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="row">
                    <div  class="col">
                        <label class="form-label" for="first_name">Add new employee</label>
                        <input class="form-control" type="text" name="first_name" placeholder="Enter employee first name" value="<?php echo $first_name ?>">
                    </div>
                    <div class="col">
                        <label class="form-label" for="last_name">.</label>
                        <input class="form-control" type="text" name="last_name" placeholder="Enter employee last name" value="<?php echo $last_name ?>">
                    </div>
                </div>
                <div class="g-3 p-2">
                <?php if(isset($_GET['edit_employee'])) { ?>
                        <button class="btn btn-info" type="submit" name="update_employee">Update</button> 
                    <?php } else {  ?>
                        <button class="btn btn-primary" type="submit" name="save_employee">Submit</button> 
                    <?php }; ?>
                </div>
            </form>

            <tr>
                <th>Number</th>
                <th>Employee name</th>
                <th>Project title</th>
                <th>Action</th>
            </tr>
            
        <?php display_employees ($result_employees); 
            }; ?>

   
        
    </table>
</div>
</div>




</body>
</html>
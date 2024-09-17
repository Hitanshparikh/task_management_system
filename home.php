<?php
// Check if a session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$twhere = "";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
<div class="col-12">
    <div class="card">
        <div class="card-body">
            Welcome <?php echo $_SESSION['login_name']; ?>!
        </div>
    </div>
</div>
<hr>

<?php 
$where = "";
if($_SESSION['login_type'] == 2){
  $where = " where manager_id = '{$_SESSION['login_id']}' ";
}elseif($_SESSION['login_type'] == 3){
  $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}

$where2 = "";
if($_SESSION['login_type'] == 2){
  $where2 = " where p.manager_id = '{$_SESSION['login_id']}' ";
}elseif($_SESSION['login_type'] == 3){
  $where2 = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
?>

<!-- User Cards Section (Before Project Progress) -->
<div class="container-fluid">
    <div class="row">
        <?php
        $user_query = $conn->query("SELECT * FROM users");
        if ($user_query) {
            while($user = $user_query->fetch_assoc()):
                $full_name = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
                $avatar = htmlspecialchars($user['avatar']);
                // Replaced assigned_to with project_id
                $tasks_assigned_query = $conn->query("SELECT * FROM task_list WHERE project_id = {$user['id']}");
                $tasks_assigned = $tasks_assigned_query ? $tasks_assigned_query->num_rows : 0;
        ?>
            <!-- Card with Matte Glass Effect and Clickable -->
            <div class="col-sm-12 col-md-6 col-lg-4 mb-3"> <!-- Adjusted for responsive grid -->
                <a href="index.php?page=view_project&id=<?php echo $user['id']; ?>" class="card-link">
                    <div class="card user-card">
                        <div class="avatar">
                            <img src="<?php echo $avatar ? 'assets/uploads/' . $avatar : 'assets/uploads/default.png'; ?>" alt="<?php echo $full_name; ?>">
                        </div>
                        <div class="details">
                            <h3><?php echo $full_name; ?></h3>
                            <p><strong>Tasks Assigned:</strong> <?php echo $tasks_assigned; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
        <?php
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
    </div>
</div>

<!-- Project Progress Section -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <b>Project Progress</b>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="30%">
                                <col width="35%">
                                <col width="15%">
                                <col width="15%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
                            $qry = $conn->query("SELECT * FROM project_list $where order by name asc");
                            if ($qry) {
                                while($row = $qry->fetch_assoc()):
                                    $prog = 0;
                                    $tprog_query = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}");
                                    $tprog = $tprog_query ? $tprog_query->num_rows : 0;
                                    $cprog_query = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3");
                                    $cprog = $cprog_query ? $cprog_query->num_rows : 0;
                                    $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
                                    $prog = $prog > 0 ? number_format($prog, 2) : $prog;
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td>
                                    <a><?php echo ucwords($row['name']); ?></a>
                                    <br>
                                    <small>Due: <?php echo date("Y-m-d", strtotime($row['end_date'])); ?></small>
                                </td>
                                <td class="project_progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="<?php echo $prog; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog; ?>%">
                                        </div>
                                    </div>
                                    <small><?php echo $prog; ?>% Complete</small>
                                </td>
                                <td class="project-state">
                                    <?php
                                        if($stat[$row['status']] == 'Pending'){
                                            echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                                        }elseif($stat[$row['status']] == 'Started'){
                                            echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
                                        }elseif($stat[$row['status']] == 'On-Progress'){
                                            echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
                                        }elseif($stat[$row['status']] == 'On-Hold'){
                                            echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                                        }elseif($stat[$row['status']] == 'Over Due'){
                                            echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                                        }elseif($stat[$row['status']] == 'Done'){
                                            echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id']; ?>">
                                        <i class="fas fa-folder"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php
                            } else {
                                echo "<tr><td colspan='5'>No projects found.</td></tr>";
                            }
                            ?>
                            </tbody>  
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Small Boxes -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3><?php echo $conn->query("SELECT * FROM project_list $where")->num_rows; ?></h3>
                    <p>Total Projects</p>
                </div>
                <div class="icon">
                    <i class="fa fa-layer-group"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3><?php echo $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t INNER JOIN project_list p ON p.id = t.project_id $where2")->num_rows; ?></h3>
                    <p>Total Tasks</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tasks"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Light Theme with White Background -->
<style>
    body {
        background-color: #f4f4f9; /* Light background */
        font-family: Arial, sans-serif;
    }

    .card {
        background: rgba(255, 255, 255, 0.7); /* Matte glass effect */
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #333;
    }

    .card.user-card {
        width: 100%; /* Full width for responsiveness */
        margin: 10px;
        text-align: center;
        padding: 20px;
    }

    .card-link {
        text-decoration: none;
        color: inherit;
    }

    .avatar img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    h3, p {
        margin: 0;
    }

    .progress-bar {
        background-color: #28a745;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    /* Sidebar Customization */
    .sidebar {
        background: rgba(255, 255, 255, 0.1); /* Matte glass effect */
        backdrop-filter: blur(10px);
        color: #fff; /* Text color */
        border-right: 1px solid rgba(255, 255, 255, 0.2); /* Optional: border to enhance glass effect */
    }

    .sidebar a {
        color: #fff; /* Link color */
    }

    .sidebar .nav-item .nav-link {
        color: #fff; /* Link color */
    }

    .sidebar .nav-item .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.2); /* Lighten on hover */
    }

    .sidebar .nav-item .nav-link.active {
        background-color: rgba(255, 255, 255, 0.3); /* Light background for active link */
    }

    .sidebar .nav-icon {
        color: #fff; /* Icon color */
    }
</style>

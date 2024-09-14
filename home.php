<?php include('db_connect.php'); ?>
<?php
session_start();
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

<!-- Project Progress -->
<div class="row">
    <div class="col-md-8">
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
                            <th>#</th>
                            <th>Project</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th></th>
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
                                $prod_query = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}");
                                $prod = $prod_query ? $prod_query->num_rows : 0;
                                if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                                    if($prod > 0 || $cprog > 0)
                                        $row['status'] = 2;
                                    else
                                        $row['status'] = 1;
                                elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                                    $row['status'] = 4;
                                endif;
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

    <!-- User Cards -->
    <div class="col-md-4">
        <div class="dashboard">
            <?php
            $user_query = $conn->query("SELECT * FROM users"); // Adjust the query as needed
            if ($user_query) {
                while($user = $user_query->fetch_assoc()):
                    $full_name = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
                    $avatar = htmlspecialchars($user['avatar']);
                    $tasks_assigned_query = $conn->query("SELECT * FROM task_list WHERE assigned_to = {$user['id']}");
                    $tasks_assigned = $tasks_assigned_query ? $tasks_assigned_query->num_rows : 0;
                    $tasks_due_query = $conn->query("SELECT * FROM task_list WHERE assigned_to = {$user['id']} AND due_date < CURDATE()");
                    $tasks_due = $tasks_due_query ? $tasks_due_query->num_rows : 0;
            ?>
                <div class="card user-card">
                    <div class="avatar">
                        <img src="<?php echo $avatar ? 'assets/uploads/' . $avatar : 'assets/uploads/default.png'; ?>" alt="<?php echo $full_name; ?>">
                    </div>
                    <div class="details">
                        <h3><?php echo $full_name; ?></h3>
                        <p><strong>Tasks Assigned:</strong> <?php echo $tasks_assigned; ?></p>
                        <p><strong>Tasks Due:</strong> <?php echo $tasks_due; ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php
            } else {
                echo "<p>No users found.</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Small Boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-12">
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
    <div class="col-12 col-sm-6 col-md-12">
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

<style>
    .dashboard {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 20px;
    }

    .user-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: calc(100% / 2 - 30px); /* Adjusted for 2 cards per row with gap */
        box-sizing: border-box;
        transition: transform 0.3s;
        overflow: hidden; /* Prevents content from overflowing */
    }

    .user-card:hover {
        transform: scale(1.05);
    }

    .avatar img {
        border-radius: 50%;
        width: 80px;
        height: 80px;
        object-fit: cover;
    }

    .details {
        margin-top: 10px;
    }
</style>

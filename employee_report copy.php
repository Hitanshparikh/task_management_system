<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "tms_db");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch user details
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 5; // Default to 5 if not provided

// Initialize variable for filtering
$time_frame = isset($_GET['time_frame']) ? $_GET['time_frame'] : '';

// Create date condition based on user input
$date_condition = '';

if ($time_frame) {
    switch ($time_frame) {
        case 'current_week':
            $date_condition = " AND YEARWEEK(t.date_created, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'last_week':
            $date_condition = " AND YEARWEEK(t.date_created, 1) = YEARWEEK(CURDATE(), 1) - 1";
            break;
        case 'this_month':
            $date_condition = " AND MONTH(t.date_created) = MONTH(CURDATE()) AND YEAR(t.date_created) = YEAR(CURDATE())";
            break;
        case 'last_month':
            $date_condition = " AND MONTH(t.date_created) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(t.date_created) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
            break;
        case 'last_3_months':
            $date_condition = " AND t.date_created >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            break;
        case 'last_6_months':
            $date_condition = " AND t.date_created >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
            break;
        case 'this_year':
            $date_condition = " AND YEAR(t.date_created) = YEAR(CURDATE())";
            break;
        case 'last_year':
            $date_condition = " AND YEAR(t.date_created) = YEAR(CURDATE() - INTERVAL 1 YEAR)";
            break;
    }
}

// SQL query to fetch tasks associated with the user
$query = "
    SELECT t.id, t.task, t.description, t.status, t.date_created, u.firstname, u.lastname, u.avatar
    FROM task_list t
    JOIN project_list p ON p.id = t.project_id
    JOIN users u ON u.id = p.user_ids
    WHERE FIND_IN_SET('$user_id', p.user_ids) > 0" . $date_condition . "
    ORDER BY t.task ASC
";

$result = $mysqli->query($query);

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

// Fetch user details for displaying on the page
$user_query = "SELECT firstname, lastname, avatar FROM users WHERE id = '$user_id'";
$user_result = $mysqli->query($user_query);

if ($user_result && $user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
} else {
    $user_data = [
        'firstname' => 'Unknown',
        'lastname' => 'User',
        'avatar' => 'default-avatar.png' // Provide a default avatar image
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        .full-description {
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
        }
        @media print {
            .user-avatar {
                width: 100px;
                height: 100px;
            }
            .full-description {
                max-width: none;
                overflow: visible;
            }
            thead { display: table-header-group; } /* Ensure header appears only once */
        }
        .back-button {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php?page=reports" class="btn btn-secondary back-button">
        <i class="fas fa-arrow-left"></i> Back
    </a>

    <div class="text-center mb-4">
        <h4>User: <?php echo ucwords($user_data['firstname'] . ' ' . $user_data['lastname']); ?></h4>
        <img src="assets/uploads/<?php echo $user_data['avatar']; ?>" alt="User's Photo" class="user-avatar" style="width: 100px; height: 100px; border-radius: 50%;">
    </div>

    <form method="GET" action="employee_report.php" class="mb-3">
        <div class="form-group">
            <label for="timeFrame">Select a Time Frame:</label>
            <select name="time_frame" class="form-control">
                <option value="">Select...</option>
                <option value="current_week">Current Week</option>
                <option value="last_week">Last Week</option>
                <option value="this_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="last_3_months">Last 3 Months</option>
                <option value="last_6_months">Last 6 Months</option>
                <option value="this_year">This Year</option>
                <option value="last_year">Last Year</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <div class="card-body p-0">
        <div class="table-responsive" id="printable">
            <table class="table table-condensed m-0 table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Task Assigned Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    while($row = $result->fetch_assoc()):
                        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                        $desc = strtr(html_entity_decode($row['description']), $trans);
                        $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td><b><?php echo ucwords($row['task']) ?></b></td>
                        <td><p class="full-description"><?php echo strip_tags($desc) ?></p></td>
                        <td>
                            <?php 
                            if($row['status'] == 1){
                                echo "<span class='badge badge-secondary'>Pending</span>";
                            } elseif($row['status'] == 2){
                                echo "<span class='badge badge-primary'>On-Progress</span>";
                            } elseif($row['status'] == 3){
                                echo "<span class='badge badge-success'>Done</span>";
                            }
                            ?>
                        </td>
                        <td><?php echo $row['date_created']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <button id="printBtn" class="btn btn-success">Print Report</button>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {
            var printableContent = document.getElementById('printable').innerHTML;
            var userName = "<?php echo ucwords($user_data['firstname'] . ' ' . $user_data['lastname']); ?>";
            var userAvatar = "<?php echo $user_data['avatar']; ?>";
            var printDate = new Date().toLocaleDateString();

            var newWindow = window.open("", "", "width=900,height=600");

            newWindow.document.write(`
                <html>
                <head>
                    <title>Print Employee Report</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                    <style>
                        .user-avatar {
                            width: 100px;
                            height: 100px;
                            border-radius: 50%;
                        }
                        .full-description {
                            white-space: normal;
                            overflow: visible;
                            text-overflow: clip;
                        }
                        @media print {
                            thead { display: table-header-group; }
                            tfoot { display: table-footer-group; }
                        }
                    </style>
                </head>
                <body>
                    <div class='text-center'>
                        <h4>User: ${userName}</h4>
                        <img src='assets/uploads/${userAvatar}' alt='User's Photo' class='user-avatar'>
                        <p>Project Task Report as of ${printDate}</p>
                    </div>
                    <br>
                    <table class="table table-condensed m-0 table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Task Assigned Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${printableContent}
                        </tbody>
                    </table>
                </body>
                </html>
            `);

            newWindow.document.close();
            newWindow.focus();
            newWindow.print();
            newWindow.close();
        });
    </script>

</div>

</body>
</html>

<?php
// Close database connection
$mysqli->close();
?>


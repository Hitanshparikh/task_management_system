<?php 
include 'db_connect.php';

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM task_list WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $qry = $stmt->get_result()->fetch_array();
    
    foreach ($qry as $k => $v) {
        $$k = $v;
    }
    
    $stmt->close();
}
?>
<div class="container-fluid">
    <dl>
        <dt><b class="border-bottom border-primary">Task</b></dt>
        <dd><?php echo ucwords(htmlspecialchars($task, ENT_QUOTES, 'UTF-8')) ?></dd>
    </dl>
    <dl>
        <dt><b class="border-bottom border-primary">Status</b></dt>
        <dd>
            <?php 
            if ($status == 1) {
                echo "<span class='badge badge-secondary'>Pending</span>";
            } elseif ($status == 2) {
                echo "<span class='badge badge-primary'>On-Progress</span>";
            } elseif ($status == 3) {
                echo "<span class='badge badge-success'>Done</span>";
            }
            ?>
        </dd>
    </dl>
    <dl>
        <dt><b class="border-bottom border-primary">Description</b></dt>
        <dd><?php echo html_entity_decode(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')) ?></dd>
    </dl>
    <?php if (!empty($file_url) && is_file($file_url)): ?>
        <dl>
            <dt><b class="border-bottom border-primary">Attached File</b></dt>
            <dd>
                <a href="<?php echo htmlspecialchars($file_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank">View File</a>
            </dd>
        </dl>
    <?php endif; ?>
</div>

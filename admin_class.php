<?php
session_start();
ini_set('display_errors', 1);

class Action {
    private $db;

    public function __construct() {
        ob_start();
        include 'db_connect.php';
        $this->db = $conn;
    }

    function __destruct() {
        $this->db->close();
        ob_end_flush();
    }

    private function sanitize($value) {
        return htmlspecialchars(strip_tags($value));
    }

    function login() {
        $email = $this->sanitize($_POST['email']);
        $password = md5($this->sanitize($_POST['password']));
        $stmt = $this->db->prepare("SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    if ($key != 'password' && !is_numeric($key)) {
                        $_SESSION['login_' . $key] = $value;
                    }
                }
            }
            return 1;
        } else {
            return 2;
        }
    }

    function logout() {
        session_destroy();
        header("Location: login.php");
    }

    function login2() {
        $student_code = $this->sanitize($_POST['student_code']);
        $stmt = $this->db->prepare("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM students WHERE student_code = ?");
        $stmt->bind_param('s', $student_code);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    if ($key != 'password' && !is_numeric($key)) {
                        $_SESSION['rs_' . $key] = $value;
                    }
                }
            }
            return 1;
        } else {
            return 3;
        }
    }

    function save_user() {
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k=? ";
                } else {
                    $data .= ", $k=? ";
                }
            }
        }
        if (!empty($_POST['password'])) {
            $data .= ", password=? ";
            $password = md5($this->sanitize($_POST['password']));
        }
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? " . (!empty($_POST['id']) ? " AND id != ? " : ''));
        $stmt->bind_param('s', $this->sanitize($_POST['email']));
        if (!empty($_POST['id'])) {
            $stmt->bind_param('ss', $this->sanitize($_POST['email']), $_POST['id']);
        }
        $stmt->execute();
        $check = $stmt->get_result()->num_rows;
        if ($check > 0) {
            return 2;
        }

        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }

        if (empty($_POST['id'])) {
            $sql = "INSERT INTO users SET $data";
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = "UPDATE users SET $data WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $_POST['id']);
        }
        $params = array_values($_POST);
        if (!empty($password)) {
            $params[] = $password;
        }
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return 1;
        }
    }

    function signup() {
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
                if ($k == 'password') {
                    $v = md5($this->sanitize($v));
                }
                if (empty($data)) {
                    $data .= " $k=? ";
                } else {
                    $data .= ", $k=? ";
                }
            }
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? " . (!empty($_POST['id']) ? " AND id != ? " : ''));
        $stmt->bind_param('s', $this->sanitize($_POST['email']));
        if (!empty($_POST['id'])) {
            $stmt->bind_param('ss', $this->sanitize($_POST['email']), $_POST['id']);
        }
        $stmt->execute();
        $check = $stmt->get_result()->num_rows;
        if ($check > 0) {
            return 2;
        }

        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }

        if (empty($_POST['id'])) {
            $sql = "INSERT INTO users SET $data";
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = "UPDATE users SET $data WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $_POST['id']);
        }
        $params = array_values($_POST);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            if (empty($_POST['id'])) {
                $_SESSION['login_id'] = $this->db->insert_id;
            }
            foreach ($_POST as $key => $value) {
                if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key)) {
                    $_SESSION['login_' . $key] = $value;
                }
            }
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])) {
                $_SESSION['login_avatar'] = $fname;
            }
            return 1;
        }
    }

    function update_user() {
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k=? ";
                } else {
                    $data .= ", $k=? ";
                }
            }
        }
        if (!empty($_POST['password'])) {
            $data .= ", password=? ";
            $password = md5($this->sanitize($_POST['password']));
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? " . (!empty($_POST['id']) ? " AND id != ? " : ''));
        $stmt->bind_param('s', $this->sanitize($_POST['email']));
        if (!empty($_POST['id'])) {
            $stmt->bind_param('ss', $this->sanitize($_POST['email']), $_POST['id']);
        }
        $stmt->execute();
        $check = $stmt->get_result()->num_rows;
        if ($check > 0) {
            return 2;
        }

        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }

        if (empty($_POST['id'])) {
            $sql = "INSERT INTO users SET $data";
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = "UPDATE users SET $data WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $_POST['id']);
        }
        $params = array_values($_POST);
        if (!empty($password)) {
            $params[] = $password;
        }
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            foreach ($_POST as $key => $value) {
                if ($key != 'password' && !is_numeric($key)) {
                    $_SESSION['login_' . $key] = $value;
                }
            }
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])) {
                $_SESSION['login_avatar'] = $fname;
            }
            return 1;
        }
    }

    function change_password() {
        $old_password = md5($this->sanitize($_POST['old_password']));
        $new_password = md5($this->sanitize($_POST['new_password']));
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? AND password = ?");
        $stmt->bind_param('is', $_SESSION['login_id'], $old_password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param('si', $new_password, $_SESSION['login_id']);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return 1;
            }
        } else {
            return 2;
        }
    }

    function delete_user() {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $_POST['id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return 1;
        }
    }

    function save_student() {
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k=? ";
                } else {
                    $data .= ", $k=? ";
                }
            }
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }
        if (empty($_POST['id'])) {
            $sql = "INSERT INTO students SET $data";
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = "UPDATE students SET $data WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $_POST['id']);
        }
        $params = array_values($_POST);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return 1;
        }
    }

    function save_task() {
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k=? ";
                } else {
                    $data .= ", $k=? ";
                }
            }
        }
        if (empty($_POST['id'])) {
            $sql = "INSERT INTO tasks SET $data";
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = "UPDATE tasks SET $data WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $_POST['id']);
        }
        $params = array_values($_POST);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return 1;
        }
    }

    function delete_task() {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param('i', $_POST['id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return 1;
        }
    }
}
?>

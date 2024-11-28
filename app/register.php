<?php
session_start();
if (isset($_POST['full_name']) && isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['role'])) {
    include "../DB_connection.php";

    function validate_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    $full_name = validate_input($_POST['full_name']);
    $user_name = validate_input($_POST['user_name']);
    $password = validate_input($_POST['password']);
    $role = validate_input($_POST['role']);

    if (empty($full_name)) {
        $em = "Full name is required";
        header("Location: ../register.php?error=$em");
        exit();
    } else if (empty($user_name)) {
        $em = "User name is required";
        header("Location: ../register.php?error=$em");
        exit();
    } else if (empty($password)) {
        $em = "Password is required";
        header("Location: ../register.php?error=$em");
        exit();
    } else {

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_name]);

        if ($stmt->rowCount() > 0) {
            $em = "Username already exists!";
            header("Location: ../register.php?error=$em");
            exit();
        }

        if ($role === 'admin') {

            $sql = "SELECT * FROM users WHERE role = 'admin'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $em = "An admin account already exists. You cannot create a new admin.";
                header("Location: ../register.php?error=$em");
                exit();
            }
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $user_name, $hashed_password, $role]);

        $success = "Account created successfully! Please login.";
        header("Location: ../login.php?success=$success");
        exit();
    }
} else {
    $em = "Unknown error occurred";
    header("Location: ../register.php?error=$em");
    exit();
}

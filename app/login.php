<?php
session_start();

if (isset($_POST['user_name']) && isset($_POST['password'])) {
    include "../DB_connection.php";

    function validate_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $user_name = validate_input($_POST['user_name']);
    $password = validate_input($_POST['password']);

    $response = [];

    if (empty($user_name)) {
        $response['error'] = "User name is required";
        echo json_encode($response);
        exit();
    } else if (empty($password)) {
        $response['error'] = "Password is required";
        echo json_encode($response);
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_name]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();
            $usernameDb = $user['username'];
            $passwordDb = $user['password'];
            $role = $user['role'];
            $id = $user['id'];

            if ($user_name === $usernameDb) {
                if (password_verify($password, $passwordDb)) {
                    $_SESSION['role'] = $role;
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $usernameDb;

                    setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/"); 
                    setcookie("username", $usernameDb, time() + (30 * 24 * 60 * 60), "/");

                    $response['success'] = "Login successful. Redirecting...";
                    echo json_encode($response);
                    exit();
                } else {
                    $response['error'] = "Incorrect username or password";
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['error'] = "Incorrect username or password";
                echo json_encode($response);
                exit();
            }
        } else {
            $response['error'] = "Account not found. Please create an account first.";
            echo json_encode($response);
            exit();
        }
    }
} else {
    $response['error'] = "Unknown error occurred";
    echo json_encode($response);
    exit();
}

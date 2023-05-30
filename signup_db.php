<?php

session_start();
require_once 'config/db.php';


if (isset($_POST['signup'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $urole = "user";

    if (empty($firstname)) {
        $_SESSION['error'] = "Please enter your firstname";
        header('Location: index.php');
    } else if (empty($lastname)) {
        $_SESSION['error'] = "Please enter your lastname";
        header('Location: index.php');
    } else if (empty($email)) {
        $_SESSION['error'] = "Please enter your email";
        header('Location: index.php');
    } else if (empty($password)) {
        $_SESSION['error'] = "Please enter your password";
        header('Location: index.php');
    } else if (strlen($_POST['password']) < 5) {
        $_SESSION['error'] = "Password must be 5-20 in length";
        header('Location: index.php');
    } else if (empty($c_password)) {
        $_SESSION['error'] = "Please confirm your password.";
        header('Location: index.php');
    } else if ($password != $c_password) {
        $_SESSION['error'] = "Passwords do not match";
        header('Location: index.php');
    } else {
        try {
            $chech = $conn->query("SELECT email FROM users");
            $row = $chech->fetch(PDO::FETCH_ASSOC);


            if ($row['email'] == $email) {
                $_SESSION['warning'] = "Email already in the system  <a href='signin.php'>Click Login</a>";
                header('Location: index.php');
            } else if (!isset($_SESSION['error'])) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (firstname ,lastname, email, password , urole) 
                                VALUES (:firstname ,:lastname ,:email ,:password , :urole)");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->bindParam(":urole", $urole);

                $stmt->execute();
                $_SESSION['success'] = "Sign up successfully <a href='signin.php' class ='alert-link'>Click Login</a>";
                header("location: index.php");
            } else {
                $_SESSION['error'] = "ERROR";
                header("location: index.php");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getLine();
            echo "Error: " . $e->getMessage();
        }
    }
}

<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        redirect('register.php');
    }

    if (isUserExists($email, $username)) {
        $_SESSION['error'] = "Email or username already exists.";
        redirect('register.php');
    }

    $hashed_password = hashPassword($password);

    $sql = "INSERT INTO users (name, username, email, password, role) VALUES (:name, :username, :email, :password, 'user')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'password' => $hashed_password
    ]);

    $_SESSION['success'] = "Registration successful. Please login.";
    redirect('login.php');
}

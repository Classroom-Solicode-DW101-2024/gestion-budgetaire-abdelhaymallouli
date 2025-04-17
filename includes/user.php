<?php

require_once __DIR__ . '/../config/config.php';


function addUser($user, $connection) {
    $name = $user['name'];
    $email = $user['email'];
    $rawPassword = $user['password'];
    $password = password_hash($rawPassword, PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if($stmt->rowCount() > 0){
        return "Email already registered";
    }

    $stmt = $connection->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $password]);

    return "User added successfully";
}

// function to check if the user is already 
function checkUser($email, $pdo) {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}

?>

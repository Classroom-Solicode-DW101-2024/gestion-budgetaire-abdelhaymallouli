<?php
session_start();

require_once __DIR__ . '/../includes/user.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); 
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email
    if (empty($email)) {
        $errors['email'] = "L'email est requis.";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Le mot de passe est requis.";
    }

    if (empty($errors['email']) && empty($errors['password'])) {
        $user = login($email, $password, $connection);

        if ($user)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nom'];
            header('location: dashboard.php');
            exit;
        } else {
            $errors = "Email ou mot de passe incorrect.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <style>
        body {
            margin: 0;
        }
        main {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        main form {
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <header></header>
    <main>
        <form method="post">
            <span style="color: red;"><?php if(isset($errors['incorrect'])) echo $errors['incorrect']; ?></span>
            <label for="email">email:</label>
            <input type="email" id="email" name="email" value="<?php if(isset($email)) echo htmlspecialchars($email); ?>">
            <span style="color: red;"><?php if(isset($errors['email'])) echo $errors['email']; ?></span>
            <label for="password">password:</label>
            <input type="password" id="password" name="password">
            <span style="color: red;"><?php if(isset($errors['password'])) echo $errors['password']; ?></span>
            <div>
                <button name="submit">login</button>
                <p>Vous n'avez pas de compte? <a href="register.php">register</a></p>
            </div>
        </form>
    </main>
    <footer></footer>
</body>
</html>

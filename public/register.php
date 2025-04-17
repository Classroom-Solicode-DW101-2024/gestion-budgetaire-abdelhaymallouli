<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/user.php';

$errors = [];
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validation
    if (empty($name)) {
        $errors['name'] = "Le nom est requis.";
    }

    if (empty($email)) {
        $errors['email'] = "L'adresse e-mail est requise.";
    } elseif (checkUser($email, $connection)) {
        $errors['email'] = "Cet email est déjà utilisé.";
    }

    if (empty($password)) {
        $errors['password'] = "Le mot de passe est requis.";
    }

    if (empty($confirmPassword)) {
        $errors['confirm_password'] = "La confirmation du mot de passe est requise.";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas.";
    }

    // If no errors, save user
    if (empty($errors)) {
        $user = [
            'name' => htmlspecialchars($name),
            'email' => htmlspecialchars($email),
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $result = addUser($user, $connection);
        if ($result === true) {
            header('Location: login.php');
            exit;
        } else {
            $errors['general'] = $result; // Display any DB errors
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        .error { color: red; font-size: 0.9em; margin-top: 4px; }
    </style>
</head>
<body>

    <h2>Créer un compte</h2>
    <form method="POST" action="">
        <label>Nom:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"><br>
        <?php if (isset($errors['name'])): ?>
            <div class="error"><?= $errors['name'] ?></div>
        <?php endif; ?>
        <br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"><br>
        <?php if (isset($errors['email'])): ?>
            <div class="error"><?= $errors['email'] ?></div>
        <?php endif; ?>
        <br>

        <label>Mot de passe:</label><br>
        <input type="password" name="password"><br>
        <?php if (isset($errors['password'])): ?>
            <div class="error"><?= $errors['password'] ?></div>
        <?php endif; ?>
        <br>

        <label>Confirmer le mot de passe:</label><br>
        <input type="password" name="confirm_password"><br>
        <?php if (isset($errors['confirm_password'])): ?>
            <div class="error"><?= $errors['confirm_password'] ?></div>
        <?php endif; ?>
        <br>

        <button type="submit">S'inscrire</button>
    </form>

    <?php if (isset($errors['general'])): ?>
        <p class="error"><?= $errors['general'] ?></p>
    <?php endif; ?>

    <p>Déjà un compte ? <a href="login.php">Connexion</a></p>

</body>
</html>

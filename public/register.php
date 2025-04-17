<?php 
require_once __DIR__ . '/../config/config.php'; 
require_once __DIR__ . '/../includes/user.php';  

$errors = [];
$name = $email = '';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
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
    

    if (empty($errors)) {
        $user = [
            'name' => htmlspecialchars($name),
            'email' => htmlspecialchars($email),
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        
        $result = addUser($user, $connection);
        if ($result === true) {
            header('Location: login.php');
            exit;
        } else {
            $errors['general'] = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MonBudget</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/register.css">

</head>
<body>
    <div class="signup-container">
        <div class="image-side">
            <div class="logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                Welcome to MonBudget
            </div>
            <div class="social-icons">
                <div class="social-icon">
                    <i class="fab fa-facebook-f"></i>
                </div>
                <div class="social-icon">
                    <i class="fab fa-twitter"></i>
                </div>
                <div class="social-icon">
                    <i class="fab fa-linkedin-in"></i>
                </div>
                <div class="social-icon">
                    <i class="fab fa-github"></i>
                </div>
            </div>
            <div class="help-links">
                <a href="#">Have an issue with 2-factor authentication?</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
        <div class="form-side">
            <div class="form-header">Sign Up</div>
            
            <?php if(isset($errors['general'])): ?>
                <div class="error-message"><?php echo $errors['general']; ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php if(isset($name)) echo htmlspecialchars($name); ?>">
                    <?php if(isset($errors['name'])): ?>
                        <div class="error-message"><?php echo $errors['name']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php if(isset($email)) echo htmlspecialchars($email); ?>">
                    <?php if(isset($errors['email'])): ?>
                        <div class="error-message"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password">
                    <?php if(isset($errors['password'])): ?>
                        <div class="error-message"><?php echo $errors['password']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password">
                    <?php if(isset($errors['confirm_password'])): ?>
                        <div class="error-message"><?php echo $errors['confirm_password']; ?></div>
                    <?php endif; ?>
                </div>
                
                
                <button type="submit" class="submit-button">Sign Up</button>
                
                <div class="form-footer">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
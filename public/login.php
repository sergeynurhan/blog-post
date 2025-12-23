<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$errors = [];
$success = $_GET['registered'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator($_POST);
    $validator->required('username');
    $validator->required('password');

    if ($validator->passed()) {
        $username = $db->connection->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $result = $db->query("SELECT * FROM users WHERE username = '$username'");
        if ($result && $user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                header("Location: index.php");
                exit();
            } else {
                $errors['general'] = "Invalid password!";
            }
        } else {
            $errors['general'] = "User not found!";
        }
    } else {
        $errors = $validator->errors();
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2>Login</h2>

    <?php if ($success): ?>
        <div
            style="color: #22c55e; background: rgba(34, 197, 94, 0.1); padding: 0.5rem; border-radius: 0.4rem; margin-bottom: 1rem;">
            Registration successful! Please login.
        </div>
    <?php endif; ?>

    <?php if (isset($errors['general'])): ?>
        <div class="error"><?= $errors['general'] ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <?php if (isset($errors['username'])): ?>
            <div class="error"><?= $errors['username'][0] ?></div>
        <?php endif; ?>

        <label>Password</label>
        <input type="password" name="password">
        <?php if (isset($errors['password'])): ?>
            <div class="error"><?= $errors['password'][0] ?></div>
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>
    <p style="margin-top: 1rem;">Don't have an account? <a href="register.php">Register here</a></p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
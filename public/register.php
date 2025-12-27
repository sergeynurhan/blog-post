<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator($_POST);
    $validator->required('username')->minimal('username', 3);
    $validator->required('email')->email($_POST['email'] ?? '');
    $validator->required('password')->minimal('password', 6);

    if ($validator->passed()) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Check if user exists
        $result = $db->query("SELECT id FROM users WHERE username = '$username' OR email = '$email'");
        if ($result && $result->num_rows > 0) {
            $errors['general'] = "Username or email already exists!";
        } else {
            $db->insert('users', [
                'username' => $username,
                'email' => $email,
                'password' => $password
            ]);
            header("Location: login.php?registered=1");
            exit();
        }
    } else {
        $errors = $validator->errors();
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2>Register</h2>

    <?php if (isset($errors['general'])): ?>
        <div class="error"><?= $errors['general'] ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <?php if (isset($errors['username'])): ?>
            <div class="error"><?= $errors['username'][0] ?></div>
        <?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <?php if (isset($errors['email'])): ?>
            <div class="error"><?= $errors['email'][0] ?></div>
        <?php endif; ?>

        <label>Password</label>
        <input type="password" name="password">
        <?php if (isset($errors['password'])): ?>
            <div class="error"><?= $errors['password'][0] ?></div>
        <?php endif; ?>

        <button type="submit">Create Account</button>
    </form>
    <p style="margin-top: 1rem;">Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
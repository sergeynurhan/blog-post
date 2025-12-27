<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator($_POST);
    $validator->required('name')->minimal('name', 2);

    if ($validator->passed()) {
        $name = $db->connection->real_escape_string($_POST['name']);

        // Check if exists
        $result = $db->query("SELECT id FROM topics WHERE name = '$name'");
        if ($result && $result->num_rows > 0) {
            $errors['name'] = ["Topic already exists!"];
        } else {
            $db->insert('topics', ['name' => $name]);
            $success = true;
        }
    } else {
        $errors = $validator->errors();
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>Add New Topic</h2>

    <?php if ($success): ?>
        <div
            style="color: #22c55e; background: rgba(34, 197, 94, 0.1); padding: 0.5rem; border-radius: 0.4rem; margin-bottom: 1rem;">
            Topic created successfully!
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Topic Name</label>
        <input type="text" name="name" placeholder="e.g. Technology, Sports...">
        <?php if (isset($errors['name'])): ?>
            <div class="error"><?= $errors['name'][0] ?></div>
        <?php endif; ?>

        <button type="submit">Create Topic</button>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
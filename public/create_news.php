<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$errors = [];
$success = false;
$user = getCurrentUser();

$topics_result = $db->query("SELECT * FROM topics ORDER BY name ASC");
$topics = [];
while ($row = $topics_result->fetch_assoc()) {
    $topics[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator($_POST);
    $validator->required('title')->minimal('title', 5);
    $validator->required('content')->minimal('content', 10);
    $validator->required('topic_id');

    if ($validator->passed()) {
        $db->insert('news', [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'topic_id' => $_POST['topic_id'],
            'user_id' => $user['id']
        ]);
        $success = true;
    } else {
        $errors = $validator->errors();
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Post New News</h2>

    <?php if ($success): ?>
        <div
            style="color: #22c55e; background: rgba(34, 197, 94, 0.1); padding: 0.5rem; border-radius: 0.4rem; margin-bottom: 1rem;">
            News posted successfully! <a href="index.php" style="color: var(--primary)">View Feed</a>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Topic</label>
        <select name="topic_id">
            <option value="">Select a topic</option>
            <?php foreach ($topics as $topic): ?>
                <option value="<?= $topic['id'] ?>"><?= htmlspecialchars($topic['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['topic_id'])): ?>
            <div class="error"><?= $errors['topic_id'][0] ?></div>
        <?php endif; ?>

        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        <?php if (isset($errors['title'])): ?>
            <div class="error"><?= $errors['title'][0] ?></div>
        <?php endif; ?>

        <label>Content</label>
        <textarea name="content" rows="10"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        <?php if (isset($errors['content'])): ?>
            <div class="error"><?= $errors['content'][0] ?></div>
        <?php endif; ?>

        <button type="submit">Publish News</button>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
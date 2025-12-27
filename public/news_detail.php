<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$id = $db->connection->real_escape_string($id);

$query = "
    SELECT news.*, topics.name as topic_name, users.username 
    FROM news 
    JOIN topics ON news.topic_id = topics.id 
    JOIN users ON news.user_id = users.id 
    WHERE news.id = '$id'
";
$result = $db->query($query);
$news = $result->fetch_assoc();

if (!$news) {
    header("Location: index.php");
    exit();
}

include __DIR__ . '/../includes/header.php';
?>

<div style="margin-bottom: 2rem;">
    <a href="index.php" style="color: var(--text-dim); text-decoration: none;">&larr; Back to News</a>
</div>

<article class="card">
    <div class="post-meta">
        <span class="topic-badge"><?= htmlspecialchars($news['topic_name']) ?></span>
        Posted by <?= htmlspecialchars($news['username']) ?> on
        <?= date('M d, Y H:i', strtotime($news['created_at'])) ?>
    </div>

    <h1 style="font-size: 2.5rem; margin-bottom: 2rem;"><?= htmlspecialchars($news['title']) ?></h1>

    <div style="line-height: 1.8; font-size: 1.1rem; color: #cbd5e1; white-space: pre-wrap;">
        <?= htmlspecialchars($news['content']) ?></div>
</article>

<?php include __DIR__ . '/../includes/footer.php'; ?>
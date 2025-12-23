<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$query = "
    SELECT news.*, topics.name as topic_name, users.username 
    FROM news 
    JOIN topics ON news.topic_id = topics.id 
    JOIN users ON news.user_id = users.id 
    ORDER BY news.created_at DESC
";
$result = $db->query($query);

include __DIR__ . '/../includes/header.php';
?>

<header style="margin-bottom: 3rem; text-align: center;">
    <h1 style="font-size: 3rem; margin-bottom: 0.5rem;">Latest News</h1>
    <p style="color: var(--text-dim)">Stay updated with the latest topics and discussions.</p>
</header>

<div class="news-list">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($news = $result->fetch_assoc()): ?>
            <div class="card" style="transition: transform 0.3s; cursor: pointer;"
                onclick="location.href='news_detail.php?id=<?= $news['id'] ?>'">
                <div class="post-meta">
                    <span class="topic-badge"><?= htmlspecialchars($news['topic_name']) ?></span>
                    Posted by <?= htmlspecialchars($news['username']) ?> on
                    <?= date('M d, Y', strtotime($news['created_at'])) ?>
                </div>
                <h2 style="margin: 0.5rem 0 1rem;"><?= htmlspecialchars($news['title']) ?></h2>
                <p style="color: var(--text-dim); line-height: 1.6;">
                    <?= htmlspecialchars(substr($news['content'], 0, 200)) ?>...
                </p>
                <div style="margin-top: 1.5rem;">
                    <a href="news_detail.php?id=<?= $news['id'] ?>" class="btn">Read More</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card" style="text-align: center;">
            <p>No news articles found yet. Be the first to post!</p>
            <?php if (isLoggedIn()): ?>
                <a href="create_news.php" class="btn">Post News</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login to Post</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
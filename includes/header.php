<?php
require_once __DIR__ . '/auth.php';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text: #f8fafc;
            --text-dim: #94a3b8;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            padding: 1rem 2rem;
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar a {
            color: var(--text);
            text-decoration: none;
            margin-left: 1.5rem;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: var(--primary);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none !important;
        }

        .container {
            flex: 1;
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
            width: 100%;
        }

        .card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0 1.5rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            border-radius: 0.5rem;
            color: var(--text);
            outline: none;
            box-sizing: border-box;
        }

        input:focus {
            border-color: var(--primary);
        }

        button,
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
        }

        button:hover,
        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .error {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            padding: 0.5rem;
            border-radius: 0.4rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .post-meta {
            font-size: 0.85rem;
            color: var(--text-dim);
            margin-bottom: 1rem;
        }

        .topic-badge {
            background: var(--primary);
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="index.php" class="logo">PHP BLOG</a>
        <div>
            <a href="index.php">Home</a>
            <?php if ($user): ?>
                <a href="create_topic.php">Add Topic</a>
                <a href="create_news.php">Add News</a>
                <span style="margin-left: 1.5rem; color: var(--text-dim)"><?= htmlspecialchars($user['username']) ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
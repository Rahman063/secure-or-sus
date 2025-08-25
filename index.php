<?php require "data.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Secure or Sus? Pick the Secure Code</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h1>🔒 Secure or Sus? Pick the Secure Code</h1>
  <div class="challenge-list">
    <?php foreach ($challenges as $c): ?>
      <div class="challenge-card">
        <a href="challenge.php?id=<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></a>
        <span class="badge <?= strtolower($c['difficulty']) ?>"><?= $c['difficulty'] ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>

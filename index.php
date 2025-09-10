<?php
require "data.php";

// Get filter values from query string
$filterCategory = $_GET['category'] ?? '';
$filterDifficulty = $_GET['difficulty'] ?? '';

// Apply filters
$filteredChallenges = array_filter($challenges, function ($c) use ($filterCategory, $filterDifficulty) {
    if ($filterCategory && strtolower($c['category']) !== strtolower($filterCategory)) {
        return false;
    }
    if ($filterDifficulty && strtolower($c['difficulty']) !== strtolower($filterDifficulty)) {
        return false;
    }
    return true;
});
?>

<!DOCTYPE html>
<html>
<head>
  <title>Secure or Sus? Pick the Secure Code</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h1>🔒 Secure or Sus? Pick the Secure Code</h1>

  <!-- Filter Form -->
  <form method="get" style="margin-bottom:20px;">
    <label>
      Category:
      <select name="category">
        <option value="">All</option>
        <option value="SQL Injection" <?= $filterCategory === 'SQL Injection' ? 'selected' : '' ?>>SQL Injection</option>
        <option value="XSS" <?= $filterCategory === 'XSS' ? 'selected' : '' ?>>XSS</option>
      </select>
    </label>

    <label style="margin-left:12px;">
      Difficulty:
      <select name="difficulty">
        <option value="">All</option>
        <option value="Beginner" <?= $filterDifficulty === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
        <option value="Intermediate" <?= $filterDifficulty === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
        <option value="Advanced" <?= $filterDifficulty === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
      </select>
    </label>

    <button type="submit" style="margin-left:12px;">Filter</button>
  </form>

  <!-- Challenge List -->
  <div class="challenge-list">
    <?php if (empty($filteredChallenges)): ?>
      <p>No challenges found for selected filters.</p>
    <?php else: ?>
      <?php foreach ($filteredChallenges as $c): ?>
        <div class="challenge-card">
          <a href="challenge.php?id=<?= htmlspecialchars($c['id']) ?>">
            <?= htmlspecialchars($c['title']) ?>
          </a>
          <span class="badge <?= strtolower($c['difficulty']) ?>">
            <?= htmlspecialchars($c['difficulty']) ?>
          </span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>

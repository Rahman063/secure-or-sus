<?php
require "data.php";

$id = $_GET['id'] ?? null;
$challenge = null;
$currentIndex = -1;

// find current challenge + index (for Next button)
foreach ($challenges as $i => $c) {
    if (($c['id'] ?? '') === $id) {
        $challenge = $c;
        $currentIndex = $i;
        break;
    }
}
if (!$challenge) { http_response_code(404); exit("Challenge not found."); }

// handle submission
$resultHtml = '';
$showFixed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $choice = strtolower(trim($_POST['choice'] ?? ''));       // "secure" or "vulnerable" from user
    $correct = strtolower(trim($challenge['answer'] ?? ''));  // "secure" or "vulnerable" from data.php
    $reason = trim($_POST['reason'] ?? '');

    if ($choice === $correct) {
        $resultHtml .= "<p class='correct'>✅ Correct!</p>";
    } else {
        $resultHtml .= "<p class='wrong'>❌ Not quite. This snippet is actually <b>" . htmlspecialchars(ucfirst($correct)) . "</b>.</p>";
    }

    // Always show the explanation so user learns either way
    if (!empty($challenge['explanation'])) {
        $resultHtml .= "<p class='explain'><b>Why:</b> " . htmlspecialchars($challenge['explanation']) . "</p>";
    }

    // If the ground truth is vulnerable and we have a fix, show it (regardless of user's choice)
    if ($correct === 'vulnerable' && !empty(trim($challenge['fixed_code'] ?? ''))) {
        $showFixed = true;
    }
}

// find next challenge id
$nextId = null;
if ($currentIndex !== -1 && isset($challenges[$currentIndex + 1])) {
    $nextId = $challenges[$currentIndex + 1]['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($challenge['title']) ?></title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* tiny safety net if your style.css doesn't have these */
    .snippet { background:#0d1117; color:#c9d1d9; border:1px solid #30363d; border-radius:8px; padding:12px; overflow:auto; }
    .fixed-box { margin-top:16px; border:1px solid #30363d; background:#161b22; border-radius:8px; }
    .fixed-box h3 { margin:0; padding:12px; border-bottom:1px solid #30363d; color:#58a6ff; }
    .fixed-box pre { margin:0; padding:12px; }
    .correct { color:#2ea043; font-weight:600; }
    .wrong { color:#f85149; font-weight:600; }
    .explain { color:black; }
    .nav-links { margin-top:18px; display:flex; justify-content:space-between; align-items:center; }
    .next-btn { background:#1f6feb; color:#fff; padding:8px 14px; border-radius:6px; text-decoration:none; }
    .next-btn:hover { background:#388bfd; }
    textarea { width:100%; height:90px; background:#0d1117; color:#e6edf3; border:1px solid #30363d; border-radius:6px; padding:8px; }
    button { background:#238636; color:#fff; border:none; padding:10px 16px; border-radius:6px; cursor:pointer; }
    button:hover { background:#2ea043; }
  </style>
</head>
<body>
  <h2><?= htmlspecialchars($challenge['title']) ?></h2>
  <p><strong>Category:</strong> <?= htmlspecialchars($challenge['category']) ?>
     | <strong>Difficulty:</strong> <?= htmlspecialchars($challenge['difficulty']) ?></p>

  <h3>Code Snippet</h3>
  <pre class="snippet"><?= htmlspecialchars($challenge['snippet']) ?></pre>

  <form method="post" style="margin-top:12px">
    <p>Is this code secure or vulnerable?</p>
    <label><input type="radio" name="choice" value="Secure" required> Secure</label>
    <label style="margin-left:12px"><input type="radio" name="choice" value="Vulnerable"> Vulnerable</label>
    <br><br>
    <textarea name="reason" placeholder="Explain your reasoning (optional, but recommended)..."></textarea><br>
    <button type="submit">Submit</button>
  </form>

  <?php if ($resultHtml): ?>
    <div style="margin-top:16px"><?= $resultHtml ?></div>
  <?php endif; ?>

  <?php if ($showFixed): ?>
    <div class="fixed-box">
      <h3>🔒 Fixed (Secure) Code</h3>
      <pre class="snippet"><?= htmlspecialchars($challenge['fixed']) ?></pre>
    </div>
  <?php endif; ?>

  <div class="nav-links">
    <a href="index.php">⬅ Back to Challenges</a>
    <?php if ($nextId): ?>
      <a class="next-btn" href="challenge.php?id=<?= htmlspecialchars($nextId) ?>">Next ➡</a>
    <?php endif; ?>
  </div>
</body>
</html>

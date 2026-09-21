<?php

require "data.php";

$id = $_GET["id"] ?? "";

$challenge = null;

foreach ($challenges as $c) {
    if ($c["id"] === $id) {
        $challenge = $c;
        break;
    }
}

if (!$challenge) {
    http_response_code(404);
    exit("Challenge not found.");
}

$userAnswer = null;
$reason = "";
$isCorrect = null;
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $userAnswer = $_POST["answer"] ?? "";
    $reason = trim($_POST["reason"] ?? "");

    $submitted = true;

    $isCorrect =
        strtolower($userAnswer) ===
        strtolower($challenge["answer"]);
}

function e($value)
{
    return htmlspecialchars(
        $value ?? "",
        ENT_QUOTES,
        "UTF-8"
    );
}

$nextChallenge = null;
$currentIndex = null;

foreach ($challenges as $index => $c) {

    if ($c["id"] === $challenge["id"]) {
        $currentIndex = $index;

        if (isset($challenges[$index + 1])) {
            $nextChallenge = $challenges[$index + 1];
        }

        break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= e($challenge["title"]) ?> | Secure or Sus?
    </title>

    <link
        rel="stylesheet"
        href="assets/style.css"
    >

</head>

<body>

<div class="page-container">

    <!-- HEADER -->

    <header class="challenge-header">

        <a
            href="index.php"
            class="back-link"
        >
            ← Back to Challenges
        </a>

        <div class="challenge-title">

            <div class="title-row">

                <h1>
                    <?= e($challenge["title"]) ?>
                </h1>

                <span
                    class="badge <?= strtolower(e($challenge["difficulty"])) ?>"
                >
                    <?= e($challenge["difficulty"]) ?>
                </span>

            </div>

            <div class="challenge-meta">

                <span>
                    <?= e($challenge["category"]) ?>
                </span>

                <?php if (!empty($challenge["subcategory"])): ?>

                    <span>•</span>

                    <span>
                        <?= e($challenge["subcategory"]) ?>
                    </span>

                <?php endif; ?>

            </div>

        </div>

    </header>


    <!-- BEFORE CODE -->

    <section class="review-section">

        <div class="section-heading">

            <span class="section-number">
                01
            </span>

            <div>

                <h2>
                    Code Before
                </h2>

                <p>
                    Review the implementation and identify the security issue.
                </p>

            </div>

        </div>

        <div class="code-card vulnerable-code">

            <div class="code-header">

                <span>
                    Vulnerable Implementation
                </span>

                <span class="code-label">
                    BEFORE
                </span>

            </div>

            <pre><code><?= e($challenge["snippet"]) ?></code></pre>

        </div>

    </section>


    <!-- USER ASSESSMENT -->

    <section class="review-section">

        <div class="section-heading">

            <span class="section-number">
                02
            </span>

            <div>

                <h2>
                    Your Assessment
                </h2>

                <p>
                    Decide whether the implementation is secure or vulnerable,
                    then explain your reasoning.
                </p>

            </div>

        </div>


        <?php if (!$submitted): ?>

            <form
                method="post"
                class="assessment-form"
            >

                <div class="answer-options">

                    <label class="answer-option">

                        <input
                            type="radio"
                            name="answer"
                            value="Vulnerable"
                            required
                        >

                        <span>
                            Vulnerable
                        </span>

                    </label>


                    <label class="answer-option">

                        <input
                            type="radio"
                            name="answer"
                            value="Secure"
                        >

                        <span>
                            Secure
                        </span>

                    </label>

                </div>


                <label
                    for="reason"
                    class="reason-label"
                >
                    Why do you think so?
                </label>

                <textarea
                    id="reason"
                    name="reason"
                    placeholder="Explain the vulnerability or security control..."
                    required
                ></textarea>


                <button
                    type="submit"
                    class="primary-button"
                >
                    Submit Assessment
                </button>

            </form>

        <?php else: ?>

            <div
                class="result-card <?= $isCorrect ? 'result-correct' : 'result-wrong' ?>"
            >

                <div class="result-title">

                    <?php if ($isCorrect): ?>

                        ✓ Correct Assessment

                    <?php else: ?>

                        ✗ Incorrect Assessment

                    <?php endif; ?>

                </div>


                <div class="result-details">

                    <div>

                        <strong>
                            Your answer
                        </strong>

                        <span>
                            <?= e($userAnswer) ?>
                        </span>

                    </div>


                    <div>

                        <strong>
                            Correct answer
                        </strong>

                        <span>
                            <?= e($challenge["answer"]) ?>
                        </span>

                    </div>

                </div>


                <?php if ($reason !== ""): ?>

                    <div class="user-reason">

                        <strong>
                            Your explanation
                        </strong>

                        <p>
                            <?= e($reason) ?>
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </section>


    <?php if ($submitted): ?>


        <!-- SECURITY ANALYSIS -->

        <section class="review-section">

            <div class="section-heading">

                <span class="section-number">
                    03
                </span>

                <div>

                    <h2>
                        Security Analysis
                    </h2>

                    <p>
                        Understand what is happening and why it matters.
                    </p>

                </div>

            </div>


            <div class="analysis-grid">

                <div class="analysis-card">

                    <span class="analysis-label">
                        VULNERABILITY
                    </span>

                    <h3>
                        <?= e($challenge["subcategory"] ?? $challenge["category"]) ?>
                    </h3>

                    <p>
                        <?= e($challenge["explanation"]) ?>
                    </p>

                </div>


                <div class="analysis-card">

                    <span class="analysis-label">
                        SECURITY IMPACT
                    </span>

                    <h3>
                        What could happen?
                    </h3>

                    <p>
                        <?= e($challenge["impact"]) ?>
                    </p>

                </div>


                <div class="analysis-card">

                    <span class="analysis-label">
                        CWE
                    </span>

                    <h3>
                        <?= e($challenge["cwe"]) ?>
                    </h3>

                    <p>
                        Common Weakness Enumeration classification.
                    </p>

                </div>


                <div class="analysis-card">

                    <span class="analysis-label">
    OWASP
</span>

                    <h3>
                        <?= e($challenge["owasp"]) ?>
                    </h3>

                    <p>
                        Relevant OWASP security category.
                    </p>

                </div>

            </div>

        </section>


        <!-- AFTER CODE -->

        <section class="review-section">

            <div class="section-heading">

                <span class="section-number">
                    04
                </span>

                <div>

                    <h2>
                        Secure Implementation
                    </h2>

                    <p>
                        Compare the vulnerable implementation with the corrected version.
                    </p>

                </div>

            </div>


            <?php if (!empty($challenge["fixed_code"])): ?>

                <div class="code-card secure-code">

                    <div class="code-header">

                        <span>
                            Secure Implementation
                        </span>

                        <span class="code-label">
                            AFTER
                        </span>

                    </div>

                    <pre><code><?= e($challenge["fixed_code"]) ?></code></pre>

                </div>

            <?php else: ?>

                <div class="secure-message">

                    ✓ This implementation already follows the expected
                    security control.

                </div>

            <?php endif; ?>

        </section>


        <!-- WHY THE FIX WORKS -->

        <section class="review-section">

            <div class="section-heading">

                <span class="section-number">
                    05
                </span>

                <div>

                    <h2>
                        Why the Fix Works
                    </h2>

                    <p>
                        The security principle behind the remediation.
                    </p>

                </div>

            </div>


            <div class="remediation-card">

                <div class="remediation-icon">
                    ✓
                </div>

                <div>

                    <h3>
                        Recommended Remediation
                    </h3>

                    <p>
                        <?= e($challenge["remediation"]) ?>
                    </p>

                </div>

            </div>

        </section>


        <!-- NAVIGATION -->

        <div class="challenge-navigation">

            <a
                href="index.php"
                class="secondary-button"
            >
                ← All Challenges
            </a>


            <?php if ($nextChallenge): ?>

                <a
                    href="challenge.php?id=<?= e($nextChallenge["id"]) ?>"
                    class="next-button"
                >
                    Next Challenge →
                </a>

            <?php else: ?>

                <a
                    href="index.php"
                    class="next-button"
                >
                    Back to Challenges →
                </a>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>

</body>

</html>
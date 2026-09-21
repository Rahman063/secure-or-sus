<?php

require "data.php";

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

$filterCategory = $_GET["category"] ?? "";
$filterDifficulty = $_GET["difficulty"] ?? "";


/*
|--------------------------------------------------------------------------
| Build category list dynamically
|--------------------------------------------------------------------------
*/

$categories = [];

foreach ($challenges as $challenge) {
    if (!empty($challenge["category"])) {
        $categories[] = $challenge["category"];
    }
}

$categories = array_values(array_unique($categories));

sort($categories);


/*
|--------------------------------------------------------------------------
| Difficulty list
|--------------------------------------------------------------------------
*/

$difficulties = [
    "Beginner",
    "Intermediate",
    "Advanced"
];


/*
|--------------------------------------------------------------------------
| Apply filters
|--------------------------------------------------------------------------
*/

$filteredChallenges = array_filter(
    $challenges,
    function ($challenge) use (
        $filterCategory,
        $filterDifficulty
    ) {

        if (
            $filterCategory !== "" &&
            strcasecmp(
                $challenge["category"],
                $filterCategory
            ) !== 0
        ) {
            return false;
        }

        if (
            $filterDifficulty !== "" &&
            strcasecmp(
                $challenge["difficulty"],
                $filterDifficulty
            ) !== 0
        ) {
            return false;
        }

        return true;
    }
);


/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$totalChallenges = count($challenges);
$visibleChallenges = count($filteredChallenges);

$beginnerCount = 0;
$intermediateCount = 0;
$advancedCount = 0;

foreach ($challenges as $challenge) {

    switch ($challenge["difficulty"]) {

        case "Beginner":
            $beginnerCount++;
            break;

        case "Intermediate":
            $intermediateCount++;
            break;

        case "Advanced":
            $advancedCount++;
            break;
    }
}


/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        $value ?? "",
        ENT_QUOTES,
        "UTF-8"
    );
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
        Secure or Sus? | Interactive Secure Code Review Lab
    </title>

    <meta
        name="description"
        content="Interactive secure code review lab covering common application security vulnerabilities."
    >

    <link
        rel="stylesheet"
        href="assets/style.css"
    >

</head>


<body>

<div class="page-container">

    <!-- =====================================================
         HEADER
         ===================================================== -->

    <header class="home-header">

        <div class="brand">

            <div class="brand-icon">
                🔐
            </div>

            <div>

                <h1>
                    Secure or Sus?
                </h1>

                <p>
                    Interactive Secure Code Review Lab
                </p>

            </div>

        </div>


        <div class="header-description">

            <p>
                Review real-world security patterns, identify
                vulnerabilities, and compare insecure code with
                secure implementations.
            </p>

        </div>

    </header>


    <!-- =====================================================
         STATS
         ===================================================== -->

    <section class="stats-grid">

        <div class="stat-card">

            <span class="stat-number">
                <?= $totalChallenges ?>
            </span>

            <span class="stat-label">
                Challenges
            </span>

        </div>


        <div class="stat-card">

            <span class="stat-number">
                <?= count($categories) ?>
            </span>

            <span class="stat-label">
                Vulnerability Categories
            </span>

        </div>


        <div class="stat-card">

            <span class="stat-number">
                <?= $beginnerCount ?>
            </span>

            <span class="stat-label">
                Beginner
            </span>

        </div>


        <div class="stat-card">

            <span class="stat-number">
                <?= $advancedCount ?>
            </span>

            <span class="stat-label">
                Advanced
            </span>

        </div>

    </section>


    <!-- =====================================================
         FILTERS
         ===================================================== -->

    <section class="filter-section">

        <div class="filter-heading">

            <div>

                <h2>
                    Challenges
                </h2>

                <p>
                    Choose a vulnerability and review the code.
                </p>

            </div>

            <span class="challenge-count">

                Showing
                <strong>
                    <?= $visibleChallenges ?>
                </strong>
                of
                <strong>
                    <?= $totalChallenges ?>
                </strong>

            </span>

        </div>


        <form
            method="get"
            class="filter-form"
        >

            <!-- CATEGORY -->

            <div class="filter-group">

                <label for="category">
                    Category
                </label>

                <select
                    id="category"
                    name="category"
                >

                    <option value="">
                        All Categories
                    </option>

                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= e($category) ?>"
                            <?= strcasecmp(
                                $filterCategory,
                                $category
                            ) === 0 ? "selected" : "" ?>
                        >

                            <?= e($category) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- DIFFICULTY -->

            <div class="filter-group">

                <label for="difficulty">
                    Difficulty
                </label>

                <select
                    id="difficulty"
                    name="difficulty"
                >

                    <option value="">
                        All Difficulties
                    </option>

                    <?php foreach ($difficulties as $difficulty): ?>

                        <option
                            value="<?= e($difficulty) ?>"
                            <?= strcasecmp(
                                $filterDifficulty,
                                $difficulty
                            ) === 0 ? "selected" : "" ?>
                        >

                            <?= e($difficulty) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- BUTTON -->

            <div class="filter-actions">

                <button
                    type="submit"
                    class="primary-button"
                >
                    Apply Filters
                </button>


                <?php if (
                    $filterCategory !== "" ||
                    $filterDifficulty !== ""
                ): ?>

                    <a
                        href="index.php"
                        class="clear-filter"
                    >
                        Clear
                    </a>

                <?php endif; ?>

            </div>

        </form>

    </section>


    <!-- =====================================================
         CHALLENGE LIST
         ===================================================== -->

    <main class="challenge-list">

        <?php if (empty($filteredChallenges)): ?>

            <div class="empty-state">

                <div class="empty-icon">
                    🔎
                </div>

                <h3>
                    No challenges found
                </h3>

                <p>
                    Try changing the selected category or difficulty.
                </p>

                <a
                    href="index.php"
                    class="next-button"
                >
                    View All Challenges
                </a>

            </div>


        <?php else: ?>

            <?php foreach ($filteredChallenges as $index => $challenge): ?>

                <article class="challenge-card">

                    <!-- CARD TOP -->

                    <div class="card-top">

                        <span class="category-label">

                            <?= e($challenge["category"]) ?>

                        </span>


                        <span
                            class="badge <?= strtolower(
                                e($challenge["difficulty"])
                            ) ?>"
                        >

                            <?= e($challenge["difficulty"]) ?>

                        </span>

                    </div>


                    <!-- TITLE -->

                    <h3>

                        <a
                            href="challenge.php?id=<?= e(
                                $challenge["id"]
                            ) ?>"
                        >

                            <?= e($challenge["title"]) ?>

                        </a>

                    </h3>


                    <!-- SUBCATEGORY -->

                    <?php if (!empty($challenge["subcategory"])): ?>

                        <p class="subcategory">

                            <?= e(
                                $challenge["subcategory"]
                            ) ?>

                        </p>

                    <?php endif; ?>


                    <!-- DESCRIPTION -->

                    <p class="challenge-description">

                        <?= e($challenge["explanation"]) ?>

                    </p>


                    <!-- METADATA -->

                    <div class="card-meta">

                        <span>

                            <?= e($challenge["cwe"]) ?>

                        </span>

                        <span>
                            •
                        </span>

                        <span>

                            <?= e($challenge["owasp"]) ?>

                        </span>

                    </div>


                    <!-- ACTION -->

                    <a
                        href="challenge.php?id=<?= e(
                            $challenge["id"]
                        ) ?>"
                        class="challenge-button"
                    >

                        Review Code
                        <span>→</span>

                    </a>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>


    <!-- =====================================================
         FOOTER
         ===================================================== -->

    <footer class="site-footer">

        <div>

            <strong>
                Secure or Sus?
            </strong>

            <span>
                Interactive Application Security Lab
            </span>

        </div>


        <div>

            <?= $totalChallenges ?>
            challenges •
            <?= count($categories) ?>
            categories

        </div>

    </footer>

</div>

</body>

</html>
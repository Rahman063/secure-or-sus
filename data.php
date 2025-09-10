<?php
// Challenge definitions
$challenges = [
    [
        "id" => "sql-injection-1",
        "title" => "SQL Injection - Basic Login Bypass",
        "category" => "SQL Injection",
        "difficulty" => "Beginner",
        "snippet" => '$query = "SELECT * FROM users WHERE username=\'" . $_POST[\'username\'] . "\' AND password=\'" . $_POST[\'password\'] . "\'";',
        "answer" => "Vulnerable",
        "fixed_code" => '$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
$stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
$stmt->execute();',
        "explanation" => "User input was directly concatenated, allowing injection. Fixed by using prepared statements."
    ],
    [
        "id" => "sql-injection-2",
        "title" => "SQL Injection - Search Field",
        "category" => "SQL Injection",
        "difficulty" => "Beginner",
        "snippet" => '$query = "SELECT * FROM products WHERE name LIKE \'%" . $_GET[\'search\'] . "%\'";',
        "answer" => "Vulnerable",
        "fixed_code" => '$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT(\'%\', ?, \'%\')");
$stmt->bind_param("s", $_GET["search"]);
$stmt->execute();',
        "explanation" => "Search input was directly injected. Fixed by using prepared statements with parameter binding."
    ],
    [
        "id" => "sql-injection-3",
        "title" => "SQL Injection - Prepared Statement (Secure)",
        "category" => "SQL Injection",
        "difficulty" => "Beginner",
        "snippet" => '$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
$stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
$stmt->execute();',
        "answer" => "Secure",
        "fixed_code" => "N/A (Already secure)",
        "explanation" => "Prepared statements prevent SQL injection by separating code from data."
    ],
    [
        "id" => "sql-injection-4",
        "title" => "SQL Injection - Dynamic Table Name",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => '$query = "SELECT * FROM " . $_GET[\'table\'];',
        "answer" => "Vulnerable",
        "fixed_code" => '$allowed_tables = ["users", "products", "orders"];
$table = in_array($_GET["table"], $allowed_tables) ? $_GET["table"] : "users";
$query = "SELECT * FROM " . $table;',
        "explanation" => "Direct table name injection was possible. Fixed by using a whitelist of allowed tables."
    ],
    [
        "id" => "sql-injection-5",
        "title" => "SQL Injection - Insert Query",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => '$query = "INSERT INTO users (username, password) VALUES (\'" . $_POST[\'username\'] . "\', \'" . $_POST[\'password\'] . "\')";',
        "answer" => "Vulnerable",
        "fixed_code" => '$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
$stmt->execute();',
        "explanation" => "Direct input inserted into query. Fixed by using parameterized prepared statements."
    ],
    [
        "id" => "sql-injection-6",
        "title" => "SQL Injection - Secure Insert",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => '$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)"); 
$stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
$stmt->execute();',
        "answer" => "Secure",
        "fixed_code" => "N/A (Already secure)",
        "explanation" => "Using parameterized insert prevents injection."
    ],
    [
        "id" => "sql-injection-7",
        "title" => "SQL Injection - ORDER BY",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => '$query = "SELECT * FROM users ORDER BY " . $_GET[\'sort\'];',
        "answer" => "Vulnerable",
        "fixed_code" => '$allowed_sorts = ["username", "email", "created_at"];
$sort = in_array($_GET["sort"], $allowed_sorts) ? $_GET["sort"] : "username";
$query = "SELECT * FROM users ORDER BY " . $sort;',
        "explanation" => "ORDER BY unsanitized input was used. Fixed by whitelisting allowed sort columns."
    ],
    [
        "id" => "sql-injection-8",
        "title" => "SQL Injection - Multi Query",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => '$query = "SELECT * FROM users WHERE id=" . $_GET[\'id\'] . "; DELETE FROM logs;";',
        "answer" => "Vulnerable",
        "fixed_code" => '$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();',
        "explanation" => "Multiple queries allowed attacker to execute destructive operations. Fixed by using parameterized query without allowing multi-query execution."
    ],
    [
        "id" => "sql-injection-9",
        "title" => "SQL Injection - Secure ORDER BY",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => '$allowed = ["username","email"];
$sort = in_array($_GET["sort"], $allowed) ? $_GET["sort"] : "username";
$query = "SELECT * FROM users ORDER BY $sort";',
        "answer" => "Secure",
        "fixed_code" => "N/A (Already secure)",
        "explanation" => "Whitelisting prevents malicious injections."
    ],
    [
        "id" => "sql-injection-10",
        "title" => "SQL Injection - UNION Attack",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => '$query = "SELECT username, password FROM users WHERE id=" . $_GET[\'id\'];',
        "answer" => "Vulnerable",
        "fixed_code" => '$stmt = $conn->prepare("SELECT username, password FROM users WHERE id=?");
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();',
        "explanation" => "Union injection possible. Fixed by using prepared statements."
    ],
];

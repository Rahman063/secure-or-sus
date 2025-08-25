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
        "explanation" => "User input is directly concatenated into the SQL query, allowing attackers to inject malicious SQL (e.g., ' OR '1'='1)."
    ],
    [
        "id" => "sql-injection-2",
        "title" => "SQL Injection - Search Field",
        "category" => "SQL Injection",
        "difficulty" => "Beginner",
        "snippet" => '$query = "SELECT * FROM products WHERE name LIKE \'%" . $_GET[\'search\'] . "%\'";',
        "answer" => "Vulnerable",
        "explanation" => "Search input is injected directly into SQL query without sanitization, leading to SQL injection risk."
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
        "explanation" => "Prepared statements with parameter binding prevent SQL injection by separating code from data."
    ],
    [
        "id" => "sql-injection-4",
        "title" => "SQL Injection - Dynamic Table Name",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => '$query = "SELECT * FROM " . $_GET[\'table\'];',
        "answer" => "Vulnerable",
        "explanation" => "Attacker controls table name → can read arbitrary tables from DB."
    ],
    [
        "id" => "sql-injection-5",
        "title" => "SQL Injection - Insert Query",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => '$query = "INSERT INTO users (username, password) VALUES (\'" . $_POST[\'username\'] . "\', \'" . $_POST[\'password\'] . "\')";',
        "answer" => "Vulnerable",
        "explanation" => "User-controlled input directly inserted → attacker can tamper with DB."
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
        "explanation" => "Using parameterized insert prevents injection."
    ],
    [
        "id" => "sql-injection-7",
        "title" => "SQL Injection - ORDER BY",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => '$query = "SELECT * FROM users ORDER BY " . $_GET[\'sort\'];',
        "answer" => "Vulnerable",
        "explanation" => "ORDER BY parameter is unsanitized → attackers can inject SQL keywords."
    ],
    [
        "id" => "sql-injection-8",
        "title" => "SQL Injection - Multi Query",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => '$query = "SELECT * FROM users WHERE id=" . $_GET[\'id\'] . "; DELETE FROM logs;";',
        "answer" => "Vulnerable",
        "explanation" => "Chained queries allow destructive operations (e.g., dropping tables)."
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
        "explanation" => "Whitelisting allowed columns prevents malicious ORDER BY injections."
    ],
    [
        "id" => "sql-injection-10",
        "title" => "SQL Injection - UNION Attack",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => '$query = "SELECT username, password FROM users WHERE id=" . $_GET[\'id\'];',
        "answer" => "Vulnerable",
        "explanation" => "Attacker can use UNION SELECT to extract sensitive data."
    ],
];

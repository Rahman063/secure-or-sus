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
    [
        "id" => "xss-1",
        "title" => "XSS - Reflected User Input",
        "category" => "XSS",
        "difficulty" => "Beginner",
        "snippet" => 'echo "Hello, " . $_GET["name"] . "!";',
        "answer" => "Vulnerable",
        "fixed_code" => 'echo "Hello, " . htmlspecialchars($_GET["name"], ENT_QUOTES, "UTF-8") . "!";',
        "explanation" => "Direct output of user input allows script injection. Fixed by escaping output."
    ],
    [
        "id" => "xss-2",
        "title" => "XSS - Persistent Comment",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => '$comment = $_POST["comment"];
file_put_contents("comments.txt", $comment . "\n", FILE_APPEND);',
        "answer" => "Vulnerable",
        "fixed_code" => '$comment = htmlspecialchars($_POST["comment"], ENT_QUOTES, "UTF-8");
file_put_contents("comments.txt", $comment . "\n", FILE_APPEND);',
        "explanation" => "User input stored without sanitization. Fixed by escaping before storage."
    ],
    [
        "id" => "xss-3",
        "title" => "XSS - DOM-based Injection",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => '<script>
    document.write("Welcome " + location.hash.substring(1));
    </script>',
        "answer" => "Vulnerable",
        "fixed_code" => '<script>
    const safeHash = document.createTextNode(location.hash.substring(1));
    document.body.appendChild(safeHash);
    </script>',
        "explanation" => "location.hash directly written to DOM allows attacker input injection. Fixed by using text nodes."
    ],
    [
        "id" => "xss-4",
        "title" => "XSS - Alert Injection",
        "category" => "XSS",
        "difficulty" => "Beginner",
        "snippet" => 'echo "<div>" . $_GET["message"] . "</div>";',
        "answer" => "Vulnerable",
        "fixed_code" => 'echo "<div>" . htmlspecialchars($_GET["message"], ENT_QUOTES, "UTF-8") . "</div>";',
        "explanation" => "User input directly injected into HTML. Fixed by escaping output."
    ],
    [
        "id" => "xss-5",
        "title" => "XSS - Unsafe Attribute Injection",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => 'echo "<img src=\'" . $_GET["img"] . "\'>";',
        "answer" => "Vulnerable",
        "fixed_code" => 'echo "<img src=\'" . htmlspecialchars($_GET["img"], ENT_QUOTES, "UTF-8") . "\'>";',
        "explanation" => "Image src attribute unsanitized allows injecting javascript: URLs. Fixed by escaping."
    ],
    [
        "id" => "xss-6",
        "title" => "XSS - Inline Event Handler",
        "category" => "XSS",
        "difficulty" => "Advanced",
        "snippet" => 'echo "<button onclick=\'' . $_GET["onclick"] . '\'>Click me</button>";',
        "answer" => "Vulnerable",
        "fixed_code" => 'echo "<button>Click me</button>";',
        "explanation" => "User-controlled event handlers are dangerous. Fixed by removing dynamic event attributes."
    ],
    [
        "id" => "xss-7",
        "title" => "XSS - Unsafe JSON Injection",
        "category" => "XSS",
        "difficulty" => "Advanced",
        "snippet" => 'echo "<script>var data = " . $_GET["data"] . ";</script>";',
        "answer" => "Vulnerable",
        "fixed_code" => 'echo "<script>var data = " . json_encode($_GET["data"]) . ";</script>";',
        "explanation" => "Unescaped user input breaks JavaScript context. Fixed by using json_encode()."
    ],
    [
        "id" => "xss-8",
        "title" => "XSS - Unsafe innerHTML",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => '<script>
    document.getElementById("output").innerHTML = location.search.substring(1);
    </script>',
        "answer" => "Vulnerable",
        "fixed_code" => '<script>
    const text = document.createTextNode(location.search.substring(1));
    document.getElementById("output").appendChild(text);
    </script>',
        "explanation" => "Direct use of innerHTML with user input enables XSS. Fixed by using text nodes."
    ],
    [
        "id" => "xss-9",
        "title" => "XSS - Unsafe Redirect URL",
        "category" => "XSS",
        "difficulty" => "Advanced",
        "snippet" => 'header("Location: " . $_GET["url"]);',
        "answer" => "Vulnerable",
        "fixed_code" => '$allowedUrls = ["https://example.com", "https://example.org"];
$url = in_array($_GET["url"], $allowedUrls) ? $_GET["url"] : "https://example.com";
header("Location: " . $url);',
        "explanation" => "Open redirect allows phishing and XSS via URL parameter. Fixed by whitelisting allowed URLs."
    ],
    [
        "id" => "xss-10",
        "title" => "XSS - Unsafe HTML Output",
        "category" => "XSS",
        "difficulty" => "Beginner",
        "snippet" => 'echo "<p>" . $_POST["comment"] . "</p>";',
        "answer" => "Vulnerable",
        "fixed_code" => 'echo "<p>" . htmlspecialchars($_POST["comment"], ENT_QUOTES, "UTF-8") . "</p>";',
        "explanation" => "User input rendered directly in HTML allows XSS. Fixed by escaping the input."
    ],
];


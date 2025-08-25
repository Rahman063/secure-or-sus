<?php
$challenges = [
  [
    "id" => "sql-injection-1",
    "title" => "SQL Injection - Basic String Concatenation",
    "category" => "SQL Injection",
    "difficulty" => "Beginner",
    "snippet" => '$query = "SELECT * FROM users WHERE username=\'$user\' AND password=\'$pass\'";',
    "answer" => "Vulnerable",
    "explanation" => "User input is directly concatenated into the query, allowing attackers to inject SQL.",
    "fixed" => '$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?"); 
$stmt->execute([$user, $pass]);'
  ],
  [
    "id" => "sql-injection-2",
    "title" => "SQL Injection - Prepared Statements",
    "category" => "SQL Injection",
    "difficulty" => "Beginner",
    "snippet" => '$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?"); 
$stmt->execute([$user, $pass]);',
    "answer" => "Secure",
    "explanation" => "This uses prepared statements, preventing SQL injection.",
    "fixed" => ""
  ],
  [
    "id" => "sql-injection-3",
    "title" => "SQL Injection - Unsanitized GET Parameter",
    "category" => "SQL Injection",
    "difficulty" => "Beginner",
    "snippet" => '$id = $_GET["id"]; 
$result = $pdo->query("SELECT * FROM products WHERE id = $id");',
    "answer" => "Vulnerable",
    "explanation" => "Directly inserting `$_GET` into the query allows attackers to manipulate SQL.",
    "fixed" => '$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?"); 
$stmt->execute([$_GET["id"]]);'
  ],
  [
    "id" => "sql-injection-4",
    "title" => "SQL Injection - LIKE Clause Injection",
    "category" => "SQL Injection",
    "difficulty" => "Intermediate",
    "snippet" => '$search = $_POST["search"]; 
$query = "SELECT * FROM books WHERE title LIKE \'%$search%\'";',
    "answer" => "Vulnerable",
    "explanation" => "LIKE clause with unsanitized input can be abused with wildcards or injections.",
    "fixed" => '$stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ?"); 
$stmt->execute(["%$search%"]);'
  ],
  [
    "id" => "sql-injection-5",
    "title" => "SQL Injection - Integer Cast",
    "category" => "SQL Injection",
    "difficulty" => "Intermediate",
    "snippet" => '$id = (int)$_GET["id"]; 
$result = $pdo->query("SELECT * FROM users WHERE id = $id");',
    "answer" => "Secure",
    "explanation" => "Casting to integer prevents SQL injection for numeric IDs, but prepared statements are still recommended.",
    "fixed" => ""
  ],
  [
    "id" => "sql-injection-6",
    "title" => "SQL Injection - Login with OR 1=1",
    "category" => "SQL Injection",
    "difficulty" => "Intermediate",
    "snippet" => '$query = "SELECT * FROM users WHERE email=\'$email\' AND password=\'$pass\'";',
    "answer" => "Vulnerable",
    "explanation" => "An attacker could bypass login with `\' OR 1=1 --`.",
    "fixed" => '$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?"); 
$stmt->execute([$email, $pass]);'
  ],
  [
    "id" => "sql-injection-7",
    "title" => "SQL Injection - ORDER BY Injection",
    "category" => "SQL Injection",
    "difficulty" => "Advanced",
    "snippet" => '$sort = $_GET["sort"]; 
$query = "SELECT * FROM items ORDER BY $sort";',
    "answer" => "Vulnerable",
    "explanation" => "ORDER BY can’t be parameterized normally. Passing user input directly allows attackers to inject arbitrary columns or statements.",
    "fixed" => '$allowed = ["price","name","created_at"]; 
$sort = in_array($_GET["sort"], $allowed) ? $_GET["sort"] : "name"; 
$query = "SELECT * FROM items ORDER BY $sort";'
  ],
  [
    "id" => "sql-injection-8",
    "title" => "SQL Injection - Secure Whitelist",
    "category" => "SQL Injection",
    "difficulty" => "Advanced",
    "snippet" => '$allowed = ["price","name","created_at"]; 
$sort = in_array($_GET["sort"], $allowed) ? $_GET["sort"] : "name"; 
$query = "SELECT * FROM items ORDER BY $sort";',
    "answer" => "Secure",
    "explanation" => "Using a whitelist of allowed columns prevents ORDER BY injection.",
    "fixed" => ""
  ],
  [
    "id" => "sql-injection-9",
    "title" => "SQL Injection - UNION Injection",
    "category" => "SQL Injection",
    "difficulty" => "Advanced",
    "snippet" => '$cat = $_GET["category"]; 
$query = "SELECT name, price FROM products WHERE category = \'$cat\'";',
    "answer" => "Vulnerable",
    "explanation" => "Attacker can inject `\' UNION SELECT ... --` to dump other data.",
    "fixed" => '$stmt = $pdo->prepare("SELECT name, price FROM products WHERE category = ?"); 
$stmt->execute([$_GET["category"]]);'
  ],
  [
    "id" => "sql-injection-10",
    "title" => "SQL Injection - Parameterized IN Clause",
    "category" => "SQL Injection",
    "difficulty" => "Expert",
    "snippet" => '$ids = $_GET["ids"]; 
$query = "SELECT * FROM products WHERE id IN ($ids)";',
    "answer" => "Vulnerable",
    "explanation" => "IN clause is dangerous when user controls it. An attacker can inject arbitrary IDs or SQL.",
    "fixed" => '$ids = explode(",", $_GET["ids"]); 
$placeholders = rtrim(str_repeat("?,", count($ids)), ","); 
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)"); 
$stmt->execute($ids);'
  ],
];

<?php
// challenges.php (SQL Injection section)
return [

    // 1. Classic Login Bypass
    [
        "id" => "sql-injection-1",
        "title" => "SQL Injection - Basic Login Bypass",
        "category" => "SQL Injection",
        "difficulty" => "Beginner",
        "snippet" => "<?php
\$user = \$_POST['username'];
\$pass = \$_POST['password'];
\$query = \"SELECT * FROM users WHERE username='\$user' AND password='\$pass'\";
\$result = mysqli_query(\$conn, \$query);
?>",
        "correct" => "vulnerable",
        "answer" => "Direct concatenation of user input allows attackers to inject payloads like `' OR '1'='1`."
    ],

    // 2. Search Field
    [
        "id" => "sql-injection-2",
        "title" => "SQL Injection - Search Field",
        "category" => "SQL Injection",
        "difficulty" => "Beginner",
        "snippet" => "<?php
\$search = \$_GET['q'];
\$result = mysqli_query(\$conn, \"SELECT * FROM products WHERE name LIKE '%\$search%'\");
?>",
        "correct" => "vulnerable",
        "answer" => "Unsanitized input inside LIKE query allows SQL Injection via wildcards and escape characters."
    ],

    // 3. Secure Prepared Statement
    [
        "id" => "sql-injection-3",
        "title" => "SQL Injection - Prepared Statement",
        "category" => "SQL Injection",
        "difficulty" => "Beginner",
        "snippet" => "<?php
\$stmt = \$conn->prepare(\"SELECT * FROM users WHERE username=? AND password=?\");
\$stmt->bind_param(\"ss\", \$_POST['username'], \$_POST['password']);
\$stmt->execute();
?>",
        "correct" => "secure",
        "answer" => "Using prepared statements with bound parameters prevents SQL injection."
    ],

    // 4. Dynamic Table Name
    [
        "id" => "sql-injection-4",
        "title" => "SQL Injection - Dynamic Table Name",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => "<?php
\$table = \$_GET['table'];
\$result = mysqli_query(\$conn, \"SELECT * FROM \$table\");
?>",
        "correct" => "vulnerable",
        "answer" => "Table names are directly controlled by user input, enabling UNION-based SQL injection."
    ],

    // 5. Insert Query
    [
        "id" => "sql-injection-5",
        "title" => "SQL Injection - Insert Query",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => "<?php
\$name = \$_POST['name'];
\$email = \$_POST['email'];
mysqli_query(\$conn, \"INSERT INTO users (name, email) VALUES ('\$name', '\$email')\");
?>",
        "correct" => "vulnerable",
        "answer" => "Input is concatenated directly into INSERT statement without escaping, vulnerable to SQLi."
    ],

    // 6. Secure Insert with Prepared Statement
    [
        "id" => "sql-injection-6",
        "title" => "SQL Injection - Secure Insert",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => "<?php
\$stmt = \$conn->prepare(\"INSERT INTO users (name, email) VALUES (?, ?)\");
\$stmt->bind_param(\"ss\", \$_POST['name'], \$_POST['email']);
\$stmt->execute();
?>",
        "correct" => "secure",
        "answer" => "Using parameterized queries ensures input is not interpreted as SQL."
    ],

    // 7. Order By Injection
    [
        "id" => "sql-injection-7",
        "title" => "SQL Injection - ORDER BY",
        "category" => "SQL Injection",
        "difficulty" => "Intermediate",
        "snippet" => "<?php
\$order = \$_GET['sort'];
\$result = mysqli_query(\$conn, \"SELECT * FROM products ORDER BY \$order\");
?>",
        "correct" => "vulnerable",
        "answer" => "Unvalidated ORDER BY clause allows attackers to inject arbitrary columns or SQL functions."
    ],

    // 8. Multi-Query Injection
    [
        "id" => "sql-injection-8",
        "title" => "SQL Injection - Multi Query",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => "<?php
\$id = \$_GET['id'];
mysqli_multi_query(\$conn, \"SELECT * FROM users WHERE id=\$id; DELETE FROM logs;\");
?>",
        "correct" => "vulnerable",
        "answer" => "Multi-query execution allows stacked SQL injection (attackers can run multiple statements)."
    ],

    // 9. Secure Whitelist for ORDER BY
    [
        "id" => "sql-injection-9",
        "title" => "SQL Injection - Secure ORDER BY",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => "<?php
\$allowed = ['name','price','id'];
\$order = in_array(\$_GET['sort'], \$allowed) ? \$_GET['sort'] : 'id';
\$result = mysqli_query(\$conn, \"SELECT * FROM products ORDER BY \$order\");
?>",
        "correct" => "secure",
        "answer" => "Column names are restricted via whitelist, preventing ORDER BY SQL injection."
    ],

    // 10. UNION Injection
    [
        "id" => "sql-injection-10",
        "title" => "SQL Injection - UNION Attack",
        "category" => "SQL Injection",
        "difficulty" => "Advanced",
        "snippet" => "<?php
\$id = \$_GET['id'];
\$query = \"SELECT name, email FROM users WHERE id='\$id'\";
\$result = mysqli_query(\$conn, \$query);
?>",
        "correct" => "vulnerable",
        "answer" => "Input is unsanitized and can be abused with UNION SELECT injection."
    ],

];

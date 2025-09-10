<!-- <?php
return [

    // ------------------- SQL Injection Challenges -------------------

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


    // ------------------- XSS Challenges -------------------

    // 1. Basic Reflected XSS
    [
        "id" => "xss-1",
        "title" => "XSS - Reflected Alert",
        "category" => "XSS",
        "difficulty" => "Beginner",
        "snippet" => "<?php
\$name = \$_GET['name'];
echo \"Hello \$name!\";
?>",
        "correct" => "vulnerable",
        "answer" => "User input is directly echoed without escaping, allowing JavaScript injection."
    ],

    // 2. Basic Stored XSS
    [
        "id" => "xss-2",
        "title" => "XSS - Stored Comment",
        "category" => "XSS",
        "difficulty" => "Beginner",
        "snippet" => "<?php
\$comment = \$_POST['comment'];
file_put_contents('comments.txt', \$comment.PHP_EOL, FILE_APPEND);
echo file_get_contents('comments.txt');
?>",
        "correct" => "vulnerable",
        "answer" => "Comments are stored and displayed without sanitization, allowing persistent XSS."
    ],

    // 3. Input in HTML Attribute
    [
        "id" => "xss-3",
        "title" => "XSS - Input in Attribute",
        "category" => "XSS",
        "difficulty" => "Beginner",
        "snippet" => "<?php
\$url = \$_GET['url'];
echo \"<a href='\$url'>Click here</a>\";
?>",
        "correct" => "vulnerable",
        "answer" => "User input inside HTML attribute without escaping can inject JavaScript using quotes."
    ],

    // 4. Secure Output with htmlspecialchars
    [
        "id" => "xss-4",
        "title" => "XSS - Escaped Output",
        "category" => "XSS",
        "difficulty" => "Beginner",
        "snippet" => "<?php
\$msg = \$_GET['msg'];
echo htmlspecialchars(\$msg, ENT_QUOTES, 'UTF-8');
?>",
        "correct" => "secure",
        "answer" => "User input is escaped properly with htmlspecialchars, preventing XSS."
    ],

    // 5. JavaScript Context Injection
    [
        "id" => "xss-5",
        "title" => "XSS - JS Context Injection",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => "<?php
\$name = \$_GET['name'];
echo \"<script>var user='\$name';</script>\";
?>",
        "correct" => "vulnerable",
        "answer" => "User input directly inside JavaScript context allows injection of arbitrary scripts."
    ],

    // 6. DOM-based XSS Example
    [
        "id" => "xss-6",
        "title" => "XSS - DOM Based",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => "<script>
var msg = location.hash.substring(1);
document.write(msg);
</script>",
        "correct" => "vulnerable",
        "answer" => "Data from URL hash is written to the DOM without encoding, allowing DOM-based XSS."
    ],

    // 7. Input in IMG SRC
    [
        "id" => "xss-7",
        "title" => "XSS - IMG SRC Injection",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => "<?php
\$img = \$_GET['img'];
echo \"<img src='\$img'>\";
?>",
        "correct" => "vulnerable",
        "answer" => "User-controlled src can include JavaScript pseudo-protocols (e.g., javascript:) for XSS."
    ],

    // 8. Secure Attribute Encoding
    [
        "id" => "xss-8",
        "title" => "XSS - Safe Attribute",
        "category" => "XSS",
        "difficulty" => "Intermediate",
        "snippet" => "<?php
\$img = htmlspecialchars(\$_GET['img'], ENT_QUOTES, 'UTF-8');
echo \"<img src='\$img'>\";
?>",
        "correct" => "secure",
        "answer" => "Input is properly escaped for HTML attributes, preventing XSS."
    ],

    // 9. Event Handler Injection
    [
        "id" => "xss-9",
        "title" => "XSS - OnClick Injection",
        "category" => "XSS",
        "difficulty" => "Advanced",
        "snippet" => "<?php
\$btn = \$_GET['btn'];
echo \"<button onclick='\$btn'>Click me</button>\";
?>",
        "correct" => "vulnerable",
        "answer" => "User-controlled onclick attribute can execute arbitrary JavaScript."
    ],

    // 10. Secure JS Template
    [
        "id" => "xss-10",
        "title" => "XSS - Safe JS Template",
        "category" => "XSS",
        "difficulty" => "Advanced",
        "snippet" => "<?php
\$name = json_encode(\$_GET['name']);
echo \"<script>var user=\$name;</script>\";
?>",
        "correct" => "secure",
        "answer" => "Using json_encode safely outputs user input in JS context, preventing XSS."
    ],

]; -->

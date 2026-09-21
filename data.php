<?php

$challenges = [

    /*
    |--------------------------------------------------------------------------
    | SQL INJECTION
    |--------------------------------------------------------------------------
    */

    [
        "id" => "sql-injection-1",
        "title" => "SQL Injection - Basic Login Bypass",
        "category" => "SQL Injection",
        "subcategory" => "Authentication SQL Injection",
        "difficulty" => "Beginner",

        "snippet" => '$username = $_POST["username"];
$password = $_POST["password"];

$query = "SELECT id, username, password_hash
          FROM users
          WHERE username = \'$username\'";',

        "answer" => "Vulnerable",

        "fixed_code" => '$stmt = $conn->prepare(
    "SELECT id, username, password_hash
     FROM users
     WHERE username = ?"
);

$stmt->bind_param("s", $_POST["username"]);
$stmt->execute();',

        "explanation" => "User-controlled input is directly inserted into the SQL statement, allowing an attacker to alter the query structure.",

        "impact" => "An attacker may manipulate the query and potentially bypass authentication or access unauthorized database records.",

        "cwe" => "CWE-89",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Use parameterized queries for database operations. Store passwords with password_hash() and verify them with password_verify()."
    ],


    [
        "id" => "sql-injection-2",
        "title" => "SQL Injection - Search Query",
        "category" => "SQL Injection",
        "subcategory" => "Search Parameter Injection",
        "difficulty" => "Beginner",

        "snippet" => '$search = $_GET["search"];

$query = "SELECT id, title
          FROM products
          WHERE title LIKE \'%$search%\'";

$result = mysqli_query($conn, $query);',

        "answer" => "Vulnerable",

        "fixed_code" => '$stmt = $conn->prepare(
    "SELECT id, title
     FROM products
     WHERE title LIKE ?"
);

$search = "%" . $_GET["search"] . "%";

$stmt->bind_param("s", $search);
$stmt->execute();',

        "explanation" => "The search parameter is concatenated directly into the SQL query. An attacker can supply SQL syntax instead of ordinary search data.",

        "impact" => "Depending on database permissions and application behavior, an attacker may retrieve or manipulate unauthorized data.",

        "cwe" => "CWE-89",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Use prepared statements and bind user-controlled values as parameters."
    ],


    [
        "id" => "sql-injection-3",
        "title" => "SQL Injection - Parameterized Query",
        "category" => "SQL Injection",
        "subcategory" => "Prepared Statements",
        "difficulty" => "Beginner",

        "snippet" => '$stmt = $conn->prepare(
    "SELECT id, username
     FROM users
     WHERE username = ?"
);

$stmt->bind_param("s", $_GET["username"]);
$stmt->execute();',

        "answer" => "Secure",

        "fixed_code" => "",

        "explanation" => "The user-controlled username is passed through a parameterized query. The database treats the value as data rather than SQL syntax.",

        "impact" => "Parameterized queries prevent the supplied value from changing the structure of the SQL statement.",

        "cwe" => "CWE-89",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Continue using parameterized queries for all user-controlled database values."
    ],


    [
        "id" => "sql-injection-4",
        "title" => "SQL Injection - Dynamic Table Selection",
        "category" => "SQL Injection",
        "subcategory" => "Identifier Injection",
        "difficulty" => "Intermediate",

        "snippet" => '$table = $_GET["table"];

$query = "SELECT * FROM " . $table;

$result = mysqli_query($conn, $query);',

        "answer" => "Vulnerable",

        "fixed_code" => '$allowedTables = [
    "products",
    "orders",
    "customers"
];

$table = $_GET["table"] ?? "";

if (!in_array($table, $allowedTables, true)) {
    exit("Invalid table");
}

$query = "SELECT * FROM " . $table;',

        "explanation" => "Parameterized queries cannot directly bind SQL identifiers such as table names. Concatenating an unrestricted table name allows the attacker to modify the SQL structure.",

        "impact" => "An attacker may access unintended database tables or cause unexpected SQL statements to execute.",

        "cwe" => "CWE-89",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Use a strict server-side allowlist for dynamic SQL identifiers and parameterize normal values."
    ],


    /*
    |--------------------------------------------------------------------------
    | CROSS-SITE SCRIPTING
    |--------------------------------------------------------------------------
    */

    [
        "id" => "xss-1",
        "title" => "XSS - Reflected Parameter",
        "category" => "Cross-Site Scripting",
        "subcategory" => "Reflected XSS",
        "difficulty" => "Beginner",

        "snippet" => '$name = $_GET["name"];

echo "Welcome, " . $name;',

        "answer" => "Vulnerable",

        "fixed_code" => '$name = $_GET["name"] ?? "";

echo "Welcome, " .
     htmlspecialchars($name, ENT_QUOTES, "UTF-8");',

        "explanation" => "The application places untrusted request data directly into an HTML response without output encoding.",

        "impact" => "An attacker may execute JavaScript in another user's browser when the malicious URL is visited.",

        "cwe" => "CWE-79",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Apply context-appropriate output encoding before inserting untrusted data into HTML."
    ],


    [
        "id" => "xss-2",
        "title" => "XSS - Stored Comment",
        "category" => "Cross-Site Scripting",
        "subcategory" => "Stored XSS",
        "difficulty" => "Beginner",

        "snippet" => '$comment = $_POST["comment"];

saveComment($comment);

// Later:
echo "<div class=\"comment\">"
     . $comment .
     "</div>";',

        "answer" => "Vulnerable",

        "fixed_code" => '$comment = $_POST["comment"];

saveComment($comment);

// Later:
echo "<div class=\"comment\">"
     . htmlspecialchars($comment, ENT_QUOTES, "UTF-8")
     . "</div>";',

        "explanation" => "The comment is stored and later rendered as HTML without encoding. Stored attacker-controlled content can therefore execute when another user views the page.",

        "impact" => "A malicious comment can affect every user who views the affected content.",

        "cwe" => "CWE-79",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Store data according to the application's data model and apply context-appropriate output encoding when rendering it."
    ],


    [
        "id" => "xss-3",
        "title" => "XSS - DOM innerHTML",
        "category" => "Cross-Site Scripting",
        "subcategory" => "DOM-Based XSS",
        "difficulty" => "Intermediate",

        "snippet" => 'const message = location.hash.substring(1);

document.getElementById("message").innerHTML = message;',

        "answer" => "Vulnerable",

        "fixed_code" => 'const message = location.hash.substring(1);

document.getElementById("message").textContent = message;',

        "explanation" => "The value from the URL fragment is inserted using innerHTML, which causes browser-supplied HTML to be parsed as markup.",

        "impact" => "An attacker may cause arbitrary script execution in the victim's browser.",

        "cwe" => "CWE-79",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Use textContent for plain text. When HTML is genuinely required, use a well-maintained sanitization library and appropriate configuration."
    ],


    [
        "id" => "xss-4",
        "title" => "XSS - Attribute Context",
        "category" => "Cross-Site Scripting",
        "subcategory" => "HTML Attribute Injection",
        "difficulty" => "Intermediate",

        "snippet" => '$value = $_GET["value"];

echo "<input value=\"" . $value . "\">";',

        "answer" => "Vulnerable",

        "fixed_code" => '$value = $_GET["value"] ?? "";

echo "<input value=\"" .
     htmlspecialchars($value, ENT_QUOTES, "UTF-8") .
     "\">";',

        "explanation" => "The value is inserted into an HTML attribute without encoding characters such as quotes.",

        "impact" => "An attacker may break out of the attribute and inject additional HTML or event-handler attributes.",

        "cwe" => "CWE-79",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Use HTML attribute encoding with ENT_QUOTES when placing untrusted data inside quoted HTML attributes."
    ],


    [
        "id" => "xss-5",
        "title" => "XSS - Unsafe URL Scheme",
        "category" => "Cross-Site Scripting",
        "subcategory" => "URL Context",
        "difficulty" => "Advanced",

        "snippet" => '$url = $_GET["url"];

echo "<a href=\"" .
     htmlspecialchars($url, ENT_QUOTES, "UTF-8") .
     "\">Open link</a>";',

        "answer" => "Vulnerable",

        "fixed_code" => '$url = $_GET["url"] ?? "";

$parsed = parse_url($url);

$allowedSchemes = ["https"];

if (
    !$parsed ||
    !isset($parsed["scheme"]) ||
    !in_array(strtolower($parsed["scheme"]), $allowedSchemes, true)
) {
    exit("Invalid URL");
}

echo "<a href=\"" .
     htmlspecialchars($url, ENT_QUOTES, "UTF-8") .
     "\">Open link</a>";',

        "explanation" => "HTML escaping protects the attribute syntax, but it does not make an unsafe URL scheme safe. A URL such as a script-capable scheme can remain dangerous after escaping.",

        "impact" => "If dangerous schemes are accepted, an attacker may create a link that executes script when a victim activates it.",

        "cwe" => "CWE-79",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Validate the URL scheme and destination according to the application's requirements, then apply context-appropriate output encoding."
    ],


    [
        "id" => "xss-6",
        "title" => "XSS - Inline Event Handler",
        "category" => "Cross-Site Scripting",
        "subcategory" => "JavaScript Context",
        "difficulty" => "Intermediate",

        "snippet" => '$name = $_GET["name"];

echo "<button onclick=\"showUser(\'$name\')\">"
     . "View"
     . "</button>";',

        "answer" => "Vulnerable",

        "fixed_code" => '$name = $_GET["name"] ?? "";

echo "<button id=\"userButton\">View</button>";

?>

<script>
const name = <?= json_encode($name, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

document
    .getElementById("userButton")
    .addEventListener("click", () => showUser(name));
</script>',

        "explanation" => "The value is inserted into an inline JavaScript event handler. HTML escaping alone is not a complete defense for a JavaScript execution context.",

        "impact" => "An attacker may break out of the JavaScript string and execute attacker-controlled code.",

        "cwe" => "CWE-79",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Avoid inline event handlers. Keep data and JavaScript separate and use context-appropriate JavaScript encoding."
    ],


    [
        "id" => "xss-7",
        "title" => "XSS - Unsafe JSON in Script",
        "category" => "Cross-Site Scripting",
        "subcategory" => "JavaScript Context",
        "difficulty" => "Advanced",

        "snippet" => '$data = $_GET["data"];

echo "<script>
const data = \'" . $data . "\';
</script>";',

        "answer" => "Vulnerable",

        "fixed_code" => '$data = $_GET["data"] ?? "";

echo "<script>
const data = " .
    json_encode(
        $data,
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    ) .
";
</script>";',

        "explanation" => "The input is concatenated directly into a JavaScript string inside a script block. JavaScript context requires JavaScript-aware encoding.",

        "impact" => "An attacker may terminate the JavaScript string or script context and execute arbitrary JavaScript.",

        "cwe" => "CWE-79",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Serialize data using a context-appropriate encoder such as json_encode() with defensive JSON flags, or avoid embedding data directly in executable script."
    ],


    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL / IDOR
    |--------------------------------------------------------------------------
    */

    [
        "id" => "idor-1",
        "title" => "IDOR - User Profile Access",
        "category" => "Broken Access Control",
        "subcategory" => "IDOR / BOLA",
        "difficulty" => "Beginner",

        "snippet" => '$userId = $_GET["id"];

$user = getUserById($userId);

echo json_encode($user);',

        "answer" => "Vulnerable",

        "fixed_code" => '$userId = $_GET["id"];

$currentUserId = $_SESSION["user_id"];

if ((int)$userId !== (int)$currentUserId) {
    http_response_code(403);
    exit("Forbidden");
}

$user = getUserById($userId);

echo json_encode($user);',

        "explanation" => "The application retrieves a user record solely from an identifier supplied by the client and does not verify whether the authenticated user is authorized to access it.",

        "impact" => "An authenticated attacker may change the identifier and access another user's information.",

        "cwe" => "CWE-639",
        "owasp" => "A01:2021 - Broken Access Control",

        "remediation" => "Perform server-side authorization checks for every object access. Never rely on the client-supplied object identifier as proof of authorization."
    ],


    [
        "id" => "idor-2",
        "title" => "Authorization - Admin Endpoint",
        "category" => "Broken Access Control",
        "subcategory" => "Privilege Enforcement",
        "difficulty" => "Intermediate",

        "snippet" => 'if (!empty($_SESSION["user_id"])) {

    deleteUser($_POST["user_id"]);

    echo "User deleted";
}',

        "answer" => "Vulnerable",

        "fixed_code" => 'if (
    !empty($_SESSION["user_id"]) &&
    ($_SESSION["role"] ?? "") === "admin"
) {

    deleteUser($_POST["user_id"]);

    echo "User deleted";
} else {
    http_response_code(403);
    exit("Forbidden");
}',

        "explanation" => "The endpoint checks whether the user is authenticated but never verifies whether the user has the administrative privilege required for the operation.",

        "impact" => "A normal authenticated user may invoke an administrative function.",

        "cwe" => "CWE-862",
        "owasp" => "A01:2021 - Broken Access Control",

        "remediation" => "Enforce authorization server-side for privileged operations and deny access by default."
    ],


    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION
    |--------------------------------------------------------------------------
    */

    [
        "id" => "auth-1",
        "title" => "Authentication - Plaintext Password",
        "category" => "Authentication",
        "subcategory" => "Password Storage",
        "difficulty" => "Beginner",

        "snippet" => '$password = $_POST["password"];

$sql = "INSERT INTO users
        (username, password)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $_POST["username"],
    $password
);

$stmt->execute();',

        "answer" => "Vulnerable",

        "fixed_code" => '$password = $_POST["password"];

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "INSERT INTO users
     (username, password_hash)
     VALUES (?, ?)"
);

$stmt->bind_param(
    "ss",
    $_POST["username"],
    $passwordHash
);

$stmt->execute();',

        "explanation" => "The user's password is stored directly rather than as a password hash.",

        "impact" => "If the database is compromised, attackers may obtain users' actual passwords.",

        "cwe" => "CWE-256",
        "owasp" => "A07:2021 - Identification and Authentication Failures",

        "remediation" => "Use a dedicated password hashing function such as password_hash() and verify passwords with password_verify()."
    ],


    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    [
        "id" => "csrf-1",
        "title" => "CSRF - Change Email Address",
        "category" => "Cross-Site Request Forgery",
        "subcategory" => "State-Changing Request",
        "difficulty" => "Intermediate",

        "snippet" => 'if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];

    updateEmail(
        $_SESSION["user_id"],
        $email
    );
}

echo "Email updated";',

        "answer" => "Vulnerable",

        "fixed_code" => 'session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        empty($_POST["csrf_token"]) ||
        !hash_equals(
            $_SESSION["csrf_token"],
            $_POST["csrf_token"]
        )
    ) {
        http_response_code(403);
        exit("Invalid CSRF token");
    }

    updateEmail(
        $_SESSION["user_id"],
        $_POST["email"]
    );
}',

        "explanation" => "The application performs a state-changing action using the user's authenticated session but does not require a CSRF token or another appropriate request-integrity defense.",

        "impact" => "A malicious website may attempt to cause a victim's browser to submit an unwanted request while the victim is authenticated.",

        "cwe" => "CWE-352",
        "owasp" => "A01:2021 - Broken Access Control",

        "remediation" => "Use CSRF tokens for applicable state-changing requests and configure appropriate cookie protections such as SameSite."
    ],


    /*
    |--------------------------------------------------------------------------
    | SSRF
    |--------------------------------------------------------------------------
    */

    [
        "id" => "ssrf-1",
        "title" => "SSRF - Remote URL Fetcher",
        "category" => "Server-Side Request Forgery",
        "subcategory" => "URL Fetch",
        "difficulty" => "Advanced",

        "snippet" => '$url = $_POST["url"];

$response = file_get_contents($url);

echo $response;',

        "answer" => "Vulnerable",

        "fixed_code" => '$url = $_POST["url"];

$parsed = parse_url($url);

$allowedHosts = [
    "api.example.com"
];

if (
    !$parsed ||
    ($parsed["scheme"] ?? "") !== "https" ||
    !in_array($parsed["host"] ?? "", $allowedHosts, true)
) {
    http_response_code(400);
    exit("Invalid destination");
}

// Fetch only an explicitly allowed destination.
$response = file_get_contents($url);

echo $response;',

        "explanation" => "The server makes a request to a URL completely controlled by the client. The server therefore becomes a proxy for attacker-selected destinations.",

        "impact" => "Depending on network controls, an attacker may make the server access internal services or sensitive endpoints that are not directly reachable from the internet.",

        "cwe" => "CWE-918",
        "owasp" => "A10:2021 - Server-Side Request Forgery",

        "remediation" => "Prefer an allowlist of approved destinations. Validate scheme, host and resolved addresses, and restrict outbound network access."
    ],


    /*
    |--------------------------------------------------------------------------
    | PATH TRAVERSAL
    |--------------------------------------------------------------------------
    */

    [
        "id" => "path-traversal-1",
        "title" => "Path Traversal - File Download",
        "category" => "Path Traversal",
        "subcategory" => "File Access",
        "difficulty" => "Intermediate",

        "snippet" => '$file = $_GET["file"];

$path = "/var/www/uploads/" . $file;

readfile($path);',

        "answer" => "Vulnerable",

        "fixed_code" => '$file = basename($_GET["file"]);

$baseDir = "/var/www/uploads/";

$path = $baseDir . $file;

if (!is_file($path)) {
    http_response_code(404);
    exit("File not found");
}

readfile($path);',

        "explanation" => "The filename is concatenated into a filesystem path without restricting it to the intended directory.",

        "impact" => "An attacker may manipulate the path and attempt to access files outside the intended upload directory.",

        "cwe" => "CWE-22",
        "owasp" => "A01:2021 - Broken Access Control",

        "remediation" => "Avoid using user input as a filesystem path. Prefer server-side file identifiers or strict allowlists and verify the resolved path remains inside the intended directory."
    ],


    /*
    |--------------------------------------------------------------------------
    | COMMAND INJECTION
    |--------------------------------------------------------------------------
    */

    [
        "id" => "command-injection-1",
        "title" => "Command Injection - Ping Utility",
        "category" => "Command Injection",
        "subcategory" => "OS Command Injection",
        "difficulty" => "Advanced",

        "snippet" => '$host = $_GET["host"];

$output = shell_exec(
    "ping -c 1 " . $host
);

echo $output;',

        "answer" => "Vulnerable",

        "fixed_code" => '$host = $_GET["host"];

if (!filter_var($host, FILTER_VALIDATE_IP)) {
    http_response_code(400);
    exit("Invalid IP address");
}

$command = "ping -c 1 " .
           escapeshellarg($host);

$output = shell_exec($command);

echo htmlspecialchars(
    $output,
    ENT_QUOTES,
    "UTF-8"
);',

        "explanation" => "Untrusted input is incorporated directly into an operating-system command.",

        "impact" => "An attacker may manipulate the command and potentially execute unauthorized operating-system commands with the privileges of the application.",

        "cwe" => "CWE-78",
        "owasp" => "A03:2021 - Injection",

        "remediation" => "Avoid shell execution where possible. Prefer safe APIs, strict input validation and allowlists. If shell execution is unavoidable, use appropriate argument escaping."
    ],


    /*
    |--------------------------------------------------------------------------
    | FILE UPLOAD
    |--------------------------------------------------------------------------
    */

    [
        "id" => "file-upload-1",
        "title" => "File Upload - Executable Upload",
        "category" => "File Upload",
        "subcategory" => "Unrestricted File Upload",
        "difficulty" => "Intermediate",

        "snippet" => '$filename = $_FILES["file"]["name"];

$destination =
    "/var/www/uploads/" . $filename;

move_uploaded_file(
    $_FILES["file"]["tmp_name"],
    $destination
);',

        "answer" => "Vulnerable",

        "fixed_code" => '$allowedExtensions = [
    "jpg",
    "jpeg",
    "png"
];

$originalName = $_FILES["file"]["name"];
$extension = strtolower(
    pathinfo($originalName, PATHINFO_EXTENSION)
);

if (!in_array($extension, $allowedExtensions, true)) {
    http_response_code(400);
    exit("Invalid file type");
}

$filename = bin2hex(random_bytes(16)) .
            "." .
            $extension;

$destination =
    "/var/www/uploads/" . $filename;

move_uploaded_file(
    $_FILES["file"]["tmp_name"],
    $destination
);',

        "explanation" => "The application accepts a client-controlled filename and places the uploaded file directly into the web-accessible upload directory without sufficiently restricting file types or execution.",

        "impact" => "Depending on server configuration, an attacker may upload content that can be interpreted or executed by the server.",

        "cwe" => "CWE-434",
        "owasp" => "A05:2021 - Security Misconfiguration",

        "remediation" => "Use an allowlist of permitted file types, generate server-side filenames, validate file content, store uploads outside the executable web root when possible, and configure the server to prevent execution of uploaded files."
    ],


    /*
    |--------------------------------------------------------------------------
    | OPEN REDIRECT
    |--------------------------------------------------------------------------
    */

    [
        "id" => "open-redirect-1",
        "title" => "Open Redirect - Login Return URL",
        "category" => "Open Redirect",
        "subcategory" => "Unvalidated Redirect",
        "difficulty" => "Beginner",

        "snippet" => '$returnUrl = $_GET["return"];

header("Location: " . $returnUrl);
exit;',

        "answer" => "Vulnerable",

        "fixed_code" => '$allowedPaths = [
    "/dashboard",
    "/profile",
    "/settings"
];

$returnUrl = $_GET["return"] ?? "/dashboard";

if (!in_array($returnUrl, $allowedPaths, true)) {
    $returnUrl = "/dashboard";
}

header("Location: " . $returnUrl);
exit;',

        "explanation" => "The redirect destination is completely controlled by the client.",

        "impact" => "An attacker may construct a trusted-domain URL that redirects victims to an attacker-controlled destination.",

        "cwe" => "CWE-601",
        "owasp" => "A01:2021 - Broken Access Control",

        "remediation" => "Use server-side allowlists for redirect destinations or accept only validated local paths."
    ],


    /*
    |--------------------------------------------------------------------------
    | JWT
    |--------------------------------------------------------------------------
    */

    [
        "id" => "jwt-1",
        "title" => "JWT - Trusting the Token Claims",
        "category" => "Authentication",
        "subcategory" => "JWT Authorization",
        "difficulty" => "Advanced",

        "snippet" => '$token = $_COOKIE["token"];

$parts = explode(".", $token);

$payload = json_decode(
    base64_decode($parts[1]),
    true
);

if (($payload["role"] ?? "") === "admin") {
    showAdminPanel();
}',

        "answer" => "Vulnerable",

        "fixed_code" => '$token = $_COOKIE["token"];

$claims = verifyJwtSignatureAndClaims(
    $token,
    $trustedKey
);

if (
    $claims !== false &&
    ($claims["role"] ?? "") === "admin"
) {
    showAdminPanel();
} else {
    http_response_code(403);
    exit("Forbidden");
}',

        "explanation" => "The application decodes the JWT payload and trusts the role claim without verifying the token's signature and relevant claims.",

        "impact" => "An attacker may modify token claims and potentially obtain privileges that were not legitimately granted.",

        "cwe" => "CWE-347",
        "owasp" => "A07:2021 - Identification and Authentication Failures",

        "remediation" => "Verify the JWT signature using a trusted key, enforce the expected algorithm, and validate relevant claims such as issuer, audience and expiration before trusting authorization data."
    ],


    /*
    |--------------------------------------------------------------------------
    | RACE CONDITION
    |--------------------------------------------------------------------------
    */

    [
        "id" => "race-condition-1",
        "title" => "Race Condition - Coupon Redemption",
        "category" => "Race Condition",
        "subcategory" => "TOCTOU",
        "difficulty" => "Advanced",

        "snippet" => '$coupon = getCoupon($_POST["code"]);

if ($coupon["uses"] < $coupon["max_uses"]) {

    // Apply discount
    applyDiscount($coupon["discount"]);

    // Record usage
    incrementCouponUsage($coupon["id"]);
}',

        "answer" => "Vulnerable",

        "fixed_code" => '$db->beginTransaction();

$coupon = getCouponForUpdate(
    $_POST["code"]
);

if ($coupon["uses"] >= $coupon["max_uses"]) {
    $db->rollBack();

    http_response_code(409);
    exit("Coupon unavailable");
}

applyDiscount($coupon["discount"]);

incrementCouponUsage($coupon["id"]);

$db->commit();',

        "explanation" => "The application checks the coupon usage count and updates it in separate operations. Concurrent requests may pass the check before either request records its usage.",

        "impact" => "An attacker may send concurrent requests and potentially redeem a limited-use resource more times than intended.",

        "cwe" => "CWE-362",
        "owasp" => "A04:2021 - Insecure Design",

        "remediation" => "Make the security-sensitive check and state change atomic using appropriate database transactions, row locking or atomic update operations."
    ]

];
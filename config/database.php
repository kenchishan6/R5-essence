
<?php
$host = 'sql207.infinityfree.com';
$dbname = 'if0_43180779_R5essence';
$username = 'admin';

$password = getenv('admin');

if (!$password) {
    http_response_code(500);
    exit('Database configuration is incomplete.');
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('R5 Essence database connection failed.');
    http_response_code(500);
    exit('Database connection failed. Check the hosting configuration.');
}

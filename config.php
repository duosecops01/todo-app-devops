<?php
// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST'));
define('DB_USER', $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER'));
define('DB_PASS', $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? getenv('DB_PASS'));
define('DB_NAME', $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME'));

// ===== TEMPORARY DEBUG =====
echo "<pre>";
echo "DB_HOST: "; var_dump(DB_HOST);
echo "DB_USER: "; var_dump(DB_USER);
echo "DB_PASS: "; var_dump(DB_PASS);
echo "DB_NAME: "; var_dump(DB_NAME);
exit;
// ===== END DEBUG =====

// Session configuration
session_start();

// Helper functions
function isPasswordComplex($password) {
    return (
        strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[0-9]/', $password)
    );
}

function getDBConnection() {
    static $conn = null;

    if ($conn === null) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        mysqli_set_charset($conn, "utf8mb4");
    }

    return $conn;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function flashMessage($message, $type = 'success') {
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }

    $_SESSION['flash_messages'][] = [
        'message' => $message,
        'type' => $type
    ];
}

function getFlashMessages() {
    if (isset($_SESSION['flash_messages'])) {
        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        return $messages;
    }

    return [];
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        flashMessage("Please log in to access this page.", "error");
        redirect("login.php");
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentUsername() {
    return $_SESSION['username'] ?? null;
}
?>

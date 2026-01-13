<?php
class Auth {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($user) {
        self::init();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_data'] = $user; // Store basic info
    }

    public static function logout() {
        self::init();
        $_SESSION = [];
        session_destroy();
    }

    public static function isLoggedIn() {
        self::init();
        return isset($_SESSION['user_id']);
    }

    public static function getUser() {
        self::init();
        return isset($_SESSION['user_data']) ? $_SESSION['user_data'] : null;
    }

    public static function getRole() {
        self::init();
        return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }

    public static function requireRole($roles) {
        self::init();
        if (!self::isLoggedIn()) {
            header('Location: ../views/login.php');
            exit();
        }

        $currentRole = self::getRole();
        if (is_array($roles)) {
            if (!in_array($currentRole, $roles)) {
                self::forbidden();
            }
        } else {
            if ($currentRole !== $roles) {
                self::forbidden();
            }
        }
    }

    private static function forbidden() {
        http_response_code(403);
        echo "<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p><a href='/dashboard.php'>Back to Dashboard</a>";
        exit();
    }
}

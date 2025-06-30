<?php
require_once 'config/auth.php';

if (isLoggedIn()) {
    $_SESSION['login_time'] = time();
    echo 'ok';
} else {
    http_response_code(401);
    echo 'no session';
}

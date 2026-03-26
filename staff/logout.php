<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
session_destroy();
header('Location: ' . BASE_URL . '/staff/login.php');
exit;
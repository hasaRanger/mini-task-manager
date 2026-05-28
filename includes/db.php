<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'intern_task_system';

try {
	$pdo = new PDO(
		"mysql:host={$dbhost};dbname={$dbname};charset=utf8mb4",
		$dbuser,
		$dbpass,
		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false,
		]
	);
} catch (PDOException $e) {
	die('Could not connect: ' . $e->getMessage());
}

// Start session (needed for CSRF tokens and auth later)
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Ensure a CSRF token exists
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
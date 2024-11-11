<?php
// download.php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $student_id = $_SESSION['user_id'];

    // Record the book download
    $stmt = $pdo->prepare("INSERT INTO book_downloads (book_id, student_id) VALUES (?, ?)");
    $stmt->execute([$book_id, $student_id]);

    // Redirect to the book file
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();

    header("Location: " . $book['file_path']);
    exit;
}
?>

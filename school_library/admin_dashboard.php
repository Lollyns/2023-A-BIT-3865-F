<?php
// admin_dashboard.php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Upload book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['book_file'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $file_name = $_FILES['book_file']['name'];
    $file_path = 'uploads/' . $file_name;
    move_uploaded_file($_FILES['book_file']['tmp_name'], $file_path);

    $stmt = $pdo->prepare("INSERT INTO books (title, author, file_path) VALUES (?, ?, ?)");
    $stmt->execute([$title, $author, $file_path]);

    header("Location: admin_dashboard.php");
    exit;
}

// Retrieve all books
$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>

    <h4>Upload New Book</h4>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Upload Book File</label>
            <input type="file" name="book_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <h4 class="mt-5">Books in Library</h4>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Download Link</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><a href="<?= $book['file_path'] ?>" target="_blank">Download</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

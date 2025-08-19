<?php
session_start();
include 'database.php'; // Database connection file

//  Check if user is logged in and has role 'user'
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
//     header("Location: index.php"); // Redirect to login if not user
//     exit();
// }

//  Search functionality
$where = "";
if (isset($_POST['search'])) {
    $term = $conn->real_escape_string($_POST['term']);
    $where = "WHERE title LIKE '%$term%' OR author LIKE '%$term%' OR category LIKE '%$term%'";
}

//  Fetch books (filtered if search applied)
$result = $conn->query("SELECT * FROM books $where");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - E-Library</title>
    <link rel="stylesheet" href="style.css"> <!-- External CSS -->
</head>
<body>

    <!--  Header -->
    <header>
        <div class="logo">📚 E-Library</div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
    </header>

    <!--  Dashboard Section -->
    <section class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user']); ?> 👋</h2>

        <!-- Search Form -->
        <form method="POST" class="search-form">
            <input type="text" name="term" placeholder="Search by title, author, or category">
            <button type="submit" name="search">Search</button>
        </form>

        <hr>

        <!--  Book List Table -->
        <table class="book-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Cover</th>
                    <th>Description</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']); ?></td>
                    <td><?= htmlspecialchars($row['author']); ?></td>
                    <td><?= htmlspecialchars($row['category']); ?></td>
                    <td>
                        <img src="<?= htmlspecialchars($row['cover_image']); ?>" alt="<?= htmlspecialchars($row['title']); ?> Cover" width="100">
                    </td>
                    <td><?= htmlspecialchars($row['description']); ?></td>
                    <td><?= $row['stock']; ?></td>
                    <td>
                        <?php if ($row['stock'] > 0) { ?>
                            <!-- Reserve button only if book is available -->
                            <a href="reserve.php?id=<?= $row['id']; ?>" class="reserve-btn">Reserve</a>
                        <?php } else { ?>
                            <span class="not-available">Not Available</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 E-Library Management System || All Rights Reserved</p>
    </footer>

</body>
</html>

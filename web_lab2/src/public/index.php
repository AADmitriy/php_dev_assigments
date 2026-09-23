<?php
include '../includes/session.php';
include '../includes/cartActions.php';
require '../config/db.php';
require_once __DIR__ . '/../includes/helper.php';


$booksData = $pdo->query("SELECT * FROM books")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Web Library</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/homeStyles.css">
</head>
<body>
    <?php
    include '../includes/navbar.php';
    ?>
    <div class="header">
        Welcome to book.ua — your favorite online book store
    </div>
    <div class="info-block">
        <h2>About us</h2>
        <p>
            Book.ua is a cozy independent bookstore offering a curated selection of contemporary fiction,
            timeless classics, and local author spotlights.
        </p>

        <h2>What We Offer</h2>
        <ul>
            <li><span>Handpicked Reads:</span> Thoughtfully selected fiction, non-fiction, poetry, and children's literature.</li>
            <li><span>Community Space:</span> Weekly book clubs, author signings, and quiet reading corners.</li>
            <li><span>Special Orders:</span> Fast ordering for hard-to-find titles and unique gifts.</li>
        </ul>
    </div>
    <h2 class="books_catalog_header" id="bestsellers">Bestsellers</h2>
    <div class="books_catalog">
        <?php
        foreach ($booksData as $book) {
            includeWithParams('../includes/bookCard.php', [
                'id' => $book["id"],
                'imgUrl' => $book["img_url"],
                'title' => $book["title"],
                'author' => $book["author"],
                'price' => $book["price"],
            ]);
        }
        ?>
    </div>

    <?php
    include '../includes/siteCreatorInfo.php'
    ?>
    
    <script src="js/bookCover.js"></script>
    <script src="js/popup.js"></script>
</body>
</html>
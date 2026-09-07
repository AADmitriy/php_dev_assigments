<?php
session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    $productId = (string) $_POST['product_id'];
    $productImgUrl = (string) $_POST['product_img_url'];
    $productTitle = (string) $_POST['product_title'];
    $productAuthor = (string) $_POST['product_author'];
    $productPrice = (float) $_POST['product_price'];

    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = [
            'imgUrl' => $productImgUrl,
            'title' => $productTitle,
            'author' => $productAuthor,
            'price' => $productPrice,
        ];
    }
    else {
        unset($_SESSION['cart'][$productId]);
    }

    header("Location: /#"); // redirect to avoid re-submitting on refresh
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Web Library</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    include '../includes/cartPopup.php';
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
        function includeWithParams(string $filePath, array $params = []): void {
            if (file_exists($filePath)) {
                // Extract array keys into local variables
                extract($params); 
                
                // Include the file within this function's isolated scope
                include $filePath; 
            }
        }

        if (($handle = fopen("data/books-small-with-price.csv", "r")) !== FALSE) {
            $headers = fgetcsv($handle, 0, ';', escape: "");
    
            // Loop through each row of the CSV file
            while (($data = fgetcsv($handle, 0, ";", escape: "")) !== FALSE) {
                $row = array_combine($headers, $data);
                // $price = rand(100, 10000) / 100;

                includeWithParams('../includes/bookCard.php', [
                    'id' => $row["ISBN"],
                    'imgUrl' => $row["Image-URL-L"],
                    'title' => $row["Book-Title"],
                    'author' => $row["Book-Author"],
                    'price' => $row["Price"],
                ]);
            }
            
            fclose($handle);
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
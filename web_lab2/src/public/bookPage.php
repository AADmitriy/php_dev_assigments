<?php
include '../includes/session.php';
include '../includes/cartActions.php';
require '../config/db.php';
include '../includes/commentActions.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT title, author, price, description, img_url, added_date, last_modified FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "<p>Book not found.</p>";
    exit;
}

$bookId = $id;
$bookTitle = $book['title'];
$bookAuthor = $book['author'];
$bookPrice = $book['price'];
$bookDescription = $book['description'];
$bookImgUrl = $book['img_url'];
$bookAddedDate = new DateTime($book['added_date']);
$bookModifiedDate = new DateTime($book['last_modified']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_book'])) {
    $bookToEditId = (int) $_POST['product_id'];
    header("Location: /bookForm.php?method=update&&id=$bookToEditId");
    exit;
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
    $bookToEditId = (int) $_POST['product_id'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id=?;");
    $stmt->execute([$bookToEditId]);
    header("Location: /index.php?id=$bookToEditId");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id, content, added_date, last_modified 
    FROM comments WHERE book_id = ? 
    ORDER BY added_date ASC;"
);
$stmt->execute([$bookId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Form</title>
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/bookPageStyles.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/deletePopupStyles.css">
    <link rel="stylesheet" href="css/commentSectionStyles.css">
</head>
<body>
    <?php
    include '../includes/navbar.php';
    include '../includes/bookDeletionPopup.php'
    ?>
    <div class="book-page">
    <!-- LEFT: sticky image -->
    <div class="book-page__media">
        <div class="book-page__img-wrapper">
        <img 
            src="<?php echo $bookImgUrl; ?>" alt="<?php echo $bookTitle; ?> cover"
            onerror="this.onerror=null; this.src='img/default_book_cover.jpg'"
        />
        </div>
    </div>

    <!-- MIDDLE: title, author, description -->
    <div class="book-page__content">
        <h1 class="book-page__title"><?php echo $bookTitle; ?></h1>
        <p class="book-page__author">by <?php echo $bookAuthor; ?></p>
        <p class="book-page__added">Book added at <?php echo $bookAddedDate->format('Y-m-d H:i'); ?></p>
        <p class="book-page__modified">Last time modified <?php echo $bookModifiedDate->format('Y-m-d H:i'); ?></p>

        <div class="book-page__divider"></div>

        <h2 class="book-page__section-label">About this book</h2>
        <p class="book-page__description"><?php echo $bookDescription; ?></p>
        
    </div>

    <!-- RIGHT: sticky price + buy -->
    <div class="book-page__purchase-col">
        <div class="book-page__purchase-box">
        <p class="book-page__price"><?php echo $bookPrice; ?> $</p>
        <form method="POST" style="display:inline;width:100%;">
            <input type="hidden" name="product_id" value="<?php echo $bookId; ?>">
            <input type="hidden" name="product_img_url" value="<?php echo $bookImgUrl; ?>">
            <input type="hidden" name="product_title" value="<?php echo $bookTitle; ?>">
            <input type="hidden" name="product_author" value="<?php echo $bookAuthor; ?>">
            <input type="hidden" name="product_price" value="<?php echo $bookPrice; ?>">
            <button class="book-page__buy-btn" type="submit" name="add_to_cart">
                <?php
                $inCart = isset($_SESSION['cart'][$bookId]);
                echo $inCart ?
                '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>'
                :
                '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M223.5-103.5Q200-127 200-160t23.5-56.5Q247-240 280-240t56.5 23.5Q360-193 360-160t-23.5 56.5Q313-80 280-80t-56.5-23.5Zm400 0Q600-127 600-160t23.5-56.5Q647-240 680-240t56.5 23.5Q760-193 760-160t-23.5 56.5Q713-80 680-80t-56.5-23.5ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>';
                echo $inCart ? 'Added to cart' : 'Add to cart';
                ?>
            </button>
        </form>
        <form method="POST" style="display:inline;width:100%;">
            <input type="hidden" name="product_id" value="<?php echo $bookId; ?>">
            <button class="book-page__edit-btn" type="submit" name="edit_book">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                Edit book
            </button>
        </form>
        <button class="book-page__delete-btn" type="submit" id="openDeletePopup">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
            Delete book
        </button>
        <p class="book-page__stock">In stock</p>
        </div>
    </div>
    <div class="comment-section-wrapper">
        <?php include '../includes/commentSection.php' ?>
    </div>
    </div>

    <script src="js/bookCover.js"></script>
    <script src="js/popup.js"></script>
    <script src="js/deletePopup.js"></script>
    <script src="js/commentSection.js"></script>
</body>
</html>
<?php
include '../includes/session.php';
include '../includes/cartActions.php';
require '../config/db.php';

$method = (string) ($_GET['method'] ?? 'insert');
$bookToEditId = (int) ($_GET['id'] ?? 0);

$defaultTitle = null;
$defaultAuthor = null;
$defaultPrice = null;
$defaultImgUrl = null;
$defaultDescription = null;
$updateMethod = false;

if ($method === 'update' && $bookToEditId > 0) {
    $stmt = $pdo->prepare("SELECT title, author, price, description, img_url FROM books WHERE id = ?");
    $stmt->execute([$bookToEditId]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        $defaultTitle = $book['title'];
        $defaultAuthor = $book['author'];
        $defaultPrice = $book['price'];
        $defaultImgUrl = $book['img_url'];
        $defaultDescription = $book['description'];
        $updateMethod = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_new_book'])) {
    $bookTitle = (string) $_POST['title'];
    $bookAuthor = (string) $_POST['author'];
    $bookPrice = (float) $_POST['price'];
    $bookImgUrl = (string) $_POST['imgUrl'];
    $bookDescription = (string) $_POST['description'];

    $stmt = $pdo->prepare(
        "INSERT INTO books (title, author, price, img_url, description, added_date)
         VALUES (?, ?, ?, ?, ?, NOW())"
    );
    $stmt->execute([
        $bookTitle,
        $bookAuthor,
        $bookPrice,
        $bookImgUrl ?? null,
        $bookDescription ?? 'Default description'
    ]);

    header("Location: /bookForm.php");
    exit;
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $bookId = (int) $_POST['book_id'];
    $bookTitle = (string) $_POST['title'];
    $bookAuthor = (string) $_POST['author'];
    $bookPrice = (float) $_POST['price'];
    $bookImgUrl = (string) $_POST['imgUrl'];
    $bookDescription = (string) $_POST['description'];

    $stmt = $pdo->prepare(
        "UPDATE books
        SET title=?, author=?, price=?, img_url=?, description=?
        WHERE id = ?;"
    );
    $stmt->execute([
        $bookTitle,
        $bookAuthor,
        $bookPrice,
        $bookImgUrl ?? null,
        $bookDescription,
        $bookId
    ]);

    header("Location: /bookPage.php?id=$bookId");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Form</title>
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/formStyles.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    include '../includes/navbar.php';
    ?>
    <div class="book-form-wrapper">

    <form method="POST" class="book-form">
    <input type="hidden" name="book_id" value="<?php echo $bookToEditId; ?>">
    <div class="book-form__header">
        <p class="book-form__title"><?php echo $updateMethod ? 'Edit' : 'Add'; ?> a book</p>
    </div>

    <div class="book-form__body">
        <div class="book-form__field">
        <label for="title" class="book-form__label">Title</label>
        <input
            type="text" id="title" name="title" class="book-form__input" placeholder="Book title"
            maxlength="250"
            value="<?php echo $defaultTitle ?? ''; ?>"
            required
        />
        </div>

        <div class="book-form__field">
        <label for="author" class="book-form__label">Author</label>
        <input
           type="text" id="author" name="author" class="book-form__input" placeholder="Author name"
           maxlength="250"
           value="<?php echo $defaultAuthor ?? ''; ?>"
           required
        />
        </div>

        <div class="book-form__field">
        <label for="price" class="book-form__label">Price</label>
        <div class="book-form__price-wrapper">
            <input
                type="number" id="price" name="price" class="book-form__input" placeholder="0.00" min="0" max="99" step="0.01"
                value="<?php echo $defaultPrice ?? ''; ?>"
                required
            />
            <span class="book-form__price-suffix">$</span>
        </div>
        </div>

        <div class="book-form__field">
        <label for="imgUrl" class="book-form__label">Image URL</label>
        <input
            type="url" id="imgUrl" name="imgUrl" class="book-form__input" placeholder="https://example.com/cover.jpg"
            maxlength="500"
            value="<?php echo $defaultImgUrl ?? ''; ?>"
        />
        </div>

        <div class="book-form__field">
        <label for="description" class="book-form__label">Description</label>
        <textarea
            id="description" name="description" class="book-form__input book-form__textarea"
            placeholder="Short description of the book" rows="4" maxlength="1000"
        ><?php echo $defaultDescription ?? ''; ?></textarea>
        </div>
    </div>

    <div class="book-form__footer">
        <button type="submit" class="book-form__submit" name="<?php echo $updateMethod ? 'update_book' : 'insert_new_book'; ?>">
            <?php echo $updateMethod ? 'Edit book' : 'Save book'; ?>
        </button>
    </div>
    </form>

    </div>

    <script src="js/bookCover.js"></script>
    <script src="js/bookCover.js"></script>
    <script src="js/popup.js"></script>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/session.php';
include '../includes/cartActions.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

const MAX_FILE_SIZE = 1024 * 1024;   // 1 MB
const MAX_ERRORS_SHOWN = 50;

$report = null;
$error = null;
$state = 'waiting';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['xml_file'] ?? null;

    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $error = 'File was not uploaded.';
    } elseif ($file['size'] > MAX_FILE_SIZE) {
        $error = 'File is too large (maximum 1 MB).';
    } elseif (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'xml') {
        $error = 'A file with the .xml extension is required.';
    } else {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($file['tmp_name'], 'SimpleXMLElement', LIBXML_NONET);

        if ($xml === false || $xml->getName() !== 'library') {
            $error = 'Invalid XML: root element <library> is expected.';
        } else {
            $added = 0;
            $skipped = 0;
            $errors = [];
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $check = $pdo->prepare("SELECT COUNT(*) FROM books WHERE title = ? AND author = ?");
            $insert = $pdo->prepare(
                "INSERT INTO books (title, author, price, img_url, description, added_date)
                 VALUES (?, ?, ?, ?, NULL, ?)"
            );

            foreach ($xml->book as $b) {
                $label = 'Book #' . (trim((string)$b['id']) ?: '?');

                try {
                    $title  = trim((string)$b->title);
                    $author = trim((string)$b->author);
                    $price  = trim((string)$b->price);
                    $img    = trim((string)$b->img_url);
                    $created = trim((string)$b->created_at);

                    if ($title === '' || $author === '') {
                        throw new InvalidArgumentException('title and author are required');
                    }
                    if (mb_strlen($title) > 255 || mb_strlen($author) > 255) {
                        throw new InvalidArgumentException('title or author is too long');
                    }
                    if (!is_numeric($price) || $price < 0 || $price > 99.99) {
                        throw new InvalidArgumentException('price must be between 0 and 999.99');
                    }
                    if ($img !== '' && (mb_strlen($img) > 511 || !preg_match('#^https?://#i', $img))) {
                        throw new InvalidArgumentException('invalid image URL');
                    }

                    try {
                        $addedDate = $created !== '' ? (new DateTime($created))->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                    } catch (Exception $e) {
                        $addedDate = date('Y-m-d H:i:s');
                    }

                    $check->execute([$title, $author]);
                    if ($check->fetchColumn() > 0) {
                        $skipped++;
                        continue;
                    }

                    $insert->execute([$title, $author, round((float)$price, 2), $img !== '' ? $img : null, $addedDate]);
                    $added++;
                } catch (InvalidArgumentException $e) {
                    $errors[] = "$label: " . $e->getMessage();
                } catch (PDOException $e) {
                    $errors[] = "$label: database error";
                }
            }

            $report = ['added' => $added, 'skipped' => $skipped, 'errors' => $errors];
            $state = 'done';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import Books</title>
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/formStyles.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/importPageStyles.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="book-form-wrapper">
        <form method="POST" enctype="multipart/form-data" class="book-form" id="importForm">
            <div class="book-form__header">
                <p class="book-form__title">Import books from XML</p>
            </div>

            <div class="book-form__body">
                <div class="import-status" id="importStatus" data-state="<?php echo $state; ?>">
                    <svg class="import-status__icon import-status__icon--waiting" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M32 44V20M22 30l10-10 10 10"/>
                        <path d="M12 44v8h40v-8"/>
                    </svg>
                    <svg class="import-status__icon import-status__icon--processing" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                        <circle cx="32" cy="32" r="22" opacity="0.2"/>
                        <path d="M32 10a22 22 0 0 1 22 22"/>
                    </svg>
                    <svg class="import-status__icon import-status__icon--done" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="32" cy="32" r="22"/>
                        <path d="M22 33l7 7 13-15"/>
                    </svg>
                    <span class="import-status__text" id="importStatusText"></span>
                </div>

                <?php if ($error): ?>
                    <div class="import-message import-message--error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($report): ?>
                    <div class="import-report">
                        <div class="import-report__item import-report__item--added">
                            <span class="import-report__num"><?php echo $report['added']; ?></span>
                            <span class="import-report__label">Added</span>
                        </div>
                        <div class="import-report__item import-report__item--skipped">
                            <span class="import-report__num"><?php echo $report['skipped']; ?></span>
                            <span class="import-report__label">Skipped</span>
                        </div>
                        <div class="import-report__item import-report__item--errors">
                            <span class="import-report__num"><?php echo count($report['errors']); ?></span>
                            <span class="import-report__label">Errors</span>
                        </div>
                    </div>

                    <?php if ($report['errors']): ?>
                        <ul class="import-errors">
                            <?php foreach (array_slice($report['errors'], 0, MAX_ERRORS_SHOWN) as $e): ?>
                                <li><?php echo htmlspecialchars($e); ?></li>
                            <?php endforeach; ?>
                            <?php if (count($report['errors']) > MAX_ERRORS_SHOWN): ?>
                                <li>...and <?php echo count($report['errors']) - MAX_ERRORS_SHOWN; ?> more</li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="book-form__field">
                    <label for="xml_file" class="book-form__label">XML file (max 1 MB)</label>
                    <input type="file" id="xml_file" name="xml_file" class="book-form__input" accept=".xml,application/xml,text/xml" required>
                </div>

                <a href="exportBooks.php" class="import-link">Download XML</a>
            </div>

            <div class="book-form__footer">
                <button type="submit" class="book-form__submit" id="importBtn" name="import" disabled>Import</button>
            </div>
        </form>
    </div>

    <script src="js/importPage.js"></script>
</body>
</html>
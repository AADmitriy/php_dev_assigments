<?php
require __DIR__ . '/db.php';

// Try creating books table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        price DECIMAL(5, 2) NOT NULL,
        img_url VARCHAR(511),
        description VARCHAR(1023),
        added_date DATETIME NOT NULL,
        last_modified DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );");
    echo "Books table ready.\n";
} catch (PDOException $e) {
    die("Error creating books table: " . $e->getMessage() . "\n");
}

// Try creating comments table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_id INT,
        content VARCHAR(1023) NOT NULL,
        added_date DATETIME NOT NULL,
        last_modified DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_book_id
        FOREIGN KEY (book_id)
        REFERENCES books(id)
        ON DELETE CASCADE
    );");
    echo "Comments table ready.\n";
} catch (PDOException $e) {
    die("Error creating comments table: " . $e->getMessage() . "\n");
}



// Seed books table if there is no records
$count = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
if ($count > 0) {
    echo "Already seeded, skipping.\n";
    exit;
}

$csvPath = __DIR__ . '/data/books-small-with-price.csv';
$handle = fopen($csvPath, 'r');
if ($handle === false) {
    die("Could not open CSV file: $csvPath\n");
}

$headers = fgetcsv($handle, 0, ';', escape: "");
if ($headers === false) {
    fclose($handle);
    die("CSV file appears empty or unreadable.\n");
}

$stmt = $pdo->prepare(
    "INSERT INTO books (title, author, price, img_url, description, added_date)
     VALUES (?, ?, ?, ?, 'Default description', NOW())"
);

$inserted = 0;
$skipped = 0;

try {
    $pdo->beginTransaction();

    while (($data = fgetcsv($handle, 0, ";", escape: "")) !== FALSE) {
        if (count($data) !== count($headers)) {
            $skipped++;
            continue;
        }
        $row = array_combine($headers, $data);

        if (!isset($row['Book-Title'], $row['Book-Author'], $row['Price'])
            || !is_numeric($row['Price'])) {
            $skipped++;
            continue;
        }

        $stmt->execute([
            $row["Book-Title"],
            $row["Book-Author"],
            (float) $row["Price"],
            $row["Image-URL-L"] ?? null,
        ]);
        $inserted++;
    }

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    fclose($handle);
    die("Seeding failed, rolled back: " . $e->getMessage() . "\n");
}

fclose($handle);
echo "Seeding complete. Inserted: $inserted, skipped: $skipped.\n";
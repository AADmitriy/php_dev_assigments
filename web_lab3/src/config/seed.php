<?php
require __DIR__ . '/db.php';

// Try creating users table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );");
    echo "Users table ready.\n";
} catch (PDOException $e) {
    die("Error creating users table: " . $e->getMessage() . "\n");
}

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
        user_id INT NULL,
        content VARCHAR(1023) NOT NULL,
        added_date DATETIME NOT NULL,
        last_modified DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_comments_user_id
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL,
        CONSTRAINT fk_book_id
        FOREIGN KEY (book_id)
        REFERENCES books(id)
        ON DELETE CASCADE
    );");
    echo "Comments table ready.\n";
} catch (PDOException $e) {
    die("Error creating comments table: " . $e->getMessage() . "\n");
}


// Try creating first admin
// Load .env file
$envPath = __DIR__ . '/.env';
if (!is_readable($envPath)) {
    die("Error: .env file not found or not readable.\n");
}

$env = parse_ini_file($envPath, false, INI_SCANNER_RAW);
if ($env === false) {
    die("Error: failed to parse .env file.\n");
}

$username = $env['ADMIN_USERNAME'];
$email    = $env['ADMIN_EMAIL'];
$password = $env['ADMIN_PASSWORD'];

try {
    // Skip if an admin already exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    if ((int) $stmt->fetchColumn() > 0) {
        echo "Admin already exists. Skipping.\n";
        exit(0);
    }

    // Hash password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($hash === false) {
        die("Error: failed to hash password.\n");
    }

    // Insert admin
    $stmt = $pdo->prepare(
        "INSERT INTO users (username, email, password_hash, role)
         VALUES (:username, :email, :password_hash, 'admin')"
    );
    $stmt->execute([
        ':username'      => $username,
        ':email'         => $email,
        ':password_hash' => $hash,
    ]);

    echo "Admin '{$username}' created (id: {$pdo->lastInsertId()}).\n";
} catch (PDOException $e) {
    if ($e->getCode() === '23000') { // duplicate username/email
        die("Error: username or email already exists.\n");
    }
    die("Error creating admin: " . $e->getMessage() . "\n");
}


// Seed test users
$testUsers = ['user1', 'user2', 'user3'];
$testPasswordHash = password_hash('Test1234', PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "INSERT INTO users (username, email, password_hash, role)
     VALUES (:username, :email, :password_hash, 'user')"
);

foreach ($testUsers as $username) {
    try {
        $stmt->execute([
            ':username'      => $username,
            ':email'         => "{$username}@example.com",
            ':password_hash' => $testPasswordHash,
        ]);
        echo "User '{$username}' created.\n";
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') { // duplicate username/email
            echo "User '{$username}' already exists. Skipping.\n";
            continue;
        }
        die("Error creating user: " . $e->getMessage() . "\n");
    }
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
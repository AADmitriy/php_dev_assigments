<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

$books = $pdo->query("SELECT id, title, author, price, added_date, img_url FROM books ORDER BY id")
             ->fetchAll(PDO::FETCH_ASSOC);

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('library');
$root->setAttribute('exported_at', date('c'));
$dom->appendChild($root);

foreach ($books as $b) {
    $book = $dom->createElement('book');
    $book->setAttribute('id', $b['id']);

    foreach (['title', 'author', 'price', 'img_url'] as $field) {
        $el = $dom->createElement($field);
        $el->appendChild($dom->createTextNode((string)$b[$field]));
        $book->appendChild($el);
    }

    $created = $dom->createElement('created_at');
    $created->appendChild($dom->createTextNode(date('c', strtotime($b['added_date']))));
    $book->appendChild($created);

    $root->appendChild($book);
}

header('Content-Type: application/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="books.xml"');
echo $dom->saveXML();
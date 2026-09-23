<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_new_comment'])) {
    $commentContent = (string) $_POST['content'];
    $bookId = (int) $_POST['book_id'];

    $stmt = $pdo->prepare(
        "INSERT INTO comments (content, book_id, added_date)
         VALUES (?, ?, NOW())"
    );
    $stmt->execute([
        $commentContent,
        $bookId
    ]);

    header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    exit;
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment'])) {
    $newContent = (string) $_POST['new_content'];
    $commentId = (int) $_POST['comment_id'];
    
    $stmt = $pdo->prepare(
        "UPDATE comments SET content=? WHERE id = ?;"
    );
    $stmt->execute([
        $newContent,
        $commentId,
    ]);

    header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    exit;
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $commentId = (int) $_POST['comment_id'];

    $stmt = $pdo->prepare("DELETE FROM comments WHERE id=?;");
    $stmt->execute([$commentId]);

    header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    exit;
}
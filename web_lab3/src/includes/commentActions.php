<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_new_comment'])
    && isLoggedIn()) {
    $commentContent = (string) $_POST['content'];
    $bookId = (int) $_POST['book_id'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare(
        "INSERT INTO comments (content, book_id, user_id, added_date)
         VALUES (?, ?, ?, NOW())"
    );
    $stmt->execute([
        $commentContent,
        $bookId,
        $userId
    ]);

    header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    exit;
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment'])
        && isLoggedIn()) {
    $newContent = (string) $_POST['new_content'];
    $commentId = (int) $_POST['comment_id'];

    $stmt = $pdo->prepare('SELECT user_id FROM comments WHERE id = ?');
    $stmt->execute([$commentId]);
    $commentInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$commentInfo || $commentInfo['user_id'] !== $_SESSION['user_id']) {
        header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
        exit;
    }

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
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])
        && isLoggedIn()) {
    $commentId = (int) $_POST['comment_id'];

    $stmt = $pdo->prepare("DELETE FROM comments WHERE id=?;");
    $stmt->execute([$commentId]);

    header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    exit;
}
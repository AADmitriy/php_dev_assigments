<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    $productId = (int) $_POST['product_id'];
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

    header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    exit;
}
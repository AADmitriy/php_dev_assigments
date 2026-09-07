<div class="book-card">
  <div class="book-card__img-wrapper">
    <img 
      alt="book cover" src="<?php echo $imgUrl; ?>"
    />
  </div>
  <div class="book-card__info">
    <p class="book-info__name"><?php echo $title; ?></p>
    <p class="book-info__author"><?php echo $author; ?></p>
  </div>
  <div class="book-purchase">
    <p class="book-purchase__price"><?php echo $price; ?> $</p>
    <form method="POST" style="display:inline;">
      <input type="hidden" name="product_id" value="<?php echo $id; ?>">
      <input type="hidden" name="product_img_url" value="<?php echo $imgUrl; ?>">
      <input type="hidden" name="product_title" value="<?php echo $title; ?>">
      <input type="hidden" name="product_author" value="<?php echo $author; ?>">
      <input type="hidden" name="product_price" value="<?php echo $price; ?>">
      <button type="submit" name="add_to_cart">
        <?php 
        $inCart = isset($_SESSION['cart'][$id]);
        echo !$inCart ?
        '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M223.5-103.5Q200-127 200-160t23.5-56.5Q247-240 280-240t56.5 23.5Q360-193 360-160t-23.5 56.5Q313-80 280-80t-56.5-23.5Zm400 0Q600-127 600-160t23.5-56.5Q647-240 680-240t56.5 23.5Q760-193 760-160t-23.5 56.5Q713-80 680-80t-56.5-23.5ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>'
        :
        '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>';
        ?>
      </button>
    </form>
  </div>
</div>
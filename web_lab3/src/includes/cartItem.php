<div class="cart-item">
  <div class="cart-item__non-price-info">
    <div class="cart-item__img-wrapper">
      <img 
        alt="book cover" src="<?php echo $imgUrl; ?>" onerror="this.onerror=null; this.src='img/default_book_cover.jpg'"
      />
    </div>
    <div class="cart-item__info-wrapper">
      <div class="cart-item__info">
        <p class="item-info__name"><?php echo $title; ?></p>
        <p class="item-info__author"><?php echo $author; ?></p>
      </div>
      <form method="POST" style="display:inline;">
        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
        <input type="hidden" name="product_img_url" value="<?php echo $imgUrl; ?>">
        <input type="hidden" name="product_title" value="<?php echo $title; ?>">
        <input type="hidden" name="product_author" value="<?php echo $author; ?>">
        <input type="hidden" name="product_price" value="<?php echo $price; ?>">
        <button type="submit" name="add_to_cart" class="cart-item__btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
          Delete
        </button>
      </form>
    </div>
  </div>
  <div class="cart-item__price"><?php echo $price; ?> $</div>
</div>
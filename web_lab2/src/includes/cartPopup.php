<div class="popup-overlay" id="popupOverlay">
  <div class="popup" role="dialog" aria-modal="true" aria-labelledby="popupTitle">
    <div class="popup__header">
      <h2 class="popup__title" id="popupTitle">Cart</h2>
      <button class="popup__close" id="closePopup" aria-label="Close popup">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
          <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
        </svg>
      </button>
    </div>
    <div class="popup__body">
      <?php
      require_once __DIR__ . '/helper.php';

      $itemsInCart = is_array($_SESSION['cart'] ?? null) ? count($_SESSION['cart']) : 0;
      if ($itemsInCart > 0) {
        echo '<div class="cart-items">';
        foreach ($_SESSION["cart"] as $id => $book) {
          includeWithParams('../includes/cartItem.php', [
            'id' => $id,
            'imgUrl' => $book["imgUrl"],
            'title' => $book["title"],
            'author' => $book["author"],
            'price' => $book["price"],
          ]);
        }

        echo '</div>';
        
      }
      else {
        include '../includes/emptyCartContent.php';
      }
      ?>
    </div>
    <div class="popup__footer">
      <p class="popup__total-wrapper">
        Total
        <span class="popup__total">
          <?php
          $priceSum = 0;
          foreach ($_SESSION["cart"] as $id => $book) {
            $priceSum += $book['price'];
          }

          echo $priceSum;
          ?>
          $
        </span>
      </p>
      <button class="popup__checkout-btn">
        Checkout
      </button>
    </div>
  </div>
</div>
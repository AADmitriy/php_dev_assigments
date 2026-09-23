<?php
include '../includes/cartPopup.php';
?>
<nav>
  <div class="links-container">
    <a href="index.php" class="logo">
      <div>Book.<span>ua</span></div>
    </a>
    <a class="nav-link" href="bookForm.php">Book Form</a>
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    if ($currentPage === 'index.php') {
      echo '<a class="nav-link" href="#bestsellers">Bestsellers</a>';
      echo '<a class="nav-link" href="#creator-info">Site creator</a>';
    }
    ?>
  </div>
  <button aria-label="Cart" class="cart" id="openPopup">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M223.5-103.5Q200-127 200-160t23.5-56.5Q247-240 280-240t56.5 23.5Q360-193 360-160t-23.5 56.5Q313-80 280-80t-56.5-23.5Zm400 0Q600-127 600-160t23.5-56.5Q647-240 680-240t56.5 23.5Q760-193 760-160t-23.5 56.5Q713-80 680-80t-56.5-23.5ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>
    
    <?php 
      $itemsInCart = is_array($_SESSION['cart'] ?? null) ? count($_SESSION['cart']) : 0;
      if ($itemsInCart > 0) {
        echo '<div class="counter">';
        echo $itemsInCart;
        echo '</div>';
      }
    ?>
  </button>
</nav>
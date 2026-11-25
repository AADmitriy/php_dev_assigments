<?php
$isLoggedIn  = isLoggedIn();
$currentPage = basename($_SERVER['PHP_SELF']);
$itemsInCart = is_array($_SESSION['cart'] ?? null) ? count($_SESSION['cart']) : 0;

if ($isLoggedIn) {
  include '../includes/cartPopup.php';
}

function navLink(string $href, string $label, string $currentPage): string {
  $active = $href === $currentPage ? ' active" aria-current="page' : '';
  return '<a class="nav-link' . $active . '" href="' . $href . '">' . $label . '</a>';
}
?>
<nav>
  <div class="nav-left">
    <a href="index.php" class="logo">Book.<span>ua</span></a>

    <?php if ($currentPage === 'index.php'): ?>
      <a class="nav-link" href="#bestsellers">Bestsellers</a>
      <a class="nav-link" href="#creator-info">Site creator</a>
    <?php endif; ?>

    <?php if (isAdmin()): ?>
      <?= navLink('bookForm.php', 'Book Form', $currentPage) ?>
      <?= navLink('importPage.php', 'Books Import', $currentPage) ?>
    <?php endif; ?>
  </div>

  <div class="nav-right">
    <?php if ($isLoggedIn): ?>
      <button type="button" aria-label="Cart" class="cart" id="openPopup">
        <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 -960 960 960"><path d="M223.5-103.5Q200-127 200-160t23.5-56.5Q247-240 280-240t56.5 23.5Q360-193 360-160t-23.5 56.5Q313-80 280-80t-56.5-23.5Zm400 0Q600-127 600-160t23.5-56.5Q647-240 680-240t56.5 23.5Q760-193 760-160t-23.5 56.5Q713-80 680-80t-56.5-23.5ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>
        <?php if ($itemsInCart > 0): ?>
          <span class="counter"><?= $itemsInCart ?></span>
        <?php endif; ?>
      </button>
      <?= navLink('userPage.php', 'Account', $currentPage) ?>
      <?= navLink('logoutForm.php', 'Logout', $currentPage) ?>
    <?php else: ?>
      <?= navLink('loginForm.php', 'Login', $currentPage) ?>
      <?= navLink('registerForm.php', 'Register', $currentPage) ?>
    <?php endif; ?>
  </div>
</nav>
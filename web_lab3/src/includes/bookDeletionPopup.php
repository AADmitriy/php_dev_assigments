<div class="delete_popup-overlay" id="deletePopupOverlay">
  <div class="delete_popup" role="dialog" aria-modal="true" aria-labelledby="deletePopupTitle">
    <div class="delete_popup__header">
      <button class="delete_popup__close" id="closeDeletePopup" aria-label="Close delete_popup">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
          <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
        </svg>
      </button>
    </div>
    <div class="delete_popup__body">
        <p>Are you sure you want to delete this book?</p>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?php echo $bookId; ?>">
            <button type="button" id="calcelDeletePopup">No</button>
            <button type="submit" name="delete_book">Yes, delete</button>
        </form>
    </div>
  </div>
</div>
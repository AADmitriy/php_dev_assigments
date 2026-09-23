<li class="comment">
    <div class="comment__avatar">
    <img src="<?php echo $userImgUrl; ?>" alt="<?php echo $userName; ?>" />
    </div>
    <div class="comment__body">
    <p class="comment__author"><?php echo $userName; ?></p>
    <p class="comment__text"><?php echo $commentText; ?></p>
    <form method="POST" class="comment__edit-form" hidden>
        <input type="hidden" name="comment_id" value="<?php echo $commentId; ?>">
        <textarea name="new_content"><?php echo $commentText; ?></textarea>
        <div class="comment__edit-form-buttons">
            <button class="comment__edit-form-cancel" type="button">Cancel</button>
            <button class="comment__edit-form-submit" type="submit" name="edit_comment">Save</button>
        </div>
    </form>
    </div>
    <div class="comment__menu">
    <button type="button" class="comment__menu-trigger" aria-label="Comment options">
        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
        <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
        </svg>
    </button>
    <div class="comment__menu-dropdown">
        <button type="button" class="comment__menu-item comment__menu-item--edit">Edit</button>
        <form method="POST">
            <input type="hidden" name="comment_id" value="<?php echo $commentId; ?>">
            <button
                class="comment__menu-item comment__menu-item--delete"
                name="delete_comment"
                type="submit"
            >
                Delete
            </button>
        </form>
    </div>
    </div>
</li>
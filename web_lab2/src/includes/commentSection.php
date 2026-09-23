<div class="comments">
  <h2 class="comments__header">Comments</h2>

  <form method="POST" class="comments__form">
    <input type="hidden" name="book_id" value="<?php echo $bookId; ?>">
    <textarea 
      class="comments__textarea" 
      name="content"
      placeholder="Write a comment..." 
      rows="3"
      maxlength="1000"
      required
    ></textarea>
    <button type="submit" name="insert_new_comment" class="comments__submit">Post comment</button>
  </form>

  <ul class="comments__list">
      <?php
      foreach($comments as $comment) {
        includeWithParams('../includes/comment.php', [
          'commentId' => (int)$comment['id'],
          'userImgUrl' => 'img/Default_pfp.jpg',
          'userName' => 'Unknown User',
          'commentText' => (string)$comment['content'],
        ]);
      }
      ?>
  </ul>
</div>
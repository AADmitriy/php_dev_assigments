document.addEventListener('click', (e) => {
  // OPEN edit mode
  const editTrigger = e.target.closest('.comment__menu-item--edit');
  if (editTrigger) {
    const comment = editTrigger.closest('.comment');
    const text = comment.querySelector('.comment__text');
    const form = comment.querySelector('.comment__edit-form');

    text.hidden = true;
    form.hidden = false;
    form.querySelector('textarea').focus();
    return;
  }

  // CANCEL edit mode
  const cancelBtn = e.target.closest('.comment__edit-form-cancel');
  if (cancelBtn) {
    const comment = cancelBtn.closest('.comment');
    const text = comment.querySelector('.comment__text');
    const form = comment.querySelector('.comment__edit-form');

    // reset textarea back to original text in case user typed changes
    form.querySelector('textarea').value = text.textContent.trim();

    form.hidden = true;
    text.hidden = false;
    return;
  }
});

// Optional: cancel edit mode if user presses Escape while textarea is focused
document.addEventListener('keydown', (e) => {
  if (e.key !== 'Escape') return;

  const activeForm = e.target.closest('.comment__edit-form');
  if (activeForm) {
    const comment = activeForm.closest('.comment');
    const text = comment.querySelector('.comment__text');

    activeForm.querySelector('textarea').value = text.textContent.trim();
    activeForm.hidden = true;
    text.hidden = false;
  }
});
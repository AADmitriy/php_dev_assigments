const deleteOverlay = document.getElementById('deletePopupOverlay');
const closeDeleteBtn = document.getElementById('closeDeletePopup');
const cancelDeleteBtn = document.getElementById('calcelDeletePopup');
const openDeleteBtn = document.getElementById('openDeletePopup');

function openDeletePopup() {
  deleteOverlay.classList.add('is-open');
  document.body.classList.add('popup-open');
}

function closeDeletePopup(event) {
  event.preventDefault()
  deleteOverlay.classList.remove('is-open');
  document.body.classList.remove('popup-open');
}

cancelDeleteBtn.addEventListener('click', closeDeletePopup);
closeDeleteBtn.addEventListener('click', closeDeletePopup);
openDeleteBtn.addEventListener('click', openDeletePopup);


deleteOverlay.addEventListener('click', (e) => {
  if (e.target === deleteOverlay) closeDeletePopup();
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && deleteOverlay.classList.contains('is-open')) {
    closeDeletePopup();
  }
});
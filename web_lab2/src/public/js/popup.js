const overlay = document.getElementById('popupOverlay');
const openBtn = document.getElementById('openPopup');
const closeBtn = document.getElementById('closePopup');

function openPopup() {
  overlay.classList.add('is-open');
  document.body.classList.add('popup-open');
}

function closePopup() {
  overlay.classList.remove('is-open');
  document.body.classList.remove('popup-open');
}

openBtn.addEventListener('click', openPopup);
closeBtn.addEventListener('click', closePopup);

// close when clicking outside the popup itself
overlay.addEventListener('click', (e) => {
  if (e.target === overlay) closePopup();
});

// close on Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
    closePopup();
  }
});
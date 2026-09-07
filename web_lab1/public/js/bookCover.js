function srcImgHasValidSize(imageEntity) {
  if (imageEntity.naturalWidth < 20 ||  imageEntity.naturalHeight < 20) {
    // console.log("invalid")
    imageEntity.src = "img/default_book_cover.jpg"
  }
}

const bookCardImages = document.querySelectorAll('.book-card__img-wrapper img');
const cartItemImages = document.querySelectorAll('.cart-item__img-wrapper img');

const images = [...bookCardImages, ...cartItemImages];

images.forEach(img => {
  // If the image is already cached/loaded
  if (img.complete) {
    srcImgHasValidSize(img);
  } else {
    // If it is still downloading
    img.addEventListener('load', () => srcImgHasValidSize(img));
  }
})

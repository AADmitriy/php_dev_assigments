const catalog = document.getElementById('booksCatalog');
const searchInput = document.getElementById('filterSearch');
const maxPriceInput = document.getElementById('filterMaxPrice');
const resetBtn = document.getElementById('filterReset');

const ICON_ADD = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M223.5-103.5Q200-127 200-160t23.5-56.5Q247-240 280-240t56.5 23.5Q360-193 360-160t-23.5 56.5Q313-80 280-80t-56.5-23.5Zm400 0Q600-127 600-160t23.5-56.5Q647-240 680-240t56.5 23.5Q760-193 760-160t-23.5 56.5Q713-80 680-80t-56.5-23.5ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>';
const ICON_IN_CART = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>';
const DEFAULT_COVER = 'img/default_book_cover.jpg';

function checkCoverSize(img) {
    if (img.naturalWidth < 20 || img.naturalHeight < 20) {
        img.src = DEFAULT_COVER;
    }
}

// Escape text before inserting into HTML (prevents XSS)
function esc(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML.replace(/"/g, '&quot;');
}

function renderBook(book) {
    const inCart = cartIds.includes(String(book.id));
    return `
    <div class="book-card">
      <a class="book-card__img-wrapper" href="bookPage.php?id=${esc(book.id)}">
        <img alt="book cover" src="${esc(book.img_url)}"
              onload="checkCoverSize(this)"
              onerror="this.onerror=null; this.src='${DEFAULT_COVER}'" />
      </a>
      <a class="book-card__info" href="bookPage.php?id=${esc(book.id)}">
        <p class="book-info__name">${esc(book.title)}</p>
        <p class="book-info__author">${esc(book.author)}</p>
      </a>
      <div class="book-purchase">
        <p class="book-purchase__price">${esc(book.price)} $</p>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="product_id" value="${esc(book.id)}">
          <input type="hidden" name="product_img_url" value="${esc(book.img_url)}">
          <input type="hidden" name="product_title" value="${esc(book.title)}">
          <input type="hidden" name="product_author" value="${esc(book.author)}">
          <input type="hidden" name="product_price" value="${esc(book.price)}">
          <button type="submit" name="add_to_cart">${inCart ? ICON_IN_CART : ICON_ADD}</button>
        </form>
      </div>
    </div>`;
}

async function loadBooks() {
    const params = new URLSearchParams({
        search: searchInput.value.trim(),
        max_price: maxPriceInput.value
    });

    try {
        const response = await fetch('api/books.php?' + params);
        if (!response.ok) throw new Error('Request failed');
        const books = await response.json();

        if (books.length === 0) {
            catalog.innerHTML = '<p class="books-empty">No books found.</p>';
            return;
        }
        catalog.innerHTML = books.map(renderBook).join('');
    } catch (err) {
        catalog.innerHTML = '<p class="books-empty">Failed to load books.</p>';
    }
}

// Wait 300 ms after the user stops typing before sending the request
let timer;
function debouncedLoad() {
    clearTimeout(timer);
    timer = setTimeout(loadBooks, 300);
}

searchInput.addEventListener('input', debouncedLoad);
maxPriceInput.addEventListener('input', debouncedLoad);
resetBtn.addEventListener('click', () => {
    searchInput.value = '';
    maxPriceInput.value = '';
    loadBooks();
});
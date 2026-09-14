import {cachedFetch} from '/experimental/JS/api/data-load.js';
import {iconLinks, loadStyles} from '/experimental/JS/fontawesome.js';

const styles = [
    "/experimental/CSS/books.css"
];

function bookHtml(book){
    return `<article class="book-card">
    <div class="book-cover">
        <img class="full-wh" style="border-radius: 10px" src="${book.image_location ?? '#'}" alt="${book.title} cover" />
    </div>
    <div class="book-info">
        <h3 class="book-title ellipsis">${book.title}</h3>
        <div class="book-author ellipsis">by ${book.author}</div>
        <div class="book-description">${book.description}</div>
    </div>
    <div class="book-actions">
        <a class="btn btn-view no-text-deco" href="${book.pdfUrl ?? '#'}" target="_blank">
        View
        <i class="fa-brands fa-readme"></i>
        </a>
        <a class="btn btn-download no-text-deco" href="${book.pdfUrl ?? '#'}" download>
        <i class="fa-sharp fa-regular fa-download"></i>
        </a>
    </div>
    </article>`;
}

function toTitle(str){
    if(typeof str != typeof 'string') return str;
    return str[0].toUpperCase() + str.substr(1);
}

function groupBooksByGenre(books){
    try{
        let genreList = [
            
        ]
        books.forEach(book => {
            if(!book.genre || book.genre.trim().length === 0){
                return;
            }
            let dest = genreList.length > 0 ? genreList.find(genre => {
                return genre.name.toLowerCase() === book.genre.toLowerCase();
            }) : null;
            if(!dest){
                dest = {
                    name: book.genre.toLowerCase(),
                    content: `<div class="section accordion-item closed">
                        <div class="genre-header">
                            <h3>${toTitle(book.genre)}</h3>
                        </div>
                        <section class="books-grid">`
                };
                genreList.push(dest);
            }
            dest.content += bookHtml(book);
        });

        let content = genreList.sort((a,b) => a.name < b.name ? -1 : 1).map(genre => {
            return genre.content + `</section>
                <div class="accordion-item-controller">
                    <span></span><i class="fa-solid fa-chevron-down"></i>
                </div>
                </div>`;
        }).join('');
        return content;
    } catch(error){
        console.log('error while grouping: ', error);
        return '<h1 style="color: var(--muted);text-align:center;">Failed to load books. Please try again later.</h1>';
    }
}


loadStyles(iconLinks);
function pageHtml(books){
    if(books.length === 0) {
        return'<h1 style="color: var(--muted);text-align: center;">No books available at the moment.</h1>';
    }
    let booksList = groupBooksByGenre(books);
    
    return `
      <header>
        <h1>📚 Bibliyotèk Liv</h1>
        <p>Navige, li, ak telechaje koleksyon liv nou an nan fòma PDF. Prop, vit, epi fasil.</p>
      </header>

      <div class="container">
        <div class="search-bar-books">
          <input type="text" placeholder="Search books by title or author…" />
        </div>

        ${booksList}
      </div>
        `;
}

function accordionFunctionality(){
    /**
     * controls the functionality of section elements like accordions.
     * Allows multiple containers to be opened at the same time.
     * For this functionality the header is not used to open but the 'more' button at the bottom.
     * 
     */
    let accordionControllers = document.querySelectorAll(".accordion-item-controller");
    accordionControllers?.forEach(ctrl => {
        ctrl.addEventListener('click', ()=>{
            let targetItem = ctrl.parentElement;
            if(!targetItem.classList.contains('accordion-item')){
                console.log("Unexpected position for accordion controller. Make sure it matches the selector: -item > controller");
                return;
            }
            let icon = ctrl.querySelector('i');
            let textBox = ctrl.querySelector('span');

            targetItem.classList.toggle('closed');
            targetItem.classList.toggle('open', !targetItem.classList.contains('open'))
        });
    });
}

function pageScript(){
    let booksContainer = document.querySelector('.books-grid');
    let searchInput = document.querySelector('.search-bar-books input');

    searchInput.addEventListener('input', () => {
      let query = searchInput.value.toLowerCase();
      let bookCards = booksContainer.querySelectorAll('.book-card');
      bookCards.forEach(card => {
        let title = card.querySelector('.book-title').textContent.toLowerCase();
        let author = card.querySelector('.book-author').textContent.toLowerCase();
        if (title.includes(query) || author.includes(query)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });

    accordionFunctionality();
    
}


const pageStyles = ()=>{
    let tag = document.querySelector("#root-style");
    if(!tag){
        tag = document.createElement("style");
        document.head.appendChild(tag);
    }
    
    let inlineCSS = `
    
    `;
    tag.innerHTML = inlineCSS;
}

export default async function booksPage(){
    //pageStyles();
    styles.forEach(src => {
        let tag = document.createElement('link');
        tag.rel = 'stylesheet';
        tag.href = src;
        document.head.appendChild(tag);
    });
    const title = 'konektem - Books';
    let tag = document.querySelector('title')
    if(!tag){
        tag = document.createElement('title');
        document.head.appendChild(tag);
    }
    tag.textContent = title;
    
    let root = document.querySelector("#root");
    if(!root){
        return;
    }
    let booksData = await cachedFetch('/php/dbReader.php?r=books', 'booksData');
    root.innerHTML = pageHtml(booksData);
    setTimeout(()=>{
        pageScript();
    }, 200);
}
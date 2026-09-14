import React, {useState, useEffect} from 'react';
import { CSSTransition, TransitionGroup } from 'react-transition-group';

import BookCard from './BookCard';
import { BookPreview } from './BookPreview';

import {groupBooksByGenre} from '../Utils/GroupData.js';
import '@styles/books.css';

const BASE_URL = '/konektem'
const API_URL = BASE_URL + '/api/v1/books/all';

export default function BooksPage(){
    const [query, setQuery] = useState('');
    const [books, setBooks] = useState([]);
    const [filteredBooks, setFilteredBooks] = useState([]);

    useEffect( ()=>{
        const getFromApi = async ()=>{
            try{
                let response = await fetch(API_URL);
                let json = await response.json();

                if(Array.isArray(json)){
                    setBooks(json);
                } else if(json.data && json.data.books && Array.isArray(json.data.books)){
                    setBooks(json.data.books);
                }
            } catch(err){
                console.log(err);
            }
        }

        getFromApi();
    }, []);

    const toTitle = (str) => {
        if(typeof str != typeof 'string') return str;
        return str[0].toUpperCase() + str.substr(1);
    }

    const expandGroup = (e)=>{
        let targetItem = e.currentTarget.parentElement;
        if(!targetItem.classList.contains('collapse')){
            targetItem = targetItem.querySelector('.collapse');
        }

        if(!targetItem){
            console.log('no item to expand');
            return;
        }

        targetItem.classList.toggle('open');
    };

    useEffect(()=>{
        try{
            const filtered = books.filter(book => {
                let title = book.title.toLowerCase();
                let author = book.author.toLowerCase();
                return title.includes(query) || author.includes(query);
            });
            setFilteredBooks(filtered ?? books);
        } catch(err){
            console.log(err);
        }
    }, [query, books]);

    
    return (
    <>
    <header>
        <h1>📚 Bibliyotèk Liv</h1>
        <p>Navige, li, ak telechaje koleksyon liv nou an nan fòma PDF. Prop, vit, epi fasil.</p>
    </header>

    <div className="container">
        <div className="search-bar-books">
            <input name="book_search" type="text"
                    placeholder="Search books by title or author…"
                    onInput={(e)=>{setQuery(e.target.value)}}/>
        </div>
        {groupBooksByGenre(filteredBooks).map((gr, i) => (
            <div key={i}>
                <div className="section">
                    <div className="genre-header">
                        <h3>{toTitle(gr.name)}</h3>
                    </div>
                    <div className="accordion-item collapse books-grid">
                        {gr.books.map(book => (
                            <BookCard key={book.id} book={book}/>
                        ))}
                    </div>
                    <div className="accordion-item-controller" onClick={expandGroup}>
                        <i className="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        ))}
    </div>
    </>
    );
}

import React, {useState, useRef} from 'react';


export default function BookCard({book}){
    const [download, setDownload] = useState(false);
    const [preview, setPreview] = useState(false);

    let downloadLinkRef = useRef(null);

    const downloadBook = ()=>{
        // if(downloadLinkRef.current){
        //     downloadLinkRef.current.click();
        // }
        if(!book.pdfUrl){
            alert('File corrupted');
            return;
        }
        let a = document.createElement('a');
        a.href = book.pdfUrl;
        a.download = `${book.title}.pdf`;
        //document.body.appendChild(a);
        a.click();
        //a.remove();
    }

    const viewBook = ()=>{
        if(!book.pdfUrl){
            alert('File corrupted');
            return;
        }
        let a = document.createElement('a');
        a.target = '_blank';
        a.href = book.pdfUrl;
        a.click();
    }
    return (
        <article className="book-card">
            <div className="book-cover">
                <img className="full-wh" style={{borderRadius: '10px'}} src={book.image_location ?? '#'} alt="book cover" />
            </div>
            <div className="book-info">
                <h3 className="book-title ellipsis">{book.title}</h3>
                <div className="book-author ellipsis">by {book.author}</div>
                <div className="book-description">{book.description}</div>
            </div>
            <div className="book-actions">
                <button type="button" className="btn btn-view no-text-deco" onClick={viewBook}>
                    View
                    <i className="fa-brands fa-readme"></i>
                </button>
                <button type="button" className="btn btn-download no-text-deco" onClick={downloadBook}>
                    <i className="fa-solid fa-download"></i>
                </button>
            </div>
            {/* <a ref={downloadLinkRef} href={book.pdfUrl} download={`${book.title}.pdf`} style={{display: 'none'}}></a>
            <a ref={downloadLinkRef} href={book.pdfUrl} target='_blank' onClick={preview} style={{display: 'none'}}></a> */}
        </article>
    )
}
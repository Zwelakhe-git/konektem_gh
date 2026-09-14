import {initPdfModule, renderPDFPage} from './pdfPageToImage.js'
import { basename } from './utils.js';

initPdfModule();

document.addEventListener('DOMContentLoaded', function(){
    const fileInp = document.getElementById('book_file');
    fileInp.addEventListener('change', function(){
        try {
            const file = this.files[0];
            if(!file){
                showError('File not selected');
                return;
            }
            document.querySelector('input#title').value = basename(file);
            document.getElementById('cover-image-load')?.classList.remove('hidden');
            renderPDFPage(file)
            .then(dataUrl => {
                const img = document.querySelector('#book-prev-img');
                img.src = dataUrl;
                document.getElementById('cover-image-load')?.classList.add('hidden');
                img.style.display = 'block';

                fetch(dataUrl)
                .then(response => response.blob())
                .then(blob => {
                    document.querySelector('#cover-image-inp-field').classList.remove('hidden');
                    const coverImageFile = new File([blob], `${basename(file)}_cover.png`, { type: 'image/png' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(coverImageFile);
                    const coverImageInput = document.querySelector('input#book_image');
                    coverImageInput.files = dataTransfer.files;
                    coverImageInput.dispatchEvent(new Event('change'))

                })
                .catch(err => {
                    console.log(err);
                })
                
            })
            .catch(err => {
                console.error('Error:', err);
                showError('Error extracting pdf coverimage. Please upload a custom cover instead.');
            });
        } catch(err){
            console.error(err);
        }
    });

    document.querySelector('input#author').addEventListener('input', function(){
        const names = this.value.split(' ');
        this.value = names.map(name => name.replace(/^./, name.charAt(0).toUpperCase()) ).join(' ');
    });

    document.querySelector('.checkbox')?.addEventListener('click', function(){
        this.classList.toggle('checked');
        document.querySelector('.checkbox ~ #publish-inp').value = Number(this.classList.contains('checked'));
    });
});
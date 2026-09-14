// pdfPageToImage.js

let pdfjsLib = null;
let loadingPromise = null;

export function initPdfModule() {
    // Если уже загружен или загружается, возвращаем существующий промис
    if (pdfjsLib) return Promise.resolve(pdfjsLib);
    if (loadingPromise) return loadingPromise;

    loadingPromise = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        
        script.onload = function() {
            pdfjsLib = window.pdfjsLib; // Берем из глобальной области
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            resolve(pdfjsLib);
        };
        
        script.onerror = function() {
            reject(new Error('Failed to load PDF.js library'));
        };
        
        document.head.appendChild(script);
    });

    return loadingPromise;
}

export async function renderPDFPage(file, pageNum = 1) {
    try {
        // Ждем загрузки библиотеки
        await initPdfModule();
        
        const arrayBuffer = await file.arrayBuffer();
        const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
        const page = await pdf.getPage(pageNum);

        const canvas = document.createElement('canvas');
        const viewport = page.getViewport({ scale: 2 });
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        const context = canvas.getContext('2d');
        await page.render({
            canvasContext: context,
            viewport: viewport
        }).promise;

        return canvas.toDataURL('image/png');
    } catch (error) {
        console.error('Error rendering PDF:', error);
        throw error;
    }
}
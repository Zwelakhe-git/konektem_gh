let jsmediatags = null;
let loadingPromise = null;

export function initModule() {
    // Если уже загружен или загружается, возвращаем существующий промис
    if (jsmediatags) return Promise.resolve(jsmediatags);
    if (loadingPromise) return loadingPromise;

    loadingPromise = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/jsmediatags@3.9.7/dist/jsmediatags.min.js';
        
        script.onload = function() {
            jsmediatags = window.jsmediatags; // Берем из глобальной области
            resolve(jsmediatags);
        };
        
        script.onerror = function() {
            reject(new Error('Failed to load jsmediatags library'));
        };
        
        document.head.appendChild(script);
    });

    return loadingPromise;
}

export async function getAudioFileInfo(file, cb){
    // jsmediatags доступен глобально
    await initModule();
    const fileInfo = {
        title: null,
        artist: null,
        cover_image_url: null
    };
    jsmediatags.read(file, {
        onSuccess: function(tag) {
            const picture = tag.tags.picture;
            
            if (picture) {
                const base64String = arrayBufferToBase64(picture.data);
                const imageUrl = `data:${picture.format};base64,${base64String}`;
                
                
                const title = tag.tags.title || 'Unknown';
                const artist = tag.tags.artist || 'Unknown';
                fileInfo.title = title;
                fileInfo.artist = artist;
                fileInfo.cover_image_url = imageUrl;
            }
            cb(fileInfo);
        },
        onError: function(error) {
            cb(fileInfo);
        }
    });
};

function arrayBufferToBase64(buffer) {
    let binary = '';
    const bytes = new Uint8Array(buffer);
    for (let i = 0; i < bytes.length; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary);
}
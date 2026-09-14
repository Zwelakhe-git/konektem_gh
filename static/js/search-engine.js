function performSearch() {
    const searchInput = document.getElementById('tb-inp-el');
    if (!searchInput) return;
    
    const searchTerm = searchInput.value.trim();
    
    if (searchTerm === '') {
        alert('Please enter a search term');
        return;
    }
    
    // Show loading state
    const root = document.getElementById('root');
    if (root) {
        let div = root.querySelector('.search-results-container');
        if(!div){
            div = document.createElement('div');
            div.classList.add('abs-pos', 'search-results-container', 'z-indx5');
        }
        div.innerHTML = `
                <div class="search-loading">
                    Searching for "${searchTerm}"...
                </div>
        `;
        document.documentElement.scrollTo(0,0);
        root.appendChild(div);
        document.documentElement.style.overflow = 'hidden';
    }
    
    const currentPage = new URLSearchParams(window.location.search).get('p') || 'index';
    
    switch(currentPage) {
        case 'actuality':
            searchNews(searchTerm);
            break;
        case 'music':
            searchMusic(searchTerm);
            break;
        case 'events':
            searchEvents(searchTerm);
            break;
        default:
            searchAllContent(searchTerm);
            break;
    }
}

async function searchNews(searchTerm) {
    try {
        //const response = await fetch(`/php/dbReader.php?r=news`);
        const newsData = await cachedFetch(`/php/dbReader.php?r=news`, 'newsData');
        //const newsData = await response.json();
        
        const filteredNews = newsData.filter(news => 
            news.newsTitle.toLowerCase().includes(searchTerm.toLowerCase()) ||
            news.newsHeadline.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (news.fullContent && news.fullContent.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        
        displaySearchResults(filteredNews, 'news', searchTerm);
    } catch (error) {
        console.error('Search error:', error);
        alert('Error performing search');
    }
}

async function searchMusic(searchTerm) {
    try {
        //const response = await fetch(`/php/dbReader.php?r=musicContent`);
        const musicData = await cachedFetch(`/php/dbReader.php?r=musicContent`, 'musicData');
        
        const filteredMusic = musicData.filter(track => 
            track.track_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            track.artist_name.toLowerCase().includes(searchTerm.toLowerCase())
        );
        
        displaySearchResults(filteredMusic, 'music', searchTerm);
    } catch (error) {
        console.error('Search error:', error);
        alert('Error performing search');
    }
}

async function searchEvents(searchTerm) {
    try {
        //const response = await fetch(`/php/dbReader.php?r=events`);
        const eventsData = await cachedFetch(`/php/dbReader.php?r=events`, 'eventsData');
        
        const filteredEvents = eventsData.filter(event => 
            event.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            event.location.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (event.host && event.host.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        
        displaySearchResults(filteredEvents, 'events', searchTerm);
    } catch (error) {
        console.error('Search error:', error);
        alert('Error performing search');
    }
}

async function searchAllContent(searchTerm) {
    try {
        /*const [newsResponse, musicResponse, eventsResponse] = await Promise.all([
            fetch(`/php/dbReader.php?r=news`),
            fetch(`/php/dbReader.php?r=musicContent`),
            fetch(`/php/dbReader.php?r=events`)
        ]);*/
        
        const [newsData, musicData, eventsData] = await Promise.all([
            cachedFetch(`/php/dbReader.php?r=news`, 'newsData'),
            cachedFetch(`/php/dbReader.php?r=musicContent`,'musicData'),
            cachedFetch(`/php/dbReader.php?r=events`,'eventsData')
        ]);
        
        const allResults = {
            news: newsData.filter(news => 
                news.newsTitle.toLowerCase().includes(searchTerm.toLowerCase()) ||
                news.newsHeadline.toLowerCase().includes(searchTerm.toLowerCase())
            ),
            music: musicData.filter(track => 
                track.track_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                track.artist_name.toLowerCase().includes(searchTerm.toLowerCase())
            ),
            events: eventsData.filter(event => 
                event.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                event.location.toLowerCase().includes(searchTerm.toLowerCase())
            )
        };
        
        displayCombinedSearchResults(allResults, searchTerm);
    } catch (error) {
        console.error('Search error:', error);
        alert('Error performing search');
    }
}

function displaySearchResults(results, contentType, searchTerm) {
    const root = document.getElementById('root');
    if (!root) return;
    
    let resultsHTML = '';
    
    switch(contentType) {
        case 'news':
            resultsHTML = generateNewsResultsHTML(results);
            break;
        case 'music':
            resultsHTML = generateMusicResultsHTML(results);
            break;
        case 'events':
            resultsHTML = generateEventsResultsHTML(results);
            break;
    }
    let div = root.querySelector('.search-results-container');
    if(!div){
        div = document.createElement('div');
        div.classList.add('abs-pos', 'search-results-container', 'z-indx5');
    }

    div.innerHTML = `
            <div class="search-results-header">
                <h2>Search Results for "${searchTerm}" <span class="results-count">${results.length} found</span></h2>
                <button class="close-search-btn" id="close-search-results">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            ${resultsHTML}
            ${results.length === 0 ? `
                <div class="no-results">
                    <p>No results found for "${searchTerm}"</p>
                    <p>Try different keywords or browse our categories.</p>
                </div>
            ` : ''}
    `;
    document.documentElement.scrollTo(0,0);
    !root.contains(div) && root.appendChild(div);
    document.documentElement.style.overflow = 'hidden';
    
    // Add event listener for close button
    const closeBtn = document.getElementById('close-search-results');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSearchResults);
    }
}

function displayCombinedSearchResults(results, searchTerm) {
    const root = document.getElementById('root');
    if (!root) return;
    const totalResults = results.news.length + results.music.length + results.events.length;

    let div = root.querySelector('.search-results-container');
    if(!div){
        div = document.createElement('div');
        div.classList.add('abs-pos', 'search-results-container', 'z-indx5');
    }

    div.innerHTML = `
            <div class="search-results-header">
                <h2>Search Results for "${searchTerm}" <span class="results-count">${totalResults} found</span></h2>
                <button class="close-search-btn" id="close-search-results">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            ${results.news.length > 0 ? `
                <div class="results-section">
                    <h3>News <span class="results-count">${results.news.length}</span></h3>
                    ${generateNewsResultsHTML(results.news)}
                </div>
            ` : ''}
            
            ${results.music.length > 0 ? `
                <div class="results-section">
                    <h3>Music <span class="results-count">${results.music.length}</span></h3>
                    ${generateMusicResultsHTML(results.music)}
                </div>
            ` : ''}
            
            ${results.events.length > 0 ? `
                <div class="results-section">
                    <h3>Events <span class="results-count">${results.events.length}</span></h3>
                    ${generateEventsResultsHTML(results.events)}
                </div>
            ` : ''}
            
            ${totalResults === 0 ? `
                <div class="no-results">
                    <p>No results found for "${searchTerm}"</p>
                    <p>Try different keywords or browse our categories.</p>
                </div>
            ` : ''}
    `;
    document.documentElement.scrollTo(0,0);
    !root.contains(div) && root.appendChild(div);
    document.documentElement.style.overflow = 'hidden';
    
    // Add event listener for close button
    const closeBtn = document.getElementById('close-search-results');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSearchResults);
    }
}

function generateNewsResultsHTML(news) {
    return news.map(item => `
        <div class="search-result-item news-item">
            <a href="/?p=actuality&id=${item.id}" class="result-link">
                <div class="result-image">
                    <img src="${item.image_location || '/media/images/default-news.jpg'}" alt="${item.newsTitle}" onerror="this.src='/media/images/default-news.jpg'">
                </div>
                <div class="result-content">
                    <h4>${item.newsTitle || 'Untitled News'}</h4>
                    <p>${item.newsHeadline || 'No description available'}</p>
                    <div class="result-meta">
                        <span class="result-date">${item.newsDate || 'Date not available'}</span>
                    </div>
                </div>
            </a>
        </div>
    `).join('');
}

function generateMusicResultsHTML(music) {
    return music.map(track => `
        <div class="search-result-item music-item">
            <a href="/?p=music&id=${track.id || track.track_id}" class="result-link">
                <div class="result-image">
                    <img src="${track.image_location || '/media/images/default-music.jpg'}" alt="${track.track_name}" onerror="this.src='/media/images/default-music.jpg'">
                </div>
                <div class="result-content">
                    <h4>${track.track_name || 'Untitled Track'}</h4>
                    <p>${track.artist_name || 'Unknown Artist'}</p>
                    <div class="result-meta">
                        <div class="track-stats">
                            <span>Plays: ${track.plays || 0}</span>
                            <span>Likes: ${track.likes || 0}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    `).join('');
}

function generateEventsResultsHTML(events) {
    return events.map(event => `
        <div class="search-result-item event-item">
            <a href="/?p=events&id=${event.id || event.event_id}" class="result-link">
                <div class="result-image">
                    <img src="${event.image_location || '/media/images/default-event.jpg'}" alt="${event.title}" onerror="this.src='/media/images/default-event.jpg'">
                </div>
                <div class="result-content">
                    <h4>${event.title || 'Untitled Event'}</h4>
                    <p>${event.location || 'Location not specified'}</p>
                    <div class="result-meta">
                        <span class="event-date">${event.eventDate || 'Date not available'}</span>
                        <span class="event-price">$${event.price || '0'}</span>
                    </div>
                </div>
            </a>
        </div>
    `).join('');
}

// Add this function to handle closing search results
function closeSearchResults() {
    const root = document.getElementById('root');
    if (!root) return;
    
    // Clear the search results and return to the original page content
    removeSearchContainers();
    document.documentElement.style.overflow = 'auto';
    
    // Clear the search input
    const searchInput = document.getElementById('tb-inp-el');
    if (searchInput) {
        searchInput.value = '';
    }
}

function removeSearchContainers(){
    const root = document.getElementById('root');
    if (!root) return;
     document.querySelectorAll('.search-results-container').forEach(itm =>{
        try{
            root.removeChild(itm);
        } catch(error){
            console.log("failed to remove search containers: " + error.message);
            return;
        }
    });
}
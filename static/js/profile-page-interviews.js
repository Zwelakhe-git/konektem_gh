document.addEventListener('DOMContentLoaded', function() {
    // DOM refs for interviews
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    let currentView = 'grid';
    
    function setView(view) {
        currentView = view;
        
        if (view === 'grid') {
            gridView.style.display = '';
            listView.style.display = 'none';
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
            localStorage.setItem('interviewsView', 'grid');
        } else {
            gridView.style.display = 'none';
            listView.style.display = '';
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
            localStorage.setItem('interviewsView', 'list');
        }
    }
    
    // Load saved view preference
    const savedView = localStorage.getItem('interviewsView') || 'grid';
    setView(savedView);
    
    gridViewBtn.addEventListener('click', () => setView('grid'));
    listViewBtn.addEventListener('click', () => setView('list'));
    
});
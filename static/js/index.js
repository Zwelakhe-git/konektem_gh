/**
 * share buttons should match the following selectors:
 * .share-btn .media-ico
 * .j-share .media-ico - for news. though changes on this will still be made to maintain consistency
 * @returns none
 */
function handleShare(){
    const newsShareIcons = document.querySelectorAll('.j-share .media-ico');
    const allShareIcons = document.querySelectorAll('.share-btn .media-ico');
    const shareMenu = document.getElementById('shareMenu');
    const shareHash = {
    	actuality: {
            key: 'newsTitle',
        	data: newsData
        },
        interviews: {
        	key: 'title',
            data: interviewsData
        },
        music: {
        	key: 'track_name',
            data: musicData
        },
        events: {
        	key: 'title',
            data: eventsData
        },
        books: {
        	key: 'title',
            data: booksData
        }
    };
    let url = "https://konektem.net/";
    
    //localStorage.getItem('currentUser') === 'guest'
    if(window.currentUser === 'guest') return;
    if(allShareIcons.length > 0){
        allShareIcons.forEach( icon => {
            icon.addEventListener('click', async ()=>{
                let share_text = "konektem";
                if(!icon.dataset.itemid && !icon.dataset.itemname){
                    console.log("no id information about the shared item");
                    return;
                }
                url += `?p=${icon.dataset.itemname}&id=${icon.dataset.itemid}`;
                let target = shareHash[`${icon.dataset.itemname}`].data.find( item => {
                	return item.id == icon.dataset.itemid;
                });
                share_text += ` ${icon.dataset.itemname}\n${target[shareHash[`${icon.dataset.itemname}`].key]}`;
                share(share_text);
            })
        });
        
        async function share(title){
            if (navigator.share){
                try{
                    await navigator.share({
                        title: title,
                        text: title,
                        url: url
                    });
                    return;
                } catch(error){
                    console.log('error while sharing: ' + icon.dataset.itemid, error);
                }
            } else {
                shareMenu.style.display = (shareMenu.style.display === "flex") ? "none" : "flex";
            }
        }

        // Каждая соцсеть
        document.getElementById('shareVK').onclick = () =>
          window.open(`https://vk.com/share.php?url=${encodeURIComponent(location.href)}`);

        document.getElementById('shareTG').onclick = () =>
          window.open(`https://t.me/share/url?url=${encodeURIComponent(location.href)}&text=${encodeURIComponent(document.title)}`);

        document.getElementById('shareWA').onclick = () =>
          window.open(`https://wa.me/?text=${encodeURIComponent(location.href)}`);
    } else {
        console.log("no share icons found");
    }
}

function shelfAnimation(){
    const containerElements = document.querySelectorAll('.shelf-anim');
    containerElements.forEach((container) => {
        container.addEventListener('click', () => {
            containerElements.forEach((cont) => {
                cont.classList.toggle('focus', cont === container);
            });
        });
    });
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.shelf-anim')) {
            containerElements.forEach((cont) => {
                cont.classList.remove('focus');
            });
        }
    });

    window.addEventListener('scroll', () => {
        const scrollPosition = window.scrollY;
        containerElements.forEach((container) => {
            const containerPosition = container.offsetTop;
            const containerHeight = container.offsetHeight;
            const containerBottom = containerPosition + containerHeight;

            /*if(containerBottom < scrollPosition){
                container.classList.remove('focus');
                container.style.opacity = '0.2';
                //container.style.transform = 'translateY(-50px)';
            }*/
            if (scrollPosition + window.innerHeight > containerPosition + 100) {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';

            }
            else{
                container.classList.remove('focus');
                container.style.opacity = '0.2';
                container.style.transform = 'translateY(50px)';
            }

        });
    })
}


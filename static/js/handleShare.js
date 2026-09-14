
export async function handleShare(){
    try{
        const session = JSON.parse(sessionStorage.getItem("user"));

        let shareBtns = document.querySelectorAll(".share-btn");
        let url = "";
        if(session.name !== 'guest'){
            shareBtns.forEach(btn => {
                btn.addEventListener('click', (e)=>{
                    const icon = btn.querySelector('.media-ico') ?? btn.querySelector('i');
                    let share_text = "konektem";
                    if(!icon.dataset.itemid && !icon.dataset.itemname){
                        console.log("no id information about the shared item");
                        return;
                    }
                    url += `?p=${icon.dataset.itemname}&id=${icon.dataset.itemid}`;
                    // get the title of the item
                    let target = shareHash[`${icon.dataset.itemname}`].data.find( item => {
                        return item.id == icon.dataset.itemid;
                    });
                    share_text += ` ${icon.dataset.itemname}\n${target[shareHash[`${icon.dataset.itemname}`].key]}`;
                    share(share_text);
                });
            });
        }
    } catch(err){}
}
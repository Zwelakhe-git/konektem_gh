import { initModule, getAudioFileInfo } from "./audioFileInfo.js";
import { basename } from "./utils.js";
initModule();

document.addEventListener('DOMContentLoaded', function(){
    const title = document.querySelector('input[name="title"]');
    const newArtist = document.querySelector('input[name="new_artist_name"]');
    const artistNameField = (document.querySelector('#artist_name') || document.querySelector('#artist_id'));
    const imagePreview = document.querySelector('#music-track-prev-img');
    const imageField = document.querySelector('#track_image');

    document.querySelector('#audio_file')?.addEventListener('change', function(){
        const file = this.files[0];
        if(!file) return;
        getAudioFileInfo(file, function(fileInfo){
            if(fileInfo['cover_image_url']){
                imagePreview.src = fileInfo['cover_image_url'];
                imagePreview.style.display = 'block';
                fetch(fileInfo['cover_image_url'])
                .then(res => res.blob())
                .then(blob => {
                    const dataTransfer = new DataTransfer();
                    const imageFile = new File([blob], `${basename(file)}_cover.png`, { type: 'image/png' })
                    dataTransfer.items.add(imageFile);
                    
                    imageField.files = dataTransfer.files;
                    imageField.dispatchEvent(new Event('change'));

                })
                .catch(err => {
                    console.error(err);
                });
            } else {
                //imageField.files = new FileList();
            }
            if(fileInfo['artist']){
                
                artistNameField.disabled = true;
                artistNameField.parentElement.style.display = 'none';
                document.querySelector('#new_artist_field').style.display = 'block';
                newArtist.value = fileInfo['artist'];
                newArtist.disabled = false;
            } else {
                artistNameField.parentElement.style.display = 'block';
                artistNameField.disabled = false;
                document.querySelector('#new_artist_field').style.display = 'none';
                newArtist.value = '';
                newArtist.disabled = true;
            }
            if(fileInfo['title']){
                title.value = fileInfo['title'];
            } else {
                title.value = basename(file);
            }
        })
    });

    document.querySelector('.checkbox')?.addEventListener('click', function(){
        this.classList.toggle('checked');
        document.querySelector('.checkbox ~ #publish-inp').value = Number(this.classList.contains('checked'));
    });

    (document.getElementById('artist_name') ?? document.getElementById('artist_id')).addEventListener('change', function() {
        const newArtistField = document.getElementById('new_artist_field');
        
        newArtistField.querySelector('input[name="new_artist_name"]').disabled = this.value !== 'new';
        newArtistField.style.display = this.value ==='new' ? 'block' : 'none';
    });


});
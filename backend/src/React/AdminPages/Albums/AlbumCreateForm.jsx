import React, {useState, useRef} from 'react';
import AlertModal from '../../Components/AlertModal';

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function ShareModal(){
    return (
    <div className="share-modal">
        <div className="share-platforms">
            <button className="whatsapp-share">
                <i className='fa-solid fa-whatsapp'></i>
                <strong>WhatsApp</strong>
            </button>
            <button className="facebook-share">
                <i className='fa-solid fa-facebook'></i>
                <strong>Facebook</strong>
            </button>
        </div>
    </div>
    )
}

function QRCode({base64Data, albumId}){
    const shareAlbum = async () => {
        if(navigator.share){
            await navigator.share({
                title: "Album",
                text: "Download the latest album",
                url: `https://konektem.net/konektem/music/album/id/${albumId}`
            });
        } else {
            alert('Your browser doesnt support sharing. Please download the code and share on your favorite platforms');
        }
    };
    return (
    <div id="qrcode-modal">
        <div className="qr-code-container card box">
            <div className="qr-code">
                <img id="qr-code-image" src={base64Data ? `data:image/png;base64,${base64Data}` : '#'} className="qr-code-image"/>
            </div>
            <a className="btn btn-primary qrcode-img-download-link" href={base64Data ? `data:image/png;base64,${base64Data}` : '#'} download="qrcode.png">
                <i className="fas fa-download"></i>Download</a>
            <div className="btn btn-success qrcode-share-btn" onClick={shareAlbum}><i className="fa-solid fa-share-nodes"></i>share</div>
            <div className="qr-code-info">
                Download album
            </div>
        </div>
    </div>
    );
}

function TracksList({tracks, setTracks}){
    const removeTrack = (idx)=>{
        setTracks(tracks.splice(idx, 1));
    }
    
    return (
    <div className="list-group">
        { tracks.map((track, index)=>(
        <div key={index} className="list-group-item" data-index={index}>
            <div className="d-flex align-items-center">
                <div className="track-preview me-3">
                    {track.imagePreview ? 
                    <img src={track.imagePreview} style={{width: '50px', height: '50px', objectFit: 'cover', borderRadius: '4px'}} /> : 
                    <div style={{width: '50px', height: '50px', background: '#e0e0e0', borderRadius: '4px', display: 'flex', alignItems: 'center', justifyContent: 'center'}}><i className="fa-solid fa-music"></i></div>
                    }
                </div>
                <div className="flex-grow-1">
                    <strong>{escapeHtml(track.name)}</strong><br/>
                    <small className="text-muted">{track.audioName || 'Fichier audio'}</small>
                </div>
                <button type="button" className="btn btn-sm btn-danger remove-track" data-index={index} onClick={()=>{removeTrack(index)}}>
                    <i className="fa-solid fa-x"></i>
                </button>
            </div>
        </div>
        ))}
    </div>
    );
}

export default function AlbumCreateForm({currentAlbum}){
    const [preview, setPreview] = useState(currentAlbum ? true :false);
    const [albumName, setAlbumName] = useState(currentAlbum ? currentAlbum.name : '');
    const [artistName, setArtistName] = useState(currentAlbum ? currentAlbum.name : '');
    const [artistId, setArtistId] = useState(currentAlbum ? currentAlbum.artist_id : null);
    const [newArtistName, setNewArtistName] = useState('');
    const [newArtist, setNewArtist] = useState(currentAlbum ? false : true);
    const [genre, setGenre] = useState(currentAlbum ? currentAlbum.genre : '');
    const [releaseYear, setReleaseYear] = useState(currentAlbum ? currentAlbum.release_year : '');
    const [description, setDescription] = useState(currentAlbum ? currentAlbum.description : '');
    const [ownerName, setOwnerName] = useState(currentAlbum ? currentAlbum.owner : '');
    const [albumImageUrl, setAlbumImageUrl] = useState(currentAlbum ? currentAlbum.image_url : '');
    const [showQrCode, setShowQrCode] = useState(currentAlbum ? true : false);
    const [qrCodeBase64Data, setQrCodeBase64Data] = useState(currentAlbum ? currentAlbum.qrcode_base64 : null);
    const [copyright, setCopyright] = useState(false);
    const [consent, setConsent] = useState(false);

    const [albumImageFile, setAlbumImageFile] = useState(null);
    const [trackFiles, setTrackFiles] = useState([]);
    const [albumId, setAlbumId] = useState(currentAlbum ? currentAlbum.album_id : null);

    const [helperIds, setHelperIds] = useState([]);
    const [submiting, setSubmiting] = useState(false);
    const [showAlert, setShowAlert] = useState({});

    const tracksInpRef = useRef(null);
    const formRef = useRef(null);
    const coverImageInpRef = useRef(null);

    const showHelper = (id) => {
        setHelperIds(prev => Array.from(new Set([...prev, id])));
    };
    const removeHelper = (id) => {
        setHelperIds(helperIds.filter(hp => hp !== id));
    };

    const loadTracks = () => {
        if(!tracksInpRef){
            return;
        }
        const audioFiles = tracksInpRef.current.files;
    
        if (!audioFiles || audioFiles.length === 0) {
            alert('Veuillez sélectionner un fichier audio');
            return;
        }
        
        setTrackFiles(Array.from(audioFiles).map(file => {
            return {
                name: file.name,
                audioFile: file,
                imageFile: null,
                imagePreview: null,
                audioName: file.name
            };
        }));
        tracksInpRef.current.value = '';
    };

    const loadCoverImage = function (e){
        if (coverImageInpRef.current && coverImageInpRef.current.files && coverImageInpRef.current.files[0]) {
            setAlbumImageFile(coverImageInpRef.current.files[0]);
            const reader = new FileReader();
            reader.onload = function(ev) {
                setAlbumImageUrl(ev.target.result);
                setPreview(true);
            };
            reader.readAsDataURL(coverImageInpRef.current.files[0]);
        } else {
            setPreview(false);
        }
    };

    const saveAlbum = (e) => {
        e.preventDefault();

        if (!albumName) {
            alert('Veuillez entrer le nom de l\'album');
            return;
        }
        if (!genre) {
            alert('Veuillez entrer le genre');
            return;
        }
        if (!releaseYear) {
            alert('Veuillez entrer l\'année de sortie');
            return;
        }
        if (!albumImageFile) {
            alert('Veuillez sélectionner une cover pour l\'album');
            return;
        }
        if (trackFiles.length === 0) {
            alert('Veuillez ajouter au moins une piste à l\'album');
            return;
        }
        if (!ownerName) {
            alert('Veuillez entrer votre nom pour la signature');
            return;
        }
        if (!copyright || !consent) {
            alert('Veuillez accepter les conditions');
            return;
        }
        
        const formData = new FormData();
        
        // Informations album
        formData.append('album_name', albumName);
        formData.append('genre', genre);
        formData.append('release_year', releaseYear);
        formData.append('album_description', description);
        formData.append('album_image', albumImageFile);
        formData.append('owner_name', ownerName);
        formData.append('copyright', copyright ? '1' : '0');
        formData.append('consent', consent ? '1' : '0');
        
        // Artiste
        if (artistName === 'new') {
            if (!newArtistName) {
                alert('Veuillez entrer le nom du nouvel artiste');
                return;
            }
            formData.append('artist_type', 'new');
            formData.append('new_artist_name', newArtistName);
        } else if (artistName) {
            formData.append('artist_type', 'existing');
            formData.append('artist_id', artistId);
        } else {
            alert('Veuillez sélectionner un artiste');
            return;
        }
        
        // Tracks
        let valid = true;
        trackFiles.forEach((track, index) => {
            if (!track.audioFile) {
                alert(`Piste ${index + 1}: Fichier audio manquant`);
                valid = false;
                return;
            }
            formData.append(`tracks[${index}][name]`, track.name);
            formData.append(`tracks[${index}][audio]`, track.audioFile);
            // remove the track image
            if (track.imageFile) {
                formData.append(`tracks[${index}][image]`, track.imageFile);
            }
        });
        
        if (!valid) return;
        
        // Désactiver le bouton pour éviter double soumission
        setSubmiting(true);
        
        // Envoyer via fetch ou soumission standard
        // Ici on utilise fetch pour mieux gérer la réponse
        
        fetch('/konektem/user/me/albums/create', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if(data.qrcode_base64){
                    setQrCodeBase64Data(data.qrcode_base64);
                    setAlbumId(data.album_id);
                    setShowQrCode(true);
                }
                setShowAlert({
                    message: (data.message || 'Successfully created album'),
                    type: 'success'
                });
                //alert('Success: ' + (data.message || 'Successfully created album'));
                setSubmiting(false);
            } else {
                setShowAlert({
                    message: 'Erreur: ' + (data.error || 'Une erreur est survenue'),
                    type: 'danger'
                });
                //alert('Erreur: ' + (data.error || 'Une erreur est survenue'));
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = '';
            submitBtn.textContent = 'Créer l\'album';
        })
        .catch(error => {
            console.log(error);
            alert('Erreur lors de l\'envoi: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Créer l\'album';
        });
    };

    return (
    <form ref={formRef} method="POST" encType="multipart/form-data" id="albumForm" onSubmit={saveAlbum}>
        {showAlert && <AlertModal message={showAlert.message} type={showAlert.type} setShow={setShowAlert}/>}
        <div className="row">
            <div className="col-md-6">
                <div className="mb-3">
                    <label htmlFor="album_name" className="form-label">Nom de l'album *</label>
                    <div>
                        <input type="text" className="form-control" id="album_name" name="album_name" onChange={(e)=>{setAlbumName(e.target.value.trim())}} required/>
                        <span className="input-helper" style={{display: helperIds.includes('album_name-helper') ? 'inline-block':'none'}} id="album_name-helper">
                            'Veuillez entrer le nom de l\'album'
                        </span>
                    </div>
                </div>
                <div className="mb-3">
                    <label htmlFor="artist_name" className="form-label">Artiste *</label>
                    <select className="form-control" id="artist_name" name="artist_name" onChange={(e)=>{setArtistName(e.target.value.trim()); setArtistId(e.target.value)}} required>
                        <option value="new" defaultValue={true}>+ Ajouter nouvel artiste</option>
                    </select>
                </div>
                
                <div className="mb-3" id="new_artist_field" style={{display: 'block'}}>
                    <label htmlFor="new_artist_name" className="form-label">Nom du nouvel artiste *</label>
                    <input type="text" className="form-control" id="new_artist_name" name="new_artist_name" onChange={(e)=>{setNewArtistName(e.target.value).trim()}} required/>
                </div>
                
                <div className="mb-3">
                    <label htmlFor="genre" className="form-label">Genre *</label>
                    <input type="text" className="form-control" id="genre" name="genre" onChange={(e)=>{setGenre(e.target.value.trim())}} required/>
                </div>
                
                <div className="mb-3">
                    <label htmlFor="release_year" className="form-label">Année de sortie *</label>
                    <input type="date" className="form-control" id="release_year" name="release_year" onChange={(e)=>{setReleaseYear(e.target.value)}} required/>
                </div>
                
                <div className="mb-3">
                    <label htmlFor="album_description" className="form-label">Description</label>
                    <textarea className="form-control" id="album_description" name="album_description" rows="4" onChange={(e)=>{setDescription(e.target.value)}}></textarea>
                </div>
                
                <div className="mb-3">
                    <label htmlFor="album_image" className="form-label">Cover de l'album</label>
                    <input ref={coverImageInpRef} type="file" className="form-control" id="album_image" name="album_image" accept="image/*" required onChange={loadCoverImage}/>
                    {preview && <img src={albumImageUrl} id="album-preview-img" style={{display: 'block', marginTop: '10px', maxWidth: '200px'}}/> }
                </div>

                { showQrCode && <QRCode base64Data={qrCodeBase64Data} albumId={albumId}/> }
            </div>
            
            <div className="col-md-6">
                <div className="card">
                    <div className="card-header bg-primary text-white">
                        <strong>Ajouter des pistes</strong>
                    </div>
                    <div className="card-body">
                        
                        <div className="mb-3">
                            <label htmlFor="audio_files" className="form-label">Fichier audio *</label>
                            <input ref={tracksInpRef} type="file" className="form-control" id="audio_files" accept="audio/mp3,audio/wav" multiple/>
                            <div className="form-text">Formats acceptés: MP3, WAV</div>
                        </div>
                        
                        <button type="button" className="btn btn-success" id="addTrackBtn" onClick={loadTracks}>
                            <i className="fa-solid fa-plus"></i> Ajouter cette piste
                        </button>
                    </div>
                </div>
                
                <div className="card mt-3">
                    <div className="card-header bg-secondary text-white">
                        <strong>Pistes ajoutées (<span id="trackCount">{trackFiles.length}</span>)</strong>
                    </div>
                    <div className="card-body" id="tracksList" style={{maxHeight: '400px', overflowY: 'auto'}}>
                        <div className="text-muted text-center">Aucune piste ajoutée pour le moment</div>
                        <TracksList tracks={trackFiles} setTracks={setTrackFiles}/>
                    </div>
                </div>
            </div>
        </div>
        
        <div className="row mt-3">
            <div className="col-md-12">
                <div className="copyright">
                    <div className="mb-3">
                        <input type="checkbox" name="copyright" id="copyright" required onChange={(e)=>{setCopyright(e.target.checked)}}/>
                        <label htmlFor="copyright">
                            &copy; J'accepte les conditions de droit d'auteur et donne mon consentement pour publier mon contenu
                        </label>
                    </div>
                    <div className="mb-3">
                        <label htmlFor="owner-name" className="form-label">
                            Nous respectons les droits de propriété du contenu.
                            Veuillez entrer votre nom pour signer votre contenu publié et réserver vos droits.
                        </label>
                        <input type="text" value={ownerName} className="form-control" id="owner-name" name="owner_name"
                            placeholder="Signature de propriété (nom)" onChange={(e)=>{setOwnerName(e.target.value.trim())}} required/>
                    </div>
                </div>
                <div className="terms-conds" style={{marginBottom: '10px'}}>
                    <input type="checkbox" id="consent" name="consent" required onChange={(e)=>{setConsent(e.target.checked)}} />
                    <label htmlFor="consent">Je suis d'accord avec les 
                        <a href="/terms" className="text-purple-600 hover:underline">Conditions d'utilisation</a>
                        et la 
                        <a href="/privacy" className="text-purple-600 hover:underline">Politique de confidentialité</a>
                    </label>
                </div>
            </div>
        </div>
        
        <button type="submit" className="btn btn-primary" disabled={submiting}>
            { submiting ?
                <i className="fa-solid fa-spinner fa-spin-pulse"></i> :
                "Créer l'album"
            }
        </button>
        <a href="/konektem/user/me/albums" className="btn btn-secondary cancel">Annuler</a>

        {trackFiles.map((track, index) => (
            <input key={index} className="track-data-field" type="hidden" name={`tracks[${index}][name]`} value={track.name} />
        ))}
    </form>);
}
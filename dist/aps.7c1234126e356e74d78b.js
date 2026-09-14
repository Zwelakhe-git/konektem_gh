/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./public/tmp-build/js/album-create-page.js"
/*!**************************************************!*\
  !*** ./public/tmp-build/js/album-create-page.js ***!
  \**************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _alert_modal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./alert-modal.js */ \"./public/tmp-build/js/alert-modal.js\");\n// Prévisualisation cover album\n//import \"../css/album-create-page.css\";\n\n\ndocument.getElementById('album_image').addEventListener('change', function(e) {\n    const preview = document.getElementById('album-preview-img');\n    if (this.files && this.files[0]) {\n        const reader = new FileReader();\n        reader.onload = function(ev) {\n            preview.src = ev.target.result;\n            preview.style.display = 'block';\n        };\n        reader.readAsDataURL(this.files[0]);\n    } else {\n        preview.style.display = 'none';\n    }\n});\n\n// Gestion du champ \"nouvel artiste\"\ndocument.getElementById('artist_name').addEventListener('change', function() {\n    const newArtistField = document.getElementById('new_artist_field');\n    if (this.value === 'new') {\n        newArtistField.style.display = 'block';\n        document.getElementById('new_artist_name').required = true;\n    } else {\n        newArtistField.style.display = 'none';\n        document.getElementById('new_artist_name').required = false;\n    }\n});\n\n// Gestion des tracks\nlet tracks = [];\n\nfunction updateTracksList() {\n    const container = document.getElementById('tracksList');\n    const countSpan = document.getElementById('trackCount');\n    \n    if (tracks.length === 0) {\n        container.innerHTML = '<div class=\"text-muted text-center\">Aucune piste ajoutée pour le moment</div>';\n        countSpan.textContent = '0';\n        return;\n    }\n    \n    countSpan.textContent = tracks.length;\n    \n    let html = '<div class=\"list-group\">';\n    tracks.forEach((track, index) => {\n        html += `\n            <div class=\"list-group-item\" data-index=\"${index}\">\n                <div class=\"d-flex align-items-center\">\n                    <div class=\"track-preview me-3\">\n                        ${track.imagePreview ? `<img src=\"${track.imagePreview}\" style=\"width: 50px; height: 50px; object-fit: cover; border-radius: 4px;\">` : '<div style=\"width: 50px; height: 50px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center;\"><i class=\"fa-solid fa-music\"></i></div>'}\n                    </div>\n                    <div class=\"flex-grow-1\">\n                        <strong>${escapeHtml(track.name)}</strong><br>\n                        <small class=\"text-muted\">${track.audioName || 'Fichier audio'}</small>\n                    </div>\n                    <button type=\"button\" class=\"btn btn-sm btn-danger remove-track\" data-index=\"${index}\"><i class=\"fa-solid fa-x\"></i></button>\n                </div>\n            </div>\n        `;\n    });\n    html += '</div>';\n    container.innerHTML = html;\n    \n    // Ajouter les écouteurs pour les boutons supprimer\n    document.querySelectorAll('.remove-track').forEach(btn => {\n        btn.addEventListener('click', function() {\n            const idx = parseInt(this.getAttribute('data-index'));\n            tracks.splice(idx, 1);\n            updateTracksList();\n            updateHiddenFields();\n        });\n    });\n}\n\nfunction escapeHtml(str) {\n    if (!str) return '';\n    return str.replace(/[&<>]/g, function(m) {\n        if (m === '&') return '&amp;';\n        if (m === '<') return '&lt;';\n        if (m === '>') return '&gt;';\n        return m;\n    });\n}\n\nfunction updateHiddenFields() {\n    // Supprimer les anciens champs cachés\n    document.querySelectorAll('.track-data-field').forEach(field => field.remove());\n    \n    // Ajouter les nouveaux champs cachés pour chaque track\n    tracks.forEach((track, index) => {\n        if (track.name) {\n            const nameInput = document.createElement('input');\n            nameInput.type = 'hidden';\n            nameInput.name = `tracks[${index}][name]`;\n            nameInput.value = track.name;\n            nameInput.className = 'track-data-field';\n            document.getElementById('albumForm').appendChild(nameInput);\n        }\n        \n        if (track.imageFile instanceof File) {\n            // Pour les fichiers, on utilise un DataTransfer pour les conserver\n            // Alternative: on stocke dans un objet global et on les envoie via FormData\n        }\n        \n        if (track.audioFile instanceof File) {\n            // Pour les fichiers, on utilise un DataTransfer pour les conserver\n        }\n    });\n}\n\ndocument.getElementById('addTrackBtn').addEventListener('click', function() {\n\n    // multiple upload\n    const audioFiles = document.getElementById('audio_files').files;\n    \n    if (!audioFiles) {\n        alert('Veuillez sélectionner un fichier audio');\n        return;\n    }\n    Array.from(audioFiles).forEach(file => {\n        tracks.push({\n            name: file.name,\n            audioFile: file,\n            imageFile: null,\n            imagePreview: null,\n            audioName: file.name\n        });\n    });\n    updateTracksList();\n    updateHiddenFields();\n    clearTrackForm();\n \n});\n\nfunction clearTrackForm() {\n    //document.getElementById('track_name').value = '';\n    //document.getElementById('track_image').value = '';\n    document.getElementById('audio_files').value = '';\n}\n\nfunction showQRCode(base64Data, albumId){\n    document.getElementById(\"qrcode-modal\").style.display = 'block';\n    let shareBtn = document.querySelector(\".qrcode-share-btn\");\n    let qrCodeImage = document.getElementById('qr-code-image');\n    let downloadBtn = document.querySelector('.qrcode-img-download-link');\n    let dataUrl = `data:image/png;base64,${base64Data}`;\n    qrCodeImage.src = dataUrl;\n    downloadBtn.href = dataUrl;\n\n    shareBtn.addEventListener('click', async ()=>{\n        if(navigator.share){\n            await navigator.share({\n                title: \"Album\",\n                text: \"Download the latest album\",\n                url: `https://konektem.net/konektem/albums/${albumId}/download`\n            });\n        } else {\n            alert('Your browser doesnt support sharing. Please download the code and share on your favorite platforms');\n        }\n    });\n}\n\n// Soumission du formulaire - Utilisation de FormData pour envoyer les fichiers\ndocument.getElementById('albumForm').addEventListener('submit', function(e) {\n    e.preventDefault();\n    \n    // Validation de base\n    const albumName = document.getElementById('album_name').value.trim();\n    const genre = document.getElementById('genre').value.trim();\n    const releaseYear = document.getElementById('release_year').value;\n    const albumImage = document.getElementById('album_image').files[0];\n    const ownerName = document.getElementById('owner-name').value.trim();\n    const copyright = document.getElementById('copyright').checked;\n    const consent = document.getElementById('consent').checked;\n    \n    if (!albumName) {\n        alert('Veuillez entrer le nom de l\\'album');\n        return;\n    }\n    if (!genre) {\n        alert('Veuillez entrer le genre');\n        return;\n    }\n    if (!releaseYear) {\n        alert('Veuillez entrer l\\'année de sortie');\n        return;\n    }\n    if (!albumImage) {\n        alert('Veuillez sélectionner une cover pour l\\'album');\n        return;\n    }\n    if (tracks.length === 0) {\n        alert('Veuillez ajouter au moins une piste à l\\'album');\n        return;\n    }\n    if (!ownerName) {\n        alert('Veuillez entrer votre nom pour la signature');\n        return;\n    }\n    if (!copyright || !consent) {\n        alert('Veuillez accepter les conditions');\n        return;\n    }\n    \n    const formData = new FormData();\n    \n    // Informations album\n    formData.append('album_name', albumName);\n    formData.append('genre', genre);\n    formData.append('release_year', releaseYear);\n    formData.append('album_description', document.getElementById('album_description').value);\n    formData.append('album_image', albumImage);\n    formData.append('owner_name', ownerName);\n    formData.append('copyright', copyright ? '1' : '0');\n    formData.append('consent', consent ? '1' : '0');\n    \n    // Artiste\n    const artistSelect = document.getElementById('artist_name');\n    if (artistSelect.value === 'new') {\n        const newArtistName = document.getElementById('new_artist_name').value.trim();\n        if (!newArtistName) {\n            alert('Veuillez entrer le nom du nouvel artiste');\n            return;\n        }\n        formData.append('artist_type', 'new');\n        formData.append('new_artist_name', newArtistName);\n    } else if (artistSelect.value) {\n        formData.append('artist_type', 'existing');\n        formData.append('artist_id', artistSelect.value);\n    } else {\n        alert('Veuillez sélectionner un artiste');\n        return;\n    }\n    \n    // Tracks\n    let valid = true;\n    tracks.forEach((track, index) => {\n        if (!track.audioFile) {\n            alert(`Piste ${index + 1}: Fichier audio manquant`);\n            valid = false;\n            return;\n        }\n        formData.append(`tracks[${index}][name]`, track.name);\n        formData.append(`tracks[${index}][audio]`, track.audioFile);\n        // remove the track image\n        if (track.imageFile) {\n            formData.append(`tracks[${index}][image]`, track.imageFile);\n        }\n    });\n    \n    if (!valid) return;\n    \n    // Désactiver le bouton pour éviter double soumission\n    const submitBtn = this.querySelector('button[type=\"submit\"]');\n    submitBtn.disabled = true;\n    submitBtn.textContent = '';\n    submitBtn.innerHTML = '<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i>';\n    \n    // Envoyer via fetch ou soumission standard\n    // Ici on utilise fetch pour mieux gérer la réponse\n    \n    fetch('/konektem/user/me/albums/create', {\n        method: 'POST',\n        body: formData\n    })\n    .then(response => response.json())\n    .then(data => {\n        if (data.success) {\n            if(data.qrcode_base64){\n                showQRCode(data.qrcode_base64, data.album_id);\n            }\n            (0,_alert_modal_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])((data.message || 'Successfully created album'), 'success');\n            //alert('Success: ' + (data.message || 'Successfully created album'));\n            //window.location.href = '/konektem/user/me/albums';\n        } else {\n            (0,_alert_modal_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('Erreur: ' + (data.error || 'Une erreur est survenue'), 'danger');\n            //alert('Erreur: ' + (data.error || 'Une erreur est survenue'));\n            \n        }\n        submitBtn.disabled = false;\n        submitBtn.innerHTML = '';\n        submitBtn.textContent = 'Créer l\\'album';\n    })\n    .catch(error => {\n        console.log(error);\n        alert('Erreur lors de l\\'envoi: ' + error.message);\n        submitBtn.disabled = false;\n        submitBtn.textContent = 'Créer l\\'album';\n    });\n});\n\n//# sourceURL=webpack://konektem/./public/tmp-build/js/album-create-page.js?\n}");

/***/ },

/***/ "./public/tmp-build/js/alert-modal.js"
/*!********************************************!*\
  !*** ./public/tmp-build/js/alert-modal.js ***!
  \********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n// Fonction pour afficher les alertes\nfunction showAlert(message, type) {\n    // Supprimer les anciennes alertes\n    const oldAlert = document.querySelector('.alert-dynamic');\n    if (oldAlert) oldAlert.remove();\n    \n    const alertDiv = document.createElement('div');\n    alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-dynamic`;\n    alertDiv.innerHTML = `\n        ${message}\n        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>\n    `;\n    \n    // Insérer l'alerte en haut du formulaire\n    const form = document.querySelector('.form');\n    if (form) {\n        form.insertBefore(alertDiv, form.firstChild);\n    }\n    \n    // Auto fermeture après 5 secondes\n    setTimeout(() => {\n        if (alertDiv) alertDiv.remove();\n    }, 5000);\n}\n\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (showAlert);\n\n//# sourceURL=webpack://konektem/./public/tmp-build/js/alert-modal.js?\n}");

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./public/tmp-build/js/album-create-page.js");
/******/ 	
/******/ })()
;
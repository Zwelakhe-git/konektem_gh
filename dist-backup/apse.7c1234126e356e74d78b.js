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

/***/ "./album-edit-page.js"
/*!****************************!*\
  !*** ./album-edit-page.js ***!
  \****************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _alert_modal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./alert-modal.js */ \"./alert-modal.js\");\n\n\ndocument.addEventListener('DOMContentLoaded', function() {\n    // Gestion du select artiste\n    const artistSelect = document.getElementById('artist_id');\n    const newArtistField = document.getElementById('new_artist_field');\n    const newArtistInput = document.getElementById('new_artist_name');\n    \n    if (artistSelect) {\n        artistSelect.addEventListener('change', function() {\n            if (this.value === 'new') {\n                newArtistField.style.display = 'block';\n                newArtistInput.required = true;\n            } else {\n                newArtistField.style.display = 'none';\n                newArtistInput.required = false;\n            }\n        });\n    }\n    \n    // Preview nouvelle cover\n    const coverInput = document.getElementById('album_image');\n    if (coverInput) {\n        coverInput.addEventListener('change', function() {\n            const file = this.files[0];\n            if (file) {\n                const reader = new FileReader();\n                reader.onload = function(e) {\n                    let preview = document.getElementById('coverPreview');\n                    if (!preview) {\n                        preview = document.createElement('img');\n                        preview.id = 'coverPreview';\n                        preview.style.width = '200px';\n                        preview.style.marginTop = '10px';\n                        preview.className = 'img-thumbnail';\n                        coverInput.parentNode.appendChild(preview);\n                    }\n                    preview.src = e.target.result;\n                    preview.style.display = 'block';\n                };\n                reader.readAsDataURL(file);\n            }\n        });\n    }\n    \n    // Gestion des nouvelles pistes\n    let newTracks = [];\n    \n    function showQRCode(base64Data, albumId){\n        document.getElementById(\"qrcode-modal\").style.display = 'block';\n        let shareBtn = document.querySelector(\".qrcode-share-btn\");\n        let qrCodeImage = document.getElementById('qr-code-image');\n        let downloadBtn = document.querySelector('.qrcode-img-download-link');\n        let dataUrl = `data:image/png;base64,${base64Data}`;\n        qrCodeImage.src = dataUrl;\n        downloadBtn.href = dataUrl;\n\n        shareBtn.addEventListener('click', async ()=>{\n            if(navigator.share){\n                await navigator.share({\n                    title: \"Album\",\n                    text: \"Download the latest album\",\n                    url: `https://konektem.net/konektem/albums/${albumId}/download`\n                });\n            } else {\n                alert('Your browser doesnt support sharing. Please download the code and share on your favorite platforms');\n            }\n        });\n    }\n    function updateNewTracksPreview() {\n        const container = document.getElementById('newTracksPreview');\n        const countSpan = document.getElementById('newTrackCount');\n        \n        if (!container) return;\n        \n        if (newTracks.length === 0) {\n            container.innerHTML = '<div class=\"text-muted text-center p-2\">Pa gen nouvo piste ajoute</div>';\n            if (countSpan) countSpan.textContent = '0';\n            return;\n        }\n        \n        if (countSpan) countSpan.textContent = newTracks.length;\n        \n        let html = '<div class=\"list-group mt-2\">';\n        newTracks.forEach((track, index) => {\n            html += `\n                <div class=\"list-group-item\" data-new-index=\"${index}\">\n                    <div class=\"d-flex align-items-center\">\n                        <div class=\"track-preview me-2\">\n                            ${track.imagePreview ? `<img src=\"${track.imagePreview}\" style=\"width: 40px; height: 40px; object-fit: cover; border-radius: 4px;\">` : '<div style=\"width: 40px; height: 40px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 20px;\">🎵</div>'}\n                        </div>\n                        <div class=\"flex-grow-1\">\n                            <strong>${escapeHtml(track.name)}</strong><br>\n                            <small class=\"text-muted\">${track.audioName || 'Fichye audio'}</small>\n                        </div>\n                        <button type=\"button\" class=\"btn btn-sm btn-danger remove-new-track\" data-index=\"${index}\">✖</button>\n                    </div>\n                </div>\n            `;\n        });\n        html += '</div>';\n        container.innerHTML = html;\n        \n        // Ajouter les écouteurs pour les boutons supprimer\n        document.querySelectorAll('.remove-new-track').forEach(btn => {\n            btn.addEventListener('click', function() {\n                const idx = parseInt(this.getAttribute('data-index'));\n                newTracks.splice(idx, 1);\n                updateNewTracksPreview();\n                updateHiddenNewTracks();\n            });\n        });\n    }\n    \n    function escapeHtml(str) {\n        if (!str) return '';\n        return str.replace(/[&<>]/g, function(m) {\n            if (m === '&') return '&amp;';\n            if (m === '<') return '&lt;';\n            if (m === '>') return '&gt;';\n            return m;\n        });\n    }\n    \n    function updateHiddenNewTracks() {\n        // Supprimer les anciens champs cachés\n        document.querySelectorAll('.new-track-data').forEach(field => field.remove());\n        \n        // Ajouter les nouveaux champs cachés\n        newTracks.forEach((track, index) => {\n            if (track.name) {\n                const nameInput = document.createElement('input');\n                nameInput.type = 'hidden';\n                nameInput.name = `new_tracks[${index}][name]`;\n                nameInput.value = track.name;\n                nameInput.className = 'new-track-data';\n                document.querySelector('form').appendChild(nameInput);\n            }\n            \n            if (track.audioFile instanceof File) {\n                // Les fichiers seront gérés via FormData à la soumission\n            }\n        });\n    }\n    \n    // Ajouter un conteneur pour les nouvelles pistes\n    const addTrackBtn = document.getElementById('addTrackBtn');\n    if (addTrackBtn) {\n        // Créer le conteneur pour les nouvelles pistes si pas présent\n        let newTracksContainer = document.getElementById('newTracksPreview');\n        if (!newTracksContainer) {\n            const tracksCard = document.querySelector('.card.mt-3:last-child');\n            if (tracksCard) {\n                const previewDiv = document.createElement('div');\n                previewDiv.id = 'newTracksPreview';\n                previewDiv.innerHTML = '<div class=\"text-muted text-center p-2\">Pa gen nouvo piste ajoute</div>';\n                tracksCard.appendChild(previewDiv);\n            }\n        }\n        \n        addTrackBtn.addEventListener('click', function() {\n            const audioFiles = document.getElementById('new_audio_files').files;\n            \n            \n            if (!audioFiles) {\n                alert('Veuillez sélectionner un fichier audio');\n                return;\n            }\n            Array.from(audioFiles).forEach(file => {\n                newTracks.push({\n                    name: file.name,\n                    audioFile: file,\n                    imageFile: null,\n                    imagePreview: null,\n                    audioName: file.name\n                });\n            });\n            updateNewTracksPreview();\n            updateHiddenNewTracks();\n            clearNewTrackForm();\n            \n        });\n    }\n    \n    function clearNewTrackForm() {\n        //document.getElementById('new_track_name').value = '';\n        //document.getElementById('new_track_image').value = '';\n        document.getElementById('new_audio_files').value = '';\n    }\n    \n    // Gestion suppression des pistes existantes (avec confirmation)\n    document.querySelectorAll('.remove-track').forEach(btn => {\n        btn.addEventListener('click', function() {\n            const trackId = this.getAttribute('data-track-id');\n            if (confirm('Èske ou sèten ou vle efase piste sa a? Aksyon sa a pa ka anile.')) {\n                // Ajouter un champ caché pour marquer la suppression\n                const input = document.createElement('input');\n                input.type = 'hidden';\n                input.name = 'delete_tracks[]';\n                input.value = trackId;\n                document.querySelector('form').appendChild(input);\n                \n                // Cacher visuellement l'élément\n                const item = this.closest('.list-group-item');\n                if (item) {\n                    item.style.display = 'none';\n                }\n            }\n        });\n    });\n    // Добавь этот код в конец тега <script> в файле edit_album.php\n\n    // Gestion de la soumission du formulaire\n    const editForm = document.getElementById('editAlbumForm');\n    if (editForm) {\n        editForm.addEventListener('submit', function(e) {\n            e.preventDefault();\n            \n            // Récupérer le bouton submit pour le désactiver\n            const submitBtn = this.querySelector('button[type=\"submit\"]');\n            const originalBtnText = submitBtn.innerHTML;\n            submitBtn.disabled = true;\n            submitBtn.innerHTML = '<i class=\"fas fa-spinner fa-spin me-2\"></i>Modification en cours...';\n            \n            // Créer FormData\n            const formData = new FormData(this);\n            \n            // Ajouter les nouvelles pistes (fichiers)\n            // Les nouvelles pistes sont déjà dans newTracks array\n            if (typeof newTracks !== 'undefined' && newTracks.length > 0) {\n                newTracks.forEach((track, index) => {\n                    formData.append(`new_tracks[${index}][name]`, track.name);\n                    if (track.audioFile) {\n                        formData.append(`new_tracks_audio_${index}`, track.audioFile);\n                    }\n                    // if (track.imageFile) {\n                    //     formData.append(`new_tracks_image_${index}`, track.imageFile);\n                    // }\n                });\n            }\n            \n            // Envoyer la requête\n            let url = `/konektem/user/me/albums/${albumId}/edit`;\n            fetch(url, {\n                method: 'POST',\n                body: formData,\n                headers: {\n                    'X-Requested-With': 'XMLHttpRequest'\n                }\n            })\n            .then(response => response.json())\n            .then(data => {\n                if (data.success) {\n                    // Afficher message de succès\n                    (0,_alert_modal_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('Album modifié avec succès!', 'success');\n                    \n                    // Rediriger après 1.5 secondes\n                    // setTimeout(() => {\n                    //     window.location.href = '?action=albums&success=1';\n                    // }, 1500);\n                } else {\n                    (0,_alert_modal_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('Erreur: ' + (data.error || 'Une erreur est survenue'), 'danger');\n                }\n                submitBtn.disabled = false;\n                submitBtn.innerHTML = originalBtnText;\n            })\n            .catch(error => {\n                console.error('Error:', error);\n                (0,_alert_modal_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])('Erreur serveur, veuillez réessayer', 'danger');\n                submitBtn.disabled = false;\n                submitBtn.innerHTML = originalBtnText;\n            });\n        });\n    }\n\n});\n\n//# sourceURL=webpack:///./album-edit-page.js?\n}");

/***/ },

/***/ "./alert-modal.js"
/*!************************!*\
  !*** ./alert-modal.js ***!
  \************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n// Fonction pour afficher les alertes\nfunction showAlert(message, type) {\n    // Supprimer les anciennes alertes\n    const oldAlert = document.querySelector('.alert-dynamic');\n    if (oldAlert) oldAlert.remove();\n    \n    const alertDiv = document.createElement('div');\n    alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-dynamic`;\n    alertDiv.innerHTML = `\n        ${message}\n        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>\n    `;\n    \n    // Insérer l'alerte en haut du formulaire\n    const form = document.querySelector('.form');\n    if (form) {\n        form.insertBefore(alertDiv, form.firstChild);\n    }\n    \n    // Auto fermeture après 5 secondes\n    setTimeout(() => {\n        if (alertDiv) alertDiv.remove();\n    }, 5000);\n}\n\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (showAlert);\n\n//# sourceURL=webpack:///./alert-modal.js?\n}");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./album-edit-page.js");
/******/ 	
/******/ })()
;
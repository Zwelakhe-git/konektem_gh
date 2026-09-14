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

/***/ "./static/js/InfFreeUtils.js"
/*!***********************************!*\
  !*** ./static/js/InfFreeUtils.js ***!
  \***********************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   InfFreeFetch: () => (/* binding */ InfFreeFetch)\n/* harmony export */ });\nasync function InfFreeFetch(url) {\n  let params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {\n    method: 'GET',\n    headers: {},\n    body: undefined\n  };\n  try {\n    let body = undefined;\n    let fetchParams = {\n      method: params.method,\n      headers: {\n        'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 YaBrowser/25.8.0.0 Safari/537.36',\n        'Connection': 'keep-alive'\n      }\n    };\n    const bodyType = params.body.constructor.name;\n    if (params && params.body && (params.method === 'POST' || params.method === 'PUT' || params.method === 'PATCH')) {\n      if (bodyType === 'Object') {\n        body = new FormData();\n        for (const [k, v] of Object.entries(params.body)) {\n          body.append(k, v);\n        }\n      } else if (bodyType === 'String') {\n        fetchParams.headers['Content-Type'] = 'application/json';\n        body = params.body;\n      } else if (bodyType === 'FormData') {\n        delete params.headers['Content-Type'];\n        body = params.body;\n      } else if (bodyType === 'URLSearchParams') {\n        params.headers['Content-Type'] = 'application/x-www-form-urlencoded';\n        body = params.body.toString();\n      }\n      fetchParams.body = body;\n    }\n    if (params.headers && Object.keys(params.headers).length > 0) {\n      fetchParams.headers = {\n        ...fetchParams.headers,\n        ...params.headers\n      };\n    }\n    fetchParams['credentials'] = 'same-origin';\n    const response = await fetch(url, fetchParams);\n    const status = response.status;\n    if (status >= 200 && status < 300) {}\n    if (status >= 300 && status < 400) {\n      console.log('redirecting...');\n    }\n    if (status >= 400 && status < 500) {\n      console.error('Client error');\n    }\n    if (status >= 500) {\n      console.error('Server error');\n    }\n    return response;\n  } catch (e) {\n    console.error(e);\n  }\n}\n\n//# sourceURL=webpack://konektem/./static/js/InfFreeUtils.js?\n}");

/***/ },

/***/ "./static/js/album-preview-page.js"
/*!*****************************************!*\
  !*** ./static/js/album-preview-page.js ***!
  \*****************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _static_js_InfFreeUtils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/static/js/InfFreeUtils.js */ \"./static/js/InfFreeUtils.js\");\n\ndocument.addEventListener('DOMContentLoaded', function () {\n  // Variables\n  const albumId = window.album['id'];\n  const albumName = window.album['id'];\n\n  // 1. Voir les pistes\n  const viewTracksBtn = document.getElementById('viewTracksBtn');\n  const tracksSection = document.getElementById('tracksSection');\n  if (viewTracksBtn) {\n    viewTracksBtn.addEventListener('click', function () {\n      if (tracksSection.style.display === 'none') {\n        tracksSection.style.display = 'block';\n        viewTracksBtn.innerHTML = '<i class=\"fas fa-eye-slash me-2\"></i>Masquer les pistes';\n      } else {\n        tracksSection.style.display = 'none';\n        viewTracksBtn.innerHTML = '<i class=\"fas fa-headphones me-2\"></i>Voir les pistes';\n      }\n    });\n  }\n\n  // 2. Like album\n  const likeAlbumBtn = document.getElementById('likeAlbumBtn');\n  if (likeAlbumBtn) {\n    likeAlbumBtn.addEventListener('click', function () {\n      allow();\n      const btn = this;\n      const likeCountSpan = btn.querySelector('.a-like-count');\n      const currentLikes = parseInt(likeCountSpan.textContent.replace(/[^0-9]/g, ''));\n\n      // Appel AJAX pour le like\n      fetch(`/konektem/api.service/like`, {\n        method: 'POST',\n        headers: {\n          'Authorization': `Bearer ${sessionStorage.getItem('token')}`,\n          'X-Requested-With': 'XMLHttpRequest'\n        },\n        body: {\n          item: 'album',\n          id: albumId\n        },\n        credentials: 'same-origin'\n      }).then(response => {\n        if (response.status === 401) {\n          sessionStorage.removeItem('token');\n          sessionStorage.removeItem('user');\n          location.href = '/konektem/auth/login';\n        }\n        return response.json();\n      }).then(result => {\n        if (result.success) {\n          likeCountSpan.textContent = result.data.likes.toLocaleString();\n\n          // Animation du bouton\n          btn.classList.add('liked');\n          setTimeout(() => btn.classList.remove('liked'), 300);\n        } else {\n          alert('Erreur: ' + (data.error || 'Impossible de liker cet album'));\n        }\n      }).catch(error => {\n        console.error('Error:', error);\n        alert('Une erreur est survenue');\n      });\n    });\n  }\n\n  // 3. Partager\n  const shareAlbumBtn = document.getElementById('shareAlbumBtn');\n  if (shareAlbumBtn) {\n    shareAlbumBtn.addEventListener('click', function () {\n      const shareCountSpan = document.querySelector('.a-share-count');\n      // Enregistrer le partage\n      (0,_static_js_InfFreeUtils_js__WEBPACK_IMPORTED_MODULE_0__.InfFreeFetch)(`/konektem/api.service/share`, {\n        method: 'POST',\n        headers: {\n          'Authorization': `Bearer ${sessionStorage.getItem('token')}`,\n          'X-Requested-With': 'XMLHttpRequest'\n        },\n        body: {\n          item: 'album',\n          id: albumId\n        }\n      }).then(response => {\n        if (response.status === 401) {\n          sessionStorage.removeItem('token');\n          sessionStorage.removeItem('user');\n          location.href = '/konektem/auth/login';\n        }\n        return response.json();\n      }).then(result => {\n        if (result.success) {\n          const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));\n          shareModal.show();\n          shareCountSpan.textContent = result.data.shares.toLocaleString();\n        }\n      }).catch(error => console.error('Error recording share:', error));\n    });\n  }\n\n  // 4. Télécharger tout l'album (à implémenter plus tard)\n  const downloadAlbumBtn = document.getElementById('downloadAlbumBtn');\n  if (downloadAlbumBtn) {\n    downloadAlbumBtn.addEventListener('click', async function () {\n      // TODO: Implémenter le téléchargement de tout l'album\n      // alert('Fonctionnalité de téléchargement complet à venir bientôt !');\n      allow();\n      const downloadCountSpan = document.querySelector('.a-download-count');\n      try {\n        // Log du téléchargement\n        (0,_static_js_InfFreeUtils_js__WEBPACK_IMPORTED_MODULE_0__.InfFreeFetch)(`/konektem/api.service/download`, {\n          method: 'POST',\n          headers: {\n            'Authorization': `Bearer ${sessionStorage.getItem('token')}`,\n            'X-Requested-With': 'XMLHttpRequest'\n          },\n          'body': {\n            item: 'album',\n            id: albumId\n          }\n        }).then(response => {\n          if (response.status === 401) {\n            sessionStorage.removeItem('token');\n            sessionStorage.removeItem('user');\n            location.href = '/konektem/auth/login';\n          }\n          return response.json();\n        }).then(result => {\n          if (result.success) {\n            //console.log(\"downloading file: \", result.data.file_name, result.data.file_url)\n            let a = document.createElement('a');\n            a.download = result.data.file_name;\n            a.href = result.data.file_url;\n            a.click();\n            downloadCountSpan.textContent = result.data.downloads.toLocaleString();\n          } else {\n            console.error(\"failed to fetch download url:\", result.message);\n          }\n        }).catch(error => {\n          console.error(error.message);\n        });\n      } catch (error) {\n        console.error(\"Error during album download: \", error);\n      }\n    });\n  }\n\n  // 5. Play track\n  document.querySelectorAll('.play-track').forEach(btn => {\n    btn.addEventListener('click', function () {\n      //const trackUrl = this.getAttribute('data-track-url');\n      const trackId = this.getAttribute('data-track-id');\n      let audioPlayer = document.getElementById('audioPlayer');\n      if (!audioPlayer) {\n        audioPlayer = document.createElement('audio');\n        audioPlayer.id = 'audioPlayer';\n        document.body.appendChild(audioPlayer);\n      }\n      // Enregistrer la lecture\n      (0,_static_js_InfFreeUtils_js__WEBPACK_IMPORTED_MODULE_0__.InfFreeFetch)(`/konektem/api.service/play-track`, {\n        method: 'POST',\n        headers: {\n          'X-Requested-With': 'XMLHttpRequest'\n        },\n        body: {\n          id: trackId\n        }\n      }).then(response => {\n        if (response.status === 401) {\n          sessionStorage.removeItem('token');\n          sessionStorage.removeItem('user');\n          location.href = '/konektem/auth/login';\n        }\n        return response.json();\n      }).then(result => {\n        if (result.success) {\n          const trackUrl = result.data.file_url;\n          if (trackUrl) {\n            // Créer ou réutiliser un lecteur audio\n            if (window.currentTrackId && window.currentTrackId === trackId) {\n              if (!audioPlayer.paused && !audioPlayer.ended && window._audioPlaying) {\n                audioPlayer.pause();\n                window._audioPlaying = false;\n                this.innerHTML = '<i class=\"fas fa-play\"></i>';\n              } else {\n                audioPlayer.play();\n                window._audioPlaying = true;\n                this.innerHTML = '<i class=\"fas fa-pause\"></i>';\n              }\n            } else {\n              audioPlayer.src = trackUrl;\n              audioPlayer.play();\n              window._audioPlaying = true;\n              this.innerHTML = '<i class=\"fas fa-pause\"></i>';\n              window.currentTrackId = trackId;\n              document.querySelectorAll('.play-track').forEach(otherBtn => {\n                if (otherBtn !== btn) {\n                  otherBtn.innerHTML = '<i class=\"fas fa-play\"></i>';\n                }\n              });\n            }\n\n            // Arrêter les autres lecteurs\n\n            // Quand la piste se termine\n            audioPlayer.onended = () => {\n              window._audioPlaying = false;\n              this.innerHTML = '<i class=\"fas fa-play\"></i>';\n            };\n          }\n        }\n      }).catch(error => console.error('Error recording play:', error));\n    });\n  });\n\n  // 6. Download track\n  document.querySelectorAll('.download-track').forEach(btn => {\n    btn.addEventListener('click', async function () {\n      // edit so that the token is also required\n      allow();\n\n      //const trackUrl = this.getAttribute('data-track-url');\n      const trackId = this.getAttribute('data-track-id');\n      let headers = {\n        'Authorization': `Bearer ${sessionStorage.getItem('token')}`,\n        //'Content-Type': 'application/json',\n        'X-Requested-With': 'XMLHttpRequest'\n      };\n      (0,_static_js_InfFreeUtils_js__WEBPACK_IMPORTED_MODULE_0__.InfFreeFetch)(`/konektem/api.service/download`, {\n        method: 'POST',\n        headers: headers,\n        body: {\n          'item': 'track',\n          'id': trackId\n        }\n      }).then(response => {\n        if (response.status === 401) {\n          sessionStorage.removeItem('token');\n          sessionStorage.removeItem('user');\n          location.href = '/konektem/auth/login';\n        }\n        return response.json();\n      }).then(result => {\n        if (result.success) {\n          //const trackUrl = result.data.file_url;\n          // Créer un lien de téléchargement\n          const link = document.createElement('a');\n          link.href = result.data.file_url;\n          link.download = result.data.file_name;\n          document.body.appendChild(link);\n          link.click();\n          document.body.removeChild(link);\n        } else {\n          console.error(\"failed to download track\", result.message);\n          // showAlert(result.message, 'error', document.body);\n        }\n      }).catch(error => {\n        console.error('Error recording download:', error);\n        // showAlert('Error recording download:', 'error', document.body);\n      });\n    });\n  });\n\n  // 7. Like track\n  document.querySelectorAll('.like-track').forEach(btn => {\n    btn.addEventListener('click', function () {\n      allow();\n      const trackId = this.getAttribute('data-track-id');\n      const likeCountSpan = this.querySelector('.track-like-count');\n      const currentLikes = parseInt(likeCountSpan.textContent.replace(/[^0-9]/g, ''));\n      (0,_static_js_InfFreeUtils_js__WEBPACK_IMPORTED_MODULE_0__.InfFreeFetch)(`/konektem/api.service/like`, {\n        method: 'POST',\n        headers: {\n          'Authorization': 'Bearer ' + sessionStorage.getItem('token'),\n          'X-Requested-With': 'XMLHttpRequest'\n        },\n        body: {\n          'item': 'track',\n          'id': trackId\n        },\n        credentials: 'same-origin'\n      }).then(response => {\n        if (response.status === 401) {\n          sessionStorage.removeItem('token');\n          sessionStorage.removeItem('user');\n          location.href = '/konektem/auth/login';\n        }\n        return response.json();\n      }).then(result => {\n        if (result.success) {\n          likeCountSpan.textContent = result.data.likes.toLocaleString();\n          this.classList.add('liked');\n          setTimeout(() => this.classList.remove('liked'), 300);\n        }\n      }).catch(error => console.error('Error:', error));\n    });\n  });\n\n  // 8. Partager sur les réseaux sociaux\n  document.querySelectorAll('.share-btn').forEach(btn => {\n    btn.addEventListener('click', function () {\n      const platform = this.getAttribute('data-platform');\n      const url = encodeURIComponent(window.location.href);\n      const text = encodeURIComponent(`Découvrez l'album \"${albumName}\" !`);\n      let shareUrl = '';\n      switch (platform) {\n        case 'facebook':\n          shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;\n          break;\n        case 'twitter':\n          shareUrl = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;\n          break;\n        case 'whatsapp':\n          shareUrl = `https://wa.me/?text=${text}%20${url}`;\n          break;\n      }\n      if (shareUrl) {\n        window.open(shareUrl, '_blank', 'width=600,height=400');\n      }\n    });\n  });\n});\nfunction allow() {\n  if (!sessionStorage.getItem('user') || !sessionStorage.getItem('token')) {\n    location.href = '/konektem/auth/login';\n  }\n}\n\n//# sourceURL=webpack://konektem/./static/js/album-preview-page.js?\n}");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./static/js/album-preview-page.js");
/******/ 	
/******/ })()
;
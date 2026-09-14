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

/***/ "./admin-page-engine.js"
/*!******************************!*\
  !*** ./admin-page-engine.js ***!
  \******************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\nObject(function webpackMissingModule() { var e = new Error(\"Cannot find module './js-6966390'\"); e.code = 'MODULE_NOT_FOUND'; throw e; }());\n// includes\n// posting, \n// logoutUser, \n// updateProfile, \n// profileModal, \n// saveProfileAvatar, \n// centerPoster, \n// closeProfileModal\n\n//\ntry{\n    Object(function webpackMissingModule() { var e = new Error(\"Cannot find module './js-6966390'\"); e.code = 'MODULE_NOT_FOUND'; throw e; }())();\n} catch(err){\n    alert(err);\n}\n\n// align the poster correctly\nlet adjacentElement = document.body.querySelector(\".container.mt-4\");\nconst poster = document.querySelector(\".poster\");\nsetTimeout(()=>{\n    let rect = adjacentElement.getBoundingClientRect();\n    document.querySelector(\".poster\").style.top = `${rect.bottom + 10}px`;\n    centerPoster();\n}, 200);\n\nasync function centerPoster(){\n    /*\n    * center a 'fixed' positioned poster\n    * only for mobile displays\n    */\n    console.log('centering fixed poster');\n    const poster = document.querySelector(\".poster\");\n    if(window.innerWidth > 768 || !poster) return;\n    let stls = window.getComputedStyle(poster);\n    //rollback if its not fixed. css is the best alternative\n    if(stls.position === 'relative' || stls.position === 'static') return;\n    \n    let rc = document.querySelector(\".container.mt-4\"); // relativeContainer\n    let firstChild = rc.children[0];\n    stls = rc ? window.getComputedStyle(rc) : null;\n    let cstls = window.getComputedStyle(firstChild);\n    let rw = rc ? rc.clientWidth : window.innerWidth;\n    // const rec = poster.getBoundingClientRect();\n    let fs = window.innerWidth - rw;// freeSpace\n    let ppl = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(stls.paddingLeft)) : 0;\n    let ppr = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(stls.paddingRight)) : 0;\n    let cpl = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(cstls.paddingLeft)) : 0;\n    let cpr = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(cstls.paddingRight)) : 0;\n    poster.style.setProperty(\"width\", `${rw - ppl - ppr - cpl - cpr}px`);\n    fs = fs + ppl + ppr + cpl;\n    poster.style.setProperty(\"margin-left\", `${fs / 2}px`);                \n}\n\n/** profile control */\nlet profileModal = document.getElementById('profileModal');\n\nfunction openProfileModal(formId = \"profileForm\") {\n    profileModal.style.display = 'block';\n    let form = profileModal.querySelector('#' + formId);\n    if(form){\n        form.style.display = 'block';\n    }\n}\n\nfunction closeProfileModal() {\n    profileModal.style.display = 'none';\n    // Reset form\n    const profileForm = document.getElementById('profileForm');\n    const avatarForm = document.getElementById('avatar-edit-form');\n    profileForm.reset();\n    avatarForm.reset();\n    profileForm.style.display = 'none';\n    avatarForm.style.display = 'none';\n}\n\nfunction updateProfile(event) {\n    event.preventDefault();\n\n    const form = event.target;\n    const formData = new FormData(form);\n    const data = Object.fromEntries(formData.entries());\n\n    // Validate passwords if new password is provided\n    if (data.new_password) {\n        if (data.new_password !== data.confirm_password) {\n            alert('New passwords do not match!');\n            return;\n        }\n        if (data.new_password.length < 6) {\n            alert('New password must be at least 6 characters long!');\n            return;\n        }\n    }\n\n    // Update the fetch URL to match your dbReader.php path\n    fetch('/php/dbReader.php?q=updateProfile', {\n        method: 'POST',\n        headers: {\n            'Content-Type': 'application/json',\n        },\n        body: JSON.stringify(data)\n    })\n    .then(response => {\n        if (!response.ok) {\n            throw new Error('Network response was not ok');\n        }\n        return response.json();\n    })\n    .then(result => {\n        if (result.response === 'success') {\n            alert('Profile updated successfully!');\n            closeProfileModal();\n            location.reload(); // Reload to show updated data\n        } else {\n            alert('Error: ' + (result.message || 'Failed to update profile'));\n        }\n    })\n    .catch(error => {\n        console.error('Error:', error);\n        alert('Failed to update profile. Please try again.');\n    });\n}\n\nfunction logoutUser() {\n    fetch('/path/to/dbReader.php?q=userlogout')\n        .then(response => response.json())\n        .then(result => {\n            if (result.response === 'success') {\n                window.location.href = '/login.php';\n            }\n        })\n        .catch(error => {\n            console.error('Error:', error);\n        });\n}\n\nasync function saveProfileAvatar(event){\n    event.preventDefault();\n    const form = event.target;\n    let formData = new FormData(form);\n    const linkedImages = [];\n    try{\n        let response = await fetch('/admin/upload.php', {\n            method: \"POST\",\n            body: formData\n        });\n\n        let data = await response.json();\n        if(data.success){\n            linkedImages.push(data);\n            response = await fetch('/php/dbReader.php?q=updateUserAvatar', {\n                method: \"POST\",\n                headers: {\n                    'Content-Type': 'application/json'\n                },\n                body: JSON.stringify({url: data.url, name: \"<?php echo $_SESSION['user']['name']; ?>\"})\n            });\n\n            data = await response.json();\n            if(data.success){\n                // show alert\n            } else {\n                alert(\"failed to update profile: \" + data.message);\n                linkedImages.forEach((img) => {\n                    formData = new FormData();\n                    formData.append(img.url);\n                    fetch(`/php/dbReader.php?q=deleteimage&id=${img.id}`, {\n                        method: \"POST\",\n                        body: formData\n                    })\n                    .then(response => response.json)\n                    .then(data => {\n                        console.log(data.message);\n                    })\n                });\n            }\n        }\n    } catch(error){\n        console.log(error.message);\n        linkedImages.forEach((img) => {\n            formData = new FormData();\n            formData.append(img.url);\n            fetch(`/php/dbReader.php?q=deleteimage&id=${img.id}`, {\n                method: \"POST\",\n                body: formData\n            })\n            .then(response => response.json)\n            .then(data => {\n                console.log(data.message);\n            })\n        });\n    }\n    \n    closeProfileModal();\n    location.reload();\n}\n\n// Close modal when clicking outside\nwindow.onclick = function(event) {\n    if (event.target === profileModal) {\n        closeProfileModal();\n    }\n}\n\n// Close modal with Escape key\ndocument.addEventListener('keydown', function(event) {\n    if (event.key === 'Escape' && profileModal.style.display === 'block') {\n        closeProfileModal();\n    }\n});\n\n//# sourceURL=webpack:///./admin-page-engine.js?\n}");

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
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
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./admin-page-engine.js"](0,__webpack_exports__,__webpack_require__);
/******/ 	
/******/ })()
;
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

/***/ "./static/css/auth-form.css"
/*!**********************************!*\
  !*** ./static/css/auth-form.css ***!
  \**********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://konektem/./static/css/auth-form.css?\n}");

/***/ },

/***/ "./static/js/auth_page_script.js"
/*!***************************************!*\
  !*** ./static/js/auth_page_script.js ***!
  \***************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _authentication_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./authentication.js */ \"./static/js/authentication.js\");\n/* harmony import */ var _profile_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./profile.js */ \"./static/js/profile.js\");\n\n\ndocument.addEventListener('DOMContentLoaded', () => {\n  (0,_authentication_js__WEBPACK_IMPORTED_MODULE_0__.renderForm)('login', false);\n\n  // Handle Google auth callback parameters\n  const urlParams = new URLSearchParams(window.location.search);\n  const googleSuccess = urlParams.get('google_success');\n  const googleError = urlParams.get('google_error');\n  const userId = urlParams.get('id');\n  const username = urlParams.get('name');\n  const avatarUrl = urlParams.get('avatar_url');\n  const role = urlParams.get('role');\n  const token = urlParams.get('token');\n  if (googleSuccess === '1' && username) {\n    // Update UI to show logged in state\n    let userPayload = {\n      id: userId,\n      name: username,\n      role: role,\n      avatar_url: avatarUrl\n    };\n    sessionStorage.setItem(\"user\", JSON.stringify(userPayload));\n    sessionStorage.setItem(\"token\", token);\n    (0,_profile_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])();\n    showAlert('Successfully logged in with Google!', 'success');\n\n    // Clean URL\n    window.history.replaceState({}, document.title, window.location.pathname);\n\n    // Redirect to home page after successful login\n    setTimeout(() => {\n      window.location.href = '/konektem/user/me';\n    }, 2000);\n  }\n  if (googleError === '1') {\n    const message = urlParams.get('message') || 'Google authentication failed';\n    showAlert(message, 'error');\n\n    // Clean URL\n    window.history.replaceState({}, document.title, window.location.pathname);\n  }\n});\nfunction showAlert(message, type) {\n  const alertDiv = document.createElement('div');\n  alertDiv.className = `alert alert-${type === 'success' ? 'ok' : 'err'}`;\n  alertDiv.innerHTML = `\n        <i class=\"fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}\"></i>\n        <span>${message}</span>\n    `;\n  document.body.appendChild(alertDiv);\n  setTimeout(() => {\n    alertDiv.remove();\n  }, 5000);\n}\n\n//# sourceURL=webpack://konektem/./static/js/auth_page_script.js?\n}");

/***/ },

/***/ "./static/js/authentication.js"
/*!*************************************!*\
  !*** ./static/js/authentication.js ***!
  \*************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__),\n/* harmony export */   renderForm: () => (/* binding */ renderForm),\n/* harmony export */   showAlert: () => (/* binding */ showAlert)\n/* harmony export */ });\n/* harmony import */ var _profile_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./profile.js */ \"./static/js/profile.js\");\n/* harmony import */ var _styles_auth_form_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @styles/auth-form.css */ \"./static/css/auth-form.css\");\n\n\nfunction signForm() {\n  return `<form class='regForm'>\n        <div class='form-close'>\n            <i class=\"fa-solid fa-xmark close-icon\"></i>\n        </div>\n        \n        <div class=\"form-header\">\n            <h2>Log in to your account</h2>\n            <p class=\"form-subtitle\">Welcome back! Please enter your details.</p>\n        </div>\n        \n        <div id=\"social-login-container\"></div>\n        \n        <div class=\"divider\">\n            <span>Or</span>\n        </div>\n        \n        <div class=\"email-login-section\">\n            <div class='field'>\n                <label for='login'>USERNAME</label>\n                <input id='login' type='text' name='login' placeholder='Enter your username'/>\n            </div>\n            \n            <div class='field'>\n                <label for='email'>EMAIL ADDRESS</label>\n                <input id='email' type='email' name='email' placeholder='Enter your email' required/>\n            </div>\n            \n            <div class='field'>\n                <label for='password'>PASSWORD</label>\n                <input id='password' type='password' name='password' placeholder='Enter your password' required/>\n            </div>\n            \n            <div class='field row remember-me'>\n                <input id='show-pswd' type='checkbox' name='show-pswd'/>\n                <label for='show-pswd'>SHOW PASSWORD</label>\n            </div>\n            \n            <div class='form-btns'>\n                <button id='send-btn' type='submit'>SIGN IN</button>\n            </div>\n        </div>\n        \n        <p id='form-btm-text'></p>\n       </form>\n        `;\n}\nfunction createSocialLoginButtons() {\n  return `\n        <div class=\"social-login-options\">\n            <button type=\"button\" id=\"google-login-btn\" class=\"social-btn google-btn\">\n                <i class=\"fa-brands fa-google\"></i>\n                <span>Continue with Google</span>\n            </button>\n        </div>\n    `;\n}\nfunction renderForm(formtype) {\n  let closeOnLog = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;\n  if (document.querySelector('.form-container')) {\n    return;\n  }\n  let formContainer = document.createElement('div');\n  formContainer.classList.add('form-container');\n  formContainer.innerHTML = signForm();\n  document.body.appendChild(formContainer);\n  let form = formContainer.querySelector('form');\n  renderFormUI();\n  form.style.display = 'block';\n  let timer1 = null;\n  let timer2 = null;\n  timer1 = setTimeout(() => {\n    form.classList.add('open');\n    let pswdField = form.querySelector('#password');\n    let loginField = form.querySelector('#login');\n    let emailField = form.querySelector('#email');\n    let closeIcon = form.querySelector('.close-icon');\n    let sendBtn = form.querySelector('#send-btn');\n    let socialLoginContainer = form.querySelector('#social-login-container');\n\n    // Add social login buttons to the form\n    socialLoginContainer.innerHTML = createSocialLoginButtons();\n    loginField.addEventListener('input', event => {\n      loginField.value = loginField.value.replace(/[^A-Za-z0-9]/g, '');\n    });\n    if (closeOnLog) {\n      closeIcon.addEventListener('click', () => {\n        form.classList.remove('open');\n        timer2 = setTimeout(() => {\n          formContainer.style.display = 'none';\n          document.body.removeChild(formContainer);\n        }, 500);\n      });\n    }\n\n    // Google login button event listener\n    let googleLoginBtn = form.querySelector('#google-login-btn');\n    googleLoginBtn.addEventListener('click', async () => {\n      googleLoginBtn.disabled = true;\n      googleLoginBtn.innerHTML = '<i class=\"fa-solid fa-spinner fa-spin\"></i><span>Loading...</span>';\n      try {\n        let response = await fetch('/konektem/auth/google');\n        let data = await response.json();\n        if (data.auth_url) {\n          window.location.href = data.auth_url;\n        } else if (data.error) {\n          throw new Error(data.error);\n        } else {\n          throw new Error('No authentication URL received from server');\n        }\n      } catch (error) {\n        console.error('Google login error:', error);\n        let errorMessage = 'Google authentication is not available at the moment.';\n        if (error.message.includes('not configured') || error.message.includes('configuration missing')) {\n          errorMessage = 'Google authentication is not configured. Please contact administrator.';\n        } else if (error.message.includes('config file missing')) {\n          errorMessage = 'Google authentication setup incomplete. Please contact administrator.';\n        }\n        showAlert(errorMessage, 'error', formContainer);\n\n        // Reset button\n        googleLoginBtn.disabled = false;\n        googleLoginBtn.innerHTML = '<i class=\"fa-brands fa-google\"></i><span>Continue with Google</span>';\n      }\n    });\n    form.addEventListener('submit', e => {\n      e.preventDefault();\n    });\n    sendBtn.addEventListener('click', async e => {\n      try {\n        let formData = new FormData(form);\n        let hasEmptyFields = false;\n\n        // Check only visible fields for login, all fields for signup\n        if (formtype === 'login') {\n          // For login, only check email and password\n          const email = form.querySelector('#email').value.trim();\n          const password = form.querySelector('#password').value.trim();\n          if (email.length === 0 || password.length === 0 || email.indexOf(\"@\") < 0) {\n            showAlert('Please fill in all required fields', 'error', formContainer);\n            return;\n          }\n        } else {\n          // For signup, check all fields including username\n          for (const [key, value] of formData.entries()) {\n            if (value.trim().length === 0) {\n              showAlert('Please fill in all required fields', 'error', formContainer);\n              return;\n            }\n          }\n        }\n        const params = new URLSearchParams(formData);\n        let url = formtype === 'login' ? '/konektem/auth/login' : '/konektem/auth/register';\n        let response = await fetch(url, {\n          method: 'POST',\n          headers: {\n            'Content-Type': 'application/x-www-form-urlencoded'\n          },\n          body: params.toString()\n        });\n        let result = await response.json();\n        if (result.success) {\n          showAlert('Success! Redirecting...', 'success', formContainer);\n          if (formtype === 'login') {\n            sessionStorage.setItem('user', JSON.stringify(result.user));\n            sessionStorage.setItem('token', result.token);\n            (0,_profile_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])();\n          }\n          if (closeOnLog) {\n            setTimeout(() => {\n              closeIcon.click();\n            }, 1000);\n          } else if (formtype === 'login') {\n            window.location.href = \"/konektem/user/me\";\n          } else {\n            showAlert(\"registration successful. Log in to your account\", 'success', formContainer);\n          }\n        } else {\n          // console.log('showing alert on login')\n          showAlert(result.message, 'error', formContainer);\n        }\n      } catch (error) {\n        console.log(error.message);\n        showAlert('An error occurred. Please try again.', 'error', formContainer);\n      }\n    });\n    let showPswdCheckBx = form.querySelector('#show-pswd');\n    showPswdCheckBx.addEventListener('change', e => {\n      pswdField.type = e.currentTarget.checked ? 'text' : 'password';\n    });\n  }, 500);\n  async function renderFormUI() {\n    let sendBtn = form.querySelector('#send-btn');\n    let formBtmParag = form.querySelector('#form-btm-text');\n    let formHeader = form.querySelector('.form-header h2');\n    let formSubtitle = form.querySelector('.form-subtitle');\n    let usernameField = form.querySelector('.field:first-child');\n    if (formtype === 'login') {\n      formHeader.textContent = 'Log in to your account';\n      formSubtitle.textContent = 'Welcome back! Please enter your details.';\n      sendBtn.textContent = 'SIGN IN';\n      formBtmParag.innerHTML = \"Don't have an account? <a id='sign-link'>Sign up</a>\";\n      // Hide username field for login\n      usernameField.style.display = 'none';\n    } else {\n      formHeader.textContent = 'Create your account';\n      formSubtitle.textContent = 'Join us today! Enter your details to get started.';\n      sendBtn.textContent = 'SIGN UP';\n      formBtmParag.innerHTML = \"Already have an account? <a id='reg-link'>Sign in</a>\";\n      // Show username field for signup\n      usernameField.style.display = 'block';\n    }\n    let formLink = form.querySelector('a');\n    formLink.addEventListener('click', () => {\n      formtype = formtype === 'login' ? 'register' : 'login';\n      renderFormUI();\n    });\n  }\n}\nfunction showAlert(message, type, container) {\n  // Remove existing alerts\n  const existingAlerts = container.querySelectorAll('.alert');\n  existingAlerts.forEach(alert => alert.remove());\n  const alertDiv = document.createElement('div');\n  alertDiv.className = `alert alert-${type === 'success' ? 'ok' : 'err'}`;\n  alertDiv.innerHTML = `\n        <i class=\"fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}\"></i>\n        <span>${message}</span>\n    `;\n  container.appendChild(alertDiv);\n  setTimeout(() => {\n    if (alertDiv.parentNode) {\n      alertDiv.remove();\n    }\n  }, 5000);\n}\nfunction userAuthPage(resourcesLoaded) {\n  let styleTag = document.querySelector('#root-style');\n  var formtype = 'register';\n  renderForm(formtype);\n}\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (userAuthPage);\n\n//# sourceURL=webpack://konektem/./static/js/authentication.js?\n}");

/***/ },

/***/ "./static/js/profile.js"
/*!******************************!*\
  !*** ./static/js/profile.js ***!
  \******************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__),\n/* harmony export */   profLinkClickHandle: () => (/* binding */ profLinkClickHandle)\n/* harmony export */ });\nfunction updateProfileUI() {\n  try {\n    let icon = document.querySelector('#nav-panel .user-prof');\n    let profileText = document.querySelector('#nav-panel .user-id');\n    let bottomProfileIcon = document.querySelector('#nav-panel-bottom .profile');\n    //modify the login\n    let user = sessionStorage.getItem('user');\n    if (user) {\n      icon?.classList.remove('fa-arrow-right-from-bracket');\n      icon?.classList.add('fa-user-plus');\n      profileText.textContent = 'Konekte/Enskri';\n      bottomProfileIcon.classList.remove('online');\n    } else {\n      user = JSON.parse('user');\n      icon?.classList.remove('fa-user-plus');\n      icon?.classList.add('fa-circle-user');\n      profileText.textContent = user.name + \"/profile\";\n      bottomProfileIcon.classList.add('online');\n    }\n  } catch (error) {\n    console.error(error.message);\n  }\n}\nasync function profLinkClickHandle(handleSet) {\n  if (handleSet) return;\n  try {\n    //localStorage.getItem('currentUser');\n\n    // let session = sessionStorage.getItem('user');\n    // let response = await fetch('/php/dbReader.php?q=uid');\n    // let user = await response.json();\n    // if(session === \"[object Object]\"){\n    //     session = { name: user.name};\n    // } else {\n    //     session = JSON.parse(session);\n    // }\n    // if(!session || session.name !== user.name){\n    //     session = { name: user.name };\n    //     sessionStorage.setItem('user', JSON.stringify(session));\n    // }\n    /**\n    * the handler is obsolete\n    */\n    let clickHandler = async () => {\n      if (!sessionStorage.getItem('user')) {\n        window.location.href = '/konektem/auth/login';\n      } else {\n        window.location.href = '/konektem/user/me';\n      }\n    };\n    updateProfileUI();\n    let profileLink = document.querySelector('#prof-link');\n    let topBarProfIcon = document.querySelector(\"#top-bar .icon-container .prof-icon\");\n    profileLink?.addEventListener('click', clickHandler);\n    topBarProfIcon?.addEventListener('click', clickHandler);\n  } catch (error) {\n    console.error(error.message);\n  }\n}\nfunction updateProfile(username) {}\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (updateProfileUI);\n\n//# sourceURL=webpack://konektem/./static/js/profile.js?\n}");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./static/js/auth_page_script.js");
/******/ 	
/******/ })()
;
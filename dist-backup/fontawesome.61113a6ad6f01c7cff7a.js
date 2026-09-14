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

/***/ "./fontawesome.js"
/*!************************!*\
  !*** ./fontawesome.js ***!
  \************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   iconLinks: () => (/* binding */ iconLinks)\n/* harmony export */ });\nconst iconLinks = [\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/whiteboard-semibold.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/utility-fill-semibold.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/utility-duo-semibold.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/utility-semibold.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/thumbprint-light.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/slab-press-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/slab-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-thin.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-solid.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-light.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-thin.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-solid.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-light.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/notdog-duo-solid.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/notdog-solid.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/jelly-fill-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/jelly-duo-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/jelly-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/etch-solid.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/duotone-thin.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/duotone.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/duotone-regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/duotone-light.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/thin.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/solid.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/regular.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/light.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/brands.css\",\n    \"https://site-assets.fontawesome.com/releases/v7.1.0/css/chisel-regular.css\",\n    \"https://use.fortawesome.com/kits/1ce05b4b/publications/131579/woff2.css\",\n];\n\n\n\n//# sourceURL=webpack:///./fontawesome.js?\n}");

/***/ },

/***/ "./script2.js"
/*!********************!*\
  !*** ./script2.js ***!
  \********************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _fontawesome_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./fontawesome.js */ \"./fontawesome.js\");\n\n\n_fontawesome_js__WEBPACK_IMPORTED_MODULE_0__.iconLinks.forEach(lk => {\n    let tag = document.createElement('link');\n    tag.rel = 'stylesheet';\n    tag.href = lk;\n\n    document.head.appendChild(tag);\n});\n\n//# sourceURL=webpack:///./script2.js?\n}");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./script2.js");
/******/ 	
/******/ })()
;
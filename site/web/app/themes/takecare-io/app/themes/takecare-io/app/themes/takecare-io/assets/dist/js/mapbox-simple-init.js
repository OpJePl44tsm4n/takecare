/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/mapbox-simple-init.js":
/*!*********************************************!*\
  !*** ./assets/src/js/mapbox-simple-init.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var map = document.getElementById('map'),
    lat = map.dataset.lat,
    lng = map.dataset.lng,
    zoom = map.dataset.zoom,
    popup = JSON.parse(map.dataset.popup);
L.mapbox.accessToken = 'pk.eyJ1IjoidGh1bmRlcnBsdWdzIiwiYSI6ImNqbjBicTl0azA5Mmsza3Bjdmt3dTFpdnUifQ.Yd8Bg_gH5S1oVss6Rr6vRA';
var baseLocation = [lat, lng];
var map = L.mapbox.map('map').setView(baseLocation, zoom);
map.scrollWheelZoom.disable();
L.mapbox.styleLayer('mapbox://styles/thunderplugs/cjn0criqe4aes2snvkcdtmbf9').addTo(map);
var featureLayer = L.mapbox.featureLayer().addTo(map);
var geoJson = {
  "type": "FeatureCollection",
  "features": [{
    "id": 1,
    "type": "Feature",
    "geometry": {
      "type": "Point",
      "coordinates": [lng, lat]
    },
    "properties": {
      "icon": {
        "className": "custom-marker-icon",
        "iconSize": "null"
      }
    }
  }]
}; // we are going to load the custom icon defined in the geojson

featureLayer.on('layeradd', function (e) {
  var marker = e.layer,
      feature = marker.feature;
  marker.setIcon(L.divIcon(feature.properties.icon));
}); // Add features to the map.

featureLayer.setGeoJSON(geoJson);
featureLayer.eachLayer(function (layer) {
  if (popup) {
    layer.bindPopup(popup);
  }

  layer.on('click', function (e) {
    map.panTo([layer.feature.geometry.coordinates[1], layer.feature.geometry.coordinates[0]]);
  });
});

/***/ }),

/***/ 1:
/*!***************************************************!*\
  !*** multi ./assets/src/js/mapbox-simple-init.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/pim/projects/takecare/site/web/app/themes/takecare-io/assets/src/js/mapbox-simple-init.js */"./assets/src/js/mapbox-simple-init.js");


/***/ })

/******/ });
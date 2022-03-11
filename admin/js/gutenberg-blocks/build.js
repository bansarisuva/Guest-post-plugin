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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

(function (wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
    var __ = wpI18n.__;
    var Component = wpElement.Component,
        Fragment = wpElement.Fragment;
    var registerBlockType = wpBlocks.registerBlockType;
    var InspectorControls = wpEditor.InspectorControls;
    var PanelBody = wpComponents.PanelBody,
        ServerSideRender = wpComponents.ServerSideRender,
        RangeControl = wpComponents.RangeControl,
        SelectControl = wpComponents.SelectControl;

    var DisplayPostBlock = function (_Component) {
        _inherits(DisplayPostBlock, _Component);

        function DisplayPostBlock() {
            _classCallCheck(this, DisplayPostBlock);

            return _possibleConstructorReturn(this, (DisplayPostBlock.__proto__ || Object.getPrototypeOf(DisplayPostBlock)).apply(this, arguments));
        }

        _createClass(DisplayPostBlock, [{
            key: 'render',
            value: function render() {
                var _props = this.props,
                    _props$attributes = _props.attributes,
                    numOfItems = _props$attributes.numOfItems,
                    postOrder = _props$attributes.postOrder,
                    setAttributes = _props.setAttributes;


                return wp.element.createElement(
                    Fragment,
                    null,
                    wp.element.createElement(
                        InspectorControls,
                        null,
                        wp.element.createElement(
                            PanelBody,
                            { title: __('Settings'), initialOpen: true },
                            wp.element.createElement(RangeControl, {
                                label: __('Number of items'),
                                value: numOfItems,
                                min: 1,
                                max: 100,
                                onChange: function onChange(item) {
                                    return setAttributes({ numOfItems: parseInt(item) });
                                }
                            }),
                            wp.element.createElement(SelectControl, {
                                label: __('Post Order'),
                                value: postOrder,
                                onChange: function onChange(order) {
                                    return setAttributes({ postOrder: order });
                                },
                                options: [{ value: 'DESC', label: 'Latest to Oldest' }, { value: 'ASC', label: 'Oldest to Latest' }]
                            })
                        )
                    ),
                    wp.element.createElement(ServerSideRender, {
                        block: 'guest-posts/server-side-render',
                        attributes: {
                            numOfItems: numOfItems,
                            postOrder: postOrder
                        }
                    })
                );
            }
        }]);

        return DisplayPostBlock;
    }(Component);

    var blockAttrs = {
        numOfItems: {
            type: 'number',
            default: 10
        },
        postOrder: {
            type: 'string',
            default: 'DESC'
        }
    };
    registerBlockType('guest-posts/server-side-render', {
        title: __('Guest Post Block', 'plugin or theme guest-posts'),
        description: __('Display Guest Posts', 'plugin or theme guest-posts'),
        icon: 'lock',
        category: 'common',
        keywords: [__('guest-posts'), __('server')],
        attributes: blockAttrs,
        edit: DisplayPostBlock,
        save: function save() {
            return null;
        }
    });
})(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components);

/***/ })
/******/ ]);
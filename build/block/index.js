/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@wordpress/icons/build-module/library/info.js":
/*!********************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/info.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ info_default)
/* harmony export */ });
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


var info_default = /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.SVG, { viewBox: "0 0 24 24", xmlns: "http://www.w3.org/2000/svg", children: /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)(
  _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.Path,
  {
    fillRule: "evenodd",
    clipRule: "evenodd",
    d: "M5.5 12a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0ZM12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16Zm.75 4v1.5h-1.5V8h1.5Zm0 8v-5h-1.5v5h1.5Z"
  }
) });

//# sourceMappingURL=info.js.map


/***/ }),

/***/ "./node_modules/@wordpress/icons/build-module/library/pages.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/pages.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ pages_default)
/* harmony export */ });
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


var pages_default = /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: [
  /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.Path, { d: "M14.5 5.5h-7V7h7V5.5ZM7.5 9h7v1.5h-7V9Zm7 3.5h-7V14h7v-1.5Z" }),
  /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.Path, { d: "M16 2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2ZM6 3.5h10a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5H6a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5Z" }),
  /* @__PURE__ */ (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.Path, { d: "M20 8v11c0 .69-.31 1-.999 1H6v1.5h13.001c1.52 0 2.499-.982 2.499-2.5V8H20Z" })
] });

//# sourceMappingURL=pages.js.map


/***/ }),

/***/ "./src/block/block.json":
/*!******************************!*\
  !*** ./src/block/block.json ***!
  \******************************/
/***/ ((module) => {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"rrze/research-data","version":"0.0.1","title":"Research Data","category":"rrze","icon":"admin-page","description":"Displays publications from research platforms.","supports":{"html":false},"attributes":{"isInitialSetup":{"type":"boolean","default":true},"authorId":{"type":"string","default":""},"source":{"type":"string","default":"orcid"},"limit":{"type":"number","default":15},"yearFrom":{"type":"number","default":0},"yearTo":{"type":"number","default":0},"type":{"type":"array","items":{"type":"string"},"default":[]},"groupBy":{"type":"string","default":""},"citationStyle":{"type":"string","default":""}},"textdomain":"rrze-research-data","editorScript":"file:./index.js","editorStyle":"file:./index.css","style":"file:./style.css","example":{"attributes":{"isInitialSetup":false}}}');

/***/ }),

/***/ "./src/block/edit.tsx":
/*!****************************!*\
  !*** ./src/block/edit.tsx ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Edit)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/library/info.js");
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/library/pages.js");
/* harmony import */ var _wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/server-side-render */ "@wordpress/server-side-render");
/* harmony import */ var _wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./editor.scss */ "./src/block/editor.scss");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__);









function Edit({
  attributes,
  setAttributes
}) {
  const {
    authorId,
    type,
    source,
    limit,
    isInitialSetup,
    yearFrom,
    yearTo,
    groupBy,
    citationStyle
  } = attributes;
  const [personList, setPersonList] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)([]);
  const [isLoadingPersons, setIsLoadingPersons] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(true);
  const [selectedPersonId, setSelectedPersonId] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)('');
  const [showManualInput, setShowManualInput] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
      path: '/rrze-research-data/v1/faudir/persons'
    }).then(persons => {
      setPersonList(persons);
      setIsLoadingPersons(false);
    }).catch(() => {
      setIsLoadingPersons(false);
    });
  }, []);
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)();
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
    ...blockProps,
    children: isInitialSetup ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Placeholder, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Research Data', 'rrze-research-data'),
      instructions: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Display research publications from external sources.', 'rrze-research-data'),
      icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_6__["default"],
      isColumnLayout: true,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("div", {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("hr", {}), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalSpacer, {
          paddingBottom: "1rem"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("div", {
          className: "rrze-research-data-form",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("label", {
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Publication Source', 'rrze-research-data')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
            value: source,
            options: [{
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('ORCID', 'rrze-research-data'),
              value: 'orcid'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('PubMed', 'rrze-research-data'),
              value: 'pubmed'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('OpenAlex', 'rrze-research-data'),
              value: 'openAlex'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('arXiv', 'rrze-research-data'),
              value: 'arxiv'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Crossref', 'rrze-research-data'),
              value: 'crossref'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('DBLP', 'rrze-research-data'),
              value: 'dblp'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Semantic Scholar', 'rrze-research-data'),
              value: 'semanticscholar'
            }],
            onChange: value => {
              setAttributes({
                source: value,
                authorId: ''
              });
              setSelectedPersonId('');
              setShowManualInput(false);
            }
          }), ['orcid', 'pubmed', 'openAlex', 'arxiv', 'crossref'].includes(source) && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.Fragment, {
            children: isLoadingPersons ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("p", {
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Loading…', 'rrze-research-data')
            }) : personList.length > 0 ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.Fragment, {
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("label", {
                children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Person (FAUdir)', 'rrze-research-data')
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
                value: selectedPersonId,
                options: [{
                  label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('— Please select —', 'rrze-research-data'),
                  value: ''
                }, ...personList.map(person => ({
                  label: person.name,
                  value: person.id
                }))],
                onChange: personId => {
                  setSelectedPersonId(personId);
                  if (!personId) return;
                  _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
                    path: `/rrze-research-data/v1/faudir/person/${personId}`
                  }).then(platformIds => {
                    var _platformIds$arxiv, _platformIds$orcid;
                    const id = source === 'arxiv' ? (_platformIds$arxiv = platformIds?.arxiv) !== null && _platformIds$arxiv !== void 0 ? _platformIds$arxiv : '' : (_platformIds$orcid = platformIds?.orcid) !== null && _platformIds$orcid !== void 0 ? _platformIds$orcid : '';
                    setAttributes({
                      authorId: id
                    });
                    if (!id) setShowManualInput(true);
                  });
                }
              }), showManualInput && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.Fragment, {
                children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("p", {
                  style: {
                    color: '#cc0000'
                  },
                  children: [source === 'arxiv' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('No arXiv ID in FAUdir found. Please enter manually.', 'rrze-research-data') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('No ORCID in FAUdir found. Please enter manually.', 'rrze-research-data'), ' ', /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Tooltip, {
                    text: source === 'arxiv' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Enter your arXiv Author-ID in idm.fau.de under: FAUdir → Entries → Portals and networks', 'rrze-research-data') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Enter your ORCID in idm.fau.de under: FAUdir → Entries → Portals and networks', 'rrze-research-data'),
                    placement: "right",
                    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("span", {
                      className: "rrze-research-data-info",
                      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
                        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__["default"]
                      })
                    })
                  })]
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("label", {
                  children: source === 'arxiv' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('arXiv Author-ID', 'rrze-research-data') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('ORCID', 'rrze-research-data')
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
                  value: authorId,
                  placeholder: source === 'arxiv' ? 'lastname_f_1' : '0000-0000-0000-0000',
                  onChange: value => setAttributes({
                    authorId: value
                  })
                })]
              })]
            }) :
            /*#__PURE__*/
            // if FAUdir ist not active → TextControl
            (0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.Fragment, {
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("label", {
                children: source === 'arxiv' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('arXiv Author-ID', 'rrze-research-data') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('ORCID', 'rrze-research-data')
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
                value: authorId,
                placeholder: source === 'arxiv' ? 'lastname_f_1' : '0000-0000-0000-0000',
                onChange: value => setAttributes({
                  authorId: value
                })
              })]
            })
          }), ['dblp', 'semanticscholar'].includes(source) && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.Fragment, {
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("label", {
              children: source === 'dblp' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('DBLP PID', 'rrze-research-data') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('SemanticScholar Author-ID', 'rrze-research-data')
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
              value: authorId,
              placeholder: source === 'dblp' ? 'xx/0000' : '0000000',
              onChange: value => setAttributes({
                authorId: value
              })
            }), source === 'semanticscholar' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("p", {
              className: "rrze-research-data-help",
              children: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('ID from your Semantic Scholar profile URL:', 'rrze-research-data'), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("code", {
                children: ["/author/Yourname/", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("strong", {
                  children: "0000000"
                })]
              })]
            }), source === 'dblp' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("p", {
              className: "rrze-research-data-help",
              children: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('ID from your DBLP profile URL:', 'rrze-research-data'), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("code", {
                children: ["/pid/", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("strong", {
                  children: "xx/0000"
                })]
              })]
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalSpacer, {
            paddingTop: ".5rem"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalSpacer, {
            paddingBottom: "0.5rem"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
              variant: "primary",
              onClick: () => setAttributes({
                isInitialSetup: false
              }),
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Save', 'rrze-research-data')
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalSpacer, {
            paddingBottom: "0.5rem"
          })]
        })]
      })
    }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.Fragment, {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Research Data', 'rrze-research-data'),
          initialOpen: true,
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Publication Source', 'rrze-research-data'),
            value: source,
            options: [{
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('ORCID', 'rrze-research-data'),
              value: 'orcid'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('PubMed', 'rrze-research-data'),
              value: 'pubmed'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('OpenAlex', 'rrze-research-data'),
              value: 'openAlex'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('arXiv', 'rrze-research-data'),
              value: 'arxiv'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Crossref', 'rrze-research-data'),
              value: 'crossref'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('DBLP', 'rrze-research-data'),
              value: 'dblp'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Semantic Scholar', 'rrze-research-data'),
              value: 'semanticscholar'
            }],
            onChange: value => setAttributes({
              source: value,
              authorId: ''
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
            label: source === 'arxiv' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('arXiv Author-ID', 'rrze-research-data') : source === 'dblp' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('DBLP PID', 'rrze-research-data') : source === 'semanticscholar' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Semantic Scholar Author-ID', 'rrze-research-data') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('ORCID', 'rrze-research-data'),
            value: authorId,
            placeholder: source === 'arxiv' ? 'lastname_x_x' : source === 'dblp' ? 'xx/0000' : source === 'semanticscholar' ? '0000000' : '0000-0000-0000-0000',
            onChange: value => setAttributes({
              authorId: value
            })
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Display Options', 'rrze-research-data'),
          initialOpen: false,
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.BaseControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Publication Type', 'rrze-research-data'),
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalSpacer, {
              paddingTop: ".2rem"
            }), [{
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Journal Article', 'rrze-research-data'),
              value: 'journal-article'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Conference', 'rrze-research-data'),
              value: 'conference'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Book', 'rrze-research-data'),
              value: 'book'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Book Chapter', 'rrze-research-data'),
              value: 'book-chapter'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Editorship', 'rrze-research-data'),
              value: 'editorship'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Preprint', 'rrze-research-data'),
              value: 'preprint'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Review', 'rrze-research-data'),
              value: 'review'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Thesis', 'rrze-research-data'),
              value: 'thesis'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Other', 'rrze-research-data'),
              value: 'other'
            }].map(option => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
              label: option.label,
              checked: type.includes(option.value),
              onChange: checked => {
                if (checked) {
                  setAttributes({
                    type: [...type, option.value]
                  });
                } else {
                  setAttributes({
                    type: type.filter(t => t !== option.value)
                  });
                }
              }
            }, option.value))]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalSpacer, {
            paddingTop: ".5rem"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Group by', 'rrze-research-data'),
            value: groupBy,
            options: [{
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('No grouping', 'rrze-research-data'),
              value: ''
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Year', 'rrze-research-data'),
              value: 'year'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Publication Type', 'rrze-research-data'),
              value: 'type'
            }],
            onChange: val => setAttributes({
              groupBy: val
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Citation Style', 'rrze-research-data'),
            value: citationStyle,
            options: [{
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Standard', 'rrze-research-data'),
              value: ''
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('APA', 'rrze-research-data'),
              value: 'apa'
            }, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('MLA', 'rrze-research-data'),
              value: 'mla'
            }],
            onChange: val => setAttributes({
              citationStyle: val
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalNumberControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Year from', 'rrze-research-data'),
            value: yearFrom === 0 ? '' : yearFrom,
            min: 1900,
            max: 2100,
            onChange: value => setAttributes({
              yearFrom: parseInt(value) || 0
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalNumberControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Year to', 'rrze-research-data'),
            value: yearTo === 0 ? '' : yearTo,
            min: 1900,
            max: 2100,
            onChange: value => setAttributes({
              yearTo: parseInt(value) || 0
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalNumberControl, {
            __next40pxDefaultSize: true,
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Limit', 'rrze-research-data'),
            value: limit,
            onChange: value => setAttributes({
              limit: Number(value)
            })
          })]
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)((_wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_7___default()), {
        block: "rrze/research-data",
        attributes: attributes
      })]
    })
  });
}

/***/ }),

/***/ "./src/block/editor.scss":
/*!*******************************!*\
  !*** ./src/block/editor.scss ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/primitives":
/*!************************************!*\
  !*** external ["wp","primitives"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["primitives"];

/***/ }),

/***/ "@wordpress/server-side-render":
/*!******************************************!*\
  !*** external ["wp","serverSideRender"] ***!
  \******************************************/
/***/ ((module) => {

module.exports = window["wp"]["serverSideRender"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

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
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
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
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!*****************************!*\
  !*** ./src/block/index.tsx ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./editor.scss */ "./src/block/editor.scss");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/block/edit.tsx");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/block/block.json");




(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__.name, {
  ..._block_json__WEBPACK_IMPORTED_MODULE_3__,
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"],
  save: () => null
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map
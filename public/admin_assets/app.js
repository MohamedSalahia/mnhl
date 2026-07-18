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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./public/admin_assets/custom/js/index.js":
/*!************************************************!*\
  !*** ./public/admin_assets/custom/js/index.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  renderOrdersChart();
  renderOrdersChartByYear();
}); //end of document ready

var renderOrdersChart = function renderOrdersChart() {
  var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

  if ($('#orders-chart-wrapper').length) {
    var loadingHtml = "\n              <div style=\"height: 400px;\" class=\"d-flex justify-content-center align-items-center\">\n                  <div class=\"loader-md\"></div>\n              </div>\n        ";
    $('#orders-chart-wrapper').empty().append(loadingHtml);
    var url = $('#orders-chart-wrapper').data('url');
    $.ajax({
      url: url,
      data: data,
      success: function success(html) {
        $('#orders-chart-wrapper').empty().append(html);
      }
    }); //end of ajax call
  } //end of if

};

var renderOrdersChartByYear = function renderOrdersChartByYear() {
  $('#orders-chart-year').on('change', function () {
    renderOrdersChart({
      year: this.value
    });
  });
};

/***/ }),

/***/ "./public/admin_assets/custom/js/languages.js":
/*!****************************************************!*\
  !*** ./public/admin_assets/custom/js/languages.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  toggleActive();
}); //end of document ready

var toggleActive = function toggleActive() {
  $(document).on('change', '.language-toggle-active', function (e) {
    e.preventDefault();
    $(this).parent().find('form').submit();
  });
};

/***/ }),

/***/ "./public/admin_assets/custom/js/roles.js":
/*!************************************************!*\
  !*** ./public/admin_assets/custom/js/roles.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $(document).on('change', '.all-permissions', function () {
    $(this).parents('tr').find('input[type="checkbox"]').prop('checked', this.checked);
  });
  $(document).on('change', '.role', function () {
    if (!this.checked) {
      $(this).parents('tr').find('.all-permissions').prop('checked', this.checked);
    }
  });
}); //end of document ready

/***/ }),

/***/ "./public/admin_assets/custom/js/shared/index.js":
/*!*******************************************************!*\
  !*** ./public/admin_assets/custom/js/shared/index.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  initAjaxHeader();
  ajaxData();
  initSelect2();
  initDatePicker();
  initGalleryImages();
  ajaxModal();
  ajaxForm();
  disabledLinks();
  dataTableRecordSelect();
  showImageUnderFileExplorer(); // checkFieldLanguage();

  toggleActive();
}); //end of document ready

var initAjaxHeader = function initAjaxHeader() {
  var loginUrl = $('meta[name="login-url"]').attr('content');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function error(xhr, status, _error) {
      if (xhr.status == 500) {
        window.handleErrorModal(xhr);
      } else if (xhr.status == 401 || xhr.status == 415 || xhr.status == 419) {
        window.location.href = loginUrl;
      }
    },
    statusCode: {}
  });
};

var ajaxData = function ajaxData() {
  $('.ajax-data').on('click', function () {
    var loadingHtml = "\n              <div style=\"height: 50vh;\" class=\"d-flex justify-content-center align-items-center\">\n                  <div class=\"loader\"></div>\n              </div>\n        ";
    $('.ajax-data').removeClass('active');
    $(this).addClass('active');
    $('#ajax-data-wrapper').empty().append(loadingHtml);
    var url = $(this).data('url');
    $.ajax({
      url: url,
      cache: false,
      success: function success(html) {
        $('#ajax-data-wrapper').empty().append(html);
      }
    }); //end of ajax call
  }); //end of on click
};

var initSelect2 = function initSelect2() {
  // select 2
  // $('.select2').select2({
  //     'width': '100%',
  // });
  $('.select2').each(function () {
    var placeholder = $(this).find('option:first').text();
    $(this).select2({
      'width': '100%',
      'placeholder': $(this).attr('multiple') == false ? placeholder : ''
    });
  }); // $('.select2-ajax').each(function () {
  //
  //     let searchUrl = $(this).attr('data-search-url');
  //     let placeholder = $(this).attr('placeholder');
  //     let loadingText = $(this).attr('data-loading-text');
  //
  //     $(this).select2({
  //         placeholder: placeholder,
  //         ajax: {
  //             url: searchUrl,
  //             delay: 250,
  //             dataType: 'json',
  //             data: function (params) {
  //                 return {
  //                     'search': params.term,
  //                     // 'not_in_names': that.val(),
  //                 };
  //             },
  //             processResults: function (data, params) {
  //                 data = data.data;
  //
  //                 return {
  //                     results: $.map(data, function (item) {
  //                         return {
  //                             text: item.title,
  //                             id: item.id,
  //                             image_asset_path: item.image_asset_path,
  //                             description: item.description.substring(0, 200),
  //                         }
  //                     })
  //                 }
  //             },
  //         },
  //         minimumInputLength: 1,
  //         escapeMarkup: function (markup) {
  //             return markup;
  //         },
  //         createTag: function (params) {
  //             return {
  //                 id: params.term,
  //                 text: params.term,
  //             };
  //         },
  //         templateResult: select2TemplateResult,
  //         templateSelection: select2TemplateSelection,
  //     });
  //
  // })

  var select2TemplateResult = function select2TemplateResult(data) {
    var el = '';

    if (data.loading) {
      var html = "\n            <div class=\"d-flex justify-content-between\">\n                <p style=\"font-size: 1.1rem; margin-bottom: 0;\">loading..</p>\n                 <div class=\"loading-container p-0\">\n                    <div class=\"loader-xs\"></div>\n                </div>\n            </div>\n        ";
      el = $(html);
    } else {
      var _html = "\n            <div class=\"d-flex\"> \n                <img src=\"".concat(data.image_asset_path, "\" style=\"width: 80px; height: 100px;\" alt=\"\">\n               \n                <div style=\"margin-left: 10px;\">\n                    <h4>").concat(data.text, "</h4>\n                    <p>").concat(data.description, "</p>\n                </div>\n            </div>\n        ");

      el = $(_html);
    }

    return el;
  };

  var select2TemplateSelection = function select2TemplateSelection(data) {
    var el = '';

    if (data.newOption & !data.addedBefore) {
      var html = "<span>".concat(data.text, "</div>");
      $.ajax({
        url: data.creationUrl,
        method: 'POST',
        data: {
          'name': data.text
        },
        cache: false,
        success: function success(resp) {
          data.addedBefore = true;
        }
      }); //end of ajax call

      el = $(html);
    } else {
      var _html2 = "<span>".concat(data.text, "</div>");

      el = $(_html2);
    }

    return el;
  };
};

var ajaxModal = function ajaxModal() {
  $(document).on('click', '.ajax-modal', function (e) {
    e.preventDefault();
    var loading = "\n            <div class=\"loading-container absolute-centered\">\n                <div class=\"loader sm\"></div>\n            </div>\n        ";
    var url = $(this).data('url');
    var modalTitle = $(this).data('modal-title');
    var modalBodyClass = $(this).data('modal-body-class');
    $('#ajax-modal').modal('show');
    $('#ajax-modal .modal-body').remove();
    $('#ajax-modal .modal-content').append('<div class="modal-body relative"></div>');
    $('#ajax-modal .modal-body').addClass(modalBodyClass);
    $('#ajax-modal .modal-body').empty().append(loading);
    $('#ajax-modal .modal-title').text(modalTitle);
    $.ajax({
      url: url,
      //processData: false,
      //contentType: false,
      cache: false,
      success: function success(response) {
        $('#ajax-modal .modal-body').empty().append(response['view']);
      }
    }); //end of ajax call
  });
};

var ajaxForm = function ajaxForm() {
  $(document).on('submit', '.ajax-form', function (e) {
    e.preventDefault();
    var that = $(this);
    var loading = $('meta[name="loading"]').attr('content');
    var submitButton = that.find('button[type="submit"]:last-child');
    var submitButtonHtml = submitButton.html();
    submitButton.attr('disabled', true);
    that.find('button[type="submit"]').html(loading);
    that.removeClass('active');
    that.addClass('active');
    that.find('.invalid-feedback').remove();
    var url = $(this).attr('action');
    var data = new FormData(this);
    $('.ajax-form.active .invalid-feedback').hide();
    $.ajax({
      url: url,
      data: data,
      method: 'POST',
      contentType: false,
      processData: false,
      cache: false,
      success: function success(response) {
        hideModals();
        handleAjaxRedirects(response, submitButtonHtml);
        handleAjaxRemoveElements(response);

        if (that.hasClass('empty-form')) {
          that.find('input:not([type=hidden]), textarea, select').val('');
        } //end of if

      },
      error: function error(xhr, exception) {
        var loginUrl = $('meta[name="login-url"]').attr('content');

        if (xhr.status == 500) {
          window.handleErrorModal(xhr);
        } else if (xhr.status == 401 || xhr.status == 415 || xhr.status == 419) {
          window.location.href = loginUrl;
        } else {
          handleAjaxErrors(xhr, submitButtonHtml);
        } //end of if

      },
      complete: function complete() {
        submitButton.attr('disabled', false);
        submitButton.html(submitButtonHtml);
      }
    }); //end of ajax call
  });
};

window.hideModals = function () {
  $(".modal").each(function () {
    $(this).modal("hide");
  });
};

window.handleErrorModal = function (xhr) {
  $('#error-modal').modal('show');
  var html = '';

  if (xhr.responseJSON) {
    var error = xhr.responseJSON;
    html += "\n            <h3> ".concat(error.message, "</h3>\n            <p><strong>Exception: </strong>").concat(error.exception, "</p>\n            <p><strong>file: </strong>").concat(error.file, "</p>\n            <p><strong>line: </strong>").concat(error.line, "</p>\n        ");

    if (error.trace) {
      html += "<h5>Trace</h5>";
    } //end of if


    error.trace.forEach(function (item, index) {
      html += "\n                <div style=\"margin-bottom: 10px\">\n                    <p class=\"mb-0\"><strong>class: </strong> ".concat(item["class"], "</p>\n                    <p class=\"mb-0\"><strong>file: </strong>").concat(item.file, "</p>\n                    <p class=\"mb-0\"><strong>function: </strong>").concat(item["function"], "</p>\n                    <p class=\"mb-0\"><strong>line: </strong>").concat(item.line, "</p>\n                </div>\n            ");
    });
  } else {
    html += xhr.responseText;
  }

  $('#error-modal .modal-body').empty().append(html);
};

var handleAjaxErrors = function handleAjaxErrors(xhr, submitButtonHtml) {
  var errors = xhr['responseJSON']['errors'];

  for (var field in xhr['responseJSON']['errors']) {
    $(".ajax-form.active input[name=\"".concat(field, "\"], .ajax-form.active select[name=\"").concat(field, "\"], .ajax-form.active textarea[name=\"").concat(field, "\"]")).closest('.form-group').append("<span class=\"invalid-feedback d-block\">".concat(errors[field][0], "</span>"));
    $(".ajax-form.active input[data-error-name=\"".concat(field, "\"], .ajax-form.active select[data-error-name=\"").concat(field, "\"], .ajax-form.active textarea[data-error-name=\"").concat(field, "\"]")).closest('.form-group').append("<span class=\"invalid-feedback d-block\">".concat(errors[field][0], "</span>"));
  }

  if ($('.invalid-feedback.d-block').length) {
    $('html, body, .page-data').animate({
      scrollTop: $('.invalid-feedback.d-block').offset().top - 300
    }, 200);
  } //end of if


  $('.ajax-form input[type="password"]').val("");
  $('.ajax-form input.empty').val("");
};

var handleAjaxRedirects = function handleAjaxRedirects(response, submitButtonHtml) {
  if (response['success_message'] && response['redirect_to']) {
    $(document).showSuccessDialogue({
      text: response['success_message']
    });
    setTimeout(function () {
      window.location.href = response['redirect_to'];
    }, 100);
  } else if (response['redirect_to'] || response['success_message'] || response['replace'] || response['modal_view']) {
    if (response['redirect_to']) {
      window.location.href = response['redirect_to'];
    }

    if (response['success_message']) {
      $(document).showSuccessDialogue({
        text: response['success_message']
      });
    }

    if (response['replace']) {
      $(response['replace']).html(response['replace_with']);
    } //end of if


    if (response['modal_view']) {
      if (response['modal-size-class']) {
        $('#ajax-modal .modal-dialog').removeAttr('class').attr('class', 'modal-dialog modal-dialog-centered ' + response['modal-size-class']);
      } //end of if


      $('#ajax-modal .modal-title').text(response['modal_title']);
      $('#ajax-modal .modal-body').empty().append(response['modal_view']);
      $('#ajax-modal').modal('show');
      $('.ajax-form.active button[type="submit"]').html(submitButtonHtml);
      $('.ajax-form.active button[type="submit"]').attr('disabled', false);
    } //end of if

  } else {
    $('.ajax-form.active button[type="submit"]').html(submitButtonHtml);
    $('.ajax-form.active button[type="submit"]').attr('disabled', false);
  }
};

var handleAjaxRemoveElements = function handleAjaxRemoveElements(response) {
  if (response['remove']) {
    $(response['remove']).remove();
  } //end of if

};

var disabledLinks = function disabledLinks() {
  $(document).on('click', 'a.disabled, .disabled a, span[disabled]', function (e) {
    e.preventDefault();
    return;
  });
};

window.initDatePicker = function () {
  $('.date-picker').flatpickr({
    dateFormat: 'Y-m-d',
    disableMobile: "true",
    locale: "ar",
    position: 'top right' // onChange: function (selectedDates, dateStr, instance) {
    //
    //     alert('wel');
    //
    // },

  });
  $('.date-range-picker').each(function () {
    var defaultFromDate = $(this).data('default-from-date');
    var defaultToDate = $(this).data('default-to-date');
    $(this).flatpickr({
      mode: "range",
      locale: "ar",
      "dateFormat": "Y/m/d",
      defaultDate: [defaultFromDate !== null && defaultFromDate !== void 0 ? defaultFromDate : '', defaultToDate !== null && defaultToDate !== void 0 ? defaultToDate : ''],
      onClose: function onClose(selectedDates, dateStr, instance) {
        var fromDate = [selectedDates[0].getFullYear(), selectedDates[0].getMonth() + 1, selectedDates[0].getDate()].join('/');
        var toDate = [selectedDates[1].getFullYear(), selectedDates[1].getMonth() + 1, selectedDates[1].getDate()].join('/');
        $('#from-date').val(fromDate).trigger('change');
        $('#to-date').val(toDate).trigger('change'); // $('.datatable').DataTable().ajax.reload();
      }
    });
  });
  $('.time-picker').each(function () {
    $(this).flatpickr({
      enableTime: true,
      noCalendar: true,
      time_24hr: false,
      locale: "ar",
      position: 'top right'
    });
  });
  $('.date-time-picker').each(function () {
    $(this).flatpickr({
      enableTime: true,
      dateFormat: "Y-m-d H:i K",
      locale: "ar",
      position: 'top right'
    });
  }); // $(".hijri-date-picker").hijriDatePicker({
  //     locale: "ar-sa",
  //     format: "YYYY-MM-DD",
  //     hijriFormat: "iYYYY-iMM-iDD",
  //     dayViewHeaderFormat: "MMMM YYYY",
  //     hijriDayViewHeaderFormat: "iMMMM iYYYY",
  //     showSwitcher: true,
  //     allowInputToggle: true,
  //     useCurrent: true,
  //     isRTL: true,
  //     viewMode: 'days',
  //     keepOpen: true,
  //     hijri: false,
  //     debug: true,
  //     // showClear: true,
  //     showTodayButton: true,
  //     minDate: new Date(),
  //     // showClose: true,
  //
  // });
};

var initGalleryImages = function initGalleryImages() {
  $('.gallery-images').each(function () {
    // the containers for all your galleries
    $(this).magnificPopup({
      delegate: 'a',
      // child items selector, by clicking on it popup will open
      type: 'image',
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0, 1] // Will preload 0 - before current, and 1 after the current image

      }
    });
  });
};

var dataTableRecordSelect = function dataTableRecordSelect() {
  //select all functionality
  $(document).on('change', '.record__select', function () {
    getSelectedRecords();
  }); // used to select all records

  $(document).on('change', '#record__select-all', function () {
    $('.record__select').prop('checked', this.checked);
    getSelectedRecords();
  });

  var getSelectedRecords = function getSelectedRecords() {
    var recordIds = [];
    $.each($(".record__select:checked"), function () {
      recordIds.push($(this).val());
    });
    $('#record-ids').val(JSON.stringify(recordIds));
    recordIds.length > 0 ? $('#bulk-delete').attr('disabled', false) : $('#bulk-delete').attr('disabled', true);
  };
};

var showImageUnderFileExplorer = function showImageUnderFileExplorer() {
  $(document).on('change', '.load-image', function (e) {
    var that = $(this);
    var reader = new FileReader();

    reader.onload = function () {
      that.parent().find('.loaded-image').attr('src', reader.result);
      that.parent().find('.loaded-image').css('display', 'block');
    };

    reader.readAsDataURL(e.target.files[0]);
  });
};

var toggleActive = function toggleActive() {
  $(document).on('change', '.toggle-active', function () {
    var url = $(this).data('url');
    $.ajax({
      url: url,
      method: 'PUT',
      cache: false,
      success: function success(data) {
        new Noty({
          type: 'warning',
          layout: 'topRight',
          text: data.message,
          timeout: 2000,
          killer: true
        }).show();
      }
    }); //end of ajax call
  });
}; // let checkFieldLanguage = () => {
//
//     $(document).on("input", 'input[type="text"]', function () {
//
//         var ranges = [ // EMOJIS RANGE
//             '[\u2700-\u27BF]',
//             '[\uE000-\uF8FF]',
//             '\uD83C[\uDC00-\uDFFF]',
//             '\uD83D[\uDC00-\uDFFF]',
//             '[\u2011-\u26FF]',
//             '\uD83E[\uDD10-\uDDFF]'
//         ];
//
//         let str = $(this).val();
//
//         str = str.replace(new RegExp(ranges.join('|'), 'g'), '');
//
//         if (str == null || str.trim() == '') {
//             str = str.trim()
//         }
//
//         $(this).val(str);
//
//     });
//
//     $(document).on('keyup', '.select2-search__field', function () {
//
//         let str = $(this).val();
//
//         str = str.replace(/([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g, "").trim();
//         // str = str.replace(/[^\u0600-\u06FF_ , a-z , A-Z , 0-9]+$/g, "");
//         $(this).val(str);
//
//     });
//
//     $(document).on(
//         'input[name="first_name"], ' +
//         'input[name="last_name"], ',
//         function () {
//
//             let regex = /[!@#$%^&*()_+\-={}[\]\\|:;"'<>,.?\,0-9]/g; //prevent this regex
//             let str = $(this).val();
//             str = str.replace(regex, "");
//
//             if (str.isEmpty || str === " ") {
//                 str = str.trim();
//             }//end of if
//
//             $(this).val(str);
//         });
//
//     $(document).on(
//         "input",
//         'input[data-error-name="ar.title"], ' +
//         'input[data-error-name="ar.subtitle"], ' +
//         'input[data-error-name="ar.name"], ' +
//         'textarea[data-error-name="ar.description"], ' +
//         'textarea[data-error-name="ar.short_description"], ' +
//         'input[data-error-name="ar.address"]',
//         function () {
//
//             let regex = /[a-z,A-Z]/g; //prevent this regex
//             let str = $(this).val();
//             str = str.replace(regex, "");
//
//             if (str.isEmpty || str === " ") {
//                 str = str.trim();
//             }//end of if
//
//             $(this).val(str);
//
//         }
//     );
//
//     $(document).on(
//         "input",
//         'input[data-error-name="en.title"], ' +
//         'input[data-error-name="en.subtitle"], ' +
//         'input[data-error-name="en.name"],' +
//         'textarea[data-error-name="en.description"], ' +
//         'textarea[data-error-name="en.short_description"], ' +
//         'input[data-error-name="en.address"]',
//         function () {
//
//             let regex = /[\u0600-\u06FF_]/g; //prevent this regex
//             let str = $(this).val();
//             str = str.replace(regex, "");
//
//             if (str.isEmpty || str === " ") {
//                 str = str.trim();
//             }//end of if
//
//             $(this).val(str);
//
//         }
//     );
//
// }

/***/ }),

/***/ 0:
/*!********************************************************************************************************************************************************************************************!*\
  !*** multi ./public/admin_assets/custom/js/shared/index.js ./public/admin_assets/custom/js/index.js ./public/admin_assets/custom/js/roles.js ./public/admin_assets/custom/js/languages.js ***!
  \********************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /home/abdeleid/www/vuex/public/admin_assets/custom/js/shared/index.js */"./public/admin_assets/custom/js/shared/index.js");
__webpack_require__(/*! /home/abdeleid/www/vuex/public/admin_assets/custom/js/index.js */"./public/admin_assets/custom/js/index.js");
__webpack_require__(/*! /home/abdeleid/www/vuex/public/admin_assets/custom/js/roles.js */"./public/admin_assets/custom/js/roles.js");
module.exports = __webpack_require__(/*! /home/abdeleid/www/vuex/public/admin_assets/custom/js/languages.js */"./public/admin_assets/custom/js/languages.js");


/***/ })

/******/ });
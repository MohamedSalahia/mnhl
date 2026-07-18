$(function () {

    handleLivewireHooks();

    initAjaxHeader();

    ajaxData();

    initEditor();

    initSelect2();

    initRepeatable();

    initIntlTel();

    initReorderable();

    initDatePicker();

    initJsTree();

    initGalleryImages();

    ajaxModal();

    initDropZone();

    ajaxForm();

    handleAjaxFormSubmitOnChange();

    disabledLinks();

    dataTableRecordSelect();

    showImageUnderFileExplorer()

    // checkFieldLanguage();

    toggleActive();

});//end of document ready

let handleLivewireHooks = () => {

    $(document).on('livewire:navigating', function (event) {

        window.destroySelect2();

        window.destroyDataTable();

    });

    $(document).on('livewire:navigated', (event) => {

        window.initSidebar();

        feather.replace();

        window.initEditor();

        window.initSelect2();

        window.initRepeatable();

        window.initIntlTel();

        window.initReorderable()

        window.initDatePicker();

        window.initJsTree();

        window.initDropZone();

        window.initCkeditor();

        $('input[autofocus]').focus();

        callLivewireNavigatedMethods();

    })

}

let callLivewireNavigatedMethods = () => {

    window.fetchModels();

    window.initEcho();
}

let initAjaxHeader = () => {

    let loginUrl = $('meta[name="login-url"]').attr('content')

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function (xhr, status, error) {

            if (xhr.status == 500) {

                window.handleErrorModal(xhr);

            } else if (xhr.status == 401 || xhr.status == 415 || xhr.status == 419) {

                window.location.href = loginUrl;

            }
        },
        statusCode: {}
    });

}

let ajaxData = () => {

    $(document).on('click', '.ajax-data', function () {

        let loadingHtml = `
              <div style="height: 50vh;" class="d-flex justify-content-center align-items-center">
                  <div class="loader"></div>
              </div>
        `;

        $('.ajax-data').removeClass('active');

        $(this).addClass('active');

        $('#ajax-data-wrapper').empty().append(loadingHtml);

        let url = $(this).data('url');

        $.ajax({
            url: url,
            cache: false,
            success: function (html) {
                $('#ajax-data-wrapper').empty().append(html);

                window.initJsTree();

                window.initSelect2();

                window.fetchModels();

                if (feather) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                }
            },

        });//end of ajax call

    });//end of on click

}

window.fetchModels = () => {


}

window.initIntlTel = () => {

    const allCountries = (window.intlTelInputGlobals && window.intlTelInputGlobals.getCountryData && window.intlTelInputGlobals.getCountryData()) || [];
    const excluded = new Set(['il']);
    const countries = allCountries.filter(c => !excluded.has(c.iso2));
    const prefCodes = ['tr', 'sy', 'sa'];
    const preferred = prefCodes.map(iso2 => countries.find(c => c.iso2 === iso2)).filter(Boolean);
    const rest = countries.filter(c => !prefCodes.includes(c.iso2));
    const defaultCountry = preferred[0] || countries[0];

    $.each($('.intl-tel'), function () {
        const $input = $(this);
        if ($input.data('phone-init')) return;
        $input.data('phone-init', true);
        $input.removeClass('intl-tel');

        const $cc = $input.closest('.form-group').find('.mobile-country-code');
        let selected = defaultCountry;

        const $wrap = $('<div>').css({ display: 'flex', alignItems: 'stretch', direction: 'ltr' });
        $input.css({ flex: '1', minWidth: '0', borderRadius: '4px 0 0 4px', direction: 'ltr' });

        const $picker = $('<div>').css({ position: 'relative', flexShrink: '0' });
        const $btn = $('<button type="button">').css({
            height: '100%', border: '1px solid #d8d6de', borderRadius: '0 4px 4px 0',
            background: '#f8f8f8', padding: '0 10px', cursor: 'pointer',
            display: 'flex', alignItems: 'center', gap: '5px', whiteSpace: 'nowrap',
        });
        const $flag = $('<span class="iti__flag">');
        const $code = $('<span>');
        const $arrow = $('<span>▾</span>').css({ opacity: 0.5, fontSize: '10px' });
        $btn.append($flag, $code, $arrow);

        const $menu = $('<div>').css({
            display: 'none', position: 'absolute', zIndex: 9999,
            background: '#fff', border: '1px solid #ccc', borderRadius: '4px',
            boxShadow: '0 3px 12px rgba(0,0,0,.15)', width: '260px',
            right: '0', top: '100%', marginTop: '2px',
        });
        const $srch = $('<input type="text" autocomplete="off">').attr('placeholder', 'ابحث...').css({
            width: '100%', boxSizing: 'border-box', padding: '8px 10px',
            border: '0', borderBottom: '1px solid #eee', outline: 'none',
            fontSize: '13px', direction: 'ltr', borderRadius: '4px 4px 0 0',
        });
        const $list = $('<ul>').css({
            listStyle: 'none', padding: '4px 0', margin: '0',
            maxHeight: '220px', overflowY: 'auto',
        });
        $menu.append($srch, $list);

        const setSelected = (c) => {
            selected = c;
            $flag.attr('class', 'iti__flag iti__' + c.iso2);
            $code.text('+' + c.dialCode);
            $cc.val(c.dialCode);
        };

        const renderList = (q = '') => {
            $list.empty();
            const low = q.toLowerCase().trim();
            const addCountry = (c) => {
                if (low && !c.name.toLowerCase().includes(low) && !('+' + c.dialCode).includes(low)) return;
                const $li = $('<li>').css({
                    display: 'flex', alignItems: 'center', gap: '8px',
                    padding: '6px 12px', cursor: 'pointer', fontSize: '13px',
                });
                $li.append(
                    $('<span>').addClass('iti__flag iti__' + c.iso2),
                    $('<span>').text(c.name).css({ flex: 1 }),
                    $('<span>').text('+' + c.dialCode).css({ color: '#999' })
                );
                $li.on('mouseenter', function () { $(this).css('background', '#f0f0f0'); });
                $li.on('mouseleave', function () { $(this).css('background', ''); });
                $li.on('mousedown', function (e) {
                    e.preventDefault();
                    setSelected(c);
                    $menu.hide();
                    $input.focus();
                });
                $list.append($li);
            };
            if (!low) {
                preferred.forEach(addCountry);
                $list.append($('<li>').css({ borderTop: '1px solid #eee', margin: '4px 0', padding: '0', height: '0' }));
                rest.forEach(addCountry);
            } else {
                [...preferred, ...rest].forEach(addCountry);
            }
        };

        $srch.on('mousedown', function (e) { e.stopPropagation(); });
        $srch.on('input', function () { renderList($(this).val()); });

        $btn.on('click', function (e) {
            e.stopPropagation();
            if ($menu.is(':visible')) {
                $menu.hide();
            } else {
                $('.phone-picker-menu').not($menu).hide();
                $menu.addClass('phone-picker-menu').show();
                renderList();
                $srch.val('').trigger('focus');
            }
        });

        $(document).on('click.phonePicker', function () { $menu.hide(); });

        $picker.append($btn, $menu);
        $input.before($wrap);
        $wrap.append($input, $picker);

        const existingVal = $input.val();
        if (existingVal && existingVal.startsWith('+')) {
            const match = countries.find(c => existingVal.startsWith('+' + c.dialCode));
            if (match) {
                $input.val(existingVal.slice(match.dialCode.length + 1));
                setSelected(match);
            } else {
                setSelected(defaultCountry);
            }
        } else {
            setSelected(defaultCountry);
        }

    });

};

window.initRepeatable = () => {

    $('.repeatable').each(function () {

        let wrapper = $(this)

        let singleRow = $(this).data('single-row');

        let addBtn = $(this).data('add-btn');

        let deleteBtn = $(this).data('delete-btn');

        let fieldName = $(this).data('field-name');

        let incrementNumberContainer = $(this).data('increment-number-container');

        $(document).on('click.repeatable', addBtn, function () {

            window.destroySelect2();

            let html = $(`${singleRow}:first`).clone();

            // Clear the new row's inputs immediately after cloning and before appending
            // This prevents duplicate name attributes from affecting existing rows
            let $clonedRow = $(html);
            // Clear all inputs (including hidden ID fields for new rows)
            $clonedRow.find('input').val('');
            $clonedRow.find('select').val('').trigger('change');
            $clonedRow.find('textarea').val('');

            wrapper.append($clonedRow);

            // Adjust accordion collapse IDs and data-target attributes
            adjustAccordionIds(`${singleRow}:last`, singleRow);

            // Handle nested repeatables - remove all rows except first and disable delete button
            handleNestedRepeatables(`${singleRow}:last`);

            if (fieldName) {
                reindex(singleRow, fieldName);
            }

            // Set default values dynamically from data-default-* attributes
            $.each(wrapper.data(), function (key, value) {

                if (key.startsWith('default')) {

                    let fieldName = key.replace('default', '').toLowerCase();

                    $(`${singleRow}:last`).find(`input[name*="[${fieldName}]"]`).val(value);

                    $(`${singleRow}:last`).find(`select[name*="[${fieldName}]"]`).val(value).trigger('change');

                    $(`${singleRow}:last`).find(`textarea[name*="[${fieldName}]"]`).val(value);

                }
            });

            toggleDeleteBtn(deleteBtn);

            handleIncrementNumberContainer(incrementNumberContainer);

            window.initSelect2();

            // Re-initialize reorderable if the wrapper is also reorderable
            if (wrapper.hasClass('reorderable')) {
                // Destroy existing sortable if it exists
                if (wrapper.hasClass('ui-sortable')) {
                    wrapper.sortable('destroy');
                }
                // Re-initialize reorderable
                window.initReorderable();
            }

        });

        if (deleteBtn) {

            $(document).on('click.repeatable', deleteBtn, function () {

                let wrapper = $(this).closest('.repeatable');

                $(this).closest(singleRow).remove();

                if (fieldName) {
                    reindex(singleRow, fieldName);
                }

                toggleDeleteBtn(deleteBtn);

                // Re-initialize reorderable if the wrapper is also reorderable
                if (wrapper.hasClass('reorderable')) {
                    // Destroy existing sortable if it exists
                    if (wrapper.hasClass('ui-sortable')) {
                        wrapper.sortable('destroy');
                    }
                    // Re-initialize reorderable
                    window.initReorderable();
                }
            });
        }

    });

    let adjustAccordionIds = (rowSelector, baseSelector) => {

        let $row = $(rowSelector);

        let allRows = $(baseSelector);

        let rowIndex = allRows.index($row);

        $row.find('[data-toggle="collapse"], [data-bs-toggle="collapse"]').each(function () {

            let $toggleBtn = $(this);

            let currentTarget = $toggleBtn.attr('data-target') || $toggleBtn.attr('data-bs-target') || '';

            if (!currentTarget) return;

            // Extract current ID from target (e.g., #collapseItem0 -> collapseItem0)
            let currentTargetId = currentTarget.replace('#', '');

            // Extract base name from current ID (e.g., collapseItem0 -> collapseItem)
            let baseName = currentTargetId.replace(/\d+$/, '');

            // Generate new IDs
            // If base name contains "collapse", replace it with "heading", otherwise prepend "heading"
            let newHeadingId = baseName.includes('collapse')
                ? baseName.replace('collapse', 'heading') + rowIndex
                : 'heading' + baseName.charAt(0).toUpperCase() + baseName.slice(1) + rowIndex;
            let newCollapseId = baseName + rowIndex;
            let newTarget = '#' + newCollapseId;

            // Update toggle button attributes
            if ($toggleBtn.attr('data-target')) {
                $toggleBtn.attr('data-target', newTarget);
            }
            if ($toggleBtn.attr('data-bs-target')) {
                $toggleBtn.attr('data-bs-target', newTarget);
            }

            // Update toggle button id if it exists
            let currentHeadingId = $toggleBtn.attr('id');
            if (currentHeadingId) {
                $toggleBtn.attr('id', newHeadingId);
            }

            // Update aria-controls
            $toggleBtn.attr('aria-controls', newCollapseId);

            // Find and update the collapse div
            let $collapseDiv = $row.find('#' + currentTargetId);

            if ($collapseDiv.length) {
                $collapseDiv.attr('id', newCollapseId);
                $collapseDiv.attr('aria-labelledby', newHeadingId);
            }

        });

    }

    let handleNestedRepeatables = (rowSelector) => {

        let $row = $(rowSelector);

        // Find all nested repeatables inside the cloned row
        $row.find('.repeatable').each(function () {

            let $nestedRepeatable = $(this);

            // Get the single-row selector for this nested repeatable
            let nestedSingleRow = $nestedRepeatable.data('single-row');

            if (!nestedSingleRow) return;

            // Get all rows in the nested repeatable
            let $nestedRows = $nestedRepeatable.find(nestedSingleRow);

            // Remove all rows except the first one
            if ($nestedRows.length > 1) {
                $nestedRows.slice(1).remove();
            }

            // Get the delete button selector for this nested repeatable
            let nestedDeleteBtn = $nestedRepeatable.data('delete-btn');

            if (nestedDeleteBtn) {
                // Disable all delete buttons in the nested repeatable
                $nestedRepeatable.find(nestedDeleteBtn).attr('disabled', true);
            }

        });

    }

    let reindex = (singleRow, fieldName) => {

        $(singleRow).each(function (index) {

            $(this).find('input, select, textarea').each(function () {

                let name = $(this).attr('name');

                if (name && name.includes(fieldName + '[')) {

                    let newName = name.replace(
                        new RegExp(fieldName + '\\[\\d+\\]'),
                        fieldName + '[' + index + ']'
                    );

                    $(this).attr('name', newName);
                }

                // Update data-error-name attribute for array fields
                let errorName = $(this).attr('data-error-name');

                if (errorName && errorName.includes(fieldName + '.')) {

                    let newErrorName = errorName.replace(
                        new RegExp(fieldName + '\\.\\d+'),
                        fieldName + '.' + index
                    );

                    $(this).attr('data-error-name', newErrorName);
                }
            });
        });
    }

    let handleIncrementNumberContainer = (incrementNumberContainer) => {

        $(incrementNumberContainer).each(function (index) {
            $(this).text(index + 1);
        });

    }

    let toggleDeleteBtn = (deleteBtn) => {

        if ($(deleteBtn).length == 1) {

            $(deleteBtn).attr('disabled', true);

        } else {

            $(deleteBtn).attr('disabled', false);

        }//end of else

    }
}

window.initSelect2 = (parent = 'body') => {

    //select 2
    $(`${parent} .select2`).each(function () {

        let placeholder = $(this).attr('placeholder') || $(this).find('option:first').text();

        let width = $(this).data('width');

        let select2Options = {
            'placeholder': placeholder,
            'dropdownParent': $(parent),
            'width': width ?? '100%',
            // 'allowClear': !isMultiple, // Don't show clear for multiple
            // 'closeOnSelect': isMultiple ? false : true,
            // 'allowHtml': true,
            'language': {
                noResults: function () {
                    return $('meta[name="no-result-found"]').attr('content');
                }
            },
            escapeMarkup: function (m) {
                return m;
            },
            templateResult: function (data) {

                if (!data.id || data.id === '') {
                    return null;
                }

                return data.text;
            }
        };

        let select2 = $(this).select2(select2Options);

    })

    $(`${parent} .select2-ajax`).each(function () {

        let searchUrl = $(this).attr('data-search-url');

        let placeholder = $(this).attr('placeholder') || $(this).find('option:first').text();

        let loadingText = $(this).attr('data-loading-text');

        let select2AjaxOptions = {
            'placeholder': placeholder,
            // 'allowClear': !isMultiple, // Don't show clear for multiple
            'dropdownParent': $(parent),
            'width': '100%',
            // 'closeOnSelect': isMultiple ? false : true,
            'ajax': {
                url: searchUrl,
                delay: 250,
                dataType: 'json',
                data: function (params) {

                    return {
                        'search': params.term,
                    };

                },
                processResults: function (data, params) {
                    return {
                        results: data.results,
                    };
                },
                cache: true
            },
            'minimumInputLength': 0,
            'templateSelection': (data) => {

                if (!data.id || data.id === '' || data.id === null) {
                    return placeholder;
                }
                return data.text || data.id;

            },
            'templateResult': (data) => {

                let imageMarkup = '';

                if (data.image) {
                    imageMarkup = `
                        <div class='select2-result-product__image' style='display: inline-block; margin-right: 10px;'>
                            <img src='${data.image}' alt='${data.text}'
                                 onerror="this.onerror=null;this.src='/path/to/default-image.jpg';"
                                 style='width: 30px; height: 30px; border-radius: 10%; object-fit: cover;' />
                        </div>
                    `;
                }

                // Append quantity if it exists
                let textWithQuantity = data.text;

                if (data.quantity) {
                    textWithQuantity += ` (Qty: ${data.quantity})`;
                }

                let markup = `
                    <div class='select2-result-product clearfix'>
                        ${imageMarkup}
                        <div class='select2-result-product__meta' style='display: inline-block; vertical-align: top;'>
                            <div class='select2-result-product__title'>${textWithQuantity}</div>
                        </div>
                    </div>
                `;

                return markup;
            },


            'escapeMarkup': function (markup) {
                return markup; // let our custom formatter work
            },
            'language': {
                'inputTooShort': function (args) {
                    return $('meta[name="type-at-least-one-character"]').attr('content');
                },
                'searching': function () {
                    return $('meta[name="loading"]').attr('content');
                }
            },
            'createTag': function (params) {
                return {
                    id: params.term,
                    text: params.term,
                };
            },
        };

        let select2Ajax = $(this).select2(select2AjaxOptions);


    })
}

window.initEditor = () => {

    // ✅ Check if Summernote is available
    if (typeof $.fn.summernote !== 'function') {
        console.warn('⚠️ Summernote is not loaded. Make sure you included its JS and CSS files.');
        return;
    }

    $('.editor').summernote({
        height: 100,
        lang: 'ar-AR',
        toolbar: [
            ['style', ['style']], // Add text formatting (headings, normal text)
            ['fontsize', ['fontsize']], // Add font size selection
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']], // Keep font styles but remove the font dropdown
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        fontNames: ['simplified-arabic'],
        fontNamesIgnoreCheck: ['simplified-arabic'],
        callbacks: {
            onPaste: function (e) {
                let clipboardData = (e.originalEvent || e).clipboardData;
                let bufferText = clipboardData.getData('text/html') || clipboardData.getData('text/plain');

                // Check if the pasted content is a URL
                function isValidURL(str) {
                    const pattern = new RegExp(
                        '^(https?:\\/\\/)?' + // protocol
                        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                        '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                        '(\\#[-a-z\\d_]*)?$', // fragment locator
                        'i'
                    );
                    return pattern.test(str);
                }

                // If it's a plain text URL
                let plainText = clipboardData.getData('text/plain').trim();
                if (isValidURL(plainText)) {
                    e.preventDefault();

                    // Create URL preview card
                    createUrlPreview(plainText);
                    return;
                }

                // Decode HTML entities manually
                function decodeHtmlEntities(str) {
                    let textArea = document.createElement('textarea');
                    textArea.innerHTML = str;
                    return textArea.value;
                }

                let decodedHTML = decodeHtmlEntities(bufferText);

                // Use another temp div to parse the decoded HTML properly
                let parserDiv = document.createElement('div');
                parserDiv.innerHTML = decodedHTML;

                // Check for Twitter blockquote embeds
                let blockquotes = parserDiv.querySelectorAll('blockquote.twitter-tweet');

                if (blockquotes.length > 0) {
                    e.preventDefault();

                    for (let blockquote of blockquotes) {
                        let tweetCode = blockquote.outerHTML;

                        $('.editor').summernote('pasteHTML', tweetCode);
                    }

                    // Ensure Twitter script is added and reload widgets
                    setTimeout(() => {
                        if (!window.twttr) {
                            let twitterScript = document.createElement('script');
                            twitterScript.src = "https://platform.twitter.com/widgets.js";
                            twitterScript.async = true;
                            twitterScript.charset = "utf-8";
                            document.body.appendChild(twitterScript);
                        } else {
                            window.twttr.widgets.load(); // Reload Twitter embeds
                        }
                    }, 500);

                    return;
                }

                // Check for iframes (e.g., YouTube embeds)
                let iframes = parserDiv.getElementsByTagName('iframe');

                if (iframes.length > 0) {
                    e.preventDefault();

                    for (let iframe of iframes) {
                        let iframeCode = iframe.outerHTML;

                        // Insert YouTube iframe embed properly
                        $('.editor').summernote('pasteHTML', iframeCode);
                    }

                    return;
                }
            }
        }
    });

    let createUrlPreview = (url) => {

        if (!/^https?:\/\//i.test(url)) {
            url = 'https://' + url;
        }

        let loadingPreviewText = $('meta[name="loading-preview"]').attr('content');

        let loadingHtml = '<div class="url-preview-loading" style="padding: 15px; border: 1px solid #e0e0e0; border-radius: 4px; margin: 10px 0; background-color: #f9f9f9; text-align: center;">' + loadingPreviewText + ': ' + url + '...</div>';

        $('.editor').summernote('pasteHTML', loadingHtml);

        $.ajax({
            url: '/url_metadata',
            type: 'POST',
            data: {
                url: url,
                _token: $('meta[name="csrf-token"]').attr('content') // Laravel CSRF token
            },
            success: function (data) {
                // Replace loading indicator with actual preview
                $('.editor').summernote('code', $('.editor').summernote('code').replace(loadingHtml, createPreviewCard(url, data)));
            },
            error: function (error) {
                console.error('Error fetching URL:', error);
                // If metadata fetch fails, create a basic preview
                $('.editor').summernote('code', $('.editor').summernote('code').replace(loadingHtml, createBasicPreviewCard(url)));
            }
        });
    }


    let createPreviewCard = (url, metadata) => {
        return `
        <div class="url-preview-card" data-original-url="${url}" style="border: 1px solid #e0e0e0; border-radius: 4px; margin: 10px 0; overflow: hidden;  position: relative;">
            <div class="url-preview-content" style="display: flex; flex-direction: column; align-items: center;">
                ${metadata.image
            ? `<div class="url-preview-image" style="width: 100%; overflow: hidden;">
                        <img src="${metadata.image}" alt="${metadata.title || 'Link preview'}" style="width: 100%; height: 100%; object-position: center;">
                    </div>`
            : ''}
                <div class="url-preview-text" style="padding: 15px; text-align: center;">
                    <div class="url-preview-title" style="font-weight: bold; font-size: 16px; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">${metadata.title || url}</div>
                    ${metadata.description
            ? `<div class="url-preview-description" style="color: #666; margin-bottom: 10px; font-size: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">${metadata.description}</div>`
            : ''}
                    <div class="url-preview-domain" style="color: #888; font-size: 12px;">${new URL(url).hostname}</div>
                </div>
            </div>
            <a href="${url}" target="_blank" rel="noopener noreferrer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>
            <div class="url-preview-close" style="position: absolute; top: 5px; left: 5px; width: 20px; height: 20px; background-color: rgba(255,255,255,0.8); border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; z-index: 2; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                <span style="font-size: 14px; line-height: 1; font-weight: bold; color: #666;">×</span>
            </div>
        </div>
    `;
    }

    let createBasicPreviewCard = (url) => {
        let domain = new URL(url).hostname;
        return `
        <div class="url-preview-card" data-original-url="${url}" style="border: 1px solid #e0e0e0; border-radius: 4px; margin: 10px 0; overflow: hidden; position: relative;">
            <div class="url-preview-content" style="padding: 15px; text-align: center;">
                <div class="url-preview-title" style="font-weight: bold; font-size: 16px; margin-bottom: 5px;">${url}</div>
                <div class="url-preview-domain" style="color: #888; font-size: 12px;">${domain}</div>
            </div>
            <a href="${url}" target="_blank" rel="noopener noreferrer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>
            <div class="url-preview-close" style="position: absolute; top: 5px; left: 5px; width: 20px; height: 20px; background-color: rgba(255,255,255,0.8); border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; z-index: 2; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                <span style="font-size: 14px; line-height: 1; font-weight: bold; color: #666;">×</span>
            </div>
        </div>
    `;
    }

}

// Helper function to reindex repeatable fields
let reindexRepeatableFields = (wrapper, singleRow, fieldName) => {

    $(singleRow).each(function (index) {

        $(this).find('input, select, textarea').each(function () {

            let name = $(this).attr('name');

            if (name && name.includes(fieldName + '[')) {
                let newName = name.replace(
                    new RegExp(fieldName + '\\[\\d+\\]'),
                    fieldName + '[' + index + ']'
                );
                $(this).attr('name', newName);
            }
        });
    });
}

// Helper function to toggle delete button state
let toggleDeleteBtnForRepeatable = (wrapper, deleteBtn) => {
    let deleteButtons = wrapper.find(deleteBtn);
    if (deleteButtons.length == 1) {
        deleteButtons.attr('disabled', true);
    } else {
        deleteButtons.attr('disabled', false);
    }
}

window.initReorderable = () => {

    $('.reorderable').each(function () {

        let that = $(this);

        // Check if sortable is already initialized
        if (that.hasClass('ui-sortable') || that.data('ui-sortable')) {
            // Destroy existing sortable before re-initializing
            if (that.sortable('instance')) {
                that.sortable('destroy');
            }
        }

        let reorderUrl = that.data('reorder-url');

        let reorderElement = that.data('reorder-element') || '> *';

        // Check if this is also a repeatable container
        let isRepeatable = that.hasClass('repeatable');
        let singleRow = isRepeatable ? that.data('single-row') : null;
        let fieldName = isRepeatable ? that.data('field-name') : null;
        let deleteBtn = isRepeatable ? that.data('delete-btn') : null;

        // Check if drag handle exists
        let hasDragHandle = that.find('.drag-handle').length > 0;

        let sortableOptions = {
            items: reorderElement,
            cursor: "move",
            tolerance: "intersect",
            // containment: "parent",
            helper: 'clone',
            opacity: 0.5,
            revert: 50,
            forceHelperSize: true,
            placeholder: "sortable-placeholder",
            forcePlaceholderSize: true,
        };

        // Only use handle if drag handle exists
        if (hasDragHandle) {
            sortableOptions.handle = '.drag-handle';
        }

        sortableOptions.update = function (event, ui) {

            // Reindex field names if this is a repeatable container
            if (isRepeatable && singleRow && fieldName) {
                reindexRepeatableFields(that, singleRow, fieldName);

                // Toggle delete button state
                if (deleteBtn) {
                    toggleDeleteBtnForRepeatable(that, deleteBtn);
                }

                // Replace feather icons after reordering
                if (feather) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                }
            }

            if (reorderUrl) {

                let ids = $(this).sortable('toArray');

                let data = {
                    'ids': ids
                }

                $.ajax({
                    url: reorderUrl,
                    method: 'post',
                    data: data,
                    success: function (data) {

                        new Noty({
                            layout: 'topRight',
                            text: data.success_message,
                            timeout: 2000,
                            killer: true
                        }).show();

                    },
                });

            }

        };

        that.sortable(sortableOptions).disableSelection();

    });

}

window.initCkeditor = () => {

    window.editors = {};

    document.querySelectorAll('.ckeditor').forEach((node, index) => {
        ClassicEditor
            .create(node, {
                language: 'ar',
                mediaEmbed: {
                    previewsInData: true,
                },
                // simpleUpload: {
                //     uploadUrl: $('meta[name="ckeditor-upload-url"]').attr('content'),
                //     headers: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                //     }
                // },
                fontSize: {
                    options: [
                        9,
                        11,
                        13,
                        16,
                        17,
                        19,
                        21
                    ]
                },

            })
            .then(newEditor => {
                window.editors[index] = newEditor
            });
    });

}

window.initDropZone = () => {

    let initReorderable = (that) => {

        that.closest('.form-group').find('.assets-wrapper').sortable({
            items: "> .single-asset",
            cursor: "move",
            tolerance: 'intersect',
            // containment: "parent",
            // helper: 'clone',
            // opacity: 0.5,
            // revert: 50,
            forceHelperSize: true,

            update: function (event, ui) {

                let url = that.data('reorder-url');

                let ids = $(this).sortable('toArray');

                let data = {
                    'ids': ids
                }

                if (url) {

                    $.ajax({
                        url: url,
                        method: 'post',
                        data: data,
                        success: function (data) {

                            new Noty({
                                layout: 'topRight',
                                text: data.message,
                                timeout: 2000,
                                killer: true
                            }).show();

                        },
                    });

                }//end of if
            }

        }).disableSelection();

    }

    let setAssetIds = (that) => {

        let assetIds = [];

        that.closest('.form-group').find('.single-asset').each(function () {

            assetIds.push($(this).attr('id'));

        })

        let inputField = that.data('input-field');

        that.closest('.form-group').find(`${inputField}`).val(JSON.stringify(assetIds))

    }

    $('.dropzone').each(function () {

        if (this.dropzone) {
            return;
        }

        let that = $(this);

        if (that.hasClass('reorderable')) {
            initReorderable(that);
        }

        let defaultMessage = '<i class="fas fa-cloud-upload-alt fa-2x"></i><br>' + (that.data('default-message') ?? $('meta[name="dropzone-text"]').attr('content'));

        let extraParams = JSON.parse(that.attr('data-extra-params') || '{}');

        let dropZone = new Dropzone(this, {
            url: that.attr('data-url'),
            dictDefaultMessage: defaultMessage,
            params: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                ...extraParams
            },
            complete: function (file) {
                dropZone.removeFile(file)
            },
            success: function (file, asset) {

                let lang = $(location).attr('href').split("/")[3];

                let deleteText = $('meta[name="delete-text"]').attr('content');

                let assetColClass = that.data('asset-col-class') ?? 'col-md-12';

                let editAssetTitleText = $('meta[name="edit-asset-title"]').attr('content');

                let previewFile = asset.thumbnail ?? asset.file;

                console.log(asset);

                let objectFit = asset.type === 'image' ? 'cover' : 'contain';

                let preview = `
                     <div style="position: relative; width: 100%; height: 150px;">
                         <img src="${previewFile}" class="img-fluid" style="width: 100%; height: 150px; object-fit: ${objectFit}; border-radius: 6px;"/>

                        ${asset.type === 'video'
                    ? `
                             <div style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);background: rgba(0,0,0,0.5);border-radius: 50%;padding: 10px;">
                                   <i class="fas fa-play" style="color: white; font-size: 24px;"></i>
                             </div>
                        `
                    : ''
                }
                     </div>
                `;

                let html = `
                    <div class="${assetColClass} single-asset" style="margin: 10px 0;" id="${asset.id}">

                        <div class="asset-container" style="border: 1px solid #D8D6DE; border-radius: 6px; padding: 10px;">

                            <div class="row">
                                <div class="col-md-12">
                                    ${preview}
                                </div>

                                <div class="col-md-12 d-flex align-self-center justify-content-end mt-1">
                                    <!--<button class="btn btn-primary btn-sm mr-1 ajax-modal" data-url="${asset.edit_title_url}" data-modal-title="${editAssetTitleText}"><i data-feather="edit"></i></button>-->
                                    <button class="btn btn-danger btn-sm delete-asset-btn btn-block" data-delete-asset-id="${asset.id}" data-delete-url="${asset.delete_url}"><i data-feather="trash"></i></button>
                                </div>

                            </div>

                        </div>
                    </div>

                `;

                that.closest('.form-group').find('.assets-wrapper').append(html);

                setAssetIds(that);

                if (that.hasClass('reorderable')) {

                    initReorderable(that);

                }//end of if

                feather.replace();

            },
            error: function (file, error) {

                let errors = error['errors'];

                $.each(errors, function (key, val) {

                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: val,
                        timeout: 2000,
                        killer: true
                    }).show();

                });
            }
        });

    });

    $(document).on('click', '.delete-asset-btn', function (e) {
        e.preventDefault();

        let dropzone = $(this).closest('.form-group').find('.dropzone');

        $('#' + $(this).data('delete-asset-id')).remove();

        setAssetIds(dropzone);

        let url = $(this).data('delete-url');

        $.ajax({
            url: url,
            method: 'DELETE',
            processData: false,
            contentType: false,
            //cache: false,
            success: function () {

            },

        });//end of ajax call

    });

}

let ajaxModal = () => {

    $(document).on('hide.bs.modal', '#ajax-modal', function (e) {

        $('#ajax-modal .modal-body').empty();

        window.destroySelect2();

        window.initSelect2();

    });

    $(document).on('click', '.ajax-modal', function (e) {
        e.preventDefault();

        let loading = `
            <div class="loading-container absolute-centered">
                <div class="loader sm"></div>
            </div>
        `;

        let url = $(this).data('url');
        let modalTitle = $(this).data('modal-title');
        let modalBodyClass = $(this).data('modal-body-class')
        let modalSizeClass = $(this).data('modal-size-class') ?? 'modal-lg'

        $('#ajax-modal').modal('show');

        $('#ajax-modal .modal-body').remove();

        $('#ajax-modal .modal-content').append('<div class="modal-body relative"></div>')

        $('#ajax-modal .modal-body').addClass(modalBodyClass);

        $('#ajax-modal .modal-body').empty().append(loading);

        $('#ajax-modal .modal-title').text(modalTitle);

        $('#ajax-modal .modal-dialog').removeAttr('class').attr('class', 'modal-dialog modal-dialog-centered ' + modalSizeClass);

        $.ajax({
            url: url,
            //processData: false,
            //contentType: false,
            cache: false,
            success: function (response) {

                $('#ajax-modal .modal-body').empty().append(response['view']);

                window.initRepeatable();

                window.initSelect2();

                window.initReorderable();

                if (feather) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                }
            },

        });//end of ajax call

    });

}

let ajaxForm = () => {

    $(document).on('submit', '.ajax-form', function (e) {
        e.preventDefault();

        let that = $(this);

        let loading = $('meta[name="loading"]').attr('content');

        let submitButton = that.find('button[type="submit"]');

        let submitButtonHtml = submitButton.html();

        submitButton.attr('disabled', true);

        that.find('button[type="submit"]').html(loading);

        that.removeClass('active');

        that.addClass('active');

        that.find('.invalid-feedback').remove();

        // Remove spaces from mobile number inputs before submission
        that.find('.intl-tel').each(function() {
            let input = $(this);
            let currentValue = input.val();
            if (currentValue) {
                // Remove all spaces from the mobile number
                input.val(currentValue.replace(/\s+/g, ''));
            }
        });

        let url = $(this).attr('action');
        let data = new FormData(this);

        $('.ajax-form.active .invalid-feedback').hide();

        $.ajax({
            url: url,
            data: data,
            method: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {

                hideModals();

                window.initJsTree();

                handleAjaxRefreshDataTable();

                handleAjaxRedirects(response, submitButtonHtml);

                handleAjaxRemoveElements(response);

                if (that.hasClass('empty-form')) {

                    that.find('input:not([type=hidden]), textarea, select').val('');

                }//end of if

            },
            error: function (xhr, exception) {

                let loginUrl = $('meta[name="login-url"]').attr('content')

                if (xhr.status == 500) {

                    window.handleErrorModal(xhr);

                } else if (xhr.status == 401 || xhr.status == 415 || xhr.status == 419) {

                    window.location.href = loginUrl;

                } else {

                    handleAjaxErrors(xhr, submitButtonHtml);

                }//end of if

            },
            complete: function () {

                submitButton.attr('disabled', false);

                submitButton.html(submitButtonHtml);
            }
        });//end of ajax call

    })

}

let handleAjaxFormSubmitOnChange = function () {

    $(document).on('change', 'form.ajax-form[data-submit-on-change] input[type="checkbox"]', function () {

        $(this).closest('form').trigger('submit');

    });

}

window.hideModals = () => {
    $(".modal").each(function () {
        $(this).modal("hide");
    });
}

window.handleErrorModal = (xhr) => {

    $('#error-modal').modal('show');

    let html = '';

    if (xhr.responseJSON) {

        let error = xhr.responseJSON;

        html += `
            <h3> ${error.message}</h3>
            <p><strong>Exception: </strong>${error.exception}</p>
            <p><strong>file: </strong>${error.file}</p>
            <p><strong>line: </strong>${error.line}</p>
        `

        if (error.trace) {

            html += `<h5>Trace</h5>`;

        }//end of if

        error.trace.forEach((item, index) => {

            html += `
                <div style="margin-bottom: 10px">
                    <p class="mb-0"><strong>class: </strong> ${item.class}</p>
                    <p class="mb-0"><strong>file: </strong>${item.file}</p>
                    <p class="mb-0"><strong>function: </strong>${item.function}</p>
                    <p class="mb-0"><strong>line: </strong>${item.line}</p>
                </div>
            `;
        })

    } else {

        html += xhr.responseText;

    }

    $('#error-modal .modal-body').empty().append(html);

}

let handleAjaxErrors = (xhr, submitButtonHtml) => {

    if (!xhr.responseJSON || !xhr.responseJSON.errors) {

        let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred';

        new Noty({
            layout: 'topRight',
            text: msg,
            type: 'error',
            timeout: 3000,
            killer: true
        }).show();

        return;

    }

    let errors = xhr['responseJSON']['errors'];

    for (const field in xhr['responseJSON']['errors']) {

        $(`.ajax-form.active input[name="${field}"], .ajax-form.active select[name="${field}"], .ajax-form.active textarea[name="${field}"]`)
            .closest('.form-group')
            .append(`<span class="invalid-feedback d-block">${errors[field][0]}</span>`)

        $(`.ajax-form.active input[data-error-name="${field}"], .ajax-form.active select[data-error-name="${field}"], .ajax-form.active textarea[data-error-name="${field}"]`)
            .closest('.form-group')
            .append(`<span class="invalid-feedback d-block">${errors[field][0]}</span>`);

    }

    $('html, body, .page-data').animate({
        scrollTop: $('.invalid-feedback.d-block').offset().top - 300
    }, 200);

    $('.ajax-form input[type="password"]').val("");

    $('.ajax-form input.empty').val("");

}

let handleAjaxRedirects = (response, submitButtonHtml) => {

    if (response['success_message'] && response['redirect_to']) {

        new Noty({
            layout: 'topRight',
            text: response['success_message'],
            timeout: 2000,
            killer: true
        }).show();

        setTimeout(function () {

            // window.location.href = response['redirect_to'];
            Livewire.navigate(response['redirect_to']);

        }, 100);

    } else if (
        response['redirect_to'] ||
        response['success_message'] ||
        response['replace'] ||
        response['modal_view']
    ) {

        if (response['redirect_to'] && response['refresh'] == true) {

            window.location.href = response['redirect_to'];

        }//end of if

        if (response['redirect_to']) {

            Livewire.navigate(response['redirect_to']);
        }

        if (response['success_message']) {

            new Noty({
                layout: 'topRight',
                text: response['success_message'],
                timeout: 2000,
                killer: true
            }).show();

        }

        if (response['replace']) {

            $(response['replace']).html(response['replace_with']);

        }//end of if

        if (response['modal_view']) {

            if (response['modal-size-class']) {

                $('#ajax-modal .modal-dialog').removeAttr('class').attr('class', 'modal-dialog modal-dialog-centered ' + response['modal-size-class']);

            } //end of if

            $('#ajax-modal .modal-title').text(response['modal_title']);

            $('#ajax-modal .modal-body').empty().append(response['modal_view']);

            $('#ajax-modal').modal('show');

            $('.ajax-form.active button[type="submit"]').html(submitButtonHtml);

            $('.ajax-form.active button[type="submit"]').attr('disabled', false)

        }//end of if

    } else {

        $('.ajax-form.active button[type="submit"]').html(submitButtonHtml);

        $('.ajax-form.active button[type="submit"]').attr('disabled', false)

    }

}

let handleAjaxRefreshDataTable = () => {

    if ($('.datatable').length) {
        $('.datatable').DataTable().ajax.reload();
    }//end of if

}

let handleAjaxRemoveElements = (response) => {

    if (response['remove']) {$(response['remove']).remove();}//end of if

}

let disabledLinks = () => {

    $(document).on('click', 'a.disabled, .disabled a, span[disabled]', function (e) {
        e.preventDefault();

        return
    })

}

window.destroySelect2 = () => {

    $('select').each(function () {

        if ($(this).data('select2') != undefined) {

            $(this).select2('destroy');
        }
    });

}

window.destroyDataTable = () => {

    $('.datatable').DataTable().destroy();

}

window.initDatePicker = () => {

    $('.date-picker').each(function () {

        // Check if flatpickr is already initialized
        if ($(this).data('flatpickr')) {
            return; // Skip if already initialized
        }

        let currentValue = $(this).val();
        let options = {
            dateFormat: 'Y-m-d',
            disableMobile: "true",
            locale: $('html').attr('dir') == 'rtl' ? "ar" : "en",
            position: 'top right',
        };

        // Preserve default value if it exists
        if (currentValue) {
            options['defaultDate'] = currentValue;
        }

        let maxDay = $(this).attr('data-max-day');

        if (maxDay) {

            maxDay === 'now' || maxDay === 'today'
                ? options['maxDate'] = 'today'
                : options['maxDate'] = maxDay;

        }

        let minDay = $(this).attr('data-min-day');

        if (minDay) {

            minDay === 'now' || minDay === 'today'
                ? options['minDate'] = 'today'
                : options['minDate'] = minDay;

        }

        $(this).flatpickr(options);

    });

    $('.date-range-picker').each(function () {

        let defaultFromDate = $(this).data('default-from-date');
        let defaultToDate = $(this).data('default-to-date');

        $(this).flatpickr({
            mode: "range",
            locale: "ar",
            "dateFormat": "Y/m/d",
            defaultDate: [defaultFromDate ?? '', defaultToDate ?? ''],
            onClose: function (selectedDates, dateStr, instance) {

                const fromDate = [selectedDates[0].getFullYear(), selectedDates[0].getMonth() + 1, selectedDates[0].getDate()].join('/');
                const toDate = [selectedDates[1].getFullYear(), selectedDates[1].getMonth() + 1, selectedDates[1].getDate()].join('/');

                $('#from-date').val(fromDate).trigger('change');
                $('#to-date').val(toDate).trigger('change');

                // $('.datatable').DataTable().ajax.reload();

            },
        });

    })

    $('.time-picker').each(function () {

        $(this).flatpickr({
            enableTime: true,
            noCalendar: true,
            time_24hr: false,
            locale: "ar",
            position: 'top right',
        });

    })

    $('.date-time-picker').each(function () {

        $(this).flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i K",
            locale: "ar",
            position: 'top right',
        });

    })

    // $(".hijri-date-picker").hijriDatePicker({
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

}

let initGalleryImages = () => {

    $('.gallery-images').each(function () { // the containers for all your galleries

        $(this).magnificPopup({
            delegate: 'a', // child items selector, by clicking on it popup will open
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            },
        });

    });

}

let dataTableRecordSelect = () => {

    //select all functionality
    $(document).on('change', '.record__select', function () {

        getSelectedRecords();
    });

    // used to select all records
    $(document).on('change', '#record__select-all', function () {

        $('.record__select').prop('checked', this.checked);
        getSelectedRecords();
    });

    let getSelectedRecords = () => {
        var recordIds = [];
        $.each($(".record__select:checked"), function () {
            recordIds.push($(this).val());
        });

        $('#record-ids').val(JSON.stringify(recordIds));

        recordIds.length > 0
            ? $('#bulk-delete').attr('disabled', false)
            : $('#bulk-delete').attr('disabled', true)

    }
}

let showImageUnderFileExplorer = () => {

    $(document).on('change', '.load-image', function (e) {

        var that = $(this);

        let reader = new FileReader();
        reader.onload = function () {
            that.parent().find('.loaded-image').attr('src', reader.result);
            that.parent().find('.loaded-image').css('display', 'block');
        }
        reader.readAsDataURL(e.target.files[0]);

    });

}

let toggleActive = () => {

    $(document).on('change', '.toggle-active', function () {

        let url = $(this).data('url');

        $.ajax({
            url: url,
            method: 'PUT',
            cache: false,
            success: function (data) {

                new Noty({
                    layout: 'topRight',
                    text: data.message,
                    timeout: 2000,
                    killer: true
                }).show();

            },
        });//end of ajax call

    });

}

window.initJsTree = (parent = 'body') => {

    $(`${parent} .jstree`).each(function () {

        let that = $(this);

        that.jstree("destroy").empty();

        let url = that.data('url');

        let reorderUrl = that.data('reorder-url');

        let plugins = ['types', 'wholerow'];

        if (that.hasClass('checkbox')) { plugins.push('checkbox'); }//end of if

        if (that.hasClass('dnd')) { plugins.push('dnd'); }//end of if

        that.jstree({
            checkbox: {
                "keep_selected_style": false,
                "three_state": false, // to avoid that selecting a node also select its children
                "tie_selection": false // for independent check and selection handling
            },
            core: {
                'check_callback': function (operation, node, parent, position, more) {

                    if (that.hasClass('dnd') && that.hasClass('dnd-root')) {

                        if (operation === "move_node") {

                            return parent.id === "#";
                        }

                    }//end of if
                    return true; // Allow other operations
                },
                'data': {
                    'url': function (node) {
                        return node.id === '#'
                            ? url
                            : urlHasQueryParameter(url) ? `${url}&parent_id=${node.id}` : `${url}?parent_id=${node.id}`;
                    },
                    'data': function (node) {
                        return {
                            'id': node.id,
                        };
                    }
                }
            },
            types: {
                "default": {
                    "icon": "far fa-folder text-primary" // default icon
                },
                "file": {
                    "icon": "far fa-file text-primary" // icon for file type
                },
            },
            plugins: plugins,
            dnd: {
                "copy": false, // false to move, true to copy
                "inside_pos": "last", // position to insert inside
                "is_draggable": function (nodes) {
                    return true; // set condition if needed
                },
                "check_while_dragging": true, // allows checking for validity while dragging
                "always_copy": false // false to move, true to copy
            },
        });

        that.on('uncheck_node.jstree', function (e, data) {
            let inputToFill = $(`${parent} .jstree`).attr('data-input-to-fill');
            $(inputToFill).val('');
        });

        that.on('check_node.jstree select_node.jstree', function (e, data) {

            let allSelectedNodes = $(`${parent} .jstree`).jstree('get_checked', true);
            let inputToFill = $(`${parent} .jstree`).attr('data-input-to-fill');

            // $.each(allSelectedNodes, function (index, value) {
            //     if (data.node.id !== value.id) {
            //         $(`${parent} .jstree`).jstree('uncheck_node', value.id);
            //     }
            // });


            if (that.hasClass('select-ancestors')) {
                // Select all parent nodes
                let currentNode = data.node;
                while (currentNode.parent !== '#') {
                    currentNode = that.jstree(true).get_node(currentNode.parent);
                    that.jstree('check_node', currentNode);
                }
            } else {
                // Original behavior: uncheck other nodes
                $.each(allSelectedNodes, function (index, value) {
                    if (data.node.id !== value.id) {
                        $(`${parent} .jstree`).jstree('uncheck_node', value.id);
                    }
                });
            }

            // Update the input field with all checked node IDs
            let checkedNodes = that.jstree('get_checked', true);
            let checkedIds = checkedNodes.map(node => node.id);

            checkedNodes.length > 1
                ? $(inputToFill).val(JSON.stringify(checkedIds))
                : $(inputToFill).val(checkedIds[0]);


            if ($('#data-table-search').length) {

                $('#data-table-search').val((data.node.text).trim()).trigger('keyup');
            }
        });

        that.on('ready.jstree', function (e, data) {
            let inputToFill = that.attr('data-input-to-fill');
            let nodeIdsToCheck = $(inputToFill).val();

            if (nodeIdsToCheck) {
                let nodeIds = [];

                // Try parsing as JSON, if it fails, treat as a single value
                try {
                    nodeIds = JSON.parse(nodeIdsToCheck);
                    if (!Array.isArray(nodeIds)) {
                        nodeIds = [nodeIds];
                    }
                } catch (error) {
                    nodeIds = [nodeIdsToCheck];
                }

                // Check each node and open its parents
                nodeIds.forEach(nodeId => {
                    that.jstree('check_node', nodeId);

                    let parentNode = that.jstree('get_parent', nodeId);
                    while (parentNode && parentNode !== '#') {
                        that.jstree('open_node', parentNode);
                        parentNode = that.jstree('get_parent', parentNode);
                    }
                });
            }
        });

        that.on('move_node.jstree', function (e, data) {

            // Function to get the order of all nodes
            let getOrderOfAllNodes = function (tree) {

                let order = [];

                let counter = 1;

                let getNodeOrder = function (node, parentId) {

                    if (node.id !== '#') {
                        order.push({id: node.id, parent_id: parentId == '#' ? null : parentId, order: counter++});
                    }

                    let children = tree.get_node(node.id).children;

                    for (let i = 0; i < children.length; i++) {
                        getNodeOrder(tree.get_node(children[i]), node.id);
                    }
                };

                getNodeOrder(tree.get_node('#'), null); // Start from the root node

                return order;
            };

            let nodes = getOrderOfAllNodes($(`${parent} .jstree`).jstree(true));

            let ajaxData = {
                'nodes': nodes,
            }

            $.ajax({
                url: reorderUrl,
                method: 'POST',
                data: ajaxData,
                success: function (response) {

                    new Noty({
                        layout: 'topRight',
                        text: response['success_message'],
                        timeout: 2000,
                        killer: true
                    }).show();

                    if ($('#data-table-search').length) {
                        $('.datatable').DataTable().ajax.reload();
                    }//end of if

                },
                error: function (error) {

                }
            });
        });


    });

}

window.urlHasQueryParameter = (url) => {
    return url.indexOf('?') > -1;
}

// Convert Arabic/Persian numerals to Latin on any input/textarea
$(document).on('input', 'input[type=text], input[type=number], input[type=tel], input[type=email], textarea', function () {
    const arabicNums  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    const persianNums = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    let val = this.value;
    arabicNums.forEach((a, i)  => { val = val.split(a).join(i); });
    persianNums.forEach((p, i) => { val = val.split(p).join(i); });
    if (val !== this.value) this.value = val;
});

// Disable browser autocomplete on all forms
$(document).on('focus', 'input:not([autocomplete]), textarea:not([autocomplete])', function () {
    $(this).attr('autocomplete', 'off');
});

$(function () {

    handleEvaluationItemClicks();
    handleDeselectEvaluationItem();
    handleAttendanceStatusChange();
    handleCreateAssessmentButton();

    // Initialize badge for existing items on page load
    updateLastItemBadge();

    // Check initial attendance status
    toggleEvaluationItems();

    // Check if reached end of level on initial load
    checkReachedEndOfLevel();

    // Initialize badge when modal content is loaded
    $(document).on('shown.bs.modal', '#ajax-modal', function () {
        // Small delay to ensure content is fully rendered
        setTimeout(function() {
            if ($('#page-buttons-container').length > 0) {
                updateLastItemBadge();
            }
            // Check attendance status when modal is shown
            toggleEvaluationItems();
            // Check if reached end of level
            checkReachedEndOfLevel();
        }, 100);
    });

});//end of document ready

let handleEvaluationItemClicks = () => {

    $(document).on('click', '.evaluation-item-btn', function () {

        const $form = $('#ajax-modal').find('form.ajax-form[data-select-evaluation-item-url]');

        const selectUrl = $form.data('select-evaluation-item-url');

        let currentPageNumber = $form.data('page-number') || null;

        const $btn = $(this);

        const evaluationItemId = $btn.data('item-id');

        // Get evaluation item name (remove badge text if exists)
        let evaluationItemName = $btn.clone();

        evaluationItemName.find('.page-number-badge').remove();

        evaluationItemName = evaluationItemName.text().trim();

        // Get badge value - badge always shows the page to be evaluated
        const $badge = $btn.find('.page-number-badge');
        const badgePageNumber = $badge.length > 0 ? parseInt($badge.text()) : null;

        // Disable button during request
        $btn.prop('disabled', true);

        // Prepare request data - always send badge value so backend evaluates the correct page
        const requestData = {
            evaluation_item_id: evaluationItemId
        };
        
        if (badgePageNumber) {
            requestData.page_number = badgePageNumber;
        }

        // Make AJAX request
        $.ajax({
            url: selectUrl,
            method: 'POST',
            data: requestData,
            success: function (response) {

                if (response.success) {

                    // Update current page number
                    currentPageNumber = response.new_page_number;

                    $form.data('page-number', currentPageNumber);

                    // Add button above notes field
                    addPageButton(response.page_number, response.evaluation_item.name, response.file_locked);

                    // If file is locked (weak/fail evaluation), keep badge on same page number
                    const badgePageNumber = response.file_locked ? response.page_number : response.new_page_number;

                    // Update all page number badges (scope to modal)
                    $('#ajax-modal').find('.page-number-badge').text(badgePageNumber);

                    // Update page number display in details card if it exists
                    const $pageDisplay = $('#ajax-modal').find('.page-number-display');
                    if ($pageDisplay.length > 0) {
                        $pageDisplay.html('<i data-feather="book-open"></i> ' + response.new_page_number);
                    }

                    // Reinitialize feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }

                    // Update badge on last item
                    updateLastItemBadge();

                    // Check if reached end of level
                    if (response.reached_end_of_level) {
                        hideEvaluationItemsAndShowMessage();
                    }

                    // Show file locked warning if evaluation is a fail
                    if (response.file_locked) {
                        showFileLockedWarning();
                    } else {
                        hideFileLockedWarning();
                    }
                }
            },
            error: function (xhr) {
                console.error('Error selecting evaluation item:', xhr);
                let errorMessage = 'Error selecting evaluation item. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                alert(errorMessage);
            },
            complete: function () {
                // Re-enable button
                $btn.prop('disabled', false);
            }
        });
    });
};

let addPageButton = (pageNumber, evaluationItemName, fileLocked) => {
    const $container = $('#page-buttons-container');
    const $section = $('#pages-with-evaluation-items');

    if ($container.length === 0) {
        return; // Exit if container not found
    }

    const btnClass = fileLocked ? 'btn btn-danger w-100 position-relative' : 'btn btn-dark w-100 position-relative';

    // Check if button for this page already exists
    const existingBtn = $container.find(`button[data-page="${pageNumber}"]`);
    if (existingBtn.length > 0) {
        // Update existing button text and class
        existingBtn.text(`${pageNumber}: ${evaluationItemName}`);
        existingBtn.attr('class', btnClass);
        updateLastItemBadge();
        return;
    }

    // Create new button wrapped in col-md-3
    const $col = $('<div>').addClass('col-md-3 mb-1');
    const $button = $('<button>')
        .attr('type', 'button')
        .addClass(btnClass)
        .attr('data-page', pageNumber)
        .text(`${pageNumber}: ${evaluationItemName}`);

    $col.append($button);

    // Add to container
    $container.append($col);

    // Show section if it was hidden
    if ($section.length > 0) {
        $section.show();
    }

    // Update badge on last item
    updateLastItemBadge();
};

let showFileLockedWarning = () => {
    let $warning = $('#file-locked-warning');
    if ($warning.length === 0) {
        const lockedText = $('meta[name="lesson-page"]').attr('content') || 'Page';
        $warning = $('<div id="file-locked-warning" class="alert alert-warning p-1 mt-1" role="alert">' +
            '<i data-feather="lock"></i> ' +
            (window.fileLockedWarningText || 'الملف مقفل - الطالب سيعيد نفس الصفحة') +
            '</div>');
        $('.evaluation-items-section').after($warning);
        if (typeof feather !== 'undefined') feather.replace();
    }
    $warning.show();
};

let hideFileLockedWarning = () => {
    $('#file-locked-warning').hide();
};

let updateLastItemBadge = () => {
    const $container = $('#page-buttons-container');
    
    if ($container.length === 0) {
        return;
    }

    // Remove all existing deselect badges
    $container.find('.deselect-evaluation-item-badge').remove();

    // Get all page buttons
    const $pageButtons = $container.find('button[data-page]');
    
    if ($pageButtons.length === 0) {
        // Hide section if no items remain
        const $section = $('#pages-with-evaluation-items');
        if ($section.length > 0) {
            $section.hide();
        }
        return;
    }

    // Get the last button (highest page number)
    let $lastButton = null;
    let maxPage = 0;

    $pageButtons.each(function() {
        const pageNum = parseInt($(this).data('page'));
        if (pageNum > maxPage) {
            maxPage = pageNum;
            $lastButton = $(this);
        }
    });

    if ($lastButton && $lastButton.length > 0) {
        // Add badge to last button
        const $badge = $('<span>')
            .addClass('deselect-evaluation-item-badge')
            .css({
                'position': 'absolute',
                'top': '-8px',
                'left': '-8px',
                'z-index': '10',
                'display': 'inline-flex',
                'align-items': 'center',
                'justify-content': 'center',
                'width': '24px',
                'height': '24px',
                'background-color': '#dc3545',
                'color': 'white',
                'border-radius': '50%',
                'font-weight': '700',
                'font-size': '0.75rem',
                'box-shadow': '0 2px 6px rgba(220, 53, 69, 0.4)',
                'cursor': 'pointer'
            })
            .html('<i data-feather="minus"></i>');

        $lastButton.append($badge);

        // Reinitialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
};

let handleDeselectEvaluationItem = () => {
    $(document).on('click', '.deselect-evaluation-item-badge', function(e) {
        e.stopPropagation(); // Prevent button click

        const $form = $('#ajax-modal').find('form.ajax-form[data-deselect-evaluation-item-url]');
        const deselectUrl = $form.data('deselect-evaluation-item-url');

        if (!deselectUrl) {
            console.error('Deselect URL not found');
            return;
        }

        const $badge = $(this);
        $badge.prop('disabled', true).css('opacity', '0.5');

        // Make AJAX request
        $.ajax({
            url: deselectUrl,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    const $container = $('#page-buttons-container');
                    
                    // Remove the last button (highest page number)
                    let $lastButton = null;
                    let maxPage = 0;

                    $container.find('button[data-page]').each(function() {
                        const pageNum = parseInt($(this).data('page'));
                        if (pageNum > maxPage) {
                            maxPage = pageNum;
                            $lastButton = $(this);
                        }
                    });

                    // Store the page number being removed (this is the last page that was evaluated)
                    const removedPageNumber = maxPage;

                    if ($lastButton && $lastButton.length > 0) {
                        // Remove the parent col div
                        $lastButton.closest('.col-md-3').remove();
                    }

                    // Find the new max page number from remaining buttons
                    let newMaxPage = 0;
                    $container.find('button[data-page]').each(function() {
                        const pageNum = parseInt($(this).data('page'));
                        if (pageNum > newMaxPage) {
                            newMaxPage = pageNum;
                        }
                    });

                    // Update page number in form data
                    $form.data('page-number', response.new_page_number);

                    // Update all page number badges on evaluation item buttons
                    // Always use the deselected page number so user can reassign that page
                    const badgePageNumber = removedPageNumber > 0 ? removedPageNumber : response.new_page_number;
                    $('#ajax-modal').find('.page-number-badge').text(badgePageNumber);

                    // Set flag so next evaluation click sends this page number to backend (reassign after deselect)
                    $form.data('reassign-page', badgePageNumber);

                    // Update page number display in details card if it exists
                    const $pageDisplay = $('#ajax-modal').find('.page-number-display');
                    if ($pageDisplay.length > 0) {
                        $pageDisplay.html('<i data-feather="book-open"></i> ' + response.new_page_number);
                    }

                    // Update badge on new last item
                    updateLastItemBadge();

                    // Reinitialize feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }

                    // Explicitly restore evaluation items if we're below the level limit
                    const levelToPage = parseInt($form.data('level-to-page')) || 0;
                    const newPageNumber = response.new_page_number;
                    
                    if (!levelToPage || newPageNumber < levelToPage) {
                        // We're below the limit, show evaluation items and hide message
                        showEvaluationItemsAndHideMessage($form);
                    } else {
                        // Still at or above limit, check normally
                        checkReachedEndOfLevel($form);
                    }
                }
            },
            error: function (xhr) {
                console.error('Error deselecting evaluation item:', xhr);
                let errorMessage = 'Error removing evaluation item. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                alert(errorMessage);
                $badge.prop('disabled', false).css('opacity', '1');
            },
            complete: function () {
                // Re-enable badge if still exists
                if ($badge.length > 0) {
                    $badge.prop('disabled', false).css('opacity', '1');
                }
            }
        });
    });
};

let handleAttendanceStatusChange = () => {
    $(document).on('change', 'select[name="attendance_status"]', function() {
        toggleEvaluationItems();
    });
};

let toggleEvaluationItems = () => {
    const attendanceStatus = $('#ajax-modal').find('select[name="attendance_status"]').val();
    const $evaluationItemsSection = $('#ajax-modal').find('.evaluation-items-section');
    const $reachedEndMessage = $('#ajax-modal').find('#reached-end-of-level-message');
    const $timeElapsedWrapper = $('#ajax-modal').find('#time-elapsed-wrapper');
    const $pagesWithEvaluationItems = $('#ajax-modal').find('#pages-with-evaluation-items');
    
    // Get form to check level limit
    const $form = $('#ajax-modal').find('form.ajax-form[data-level-to-page]');
    
    // Don't show evaluation items if reached end of level
    if (hasReachedEndOfLevel($form)) {
        $evaluationItemsSection.hide();
        if ($reachedEndMessage.length > 0) {
            $reachedEndMessage.show();
        }
        return;
    }
    
    if (attendanceStatus === 'absent') {
        // Hide evaluation items buttons
        $evaluationItemsSection.hide();
        $reachedEndMessage.hide();
        // Hide time_elapsed field
        if ($timeElapsedWrapper.length > 0) {
            $timeElapsedWrapper.hide();
        }
        // Hide pages with evaluation items container
        if ($pagesWithEvaluationItems.length > 0) {
            $pagesWithEvaluationItems.hide();
        }
    } else {
        // Show evaluation items buttons
        $evaluationItemsSection.show();
        $reachedEndMessage.hide();
        // Show time_elapsed field
        if ($timeElapsedWrapper.length > 0) {
            $timeElapsedWrapper.show();
        }
        // Show pages with evaluation items container
        if ($pagesWithEvaluationItems.length > 0) {
            $pagesWithEvaluationItems.show();
        }
    }
};

let checkReachedEndOfLevel = ($form) => {
    // If $form not provided, scope lookup to modal
    if (!$form || $form.length === 0) {
        $form = $('#ajax-modal').find('form.ajax-form[data-level-to-page]');
    }
    
    if ($form.length === 0) {
        return; // Form not found
    }
    
    const levelToPage = $form.data('level-to-page');
    const pageNumber = $form.data('page-number');
    const attendanceStatus = $('#ajax-modal').find('select[name="attendance_status"]').val();

    if (levelToPage && pageNumber !== null && pageNumber !== '' && parseInt(pageNumber) >= parseInt(levelToPage)) {
        hideEvaluationItemsAndShowMessage();
    } else {
        // Not at end of level, show buttons if attendance is not absent
        showEvaluationItemsAndHideMessage($form, attendanceStatus);
    }
};

let hasReachedEndOfLevel = ($form) => {
    // If $form not provided, scope lookup to modal
    if (!$form || $form.length === 0) {
        $form = $('#ajax-modal').find('form.ajax-form[data-level-to-page]');
    }
    
    if ($form.length === 0) {
        return false; // Form not found, assume not at end
    }
    
    const levelToPage = $form.data('level-to-page');
    const pageNumber = $form.data('page-number');

    if (levelToPage && pageNumber !== null && pageNumber !== '' && parseInt(pageNumber) >= parseInt(levelToPage)) {
        return true;
    }
    return false;
};

let hideEvaluationItemsAndShowMessage = () => {
    const $evaluationItemsSection = $('#ajax-modal').find('.evaluation-items-section');
    const $reachedEndMessage = $('#ajax-modal').find('#reached-end-of-level-message');
    
    $evaluationItemsSection.hide();
    if ($reachedEndMessage.length > 0) {
        $reachedEndMessage.show();
        // Reinitialize feather icons for the message
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
};

let showEvaluationItemsAndHideMessage = ($form, attendanceStatus) => {
    // If $form not provided, scope lookup to modal
    if (!$form || $form.length === 0) {
        $form = $('#ajax-modal').find('form.ajax-form[data-level-to-page]');
    }

    const $evaluationItemsSection = $('#ajax-modal').find('.evaluation-items-section');
    const $reachedEndMessage = $('#ajax-modal').find('#reached-end-of-level-message');
    const $timeElapsedWrapper = $('#ajax-modal').find('#time-elapsed-wrapper');
    const $pagesWithEvaluationItems = $('#ajax-modal').find('#pages-with-evaluation-items');

    // If attendanceStatus not provided, get it from the form
    if (attendanceStatus === undefined) {
        attendanceStatus = $('#ajax-modal').find('select[name="attendance_status"]').val();
    }

    // Only show evaluation items if attendance is not absent
    if (attendanceStatus !== 'absent') {
        $evaluationItemsSection.show();
        // Show time_elapsed field
        if ($timeElapsedWrapper.length > 0) {
            $timeElapsedWrapper.show();
        }
        // Show pages with evaluation items container
        if ($pagesWithEvaluationItems.length > 0) {
            $pagesWithEvaluationItems.show();
        }
    } else {
        // Hide time_elapsed field when absent
        if ($timeElapsedWrapper.length > 0) {
            $timeElapsedWrapper.hide();
        }
        // Hide pages with evaluation items container when absent
        if ($pagesWithEvaluationItems.length > 0) {
            $pagesWithEvaluationItems.hide();
        }
    }
    $reachedEndMessage.hide();
};

let handleCreateAssessmentButton = () => {
    $(document).on('click', '[data-create-assessment="true"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        const $button = $(this);
        const assessmentUrl = $button.data('url');
        const modalTitle = $button.data('modal-title');

        // Find the main student lesson form within the modal
        const $form = $('#ajax-modal').find('form.ajax-form[data-student-lesson-id]');

        if ($form.length === 0) {
            console.error('Student lesson form not found');
            return;
        }

        // Check if attendance_status is selected
        const attendanceStatus = $form.find('select[name="attendance_status"]').val();
        if (!attendanceStatus) {
            // Highlight the attendance_status field with inline error only
            $form.find('select[name="attendance_status"]').closest('.form-group').find('.invalid-feedback').remove();
            $form.find('select[name="attendance_status"]').closest('.form-group').append('<span class="invalid-feedback d-block">' + ($('meta[name="attendance-status-required"]').attr('content') || 'Please select attendance status first') + '</span>');

            // Scroll to the field
            $('html, body, .page-data').animate({
                scrollTop: $form.find('select[name="attendance_status"]').offset().top - 300
            }, 200);

            return;
        }

        // Store original button state
        const originalButtonHtml = $button.html();
        const loadingText = $('meta[name="loading"]').attr('content') || 'Loading...';

        // Show loading state
        $button.attr('disabled', true);
        $button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + loadingText);

        // Remove any existing error messages
        $form.find('.invalid-feedback').remove();

        // Prepare form data
        const url = $form.attr('action');
        const data = new FormData($form[0]);

        // Submit the form via AJAX
        $.ajax({
            url: url,
            data: data,
            method: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {
                // Form submitted successfully, now open the assessment modal
                new Noty({
                    layout: 'topRight',
                    text: response.success_message || $('meta[name="student-lesson-saved"]').attr('content') || 'Student lesson saved successfully',
                    timeout: 2000,
                    killer: true
                }).show();

                // Open the assessment creation modal
                openAssessmentModal(assessmentUrl, modalTitle);
            },
            error: function (xhr) {
                let loginUrl = $('meta[name="login-url"]').attr('content');

                if (xhr.status == 500) {
                    window.handleErrorModal(xhr);
                } else if (xhr.status == 401 || xhr.status == 415 || xhr.status == 419) {
                    window.location.href = loginUrl;
                } else if (xhr.status == 422) {
                    // Validation errors - display them inline only
                    let errors = xhr.responseJSON?.errors || {};

                    for (const field in errors) {
                        $form.find('input[name="' + field + '"], select[name="' + field + '"], textarea[name="' + field + '"]')
                            .closest('.form-group')
                            .append('<span class="invalid-feedback d-block">' + errors[field][0] + '</span>');
                    }

                    // Scroll to first error
                    let $firstError = $form.find('.invalid-feedback.d-block').first();
                    if ($firstError.length > 0) {
                        $('html, body, .page-data').animate({
                            scrollTop: $firstError.offset().top - 300
                        }, 200);
                    }
                } else {
                    let errorMessage = xhr.responseJSON?.message || 'An error occurred';
                    new Noty({
                        layout: 'topRight',
                        text: errorMessage,
                        type: 'error',
                        timeout: 3000,
                        killer: true
                    }).show();
                }
            },
            complete: function () {
                // Restore button state
                $button.attr('disabled', false);
                $button.html(originalButtonHtml);

                // Reinitialize feather icons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        });
    });
};

let openAssessmentModal = (url, modalTitle) => {
    let loading = `
        <div class="loading-container absolute-centered">
            <div class="loader sm"></div>
        </div>
    `;

    $('#ajax-modal').modal('show');
    $('#ajax-modal .modal-body').remove();
    $('#ajax-modal .modal-content').append('<div class="modal-body relative"></div>');
    $('#ajax-modal .modal-body').empty().append(loading);
    $('#ajax-modal .modal-title').text(modalTitle);
    $('#ajax-modal .modal-dialog').removeAttr('class').attr('class', 'modal-dialog modal-dialog-centered modal-lg');

    $.ajax({
        url: url,
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
        error: function (xhr) {
            let errorMessage = xhr.responseJSON?.message || 'Error loading assessment form';
            $('#ajax-modal .modal-body').empty().append('<div class="alert alert-danger">' + errorMessage + '</div>');
        }
    });
};

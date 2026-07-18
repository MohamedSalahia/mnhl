$(function () {
    handleDeductionQuantityButtons();
    handleScoreCalculation();
    handleStartAssessmentSubmit();
});// end document ready

let handleDeductionQuantityButtons = () => {
    // Function to update plus button state based on quantity and max_clicks
    const updatePlusButtonState = (deductionId) => {
        const quantityInput = $(`.deduction-quantity-input[data-deduction-id="${deductionId}"]`);
        const plusButton = $(`.deduction-plus[data-deduction-id="${deductionId}"]`);
        const currentValue = parseInt(quantityInput.val()) || 0;
        const maxClicks = plusButton.data('max-clicks');

        if (maxClicks !== null && maxClicks !== '' && currentValue >= parseInt(maxClicks)) {
            plusButton.prop('disabled', true).addClass('disabled');
        } else {
            plusButton.prop('disabled', false).removeClass('disabled');
        }
    };

    // Function to update minus button state based on quantity
    const updateMinusButtonState = (deductionId) => {
        const quantityInput = $(`.deduction-quantity-input[data-deduction-id="${deductionId}"]`);
        const minusButton = $(`.deduction-minus[data-deduction-id="${deductionId}"]`);
        const currentValue = parseInt(quantityInput.val()) || 0;

        if (currentValue <= 0) {
            minusButton.prop('disabled', true).addClass('disabled');
        } else {
            minusButton.prop('disabled', false).removeClass('disabled');
        }
    };

    // Plus button click
    $(document).on('click', '.deduction-plus:not(:disabled)', function () {
        const deductionId = $(this).data('deduction-id');
        const quantityInput = $(`.deduction-quantity-input[data-deduction-id="${deductionId}"]`);
        const plusButton = $(this);
        const currentValue = parseInt(quantityInput.val()) || 0;
        const maxClicks = plusButton.data('max-clicks');

        // Check if max_clicks is reached
        if (maxClicks !== null && maxClicks !== '' && currentValue >= parseInt(maxClicks)) {
            return; // Don't increment if max is reached
        }

        quantityInput.val(currentValue + 1).trigger('change');
    });

    // Minus button click
    $(document).on('click', '.deduction-minus:not(:disabled)', function () {
        const deductionId = $(this).data('deduction-id');
        const quantityInput = $(`.deduction-quantity-input[data-deduction-id="${deductionId}"]`);
        const currentValue = parseInt(quantityInput.val()) || 0;
        if (currentValue > 0) {
            quantityInput.val(currentValue - 1).trigger('change');
        }
    });

    // Quantity input change - ensure non-negative and within max_clicks
    $(document).on('change', '.deduction-quantity-input', function () {
        const value = parseInt($(this).val()) || 0;
        const deductionId = $(this).data('deduction-id');
        const maxClicks = $(this).data('max-clicks');

        if (value < 0) {
            $(this).val(0);
        } else if (maxClicks !== null && maxClicks !== '' && value > parseInt(maxClicks)) {
            $(this).val(maxClicks);
        }

        // Update plus and minus button states
        updatePlusButtonState(deductionId);
        updateMinusButtonState(deductionId);
    });

    // Also listen for input event to update minus button in real-time
    $(document).on('input', '.deduction-quantity-input', function () {
        const deductionId = $(this).data('deduction-id');
        updateMinusButtonState(deductionId);
    });

    // Initialize button states on page load and when modal content is loaded
    const initializeButtonStates = () => {
        $('.deduction-quantity-input').each(function () {
            const deductionId = $(this).data('deduction-id');
            updatePlusButtonState(deductionId);
            updateMinusButtonState(deductionId);
        });
    };

    // Initialize on page load
    $(document).ready(function () {
        initializeButtonStates();
    });

    // Initialize when modal is shown (for AJAX-loaded content)
    $(document).on('shown.bs.modal', '#ajax-modal', function () {
        setTimeout(initializeButtonStates, 100);
    });
}

let handleScoreCalculation = () => {
    // Function to calculate and update remaining score
    const updateRemainingScore = () => {
        const originalMaxScore = parseInt($('#original-max-score').val()) || 0;
        const minPassingScore = parseInt($('#min-passing-score').val()) || 0;

        if (originalMaxScore === 0) return;

        let totalDeductions = 0;

        // Calculate total deductions from all quantity inputs
        $('.deduction-quantity-input').each(function () {
            const quantity = parseInt($(this).val()) || 0;
            const deductionValue = parseInt($(this).data('deduction-value')) || 0;
            totalDeductions += quantity * deductionValue;
        });

        // Calculate remaining score
        let remainingScore = originalMaxScore - totalDeductions;

        // Ensure remaining score doesn't go below 0
        if (remainingScore < 0) {
            remainingScore = 0;
        }

        // Update the display
        $('#remaining-score-display').text(remainingScore);

        // Update visual indicator based on passing threshold
        const remainingCard = $('#remaining-score-card');
        if (remainingScore < minPassingScore) {
            remainingCard.addClass('below-passing');
        } else {
            remainingCard.removeClass('below-passing');
        }
    };

    // Listen for changes on quantity inputs - this catches both manual input and triggered changes
    $(document).on('change', '.deduction-quantity-input', function () {
        updateRemainingScore();
    });

    // Also listen for input event for real-time updates during typing
    $(document).on('input', '.deduction-quantity-input', function () {
        updateRemainingScore();
    });

    // Listen for plus/minus button clicks with longer delay to ensure value is updated
    $(document).on('click', '.deduction-plus, .deduction-minus', function () {
        // Delay to allow the quantity button handler to update the input value first
        setTimeout(updateRemainingScore, 100);
    });

    // Initialize score display when modal is shown
    // $(document).on('shown.bs.modal', '#ajax-modal', function() {
    //     updateRemainingScore();
    // });

    // Initialize on page load if elements exist
    if ($('#original-max-score').length > 0) {
        updateRemainingScore();
    }
}

let handleStartAssessmentSubmit = () => {
    // Handle button clicks to set status and trigger form submission
    // The form has 'ajax-form' class, so it will be handled by the shared ajax-form handler
    $(document).on('click', '.assessment-submit-btn', function (e) {
        e.preventDefault();

        const btn = $(this);
        const status = btn.data('status');
        const form = $('#assessment-start-form');

        // Set the status value in the hidden input
        $('#assessment-status').val(status);

        // Temporarily change button to type="submit" so ajax-form handler can find it for loading state
        const originalType = btn.attr('type');
        btn.attr('type', 'submit');

        // Trigger form submit - the ajax-form handler will catch this and handle everything
        form.trigger('submit');

        // Restore button type after handler processes it
        setTimeout(() => {
            btn.attr('type', originalType);
        }, 100);
    });
}

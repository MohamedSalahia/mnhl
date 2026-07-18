$(function () {

    fetchProjects();

    fetchLevels();

    fetchPageNumbers();

});//end of document ready

let fetchProjects = () => {

    $(document).on('change', '#curriculum-id, [name="curriculum_id"]', function () {

        if (this.value && this.value != 0) {

            let url = $(this).find(':selected').data('projects-url');

            let projectSelect = $('#project-id, [name="project_id"]');
            let emptyValueText = projectSelect.find(':selected').text();

            $.ajax({
                url: url,
                data: {
                    'empty_value_text': emptyValueText
                },
                cache: false,
                success: function (html) {

                    projectSelect.find('option').not(':first').remove();
                    projectSelect.append(html);
                    projectSelect.attr('disabled', false);

                    let levelSelect = $('#level-id, [name="level_id"]');
                    levelSelect.find('option').not(':first').remove();
                    levelSelect.attr('disabled', true);

                    let pageNumberSelect = $('#page-number, [name="page_number"]');
                    pageNumberSelect.find('option').not(':first').remove();
                    pageNumberSelect.attr('disabled', true);

                },
            });//end of ajax call

        } else {

            let projectSelect = $('#project-id, [name="project_id"]');
            projectSelect.attr('disabled', true);
            projectSelect.val('').trigger('change');

            let levelSelect = $('#level-id, [name="level_id"]');
            levelSelect.attr('disabled', true);
            levelSelect.val('').trigger('change');

            let pageNumberSelect = $('#page-number, [name="page_number"]');
            pageNumberSelect.attr('disabled', true);
            pageNumberSelect.val('').trigger('change');

        } //end of else

    });

}

let fetchLevels = () => {

    $(document).on('change', '#project-id, [name="project_id"]', function () {

        if (this.value && this.value != 0) {

            let url = $(this).find(':selected').data('levels-url');

            $.ajax({
                url: url,
                cache: false,
                success: function (response) {

                    // Handle JSON response with 'view' property or direct HTML
                    let html = (typeof response === 'object' && response.view) ? response.view : response;

                    let levelSelect = $('#level-id, [name="level_id"]');
                    levelSelect.find('option').not(':first').remove();
                    levelSelect.append(html);
                    levelSelect.attr('disabled', false);

                    let pageNumberSelect = $('#page-number, [name="page_number"]');
                    pageNumberSelect.find('option').not(':first').remove();
                    pageNumberSelect.attr('disabled', true);

                },
            });//end of ajax call

        } else {

            let levelSelect = $('#level-id, [name="level_id"]');
            levelSelect.attr('disabled', true);
            levelSelect.val('').trigger('change');

            let pageNumberSelect = $('#page-number, [name="page_number"]');
            pageNumberSelect.attr('disabled', true);
            pageNumberSelect.val('').trigger('change');

        } //end of else
    });

}

let fetchPageNumbers = () => {

    // Handle page load - populate page_number if level is already selected (for edit forms)
    $(function () {
        let levelSelect = $('#level-id, [name="level_id"]');
        if (levelSelect.length && levelSelect.val() && levelSelect.val() != 0) {
            populatePageNumbers(levelSelect);
        }
    });

    $(document).on('change', '#level-id, [name="level_id"]', function () {
        populatePageNumbers($(this));
    });

}

let populatePageNumbers = (levelSelect) => {

    if (levelSelect.val() && levelSelect.val() != 0) {

        let selectedOption = levelSelect.find(':selected');
        let fromPage = parseInt(selectedOption.data('from-page'));
        let toPage = parseInt(selectedOption.data('to-page'));

        if (fromPage && toPage && fromPage <= toPage) {

            let pageNumberSelect = $('#page-number, [name="page_number"]');
            let currentValue = pageNumberSelect.val(); // Preserve current value
            pageNumberSelect.find('option').not(':first').remove();

            for (let page = fromPage; page <= toPage; page++) {
                pageNumberSelect.append($('<option>', {
                    value: page,
                    text: page,
                    selected: (currentValue && currentValue == page)
                }));
            }

            pageNumberSelect.attr('disabled', false);
            
            // Restore previous value if it's still valid
            if (currentValue && currentValue >= fromPage && currentValue <= toPage) {
                pageNumberSelect.val(currentValue);
            }
            
            pageNumberSelect.trigger('change');

        } else {

            let pageNumberSelect = $('#page-number, [name="page_number"]');
            pageNumberSelect.find('option').not(':first').remove();
            pageNumberSelect.attr('disabled', true);

        }

    } else {

        let pageNumberSelect = $('#page-number, [name="page_number"]');
        pageNumberSelect.find('option').not(':first').remove();
        pageNumberSelect.attr('disabled', true);
        pageNumberSelect.val('').trigger('change');

    } //end of else

}

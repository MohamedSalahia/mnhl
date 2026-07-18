$(function () {

    toggleActive();
    
});//end of document ready

let toggleActive = () => {
    
    $(document).on('change', '.language-toggle-active', function (e) {
        e.preventDefault();

        $(this).parent().find('form').submit();

    })
    
}
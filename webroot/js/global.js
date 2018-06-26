
$( document ).ready(function() {

    // ajax function to get images by requested query or page number
    function getImages(page) {
        //e.preventDefault();
        const form = $('#filterForm');
        const submit_button = $(form).find('input[type=submit]');
        const url = form.attr('action');
        let form_data = form.serialize();

        // check if page argument is set
        if (page > 0) {
            form_data += '&page=' + page;
        }
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: form_data,
            processData: false,
            beforeSend: function(){
                submit_button.attr('disabled','disabled');
            },
            complete: function(){
                submit_button.removeAttr('disabled');
            },
            success: function(data) {
                $('#gallery').html(data.content);
                $('#paginator').html(data.pag);
            },
            error: function(data){
                console.log(data);
            }
        });
    }
    $('#filterForm').submit( function(e) {
        e.preventDefault();

        getImages(0);
    });

    // change page
    $('#paginator li').on( "click", "a", function (e) {
        e.preventDefault();
        let classList = e.currentTarget.parentNode.classList;
        if (!classList.contains('active')) {
            getImages(parseInt(e.currentTarget.innerText));
        }
    });
});

jQuery(document).ready(function ($) {

    $('.remove-asset').click(function (e) {

        e.preventDefault()
        var id = $(this).attr('id')

        $.ajax({
            type: "POST",      // use $_POST request to submit data
            url: ajaxurl,      // URL to "wp-admin/admin-ajax.php"
            data: {
                action: 'remove_asset', // wp_ajax_*, wp_ajax_nopriv_*
                id: id
            },
            success: function (data) {
                console.log('Success') // success
                location.reload()
            },
            error: function (error) {
                console.log('error') // error
                console.log(error) // error
            }
        })
    })
});
jQuery(document).ready(function ($) {

    $('#ajax-save-asset-form').submit(function (e) {

        e.preventDefault()

        var name = $("input[name=name]").val()
        var url = $("input[name=url]").val()
        var footer = $('input[name=footer]:checked').val()

        $.ajax({
            type: "POST",      // use $_POST request to submit data
            url: ajaxurl,      // URL to "wp-admin/admin-ajax.php"
            data: {
                action: 'save_asset', // wp_ajax_*, wp_ajax_nopriv_*
                name: name,
                url: url,
                footer: footer
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
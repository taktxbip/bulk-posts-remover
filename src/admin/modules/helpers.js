function defaultAjax(formID, action, type, callback) {
    var form = jQuery('#' + formID);

    form.on('submit', function (e) {
        e.preventDefault();

        if (form.hasClass('loading')) return;
        form.addClass('loading');

        var formdata = form.serialize() + '&action=' + action + '&nonce_code=' + bpr_ajax.nonce;

        jQuery.ajax({
            url: bpr_ajax.url,
            data: formdata,
            type: type,
            success: function (response) {
                var data = JSON.parse(response);
                callback(data);
            },
        });
    });
}

export {
    defaultAjax
};
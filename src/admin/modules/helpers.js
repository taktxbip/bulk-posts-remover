function defaultAjax(formID, action, type, callbackBefore, callbackSuccess) {
    const form = jQuery('#' + formID);

    form.on('submit', function (e) {
        e.preventDefault();

        if (form.hasClass('loading')) return;
        form.addClass('loading');

        const formdata = form.serialize() + '&action=' + action + '&nonce_code=' + bpr_ajax.nonce;

        callbackBefore();

        jQuery.ajax({
            url: bpr_ajax.url,
            data: formdata,
            type: type,
            success: function (data) {
                form.removeClass('loading');
                callbackSuccess(data);
            },
        });
    });
}

const firstZero = num => num >= 0 && num < 10 ? `0${num}` : num;

function parseHms(s) {
    return {
        h: firstZero(Math.floor(s / (1000 * 60 * 60) % 24)),
        m: firstZero(Math.floor(s / (1000 * 60) % 60)),
        s: firstZero(Math.floor((s / 1000) % 60))
    };
}

export {
    parseHms,
    firstZero,
    defaultAjax
};
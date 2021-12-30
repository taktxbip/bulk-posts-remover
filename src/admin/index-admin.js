import './main-admin.scss';
'use strict';

(function ($) {
    var state = {
        run: false,
        pause: false,
        ids: null,
        length: null,
        post_type: null,
    };

    $(function () {
        defaultAjax('posts-remover-form', 'get_posts_ids', 'GET', prepareRemover);
    });

    function oneStepAjax(callback) {
        var data = {
            post_type: state.post_type,
            ids: state.ids.slice(0, 10),
            nonce_code: bpr_ajax.nonce,
            action: 'remove_posts'
        };
        $.ajax({
            url: bpr_ajax.url,
            data: data,
            type: 'POST',
            success: function (response) {
                var data = JSON.parse(response);
                callback(data);
            },
        });
    }

    function updateIds(data) {
        var summary = $('#posts-remover-summary'),
            log = $('#posts-remover-log ol'),
            removerLog = document.getElementById('posts-remover-log');
        if (state.run) {
            state.ids = state.ids.slice(10);
            updateProgressBar();
            for (const property in data.log) {
                log.append('<li>' + data.log[property] + '</li>');
            }
            removerLog.scrollTop = removerLog.scrollHeight;
            if (!state.ids.length) {
                summary.html('<p>Posts removed</p>');
                state.run = false;
                state.ids = null;
                state.length = null;
                state.post_type = null;
                $('.loading').removeClass('loading');
                $('.glare').removeClass('glare');
                return;
            }
            oneStepAjax(updateIds);
        }
    }

    function prepareRemover(data) {
        var summary = $('#posts-remover-summary');
        if (data.status) {
            summary.html('<p>Found ' + data.ids.length + ' posts</p>');
            if (!data.ids.length) return;
            else {
                state.ids = data.ids;
                state.run = true;
                state.length = data.ids.length;
                state.post_type = data.post_type;
                oneStepAjax(updateIds);
            }
        }
        else {
            summary.html('<p>Select post type first</p>');
        }
    }

    function defaultAjax(formID, action, type, callback) {
        var form = $('#' + formID);

        form.on('submit', function (e) {
            e.preventDefault();

            if (form.hasClass('loading')) return;
            form.addClass('loading');

            var formdata = form.serialize() + '&action=' + action + '&nonce_code=' + bpr_ajax.nonce;

            $.ajax({
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

    function updateProgressBar() {
        var progressbar = $('.progressbar'),
            percent = 100 * (state.length - state.ids.length) / state.length,
            fixed = percent.toFixed(2);

        progressbar.css({ 'width': fixed + '%', 'opacity': 1 }).find('.num').text(fixed);
    }
})(jQuery);
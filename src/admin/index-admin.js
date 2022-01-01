import './main-admin.scss';
import 'jquery-datetimepicker/build/jquery.datetimepicker.min.css';
'use strict';
import 'jquery-datetimepicker/build/jquery.datetimepicker.full.min';
import { defaultAjax, parseHms } from './modules/helpers';
import Modal from './modules/modal';

(function ($) {
    const state = {
        run: false,
        pause: false,
        ids: null,
        length: null,
        startTime: null,
        chunkSize: 100,
        formdata: null,
        modal: null
    };

    $(function () {
        const { chunkSize } = { ...bpr };
        if (typeof chunkSize !== 'undefined') {
            state.chunkSize = chunkSize;
        }
        state.chunkSize = +state.chunkSize;

        initDate();

        defaultAjax('posts-remover-form', 'get_posts_ids_ajax', 'GET', beforeStart, showModal);
    });

    function beforeStart() {
        const form = $('#posts-remover-form');
        state.formdata = form.serialize();
    }

    function initDate() {
        $.datetimepicker.setLocale('en');
        $('#posts-remover-app .datetimepicker').datetimepicker({
            timepicker: true
        });
    }

    function oneStepAjax(callback) {
        const ids = state.ids.slice(0, state.chunkSize);
        const data = `${state.formdata}&action=remove_posts_ajax&nonce_code=${bpr_ajax.nonce}&ids=${JSON.stringify(ids)}`;

        $.post({
            url: bpr_ajax.url,
            data: data,
            success: function (data) {
                if (data.status) {
                    callback(data);
                }
                else {
                    alert(data.message);
                }
            },
        });
    }

    function updateIds(data) {
        var summary = $('#posts-remover-summary'),
            log = $('#posts-remover-log ol'),
            removerLog = document.getElementById('posts-remover-log');
        if (state.run) {
            state.ids = state.ids.slice(state.chunkSize);
            updateProgressBar();
            updateEstimate();
            for (const property in data.log) {
                log.append('<li>' + data.log[property] + '</li>');
            }
            removerLog.scrollTop = removerLog.scrollHeight;
            if (!state.ids.length) {
                summary.html('<p>Posts removed</p>');
                resetState();
                return;
            }
            oneStepAjax(updateIds);
        }
    }

    function showModal(data) {
        if (!data.status) {
            const summary = $('#posts-remover-summary');
            summary.html('<p><strong>Select post type first</strong></p>');
            resetState();
            return;
        }

        const urlSearchParams = new URLSearchParams('?' + state.formdata);
        const args = {
            postType: urlSearchParams.get('post_type'),
            dateFrom: urlSearchParams.get('date_from'),
            dateTo: urlSearchParams.get('date_to'),
            found: data.ids.length
        };
        state.modal = new Modal(args, prepareRemover, data);
    }

    function prepareRemover(data) {
        const button = $('#posts-remover-form button[type=submit]');
        button.html('Removing...');
        const summary = $('#posts-remover-summary');

        if (data.status) {
            summary.html('<p>Found ' + data.ids.length + ' posts</p>');
            if (!data.ids.length) {
                resetState();
                return;
            }
            else {
                state.ids = data.ids;
                state.run = true;
                state.length = data.ids.length;
                state.startTime = Date.now();
                oneStepAjax(updateIds);
            }
        }

    }

    function resetState() {
        state.run = false;
        state.pause = false;
        state.ids = null;
        state.length = null;
        state.post_type = null;
        state.startTime = null;
        state.formdata = null;
        const form = $('#posts-remover-form');
        const button = form.find('button[type=submit]');
        form.removeClass('loading');
        button.html('Remove');
        $('.glare').removeClass('glare');
    }

    function updateEstimate() {
        const timeFromStart = Date.now() - state.startTime;
        const estimate = $('#posts-remover-estimate');
        const elapsedTimeEl = estimate.find('.posts-remover-elapsed .time');
        const remainingTimeEl = estimate.find('.posts-remover-remaining .time');

        // Insert elapsed
        const e = parseHms(timeFromStart);
        elapsedTimeEl.find('.s').html(e.s);
        elapsedTimeEl.find('.m').html(e.m);
        elapsedTimeEl.find('.h').html(e.h);

        // Insert remaining
        const percent = countElapsedPercent();
        const remainingTime = (100 - percent) * timeFromStart / percent;

        const r = parseHms(remainingTime);
        remainingTimeEl.find('.s').html(r.s);
        remainingTimeEl.find('.m').html(r.m);
        remainingTimeEl.find('.h').html(r.h);
    }

    function countElapsedPercent() {
        return 100 * (state.length - state.ids.length) / state.length;
    }

    function updateProgressBar() {
        const progressbar = $('.progressbar');
        let percent = countElapsedPercent();
        percent = percent.toFixed(2);

        progressbar.css({ 'width': percent + '%', 'opacity': 1 }).find('.num').text(percent);
    }
})(jQuery);
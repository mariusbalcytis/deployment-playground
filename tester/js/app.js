var template = $('.body-template');
$('[data-use-case]').each(function() {
    var elem = $(this);
    elem.append(template.clone().removeClass('hidden'));

    var urlPostfix = elem.data('useCase');
    var compare = elem.data('compare');
    var compareWith = elem.data('compareWith');
    var playButton = elem.find('.play');
    var stopButton = elem.find('.stop');
    var resetButton = elem.find('.reset');
    var counterSuccess = elem.find('.counter-success');
    var counterFailure = elem.find('.counter-failure');
    var counterInconsistent = elem.find('.counter-inconsistent');
    var log = elem.find('.log');

    var maxPendingRequests = 24;

    var sending = false;
    var requestsPending = 0;
    var success = 0, failure = 0, inconsistent = 0;

    playButton.click(function() {
        sending = true;
        send();
    });
    stopButton.click(function() {
        sending = false;
    });
    resetButton.click(function() {
        log.empty();
        success = failure = inconsistent = 0;
        counterSuccess.text(0);
        counterFailure.text(0);
        counterInconsistent.text(0);
    });

    function send(url) {
        if (!sending) {
            return;
        }
        if (requestsPending >= maxPendingRequests) {
            return;
        }
        requestsPending++;

        url = url || getUrl();
        $.get(url)
            .done(function (data, status, response) {
                if (response.status !== 200 || data[compare] === undefined) {
                    counterFailure.text(++failure);
                    log.prepend($('<pre/>').text(
                        response.status + ' ' + response.statusText + "\n" + response.responseText
                    ));
                } else if (data[compare] === data[compareWith]) {
                    counterSuccess.text(++success);
                } else {
                    counterInconsistent.text(++inconsistent);
                    log.prepend($('<pre/>').text(response.responseText));
                }
            })
            .fail(function (err) {
                counterFailure.text(++failure);
            })
            .always(function () {
                requestsPending--;
                send(url);
            });

        send();
    }

    function getUrl() {
        return 'http://127.0.0.' + (parseInt(requestsPending / 6) + 1) + urlPostfix;
    }
});

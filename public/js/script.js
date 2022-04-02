const ACTION_CONTAINER = '#action';
const ALERT_CLASSES = 'alert-info alert-success alert-danger';
const ALERT_CONTAINER = '#alert';
const DOWNLOAD_TRIGGER = "#download";
const FORM = '#get-tickets-form';

$(document).on('submit', FORM, formSubmitHandler);
// $(document).on('click', DOWNLOAD_TRIGGER, downloadTriggerClickHandler);

async function downloadTriggerClickHandler(e) {
    e.preventDefault();
    let filepath = await $.get({
        url: 'http/DownloadFile.php',
        data: {
            filename: $(this).attr('data-filename')
        }
    });

    console.log(filepath);
}

async function formSubmitHandler(e) {
    e.preventDefault();

    let action = $(ACTION_CONTAINER).remove();
    let alert = $(ALERT_CONTAINER)
        .html('Processing...')
        .removeClass(ALERT_CLASSES)
        .addClass('alert-info');
    
    let response = await $.post({
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'json'
    });

    $(alert).removeClass(ALERT_CLASSES);

    if (response.status == 'OK') {

        $(alert)
            .html(response.body)
            .addClass('alert-success')
            .before(action)
    } else {
        let error = response.body;
        if (error.title) {
            let title = $('<strong>').html(error.title);
            let message = $('<p>').html(error.message);

            $(alert).html(title).append(message);
        } else {
            $(alert).html(error);
        }
        
        $(alert).addClass('alert-danger').before(action);
    }
}
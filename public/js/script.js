const ACTION_CONTAINER = '#action';
const ALERT_CLASSES = 'alert-info alert-success alert-danger';
const ALERT_CONTAINER = '#alert';
const FORM = '#get-tickets-form';

$(document).on('submit', FORM, formSubmitHandler);

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
            let message = $('<div>').html(error.message);

            $(alert).html(title).append(message);
        } else {
            $(alert).html(error);
        }
        
        $(alert).addClass('alert-danger').before(action);
    }
}
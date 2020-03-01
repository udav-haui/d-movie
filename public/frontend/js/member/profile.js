$(document).ready(function () {
    'use strict';

    let dateNow = new Date();

    let $datepicker = $('[dmovie-datepicker]');
    /* Datepicker Initialization */
    $datepicker.datepicker({
        classes: 'select-none dmovie-border dmovie-datepicker',
        autoClose: true,
        dateFormat: 'dd-mm-yyyy',
        maxDate: dateNow
    }).each(function () {
        if ($(this).val()) {
            let date = moment($(this).val(), 'DD-MM-YYYY').toDate();
            $(this).data('datepicker')
                .selectDate(date);
        }
    });

    $('#_updatePassword').on('click', function () {
        let $currentPassword = $('#current_password'),
            $newPassword = $('#password'),
            $passwordConfirmation = $('#password_confirmation'),
            self = $(this);

        let data = {
            password: $newPassword.val(),
            password_confirmation: $passwordConfirmation.val()
        };

        if ($currentPassword.length > 0) {
            data['current_password'] = $currentPassword.val()
        }

        try {
            $.ajax({
                url: route('member.changePassword', {member: self.attr('data-id')}).url(),
                data: data,
                dataType: 'json',
                type: 'POST',
            }).done(function (res) {
                if (res.status === 200) {
                    successMessage(res.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else {
                    let message = '';
                    if (res.message['password'] !== undefined) {
                        res.message['password'].forEach(function (msg) {
                            message += `<li>${msg}</li>`;
                        })
                    } else {
                        message += `<li>${res.message}</li>`;
                    }
                    let $message =
                        `<ul>
                            ${message}
                        </ul>`;
                    errorMessage($message);

                    $currentPassword.val('');
                    $newPassword.val('');
                    $passwordConfirmation.val('');
                }
            }).fail(function (res) {
                console.log(res.responseJSON.message);
            });
        } catch (e) {
            console.log(e);
        }
    });
});

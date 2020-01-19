$(document).ready(function () {
    let selectedUser = $('#user_id_select2'),
        langText = $('.lang-text'),
        placeholderText = langText.attr('placeholderText');

    selectedUser.select2({
        placeholder: placeholderText,
        pagination: {
            more: true,
        },
        ajax: {
            url: route('user.getUsers'),
            type: 'get',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    name: params.term,
                };
            },
            processResults: function (data) {
                console.log(data);
                return {
                    results: $.map(data.data, function (val, i) {
                        /**
                         * Nếu data có root thì ko thêm vào list
                         */
                        return val.role != 1 ? {
                            id: val.id,
                            text: val.name == null ? `ID: ${val.id} - Chưa cập nhật tên` : val.name
                        } : null;
                    })
                }
            }
        }
    });
});

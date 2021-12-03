function showError(message, form) {
    form.find('.tq_errors').remove();
    form.prepend('<div class="tq_errors"><ul>' + message + '</ul></div>');
}
$(document).on('submit', '#register', function () {
    let $this = $(this);
    $this.find('.tq_errors').remove();

    $.ajax({
        url: $this.attr('action'),
        type: 'POST',
        data: new FormData($this.get(0)),
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData: false,        // To send DOMDocument or non processed data file it is set to false
        dataType: 'json',
        success: function (result) {
            if (result.status == 'success') {
                if (typeof result.html != "undefined" && result.html) {
                    $('#tq_code').remove();
                    $this.find('.captcha').before(result.html)
                } else {
                    location.reload();
                }
            } else {
                showError(result.message, $this);
            }

        },
        error: function (data) {
            let message = [];
            $.each(data.responseJSON.errors, function (index, value) {
                $.each(value, function ($key, val) {
                    message.push('<li>' + val + '</li>')
                })
            })
            showError(message.join(''), $this);
        }
    });


    return false;

})
let suggestionAjax;
$(document).on('input', '[data-suggestion]', function () {
    let $this = $(this),
        suggestion = $this.siblings('.tq_suggestion');
    suggestion.html('');
    if ($this.val().length > 2) {
        if (!!suggestionAjax) suggestionAjax.abort();
        suggestionAjax = $.ajax({
            url: $this.data('suggestion'),
            method: 'post',
            type: "POST",
            data: {
                query: $this.val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (result) {
                if (result) {
                    $.each(result, function (index, value) {
                        suggestion.append('<li>' + value.value + '</li>')
                    })
                    suggestion.show();
                } else {
                    suggestion.hide();
                }

            },
        });
    }
})
$(document).on('click', '.tq_suggestion li', function () {
    let $this = $(this);
    $this.closest('.tq_suggestion').siblings('input').val($(this).text());
    $this.closest('.tq_suggestion').hide();
})
$(document).on('click', '.add-salary-finish .close', function () {
    $(this).closest('.add-salary-finish').remove();
})
$(document).on('click', function () {
    $('.tq_suggestion').hide();
})
$(document).ready(function () {
    if ($('.add-salary-finish').length > 0) {
        setTimeout(function () {
            $('.add-salary-finish').remove();
        }, 3000);
    }
})
$(document).on('mouseover click','[data-title]',function () {
    let $this = $(this),
    tooltip = $('.tq_tooltip');
    tooltip.css('width',tooltip.css('width'));
    //$this.append(tooltip);
    tooltip.css({top:$this.parent().position().top+$this.position().top+32,left:$this.parent().position().left});
    tooltip.text($this.data('title')).addClass('active');
})
$(document).on('mouseout','[data-title]',function () {
    let $this = $(this),
        tooltip = $this.closest('.percentiles').find('.tq_tooltip');
    tooltip.removeClass('active');
})
$(document).ready(function () {
    $('.phone-mask').mask('+375 99 999-99-99');
})

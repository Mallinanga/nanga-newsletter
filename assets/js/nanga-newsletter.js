(function ($) {
    $(function () {
        var newsletter = $('.nanga-newsletter');
        newsletter.submit(function (e) {
            var form = $(this);
            var email = form.find('input[name="email"]').val();
            var nanga_newsletter = form.find('input[name="nanga_newsletter"]').val();
            form.addClass('is-sending');
            $.post(nangaNewsletter.endpoint, {action: 'nanga_newsletter', nanga_newsletter: nanga_newsletter, email: email},
                function (response) {
                    form.removeClass('is-sending');
                    form.find('.form__message').hide().html(response.data).fadeIn();
                    form.find('.form__fields').hide();
                    setTimeout(function () {
                        form.find('.form__message').hide();
                        form.find('input[name="email"]').val('');
                        form.find('.form__fields').fadeIn();
                    }, 2500);
                }
            );
            e.preventDefault();
        });
        newsletter.keypress(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                $(this).submit();
            }
        });
    });
})(jQuery);


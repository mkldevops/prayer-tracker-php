$(function () {
    $('.add-prayer').click(function () {
        let button = $(this)
        let objective = $(this).data('objective')
        $.post(Routing.generate('api_prayer_add', { id : objective }))
        .done(function (response) {
            $('.objective-number', button).text(response.count)
            console.log(response)
        })
    })
})

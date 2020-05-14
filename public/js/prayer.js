$(function () {
    $('.add-prayer').click(function () {
        let button = $(this)
        let objective = $(this).data('objective')
        $.post(Routing.generate('api_prayer_add', { id : objective }))
        .done(function (response) {
            $('#program-count').text(response.program.count)
            $('#program-sub').text(response.program.number - response.program.count)
            let percent = (response.program.count / response.program.number * 100)
            $('#program-percent').text(Math.round(percent * 100) / 100)

            $('.objective-count', button).text(response.objective.count)
            $('.objective-sub', button).text(response.objective.sub)
            $('.objective-percent', button).text(response.objective.percent)
            console.log(response)
        })
    })
})

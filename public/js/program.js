$(function () {
    let dataChart = {
        chart: { type: 'column' },
        title: { text: 'Stats of program'},
        xAxis: { categories: [] },
        yAxis: {
            min: 0,
            title: { text: 'Total prayer of program' },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: { column: { stacking: 'normal', dataLabels: { enabled: true }}},
        series: []
    };

    let program = $('#program').data('id')
    $.get(Routing.generate('api_program_stats', { id : program }))
        .done(function (response) {
            Object.keys(response).map(function (objectKey, index) {
                let value = response[objectKey];

                if (!dataChart.xAxis.categories.length) {
                    dataChart.xAxis.categories = Object.keys(value);
                }

                dataChart.series.push({ name : objectKey, data : Object.values(value) });
            })
            Highcharts.chart('chart', dataChart);
        })
        .fail(function (response, ctx) {
            console.log(response, ctx)
        })

    countDay()
})

function countDay()
{
    let program = $('#program').data('id')
    console.log(program)
    if (program) {
        $.get(Routing.generate('api_program_count_day', { id : program }))
            .done(function (response) {
                $('#program-count-day').html(response.countDay)
            })
            .fail(function (response, ctx) {
                console.log(response, ctx)
            })
    }
}

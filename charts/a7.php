<?php
$sql4 = "select
date_format(crawl.date_executed, '%Y-%m-%d') Date,
count(distinct crawl_results.product_id) as skucount,
count(distinct crawl_results.website_id) as dealercount
from
crawl_results
inner join
website ON crawl_results.website_id = website.id
inner join
crawl ON crawl.id = crawl_results.crawl_id
where
crawl_results.violation_amount > 0.05
and website.excluded = 0
group by date_format(crawl.date_executed, '%Y-%m-%d')";


$dashchart_array = $db_resource->GetResultObj($sql4);

// collecting rows information
$chart_vendor_rows = array();
$chart_violation_amount_rows = array();
$chart_violation_amount2_rows = array();

foreach ($dashchart_array as $dashch) {
    $chart_row = strtotime($dashch->Date) * 1000;
    array_push($chart_vendor_rows, $chart_row);
    array_push($chart_violation_amount_rows, $dashch->skucount);
    array_push($chart_violation_amount2_rows, $dashch->dealercount);
}

$js_data_string_vendors = implode($chart_vendor_rows, ",");
$js_data_string_amounts = implode($chart_violation_amount_rows, ",");
$js_data2_string_amounts = implode($chart_violation_amount2_rows, ",");
?>

<script type="text/javascript">
    $(function() {
        $('#container').highcharts({
            colors: ['#058DC7', '#000000'],
            chart: {           
                type: 'areaspline'
            },
            title: {
                text: 'Violations by Dealers & Products'

            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF'
            },
            xAxis: {
                categories: [
<?php echo $js_data_string_vendors; ?>
                ],
                labels: {
                    rotation: -50,
                    align: 'right',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Arial'
                    },
                    formatter: function() {
                        return Highcharts.dateFormat('%a %d %b', this.value);
                    },
                }

            },
            yAxis: {
                title: {
                    text: 'Violations',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Arial',
                        fontWeight: 'regular'
                    },
                },
                labels: {
                    formatter: function() {

                        return '' + Highcharts.numberFormat(this.value / 1, 0);
                    }
                },
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + Highcharts.dateFormat('%a %d %b %Y', this.x) + '</b><br/>' +
                            'Violation Count: ' + this.y;
                }
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.2
                }
            },
            series: [{
                    name: 'SKU Count',
                    data: [<?php echo $js_data_string_amounts; ?>]
                }, {
                    name: 'Dealer Count',
                    data: [<?php echo $js_data2_string_amounts; ?>]
                }]
        });
    });
</script>

<div id="container" style="width: 1520px; height: 400px ; margin: auto"></div>
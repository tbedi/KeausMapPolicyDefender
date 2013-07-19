<?php
$limit = 10;
$sql = "select 
sum(map_price) as Violations_amount,
 crawl.date_executed as DateExec		 
from crawl_results res
inner join catalog_product_flat_1 prods on prods.entity_id = res.product_id
inner join website sites on sites.id = res.website_id
inner join crawl on crawl.id = res.crawl_id
where
violation_amount > 0.05 
and sites.excluded = 0 
group by crawl.date_executed
order by crawl.date_executed  limit " . $limit;
$result = mysql_query($sql);



$chart_vendor_rows = array();
$chart_violation_amount_rows = array();
while ($row = mysql_fetch_assoc($result)) {
    $chart_row = strtotime($row ['DateExec']) * 1000;
    array_push($chart_vendor_rows, $chart_row);
    array_push($chart_violation_amount_rows, $row ['Violations_amount']);
}


$js_data_string_vendors = implode($chart_vendor_rows, ",");
$js_data_string_amounts = implode($chart_violation_amount_rows, ",");
?>


<script type="text/javascript">
    $(function() {

        function Currency(sSymbol, vValue) {
            aDigits = vValue.toFixed(2).split(".");
            aDigits[0] = aDigits[0].split("").reverse().join("").replace(/(\d{3})(?=\d)/g, "$1,").split("").reverse().join("");
            return sSymbol + aDigits.join(".");
        }

        $('#chart-a6').highcharts({
            chart: {
                renderTo: 'a6',
                defaultSeriesType: 'line',
                margin: [50, 50, 100, 80],
                //zoomType: 'y'
            },
            title: {
                text: 'Violation Trend By Amount',
                x: -20 //center
            },
            xAxis: {
                categories: [
<?php echo $js_data_string_vendors; ?>
                ],
                labels: {
                    rotation: -90,
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
                    text: 'Violation',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Arial',
                        fontWeight: 'regular'
                    },
                },
                labels: {
                    formatter: function() {
                        return '$' + Highcharts.numberFormat(this.value / 1000, 0) + 'k ';
                    }
                },
                minTickInterval: 5000,
                minorTickInterval: 5000,
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + Highcharts.dateFormat('%a %d %b %Y', this.x) + '</b><br/>' +
                            'Total Price Violation: ' + Currency('$', this.y);
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                borderWidth: 0,
                x: -10,
                y: 100,
            },
            series: [{
                    name: 'Violation Amount',
                    data: [<?php echo $js_data_string_amounts; ?>]
                }]
        });
    });
</script>
<?php
if (!$result) {
    echo mysql_error();
}
?>
<div id="chart-a6" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
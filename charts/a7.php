<?php

$sql4 = "select
crawl.date_executed Date,
count(distinct crawl_results.product_id) as skucount,
count(distinct crawl_results.website_id) as dealercount
from
crawl_results
inner join
catalog_product_flat_1
on crawl_results.product_id = catalog_product_flat_1.entity_id
inner join
website
on crawl_results.website_id = website.id
inner join
crawl
on crawl.id = crawl_results.crawl_id
and date_format(crawl.date_executed, '%Y-%m-%d') between DATE_ADD(sysdate(), INTERVAL -7 DAY) and sysdate() where crawl_results.violation_amount>0.05 and website.excluded=0
group by date_format(crawl.date_executed, '%Y-%m-%d') limit 10
";
$dashchart_array = $db_resource->GetResultObj($sql4);
//$result4 = mysql_query($sql4);
$chart_vendor_rows = array();
$chart_violation_amount_rows = array();
$chart_violation_amount2_rows = array();
foreach ($dashchart_array as $dashch) {
    

//while ($row = mysql_fetch_array($result4)) {
    
    $chart_row = strtotime($dashch-> Date) * 1000;
    array_push($chart_vendor_rows, $chart_row);
    array_push($chart_violation_amount_rows, $dashch->skucount);
    array_push($chart_violation_amount2_rows, $dashch->dealercount);
}
//print_r($result1);
$js_data_string_vendors = implode($chart_vendor_rows, ",");
$js_data_string_amounts = implode($chart_violation_amount_rows, ",");
$js_data2_string_amounts = implode($chart_violation_amount2_rows, ",");
?>

<script>$(function () {
        $('#container').highcharts({
            
       colors: ['#058DC7','#800040'],
            chart: {
                
                 backgroundColor: {
                  linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
         stops: [
            [0, 'rgb(226, 226, 226)'],
            [1, 'rgb(167, 167, 167)']
         ]
      },           
                type: 'areaspline'
            },
            title: {
                
                     
                text: 'Violations by Dealers & Products'
                
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
                    
                        return '' + Highcharts.numberFormat(this.value / 1, 0) ;                
    }
                },
            
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + Highcharts.dateFormat('%a %d %b %Y', this.x) + '</b><br/>' +
                           'Violation Count: ' +  this.y;
                }
            },
//            credits: {
//                enabled: false
//            },
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
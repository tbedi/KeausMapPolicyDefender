
<?php
//getting last crawl
$sql = "select id  from crawl  ORDER BY id DESC  LIMIT 1";
$last_crawl = $db_resource->GetResultObj($sql);
$cmax = '';
foreach ($last_crawl as $date1) {
    $cmax = $cmax . $date1->id;  //$cmax gives maxid of crawl
}
$limit = 10; // x in the Top x Products  
//Getting Top x Price violations by Product from last Crawl process
$sql = "SELECT  w.`name`,
    COUNT(w.`name`) as violations 
    FROM crawl_results  r 
    INNER JOIN
    website w 
    ON
    r.website_id=w.id 
    WHERE r.crawl_id=" . $cmax . "
    AND r.violation_amount>0.05
    and w.excluded = 0 
    GROUP BY w.`name` ORDER BY COUNT(w.`name`) DESC LIMIT " . $limit;

$row = $db_resource->GetResultObj($sql);

//getting sum
$sum = 0;
$items = array();
$item = '';
foreach ($row as $rows11) {
    $sum+=$rows11->violations;
    $item['name'] = preg_replace('/[^A-Za-z0-9. \-]/', '', $rows11->name);
    $item['violations'] = $rows11->violations;
    array_push($items, $item);
}

//collecting rows information
$chart_rows = array();

foreach ($items as $product) {
    $chart_row = "['" . $product['name'] . "'," . round(100 * $product['violations'] / $sum, 2) . "]";
    array_push($chart_rows, $chart_row);
}

$js_data_string = implode($chart_rows, ",");
//print_r($js_data_string);
?>
<script type="text/javascript">
    $(function() {

        // Build the chart
        $('#chart-a2').highcharts({
            chart: {
                plotBackgroundColor: null,
               // plotBorderWidth: 3,
                plotShadow: false,
                margin: [2, 2, 5, 5]
            },
            title: {
                text: 'Price violation by Dealers'
            },
            tooltip: {
                formatter: function() {
                    return this.point.name + ': <b>' + Highcharts.numberFormat(this.percentage, 2) + '%</b>';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return  '<b>' + this.point.name + '</b>: ' + Highcharts.numberFormat(this.percentage, 2, '.') + ' %';
                        }
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Price violation by Dealers',
                    point: {
                        events: {
                          click: function(e) {
                             this.slice();                                                   
                             location.href = "/index.php?tab=violations-history";                        
                             e.preventDefault();
                          }
                       }
                     },
                    data: [
                        <?php echo $js_data_string; ?>
                    ]
                }]
        });
    });
</script>

<div id="chart-a2" style="width: 550px; height: 340px; margin: 0 auto"></div>
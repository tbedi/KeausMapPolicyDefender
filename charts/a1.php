<?php
//getting last crawl
$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result = mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);

$limit = 10; // x in the Top x Products  
//Getting Top x Price violations by Product from last Crawl process
$sql = "SELECT  p.sku,
    COUNT(p.sku) as violations
    FROM crawl_results  r 
    INNER JOIN
    catalog_product_flat_1 p
    ON
    p.entity_id=r.product_id 
    WHERE r.crawl_id=" . $last_crawl['id'] . "
        AND r.violation_amount>0.05  
        GROUP BY p.sku ORDER BY COUNT(p.sku) DESC LIMIT " . $limit;
$result = mysql_query($sql);

//getting sum
$sum = 0;

$items = array();

while ($row = mysql_fetch_assoc($result)) {
    $sum+=$row['violations'];
    $item['sku'] = $row['sku'];
    $item['violations'] = $row['violations'];
    array_push($items, $item);
}

//collecting rows information
$chart_rows = array();

foreach ($items as $product) {
    $chart_row = "['" . $product['sku'] . "'," . round(100 * $product['violations'] / $sum, 2) . "]";
    array_push($chart_rows, $chart_row);
    
}

$js_data_string = implode($chart_rows, ",");
//print_r($js_data_string);
?>
<script type="text/javascript">
    $(function() {
        // Build the chart
        $('#chart-a1').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false,
                margin: [2, 2, 5, 5]
            },
            title: {
                text: 'Price Violation by Products'
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
                            return '<b>' + this.point.name + '</b>: ' + Highcharts.numberFormat(this.percentage, 2, '.') + ' %';
                        }
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Violation by Products',
                    
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

<div id="chart-a1" style="width: 460px; height: 340px; margin: 0 auto"></div>
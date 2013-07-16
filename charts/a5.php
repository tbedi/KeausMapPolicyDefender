<?php
$limit = 10;

$sql = "select
count(*)  as Violations_amount,
DATE_FORMAT(crawl.date_executed, '%Y-%m-%d') as DateExec
from crawl_results res
inner join catalog_product_flat_1 prods on prods.entity_id = res.product_id
inner join website sites on sites.id = res.website_id
inner join crawl on crawl.id = res.crawl_id
where
violation_amount > 0.05 
and sites.excluded = 0 
group by crawl.date_executed
order by 1 desc limit " . $limit;
$result = mysql_query( $sql );



$chart_vendor_rows = array ();
$chart_violation_amount_rows = array ();
while ( $row = mysql_fetch_assoc ( $result ) ) {
  $chart_row = "'" . $row ['DateExec'] . "'";
  array_push ( $chart_vendor_rows, $chart_row ); 
  array_push ( $chart_violation_amount_rows,  $row ['Violations_amount']);
}


$js_data_string_vendors = implode ( $chart_vendor_rows, "," );
$js_data_string_amounts = implode ( $chart_violation_amount_rows, "," );
 

?>


<script type="text/javascript">
    $(function() {
        $('#chart-a5').highcharts({
            chart: {
                renderTo: 'a5',
                defaultSeriesType: 'line',
                margin: [ 50, 50, 100, 80]
                
            },
            title: {
                text: 'Violation Trend By Incidents',
                x: -20 //center
            },
           
            xAxis: {
                categories: [
<?php echo $js_data_string_vendors; ?>
                ],
                labels: {
                rotation: -45,
                 align: 'right',
                 style: {
                     fontSize: '13px',
                     fontFamily: 'Verdana, sans-serif'
                 }
             }
            },
            yAxis: {
                title: {
                    text: 'Violations'
                        },
                
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.x + '</b><br/>' +
                            'Price Violation:'+this.y;

                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                    name: 'Violation Amount',
                    data: [<?php echo $js_data_string_amounts; ?>]


                }]
        

  
    });
});
</script>

<div id="chart-a5" style="min-width: 400px; height: 300px; margin: 0 auto"></div>
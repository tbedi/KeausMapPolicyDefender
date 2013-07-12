 
<?php
 $product_id = $_REQUEST['product_id'];
// getting last crawl
$sql = "select id, date_executed from crawl ORDER BY id DESC LIMIT 1";
$result = mysql_query ( $sql );
$last_crawl = mysql_fetch_assoc ( $result );

$limit = 10; // x in the Top x Products
           // Getting Top x Price violations by Product from last Crawl process
$sql = "SELECT  w.`name` as vendor ,
    format(r.violation_amount,2) as violation_amount
    FROM crawl_results  r 
    INNER JOIN website w 
    ON r.website_id=w.id 
    INNER JOIN catalog_product_flat_1 p 
    ON p.entity_id=r.product_id  
    AND p.entity_id='".$product_id."'  
    WHERE r.crawl_id=".$last_crawl['id']." 
            AND r.violation_amount>0.05 
            ORDER BY r.violation_amount DESC LIMIT ".$limit;
$result = mysql_query ( $sql );

// collecting rows information
$chart_vendor_rows = array ();
$chart_violation_amount_rows = array ();
while ( $row = mysql_fetch_assoc ( $result ) ) {
  $chart_row = "'" . preg_replace('/[^A-Za-z0-9\-]/', '', $row['vendor']) . "'";
  array_push ( $chart_vendor_rows, $chart_row ); 
  array_push ( $chart_violation_amount_rows,  $row ['violation_amount']);
}


$js_data_string_vendors = implode ( $chart_vendor_rows, "," );
$js_data_string_amounts = implode ( $chart_violation_amount_rows, "," );
 
?>
 <script type="text/javascript">
 $(function () {
     $('#chart-a3').highcharts({
         chart: {
             type: 'column',
             margin: [ 50, 50, 100, 80]
         },
         title: {
             text: 'Violation by product'
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
             min: 0,
             title: {
                 text: 'Violation amount'
             }
         },
         legend: {
             enabled: false
         },
         tooltip: {
             formatter: function() {
                 return '<b>'+ this.x +'</b><br/>'+
                     'Price Violation: '+ Highcharts.numberFormat(this.y, 2) +
                     ' $';
             }
         },
        plotOptions: {
             series: {
                 colorByPoint: true
             }
         },
         series: [{
             name: 'Violation Amount',
             data: [  <?php echo $js_data_string_amounts; ?> ],
             dataLabels: {
                 enabled: true,
                 rotation: -90,
                 color: '#FFFFFF',
                 align: 'right',
                 x: 4,
                 y: 10,
                 style: {
                     fontSize: '13px',
                     fontFamily: 'Verdana, sans-serif'
                 }
             }
         }]
     });
 });
 

</script>

<div id="chart-a3"  style="width: 800px; height: 300px; margin: 0 auto"></div>
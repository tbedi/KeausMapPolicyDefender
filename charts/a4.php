<?php
$web_id = $_REQUEST['website_id'];
?>
<?php
//example sku
//$sku='GV-101-CH';
// getting last crawl
$sql = "select id, date_executed from crawl ORDER BY id DESC LIMIT 1";
$result = mysql_query ( $sql );
$last_crawl = mysql_fetch_assoc ( $result );

$limit = 10; // x in the Top x Products
           // Getting Top x Price violations by Product from last Crawl process
$sql = "select 
catalog_product_flat_1.sku as product,
crawl_results.violation_amount
from crawl_results
inner join
website
on website.id =crawl_results.website_id
AND website_id = $web_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id 
      WHERE crawl_id=".$last_crawl['id']." 
      AND violation_amount>0.05 
      ORDER BY crawl_results.violation_amount DESC LIMIT ".$limit;
$result = mysql_query ( $sql );

// collecting rows information
$chart_vendor_rows = array ();
$chart_violation_amount_rows = array ();
while ( $row = mysql_fetch_assoc ( $result ) ) {
  $chart_row = "'" . preg_replace('/[^A-Za-z0-9\-]/', '', $row['product']) . "'";
  array_push ( $chart_vendor_rows, $chart_row ); 
  array_push ( $chart_violation_amount_rows,  $row ['violation_amount']);
}


$js_data_string_vendors = implode ( $chart_vendor_rows, "," );
$js_data_string_amounts = implode ( $chart_violation_amount_rows, "," );
 
?>
 <script type="text/javascript">
 $(function () {
     $('#chart-a4').highcharts({
         chart: {
             type: 'column',
             margin: [ 50, 50, 100, 80]
         },
         title: {
             text: 'Violation by Sellers'
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

<div id="chart-a4"  style="width: 800px; height: 300px; margin: 0 auto"></div>
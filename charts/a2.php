<?php include_once 'db.php'; ?>
<?php 
 
//getting last crawl
$sql="select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result=mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);
	
$limit=10; // x in the Top x Products  
//Getting Top x Price violations by Product from last Crawl process
$sql="SELECT  w.`name`, COUNT(w.`name`) as violations   FROM crawl_results  r INNER JOIN website w ON r.website_id=w.id WHERE r.crawl_id=".$last_crawl['id']." AND r.violation_amount>1   GROUP BY w.`name` ORDER BY COUNT(w.`name`) DESC LIMIT ".$limit;
$result=mysql_query($sql);

//getting sum
$sum=0;

$items=array();

while($row = mysql_fetch_assoc($result)) {
	$sum+=$row['violations'];
	$item['name']=preg_replace('/[^A-Za-z0-9\-]/', '', $row['name']);
	$item['violations']=$row['violations'];
	array_push($items,$item);
}

//collecting rows information
$chart_rows=array();
 
foreach ($items as $product) {  
	$chart_row="['".$product['name']."',".round(100*$product['violations']/$sum,2)."]";
 	array_push($chart_rows,$chart_row);
 }
 
 $js_data_string=implode($chart_rows,",");
 print_r($js_data_string);
?>
<script type="text/javascript">
$(function () {	
 
	// Build the chart
    $('#chart-a2').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Price violation by Seller'
        },
        tooltip: {
            formatter: function () {
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
                        return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,2,'.') +' %';
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Price violation by Seller',
            data: [
                <?php echo $js_data_string;?>
            ]
        }]
    });
});
</script>

<div id="chart-a2" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
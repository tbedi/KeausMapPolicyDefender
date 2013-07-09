<?php include_once './db.php'; ?>
<?php 
 
//getting last crawl
$sql="select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result=mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);
	
$limit=10; // x in the Top x Products  
//Getting Top x Price violations by Product from last Crawl process
$sql="SELECT  p.sku, COUNT(p.sku) as violations  FROM crawl_results  r INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  WHERE r.crawl_id=".$last_crawl['id']." AND r.violation_amount>1   GROUP BY p.sku ORDER BY COUNT(p.sku) DESC LIMIT ".$limit;
$result=mysql_query($sql);

//getting sum
$sum=0;

$items=array();

while($row = mysql_fetch_assoc($result)) {
	$sum+=$row['violations'];
	$item['sku']=$row['sku'];
	$item['violations']=$row['violations'];
	array_push($items,$item);
}

//collecting rows information
$chart_rows=array();
 
foreach ($items as $product) {  
	$chart_row="['".$product['sku']."',".round(100*$product['violations']/$sum,2)."]";
 	array_push($chart_rows,$chart_row);
 }
 
 $js_data_string=implode($chart_rows,",");
 print_r($js_data_string);
?>
<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Price Violation by Product'
            },
            xAxis: {
                categories:<?php echo $product['sku']; ?>
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Violation Amount'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -100,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
             tooltip: {
            formatter: function () {
                           
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.data +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
             
                name: 'Product',
                data:   <?php echo $js_data_string;?>
            }]
        });
    });
    

</script>

<div id="chart-a3" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
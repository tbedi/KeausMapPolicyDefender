<?php
if (  (isset($_REQUEST['to'])  ||  isset($_REQUEST['from']) )  && ($_REQUEST['to']!=$_REQUEST['from']) ) {
	if (isset($_REQUEST['to']))
		$to_sql="'".date("Y-m-d 23:59:59",strtotime($_REQUEST['to']))."'";
		
	if (isset($_REQUEST['from']))
		$from_sql="'".date("Y-m-d 00:00:00",strtotime($_REQUEST['from']))."'";
} else {
	$from_sql=" CURDATE() - INTERVAL 29 DAY ";
	$to_sql="  CURDATE() + INTERVAL 1 DAY ";
}

$where="";
$name="";
if (isset($_REQUEST['product_id']) )
{
    $name_sql="SELECT sku from catalog_product_flat_1 WHERE entity_id=".$_REQUEST['product_id'];
    $name=$db_resource->GetResultObj($name_sql);
    $name=$name[0]->sku;
    
    $where=" AND  cr.product_id =".$_REQUEST['product_id']." ";   
}
 
$sql="SELECT date_executed  FROM crawl  WHERE date_executed  BETWEEN " .$from_sql. " AND  " .$to_sql." ORDER BY  date_executed DESC  LIMIT 30"; 
$last_30_days = array_reverse($db_resource->GetResultObj($sql));

$chart_vendor_rows = array();
$chart_violation_amount_rows = array();
foreach ($last_30_days as $day) {
    $chart_row = strtotime($day->date_executed) * 1000;
    array_push($chart_vendor_rows, $chart_row);
    $dealers_count_sql="SELECT SQL_CALC_FOUND_ROWS cr.website_id FROM crawl_results cr INNER JOIN  website w ON w.id=cr.website_id AND w.excluded=0   WHERE  cr.date_created='".date("Y-m-d",strtotime($day->date_executed))."' AND cr.violation_amount > 0.05 ".$where." GROUP BY    cr.website_id ORDER BY cr.date_created DESC LIMIT 1";
    $db_resource->GetResultObj($dealers_count_sql);
    $total_dealers_of_the_day_sql = " SELECT FOUND_ROWS() as total;";
    $total_dealers = $db_resource->GetResultObj($total_dealers_of_the_day_sql);
    $total_dealers = $total_dealers[0]->total;
    array_push($chart_violation_amount_rows, $total_dealers);
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
             colors: ['#000062'],
        
            chart: {
                renderTo: 'a6',
                defaultSeriesType: 'line',
                margin: [50, 50, 100, 80],
                zoomType: 'xy'
            },
            title: {
                text: 'Dealers Violated Daily <?php echo ($name ? "Of ".$name : "" ); ?>',
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
                        return Highcharts.dateFormat('%d %b %y', this.value);
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
                           'Dealers Violated: ' +  this.y;
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
                    name: 'Violations Count',
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
<div id="chart-a6" style="min-width: 530px; height: 350px; margin: 0 auto"></div>
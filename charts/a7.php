<?php
//if(isset($_REQUEST['limit'])){
//$limit=$_REQUEST['limit'];
//}
//else $limit=15;

if (isset($_REQUEST['website_id']) )
{
    $wname=$_REQUEST['website_id'];
    
    $name=$_REQUEST['wname'];
    $condition=" and website_id =".$wname." ";
}
else
{
    $name="";
    $condition="";
}


$sql = "select Violations_amount, DateExec
from
(select 
count(*) as Violations_amount,
 crawl.date_executed as DateExec		 
from crawl_results res
inner join catalog_product_flat_1 prods on prods.entity_id = res.product_id
inner join website sites on sites.id = res.website_id
inner join crawl on crawl.id = res.crawl_id
where
violation_amount > 0.05 
and sites.excluded = 0 ". $condition. " 
and (date_format(crawl.date_executed,'%Y-%m-%d') between '" .$from. "'and '" .$to."')
group by crawl.date_executed
order by crawl.date_executed desc ) as yy order by DateExec";

//order by crawl.date_executed desc limit 0," . $limit. " ) as yy order by DateExec";


//echo $sql;

$result = mysql_query($sql);


//echo $sql;
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
                zoomType: 'xy'
            },
            title: {
                text: 'Violation Count By Dealer <?php echo $name ?>',
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
                    
                        return '' + Highcharts.numberFormat(this.value / 1, 0) ;                
    }
                },
            
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + Highcharts.dateFormat('%a %d %b %Y', this.x) + '</b><br/>' +
                           'Price Violations: ' +  this.y;
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
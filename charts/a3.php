<?php
// collecting rows information
$chart_vendor_rows = array();
$chart_violation_amount_rows = array();
if (isset($_REQUEST['product_id']) )
{
	$name_sql="SELECT sku from catalog_product_flat_1 WHERE entity_id=".$_REQUEST['product_id'];
	$name=$db_resource->GetResultObj($name_sql);
	$sku=$name[0]->sku;
    
}

foreach ($violators_array as $violator) {
    $chart_row = "'" . preg_replace('/[^A-Za-z0-9\-]/', '', $violator->vendor) . "'";
    array_push($chart_vendor_rows, $chart_row);
    array_push($chart_violation_amount_rows, $violator->violation_amount);
}

$js_data_string_vendors = implode($chart_vendor_rows, ",");
$js_data_string_amounts = implode($chart_violation_amount_rows, ",");
?>
<script type="text/javascript">
    $(function () {
    $('#chart-a3').highcharts({
    chart: {
    type: 'column',
            zoomType: 'xy',
            margin: [ 50, 50, 160, 150],
    },
            title: {
    text: 'Amount violations of <?php echo $sku ?> for as <?php echo date("d/m/Y",strtotime($datecp[0]->date_executed)) ?>',
    },
            xAxis: {
    categories: [
<?php echo $js_data_string_vendors; ?>
    ],
            labels: {
    rotation: - 45,
            align: 'right',
            style: {
    fontSize: '12px',
            fontFamily: 'Verdana, sans-serif'
    }
    }
    },
            yAxis: {
    min: 0,
            title: {
    text: 'Violation amount'
    },
    labels: {
    formatter: function() {

    return '$' + Highcharts.numberFormat(this.value / 1, 0);
    }
    }
    },
            legend: {
    enabled: false
    },
            tooltip: {
    formatter: function() {
    return '<b>' + this.x + '</b><br/>' +
            'Price Violation: ' + Highcharts.numberFormat(this.y, 2) +
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
            rotation: - 90,
            color: '#FFFFFF',
            align: 'right',
            x: 4,
            y: 4,
            style: {
    fontSize: '10px',
            fontFamily: 'Verdana, sans-serif'
    }
    }
    }]
    });
    });


</script>

<div id="chart-a3"  style="width: 600px; height: 400px; margin: 0 auto"></div>
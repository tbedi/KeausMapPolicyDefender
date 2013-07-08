

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="js/highcharts1.js"></script>
<script src="js/exporting.js"></script>

		<script type="text/javascript">
$(function () {
    	
    	// Radialize the colors
		Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
		    return {
		        radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
		        stops: [
		            [0, color],
		            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		        ]
		    };
		});
		
		// Build the chart
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Price Violation By Seller '
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
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
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Violation',
                data: [
                    ['Open Box Savings', 10.2  ],
                    ['Home Depot',       26.8],
                    {
                        name: 'Culinary Depot',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['CookwarePro',    15.5],
                    ['Sprinkler Warehouse',   34.0  ],
                    ['TigerChef',   0.7]
                ]
            }]
        });
    });

		</script>


<div id="container" style="min-width: 250px; height: 250px; margin: 0 auto"></div>


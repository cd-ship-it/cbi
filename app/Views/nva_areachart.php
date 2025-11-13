<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>area chart</title>
 
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

 

</head>

<body>


                                    <div class="chart-area">
                                        <canvas style="height: 300px;" id="myAreaChart"></canvas>
                                    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/elearning/vendor/jquery/jquery.min.js'); ?>"></script> 
	<!-- Page level plugins --> 
    <script src="<?= base_url('assets/elearning/vendor/chart.js/Chart.min.js'); ?>"></script>
	
    <script type="text/javascript">
 
 
 <?php

 $data = [];
 $color = ['red','green','blue','darkorange','darkorchid','brown','black'];
 
  if($areachart_data && count($areachart_data)){
	  
	 $data['labels'] = array_keys($areachart_data[0]['data']);
	 
	 foreach($areachart_data as $key => $item){
		 
		$data['datasets'][$key]['label'] = $item['campus'];
		$data['datasets'][$key]['lineTension'] = 0.3;
		$data['datasets'][$key]['backgroundColor'] = "rgba(78, 115, 223, 0.05)";
		$data['datasets'][$key]['borderColor'] = $color[$key];
		$data['datasets'][$key]['pointRadius'] = 3;
		$data['datasets'][$key]['pointBackgroundColor'] = $color[$key];
		$data['datasets'][$key]['pointBorderColor'] =  $color[$key];
		$data['datasets'][$key]['pointHoverRadius'] =3;
		$data['datasets'][$key]['pointHoverBackgroundColor'] = "rgba(78, 115, 223, 1)";
		$data['datasets'][$key]['pointHoverBorderColor'] = "rgba(78, 115, 223, 1)";
		$data['datasets'][$key]['pointHitRadius'] = 10;
		$data['datasets'][$key]['pointBorderWidth'] = 2;
		$data['datasets'][$key]['data'] = array_values($item['data']);
	 }
 
 
  }
 
 ?>
 
 
 
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: <?= (json_encode($data)); ?>,
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ':' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});	


    </script>	
	
</body>

</html>

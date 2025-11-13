<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>pie chart</title>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
 
 
		
		
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(function(){
        var data = google.visualization.arrayToDataTable(<?= (json_encode($piechart_data, JSON_NUMERIC_CHECK)); ?>);
        var options = {
          title: '<?= $piechart_option_title; ?>',
          slices: {
            0: { color: 'red' },
            1: { color: 'blue' },
            2: { color: 'orange' },
            3: { color: 'green' },
            4: { color: 'blueviolet' },
          }
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);

      });		


    </script>
</head>

<body>
 
                                    <div id="piechart" style="height: 300px;"></div>
                              




    <!-- Page level plugins -->  
    <script src="<?= base_url('assets/elearning/vendor/chart.js/Chart.min.js'); ?>"></script>
	
	
</body>

</html>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      google.load('visualization', '1.0', {'packages':['corechart']});

      google.setOnLoadCallback(drawChart);

      function drawChart() {

        //Cria a tabela.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Contribuinte');
        data.addColumn('number', 'Valor');
        data.addRows([
        	<?php foreach ($contribuicoes as $indice => $contribuicao) { ?>
          ['<?= $indice ?>', <?= $contribuicao ?>],
          <?php } ?>
        ]);

        // Set chart options
        var options = {
            pieStartAngle: 100,
           
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <p></p>
  <div id="chart_div" style="width: 72.2%; height: 400px;"></div>

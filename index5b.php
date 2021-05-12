
<!DOCTYPE html>
<html>
 <head>
  <title>Ab SCADA Load profile</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <style>
  
  </style>

 </head>
 <body>
  <br /><br />
  <div class="container" style="width:900px;">
   <h2 align="center">SCADA Load profile</h2>

   <div class="form-group" id="catRadio">
     <label class="form-check-label">
       <input type="radio" name="category" value="subload" required> MW
     </label>
     <label class="form-check-label">
       <input type="radio" name="category" value="submvar" > MVar
     </label>
     <label class="form-check-label">
       <input type="radio" name="category" value="kV" > kV
     </label>
   </div>

   <div class="row">
    <div class="col-md-3">
      <label>Substation</label>
      <select name="sub" id="sub" name="sub" class="form-control">
        <option value="">Select Substation</option>
      </select>
    </div>

     <div class="col-md-3">
       <label>Feeder</label>
       <select name="feeder" id="feeder" name="feeder" class="form-control">
         <option value="">Select Feeder</option>
       </select>
     </div>

     <div class="col-md-3">
         <label for="startDate">Start:</label>
         <input type="date" id="startDate" name="startDate" class="form-control">
     </div>

     <div class="col-md-3">
         <label for="endDate">End:</label>
         <input type="date" id="endDate" name="endDate" class="form-control">
     </div>
    </div>
    <br />

    <div class="row">
      <div class="col-md-4">
        <button type="button" name="search" id="search" class="btn btn-info">Search</button>
      </div>
    </div>
   </div>
   <br />

   <figure class="highcharts-figure">
     <div id="chart-container"></div>
   </figure>

 </body>
</html>

<script>
$(document).ready(function(){
 $('#search').click(function(){
  var catRadio = $('input[type="radio"]').val();
  var sub= $('#sub').val();
  var feeder = $('#feeder').val();
  var startDate = $('#startDate').val();
  var endDate = $('#endDate').val();

  if(catRadio != '' && sub != '' && feeder != '' && startDate != '' && endDate != '')
  {
   $.ajax({
    url:"data4.php",
    method:"POST",
    data:{sub:sub,
          feeder:feeder,
          catRadio:catRadio,
          startDate:startDate,
          endDate:endDate
         },
    dataType:"JSON",
    success:function(data)
    {
     // console.log(data);
     highchartsPlot(data);
    }
   })
  }
  else
  {
   alert("Please fill all the filed!");
  }
 });
});

function highchartsPlot(data){
  console.log(data);
  let timeAxis = [];
  let yAxis = [];
  let RadioSelected = document.getElementsByName("category");
  
  if (RadioSelected[0].checked) {
    for (let i in data) {
      timeAxis.push(data[i].time);
      yAxis.push(data[i].subload);
    }

    var myChart = Highcharts.chart('chart-container', {

      chart: {
        type: 'area'
      },
      title: {
        text: 'Load Curve'
      },
      subtitle: {
        text: 'Sources: SMC'
      },
      xAxis: {
        categories: timeAxis,
        allowDecimals: false
      },
      yAxis: {
        title: {
          text: 'Power (MW)'
        },
        labels: {
          formatter: function () {
            return this.value;
          }
        }
      },
      tooltip: {
        pointFormat: '{point.y} MW'
      },
      plotOptions: {
        area: {
          marker: {
            enabled: false,
            symbol: 'circle',
            radius: 2,
            states: {
              hover: {
                enabled: true
              }
            }
          }
        }
      },
      series: [{
        name: 'MW',
        data: yAxis
      }]
    });
  } else if (RadioSelected[1].checked) {
    for (let i in data) {
      timeAxis.push(data[i].time);
      yAxis.push(data[i].submvar);
    }

    var myChart = Highcharts.chart('chart-container', {

      chart: {
        type: 'area'
      },
      title: {
        text: 'Load Curve'
      },
      subtitle: {
        text: 'Sources: SMC'
      },
      xAxis: {
        categories: timeAxis,
        allowDecimals: false
      },
      yAxis: {
        title: {
          text: 'Reactive Power (MVar)'
        },
        labels: {
          formatter: function () {
            return this.value;
          }
        }
      },
      tooltip: {
        pointFormat: '{point.y} MVar'
      },
      plotOptions: {
        area: {
          marker: {
            enabled: false,
            symbol: 'circle',
            radius: 2,
            states: {
              hover: {
                enabled: true
              }
            }
          }
        }
      },
      series: [{
        name: 'MVar',
        data: yAxis
      }]
    });
  } else {
    let yAxis2 = [];
    let yAxis3 = [];
    for (let i in data) {
      timeAxis.push(data[i].time);
      yAxis.push(data[i].subvab);
      yAxis2.push(data[i].subvbc);
      yAxis3.push(data[i].subvca);
    }

      // console.log(timeAxis);
      // console.log(yAxis);

    var myChart = Highcharts.chart('chart-container', {

      title: {
        text: 'Voltage profile'
      },
      subtitle: {
        text: 'Sources: SMC'
      },
      xAxis: {
        categories: timeAxis,
        allowDecimals: false
      },
      yAxis: {
        title: {
          text: 'kV'
        }
      },
      tooltip: {
        pointFormat: '{point.y} kV'
      },
      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
      },
      plotOptions: {
        series: {
          label: {
            connectorAllowed: false
          }
        }
      },
      series: [{
        name: 'Vab',
        data: yAxis
      },
      {
        name: 'Vbc',
        data: yAxis2
      },
      {
        name: 'Vca',
        data: yAxis3
      }]
    });
  }
}
</script>

<script src="substation_dropdown3.js"></script>

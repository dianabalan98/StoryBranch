function makeGraphic(labelsArr, dataArr, colors, chartType, labelStr) {

  var myChart = document.getElementById('myChart').getContext('2d');
  var massPopChart = new Chart(myChart, {
    type: chartType, //bar, horizontan bar, pie, line, radar, doughnut
    data:{
      labels: labelsArr,  // array
      datasets:[{  // array of objects
        label: labelStr,
        data: dataArr,
        backgroundColor: colors
      }],
    },  // {} = obiect
    options: {
        responsive: false,
        maintainAspectRatio: false,
        title: {
            display: true,
            text: labelStr
        }
    }
  });
}


function randomColors(number) {
  var rgbArr = []

  for (i = 0; i < number; i++) {

  var r = Math.floor(Math.random()*256);   // Random between 0-255
  var g = Math.floor(Math.random()*256);   // Random between 0-255
  var b = Math.floor(Math.random()*256);

  rgb = 'rgba('+r+', '+g+', '+b+', 0.4)'
  rgbArr.push(rgb)
  }

  return rgbArr        	
}


function addCanvas(width, height) {
    if($("#myChart").length > 0) $("#myChart").remove()

    canvas = "<canvas id='myChart' width='" + width + "' height='" + height + "'></canvas>"
    $("#canvas-holder").append(canvas)
}
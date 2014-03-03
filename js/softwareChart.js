

var data = {
  labels : ["Sublime Text","PhotoShop","Final Cut","Illustrator","Linux"],
  datasets : [
    {
      fillColor : "#3497a3",
      data : [75,100,75,81,56]
    }
    
  ]
}


var baroptions = {
        
  //Boolean - If we show the scale above the chart data     
  scaleOverlay : false,
  
  //Boolean - If we want to override with a hard coded scale
  scaleOverride : false,
  
  //** Required if scaleOverride is true **
  //Number - The number of steps in a hard coded scale
  scaleSteps : null,
  //Number - The value jump in the hard coded scale
  scaleStepWidth : null,
  //Number - The scale starting value
  scaleStartValue : null,

  //String - Colour of the scale line 
  scaleLineColor : "rgba(0,0,0,0)",
  
  //Number - Pixel width of the scale line  
  scaleLineWidth : 0,

  //Boolean - Whether to show labels on the scale 
  scaleShowLabels : false,
  
  //Interpolated JS string - can access value
  scaleLabel : "<%=value%>",
  
  //String - Scale label font declaration for the scale label
  scaleFontFamily : "'helvetica'",
  
  //Number - Scale label font size in pixels  
  scaleFontSize : 12,
  
  //String - Scale label font weight style  
  scaleFontStyle : "normal",
  
  //String - Scale label font colour  
  scaleFontColor : "#666",  
  
  ///Boolean - Whether grid lines are shown across the chart
  scaleShowGridLines : false,
  
  //Boolean - If there is a stroke on each bar  
  barShowStroke : false,

  //Number - Spacing between each of the X value sets
  barValueSpacing : 5,
  
  //Number - Spacing between data sets within X values
  barDatasetSpacing : 0,
  
  //Boolean - Whether to animate the chart
  animation : true,

  //Number - Number of animation steps
  animationSteps : 60,
  
  //String - Animation easing effect
  animationEasing : "easeOutQuart",

  //Function - Fires when the animation is complete
  onAnimationComplete : null
  
}

//Get the context of the canvas element we want to select
var ctx = document.getElementById("softwareChart").getContext("2d");
var myNewChart = new Chart(ctx).Bar(data);

new Chart(ctx).Bar(data,baroptions);


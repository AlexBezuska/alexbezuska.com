
var options = {
  //Boolean - Whether we should show a stroke on each segment
  segmentShowStroke : false,
  
  //String - The colour of each segment stroke
  segmentStrokeColor : "#fff",
  
  //Number - The width of each segment stroke
  segmentStrokeWidth : 2,
  
  //The percentage of the chart that we cut out of the middle.
  percentageInnerCutout : 75,
  
  //Boolean - Whether we should animate the chart 
  animation : true,
  
  //Number - Amount of animation steps
  animationSteps : 100,
  
  //String - Animation easing effect
  animationEasing : "easeOutBounce",
  
  //Boolean - Whether we animate the rotation of the Doughnut
  animateRotate : true,

  //Boolean - Whether we animate scaling the Doughnut from the centre
  animateScale : false,
  
  //Function - Will fire on animation completion.
  onAnimationComplete : null
};


var htmlData = [
  { value : 25, color : "#E2EAE9"},
    { value: 75, color:"#3497a3" }
  
  ]
  var ctxHtml = document.getElementById("skillsChart-html").getContext("2d");
var ctxHtmlChart = new Chart(ctxHtml).Doughnut(htmlData);
new Chart(ctxHtml).Doughnut(htmlData, options);


//  var csssass = [
//  { value : 10, color : "#E2EAE9"},
//      { value: 90, color:"#3497a3" }
    
//    ]
// var ctxcsssass = document.getElementById("skillsChart-csssass").getContext("2d");
// var ctxHtmlCcsssass = new Chart(ctxcsssass).Doughnut(csssass);
// new Chart(ctxcsssass).Doughnut(csssass, options);


//  var javascript = [
//  { value : 35, color : "#E2EAE9"},
//      { value: 65, color:"#3497a3" }
//    ]
// var ctxjavascript = document.getElementById("skillsChart-javascript").getContext("2d");
// var ctxjavascriptChart = new Chart(ctxHtml).Doughnut(javascript);
// new Chart(ctxjavascript).Doughnut(javascript, options);


//  var csharp = [
//  { value : 50, color : "#E2EAE9"},
//      { value: 50, color:"#3497a3" }
     
//    ]
// var ctxcsharp = document.getElementById("skillsChart-csharp").getContext("2d");
// var ctxcsharpChart = new Chart(ctxcsharp).Doughnut(csharp);
// new Chart(ctxcsharp).Doughnut(csharp, options);


//  var webDesign = [
//  { value : 5, color : "#E2EAE9"},
//      { value: 95, color:"#3497a3" }
     
//    ]
// var ctxwebDesign = document.getElementById("skillsChart-webDesign").getContext("2d");
// var ctxwebDesignChart = new Chart(ctxwebDesign).Doughnut(webDesign);
// new Chart(ctxwebDesign).Doughnut(webDesign, options);


//  var userExperience = [
//  { value : 25, color : "#E2EAE9"},
//      { value: 75, color:"#3497a3" }
     
//    ]
// var ctxuserExperience = document.getElementById("skillsChart-userExperience").getContext("2d");
// var ctxuserExperienceChart = new Chart(ctxuserExperience).Doughnut(userExperience);
// new Chart(ctxuserExperience).Doughnut(userExperience, options);














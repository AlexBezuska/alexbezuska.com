function generatePortfolio (category, pageLocaion){
  var html = "";
  for(var i = 0; i < data.length; i++){
    if (data[i].show && data[i].category === category){
      html += "<div class='col1-4'><div class='item module'><a href='";
      if(data[i].link){
        html += data[i].link;
      }
      html += "' target='_blank'><div class='img-box'><img src='"+ data[i].img +"'  alt='"+data[i].name+"'></div><div class='caption'>"+data[i].name+"</div></a></div></div>";
    }
  }

  $(pageLocaion).append(html);
}

function generateItem (data){
  return "<div class='col1-4'><div class='item module'><a href='" + data.link + "' target='_blank'><div class='img-box'><img src='"+ data.img +"'  alt='" + data.name + "'></div><div class='caption'>" + data.name + "</div><div class='hover'>" + data.hover + "</div><div class='hover-button'>" + data.hoverbutton + "</div></a></div></div>";
}

function generatePortfolio (category, pageLocation){
  var html = "";
  for(var i = 0; i < data.length; i++){
    if (data[i].show && data[i].category === category){
      html += generateItem( data[i] );
    }
  }
  $(pageLocation).append(html);
}

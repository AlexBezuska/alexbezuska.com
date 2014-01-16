

/*
  schema

  title
  type
  url
  iconurl
*/
Meteor.startup(function () {
    // Links.insert({
    //    'title' : 'twitter'
    //   ,'type' : 'external'
    //   ,'url' : 'http://www.twitter.com/alexbezuska'
    //   ,'iconurl' : '#'
    // }); 
    // alert("startup");
  });

 

Template.externalLinks.externalLink = function () {
  return Links.find(
     {}
    ,{'type' : 'external' }
    ,{sort :{'title': 1}}
  );
};



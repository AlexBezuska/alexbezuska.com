var fs = require("fs");
var handlebars = require("handlebars");

var pages = require("./source/data/pages.json");

createPages(pages);

function createPages(pages) {
  for (var i = 0; i < pages.length; i++) {
    var page = pages[i];
    createPage(page);
  }
}

function createPage(page) {
  var html = "";
  var parts = page.parts;
  for (var i = 0; i < parts.length; i++) {
    var data = [];
    if (parts[i].data) {
      data = require("./source/data/" + parts[i].data + ".json");
      var templateFile = "./source/templates/" + parts[i].template + ".hbs";
      html += renderFromExternalTemplate(templateFile, data);
    } else if (parts[i].template === "header"){
      var templateFile = "./source/templates/" + parts[i].template + ".hbs";
      data = {
        meta: require("./source/data/meta.json"),
        pages: getPageLinks(pages)
      };
      html += renderFromExternalTemplate(templateFile, data);
    }else {
      var template = fs.readFileSync("./source/templates/" + parts[i].template + ".hbs", "utf8");
      html += template;
    }
  }
  var file = "./build/" + page.fileName;

  fs.writeFileSync(file, html);
  console.log("WRITE FILE:", file);
}


function getPageLinks(pages) {
  var data = [];
    for (var i = 0; i < pages.length; i++) {
      data.push({
        "fileName": pages[i].fileName,
        "pageName": pages[i].pageName
      });
    }
    return data;
}

function renderFromExternalTemplate(templateFile, data){
  var file = fs.readFileSync(templateFile, "utf8");
  var template = handlebars.compile(file);
  return template(data);
}

function replaceAll(str, find, replace) {
  return str.replace(new RegExp(find, 'g'), replace);
}

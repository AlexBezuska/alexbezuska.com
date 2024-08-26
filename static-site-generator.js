var fs = require("fs");
var path = require("path");
var handlebars = require("handlebars");
var copydir = require("copy-dir");

var site = require("./site.json");

var src = "./src";
var dest = "./dest";

makeDirIfNotExist(dest);

console.log(site);
var template = fs.readFileSync(path.join(src, "index.hbs"), "utf8");
var compiledTemplate = handlebars.compile(template);
var html = compiledTemplate(site);
fs.writeFileSync(path.join(dest, "index.html"), html);

copyDirectory(path.join(src, "css"), path.join(dest, "css"));
copyDirectory(path.join(src, "img"), path.join(dest, "img"));
copyDirectory(path.join(src, "js"), path.join(dest, "js"));

function makeDirIfNotExist(filePath) {
  if (!fs.existsSync(filePath)) {
    fs.mkdirSync(filePath, { recursive: true });
  }
}

function copyDirectory(srcDir, destDir) {
  if (fs.existsSync(srcDir)) {
    copydir.sync(srcDir, destDir, { utimes: true, mode: true, cover: true });
  }
}

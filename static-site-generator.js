const fs = require("fs");
const path = require("path");
const handlebars = require("handlebars");
const copydir = require("copy-dir");

const src = "./src";
const dest = "./dest";
const dataDir = path.join(__dirname, "data");
const partialsDir = path.join(src, "partials");

// Register Handlebars helpers
handlebars.registerHelper("isEven", (value) => value % 2 === 0);
handlebars.registerHelper("dashify", (str) => {
  if (typeof str !== "string") return "";
  return str.toLowerCase().replace(/\s+/g, '-');
});
handlebars.registerHelper("json", function (context) {
  return JSON.stringify(context, null, 2);
});

handlebars.registerHelper('even', function(index) {
  return index % 2 === 0;
});


// Initialize site data object
const site = {};

// Load all JSON files in /data
fs.readdirSync(dataDir).forEach((file) => {
  if (file.endsWith(".json")) {
    const key = path.basename(file, ".json").replace(/-([a-z])/g, (_, c) => c.toUpperCase());
    const data = require(path.join(dataDir, file));

    // Special-case for site.json: merge directly into root
    if (key === "site") {
      Object.assign(site, data);
    } else {
      site[key] = data;
    }
  }
});

// DEBUG: Output the site object to console
console.log("✅ Loaded site data:");
console.log(JSON.stringify(site, null, 2));

// Register Handlebars partials from /src/partials
if (fs.existsSync(partialsDir)) {
  fs.readdirSync(partialsDir).forEach((filename) => {
    if (filename.endsWith(".hbs")) {
      const name = path.basename(filename, ".hbs");
      const content = fs.readFileSync(path.join(partialsDir, filename), "utf8");
      handlebars.registerPartial(name, content);
    }
  });
}

// Ensure destination directory exists
makeDirIfNotExist(dest);

// Compile and write HTML
const template = fs.readFileSync(path.join(src, "index.hbs"), "utf8");
const compiledTemplate = handlebars.compile(template);
const html = compiledTemplate(site);
fs.writeFileSync(path.join(dest, "index.html"), html);

// Copy static assets
copyDirectory(path.join(src, "css"), path.join(dest, "css"));
copyDirectory(path.join(src, "img"), path.join(dest, "img"));
copyDirectory(path.join(src, "icons"), path.join(dest, "icons"));
copyDirectory(path.join(src, "js"), path.join(dest, "js"));

// Helpers
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

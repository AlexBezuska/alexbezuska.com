var gulp = require('gulp');
var less = require('gulp-less');
var autoprefixer = require('gulp-autoprefixer');
var minifyCSS = require('gulp-minify-css');
var gutil = require('gulp-util');
var webserver = require('gulp-webserver');
var clean = require('gulp-clean');

var paths = {
  src: 'src/**/*',
  srcHTML: 'src/**/*.html',
  srcCSS: 'src/**/*.css',
  srcJS: 'src/**/*.js',
  srcImg: 'src/img/**/*',
  srcFonts: 'src/fonts/**/*',

  srcLess: 'src/less/main.less',

  tmp: 'tmp',
  tmpIndex: 'tmp/index.html',
  tmpCSS: 'tmp/**/*.css',
  tmpJS: 'tmp/**/*.js',

  dist: 'dist',
  distIndex: 'dist/index.html',
  distCSS: 'dist/**/*.css',
  distJS: 'dist/**/*.js'
};

gulp.task('html', function () {
  return gulp.src(paths.srcHTML).pipe(gulp.dest(paths.tmp));
});

gulp.task('css', function () {
  return gulp.src(paths.srcCSS).pipe(gulp.dest(paths.tmp));
});

gulp.task('less', function () {
  return gulp.src(paths.srcLess)
    .pipe(less({compress: true}).on('error', gutil.log))
    .pipe(autoprefixer('last 10 versions', 'ie 9'))
    .pipe(minifyCSS({keepBreaks: false}))
    .pipe(gulp.dest(paths.tmp + "/css/"));
});

gulp.task('js', function () {
  return gulp.src(paths.srcJS).pipe(gulp.dest(paths.tmp));
});

gulp.task('img', function () {
  return gulp.src(paths.srcImg).pipe(gulp.dest(paths.tmp + "/img/"));
});

gulp.task('fonts', function () {
  return gulp.src(paths.srcFonts).pipe(gulp.dest(paths.tmp + "/fonts/"));
});

gulp.task('copy', ['html', 'css', 'less', 'js', 'img', 'fonts']);

gulp.task('serve', ['copy'], function () {
  return gulp.src(paths.tmp)
    .pipe(webserver({
      port: 3000,
      livereload: true
    }));
});

gulp.task('default', function () {
  console.log('Hello World!');
});

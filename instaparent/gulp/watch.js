'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var livereload = require('gulp-livereload');

function isOnlyChange(event) {
  return event.type === 'changed';
}



gulp.task('watch', ['build'], function() {

  gulp.watch('./insta-common/bootstrap/css/vendor/**/*', ['styles:vendor']);

  //gulp.watch('./js/src/**/*.js', ['scripts']);

  //gulp.watch('./papi/**/*.tmpl', ['templates']);

  gulp.watch(path.join(conf.paths.src, '/app/**/*.html'), function(event) {
    //browserSync.reload(event.path);
  });
});


gulp.task('build', ['styles']);
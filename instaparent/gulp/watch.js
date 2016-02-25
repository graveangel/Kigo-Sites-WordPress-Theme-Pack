'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var livereload = require('gulp-livereload');

function isOnlyChange(event) {
  return event.type === 'changed';
}

var src = {};
src.bootstrap = './insta-common/bootstrap';

gulp.task('watch', ['build'], function() {

  gulp.watch(src.bootstrap+'/css/vendor/**/*', ['styles:bootstrap']);
  gulp.watch(src.bootstrap+'/css/custom/**/*', ['styles:bootstrap']);


  gulp.watch(src.bootstrap+'/js/src/**/*.js', ['scripts']);

  //gulp.watch('./papi/**/*.tmpl', ['templates']);

  gulp.watch(path.join(conf.paths.src, '/app/**/*.html'), function(event) {
    //browserSync.reload(event.path);
  });
});


gulp.task('build', ['styles', 'scripts']);
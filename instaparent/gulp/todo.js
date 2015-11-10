'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var $ = require('gulp-load-plugins')();

gulp.task('todo', function () {
  return gulp.src(path.join(conf.paths.src, './js/*.js'))
    .pipe($.todo({
    	absolute: true
    }))
    .pipe(gulp.dest('./'));
});

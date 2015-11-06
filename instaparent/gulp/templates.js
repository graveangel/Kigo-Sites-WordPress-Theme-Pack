'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var $ = require('gulp-load-plugins')();

gulp.task('templates', function () {
  	return gulp.src('./papi/src/**/*.tmpl')
	    .pipe($.concat('papi.ui.mustache.tmpl'))
	    .pipe(gulp.dest('./papi/'))
	    .pipe($.notify('Templates are done!'));
});

'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');
var sourcemaps = require('gulp-sourcemaps');
var $ = require('gulp-load-plugins')();
var jsSrc = path.join(conf.paths.src, './insta-common/bootstrap/js/src/');

gulp.task('scripts:common', function () {

  	return gulp.src([
  			path.join(jsSrc, 'bootstrap.js'),
  			path.join(jsSrc, 'bootstrap-paginator.min.js'),
  			path.join(jsSrc, 'bootstrap-dropdown.js'),
  			path.join(jsSrc, 'flexslider.js')
  		])
	  	.pipe(sourcemaps.init())
	    .pipe($.jshint())
	    .pipe($.jshint.reporter('jshint-stylish'))
	    .pipe($.concat('insta-common.js'))
	    //.pipe($.uglify())
	    .pipe($.size())
	    .pipe(sourcemaps.write('../sourcemaps'))
	    .pipe(gulp.dest('./insta-common/bootstrap/js'))
	    .pipe($.notify('Scripts are done!'));
});


gulp.task('scripts', ['scripts:common']);
'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var sourcemaps = require('gulp-sourcemaps');



var $ = require('gulp-load-plugins')();

gulp.task('scripts', ['todo'], function () {
	var banner = '/* <%= pkg.name %> <%= pkg.version %> | <%= new Date() %> */\n';

  	return gulp.src(path.join(conf.paths.src, './js/src/**/*.js'))
	  	.pipe(sourcemaps.init())
	    .pipe($.jshint())
	    .pipe($.jshint.reporter('jshint-stylish'))
	    .pipe($.concat('scripts.min.js'))
	    .pipe($.uglify())
	    //.pipe($.header(banner, {pkg: conf.pkg}))
	    .pipe($.size())
	    .pipe(sourcemaps.write('../sourcemaps'))
	    .pipe(gulp.dest('./js'))
	    .pipe($.notify('Scripts are done!'));
});

'use strict';

var gulp = require('gulp'),
  $ = require('gulp-load-plugins')({ camelize: true }),
  sourcemaps = require('gulp-sourcemaps'),
  merge = require('merge2'),
  src = {};

src.bootstrap = './insta-common/bootstrap/css';
src.vendor = src.bootstrap+'/vendor';
src.custom = src.bootstrap+'/custom';


gulp.task('styles:bootstrap', function() {
  var vendorStream = gulp.src([
      src.vendor+'/normalize.css',
      src.vendor+'/bootstrap.css',
      src.vendor+'/bootstrap-responsive.css',
      src.vendor+'/glyph-icons/**/*',
      src.vendor+'/flag-sprites.css',
      src.vendor+'/flexslider.min.css',
      src.vendor+'/pickadate/**/*',
      src.vendor+'/dropdown.css'
    ])
    .pipe(sourcemaps.init())
    .pipe($.concat('vendor-files.css'));

  var customStream = gulp.src([
      src.custom+'/**/*.scss'
    ])
    .pipe($.sass({sourcemaps:true}));

  var mergedStream = merge(vendorStream, customStream)
    .pipe($.concat('insta-common.css'))
    .pipe(sourcemaps.write('../sourcemaps'))
    .pipe($.minifyCss())
    .pipe(gulp.dest('./insta-common/bootstrap/css'))
    .pipe($.notify({
      message: 'Styles are done!',
      onLast: true
    }));

  return mergedStream;

});

gulp.task('styles', ['styles:bootstrap']);
'use strict';

var gulp = require('gulp'),
  $ = require('gulp-load-plugins')({ camelize: true }),
  sourcemaps = require('gulp-sourcemaps'),
  src = {};

src.vendor = './insta-common/bootstrap/css/vendor';


gulp.task('styles:vendor', function() {
  gulp.src([
      src.vendor+'/normalize.css',
      src.vendor+'/bootstrap.css',
      src.vendor+'/bootstrap-responsive.css',
      src.vendor+'/glyph-icons/**/*',
      src.vendor+'/flag-sprites.css',
      src.vendor+'/flexslider.min.css',
      src.vendor+'/pickadate/**/*',
      src.vendor+'/dropdown.css',
      './insta-common/bootstrap/css/mainstyle.css'
    ])
    .pipe(sourcemaps.init())
    .pipe($.concatCss('mainstyle.min.css'))
    .pipe($.minifyCss())
    .pipe(sourcemaps.write('../sourcemaps'))
    .pipe(gulp.dest('./insta-common/bootstrap/css'))
    .pipe($.notify('Styles are done!'));
});

gulp.task('styles', ['styles:vendor']);
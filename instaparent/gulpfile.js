'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var babel = require('gulp-babel');
var livereload = require('gulp-livereload');


/* Default */

gulp.task('default', ['siteStyles', 'customScripts', 'styles:watch', 'scripts:watch', 'papiTemplates', 'papi:watch','lr']);

/* Styles */

var siteStyles = ['./scss/**/*.scss'];

gulp.task('siteStyles', function () {
    return gulp.src(siteStyles)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./css'))
        .pipe(livereload());
});

gulp.task('styles:watch', function () {
    gulp.watch(siteStyles, ['siteStyles']);
});

/* Scripts */

var customScripts = ['./js/src/**/*.js'];

gulp.task('customScripts', function() {
    return gulp.src(customScripts)
        .pipe(sourcemaps.init())
        //.pipe(babel( {presets: ['es2015']} ))
        //.pipe(concat('scripts.js'))
        .pipe(uglify({compress: true}))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./js'))
        .pipe(livereload());
});

gulp.task('scripts:watch', function () {
    gulp.watch(customScripts, ['customScripts']);
});

/* Mustache Templates */

var papiPath = 'papi/',
    papiPartials = papiPath+'**/*.tmpl';

gulp.task('papiTemplates', function() {
    return gulp.src(papiPartials)
        .pipe(concat('papi.ui.mustache.tmpl'))
        .pipe(gulp.dest(papiPath));
});


gulp.task('papi:watch', function () {
    gulp.watch(papiPartials, ['papiTemplates']);
});

/* Livereload */

gulp.task('lr', function () {
    livereload.listen();
});

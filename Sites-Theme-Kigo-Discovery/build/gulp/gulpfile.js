/*
* Installation: Move 'package.json' to root theme folder & install.
* Run 'gulpfile.js' from /build/gulp fodler
* */


'use strict';

var     gulp = require('gulp')
    ,   sass = require('gulp-sass')
    ,   concat = require('gulp-concat')
    ,   uglify = require('gulp-uglify')
    ,   cssmin = require('gulp-cssmin')
    ,   header = require('gulp-header')
    ,   rename = require('gulp-rename')
    ,   livereload = require('gulp-livereload');

var kdCommonPath = '../../kd-common/';

/* Styles */

var stylesOrigin = kdCommonPath+'scss/**/*.scss',
    mainStyle = [kdCommonPath+'scss/main.scss', kdCommonPath+'scss/admin.scss'],
    stylesDestination = kdCommonPath+'css';

gulp.task('styles', function () {
    var date = new Date();
    gulp.src(mainStyle)
        .pipe(sass().on('error', sass.logError))
        .pipe(header('/* Miquel Waller - '+date+' */\n'))
        .pipe(gulp.dest(stylesDestination))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(stylesDestination))
        .pipe(livereload());
});

gulp.task('styles:watch', function () {
    gulp.watch([stylesOrigin, mainStyle], ['styles']);
});

/* Scripts */

var frontScriptsOrigin = [kdCommonPath+'js/global/app.js', kdCommonPath+'js/vendor/*.js',  kdCommonPath+'js/global/*.js', kdCommonPath+'js/templates/*.js', kdCommonPath+'js/widgets/*.js'],
    backScriptsOrigin = [kdCommonPath+'js/admin/*.js'],
    mainScript = kdCommonPath+'js/main.js',
    scriptsDestination = kdCommonPath+'js';

gulp.task('frontScripts', function() {
    var date = new Date();
    return gulp.src(frontScriptsOrigin)
        .pipe(concat('main.js'))
        .pipe(header('/* Miquel Waller - '+date+' */\n'))
        .pipe(gulp.dest(scriptsDestination))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(scriptsDestination));
});

gulp.task('backScripts', function() {
    var date = new Date();
    return gulp.src(backScriptsOrigin)
        .pipe(concat('admin.js'))
        .pipe(header('/* Miquel Waller - '+date+' */\n'))
        .pipe(gulp.dest(scriptsDestination))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(scriptsDestination));
});

gulp.task('scripts:watch', function () {
    livereload.listen();
    gulp.watch(frontScriptsOrigin, ['frontScripts']);
    gulp.watch(backScriptsOrigin, ['backScripts']);
});

/* Bapi Templates */


var bapiPath = '../../bapi/',
    bapiPartials = bapiPath+'partials/*.tmpl';

gulp.task('bapiTemplates', function() {
    return gulp.src(bapiPartials)
        .pipe(concat('bapi.ui.mustache.tmpl'))
        .pipe(gulp.dest(bapiPath));
});

gulp.task('bapi:watch', function () {
    gulp.watch(bapiPartials, ['bapiTemplates']);
});

/* Default */

gulp.task('default', ['styles', 'frontScripts', 'backScripts', 'bapiTemplates', 'styles:watch', 'scripts:watch', 'bapi:watch']);
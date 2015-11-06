'use strict';

var gulp = require('gulp'),
    browserSync = require('browser-sync');

gulp.task('serve', ['watch'], function() {
  var url = 'kigo.localdomain'; //Will need to adjust vhosts to match this
  var files = [
          '**/*.php',
          '**/*.{png,jpg,gif,css}'
        ];
  browserSync.init(files, {

    // Read here http://www.browsersync.io/docs/options/
    proxy: url,

    // port: 8080,

    // Tunnel the Browsersync server through a random Public URL
    // tunnel: true,

    // Attempt to use the URL "http://my-private-site.localtunnel.me"
    // tunnel: "ppress",

    // Inject CSS changes
    injectChanges: true

  });
});
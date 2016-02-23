module.exports = function (grunt) {

    //Init config
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jshint: {
            files: {
                src: ['../../kd-common/js/global/**/*.js', '../../kd-common/js/templates/**/*.js', '../../kd-common/js/widgets/**/*.js']
            },
        },
        uglify: {
            options: {
                banner: '/* kd-main by j~: <%=grunt.template.today("dd-mm-yyyy")%>*/\n'
            },
            build: {
                src: ['../../kd-common/js/global/**/*.js', '../../kd-common/js/templates/**/*.js', '../../kd-common/js/widgets/**/*.js'],
                dest: '../../kd-common/js/main.min.js'
            }

        },
        concat: {
            options: {
                banner: '/* kd-main by j~: <%=grunt.template.today("dd-mm-yyyy")%>*/\n',
            },
            dist: {
                src: ['../../kd-common/js/global/**/*.js', '../../kd-common/js/templates/**/*.js', '../../kd-common/js/widgets/**/*.js'],
                dest: '../../kd-common/js/main.js'
            },
            admin: {
                src: ['../../kd-common/js/admin/**/*.js'],
                dest: '../../kd-common/js/admin.js'
            },
            mustache: {
                src: ['../../bapi/partials/**/*.tmpl'],
                dest: '../../bapi/bapi.ui.mustache.tmpl'
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    '../../kd-common/css/main.css': '../../kd-common/scss/main.scss',
                    '../../kd-common/css/admin.css': '../../kd-common/scss/admin.scss'
                }
            }
        },
        autoprefixer: {
            options: {
                browsers: ['last 2 version', 'ie 9'],
                banner: '/* kd-main by j~: <%=grunt.template.today("dd-mm-yyyy")%>*/\n'
            },
            main: {
                expand: true,
                flatten: true,
                src: '../../kd-common/css/main.css',
                dest: '../../kd-common/css'

            }
        },
        cssmin: {
            options: {
                banner: '/* kd-main by j~: <%=grunt.template.today("dd-mm-yyyy")%>*/\n',
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    '../../kd-common/css/main.min.css': ['../../kd-common/css/main.css'],
                    '../../kd-common/css/admin.min.css': ['../../kd-common/css/admin.css']
                }
            }
        },
        watch: {
            scritps: {
                files: ['../../kd-common/js/global/**/*.js', '../../kd-common/js/templates/**/*.js', '../../kd-common/js/widgets/**/*.js'],
                tasks: ['uglify', 'concat'],
                options: {
                    spawn: false,
                    livereload: true, /*  <script src="//localhost:35729/livereload.js"></script>  */
                }
            },
            sass: {
                files: ['../../kd-common/scss/**/*.scss'],
                tasks: ['sass', 'autoprefixer'],
                options: {
                    spawn: false,
                    livereload: true,
                }
            },
            css: {
                files: ['../../kd-common/css/main.css'],
                tasks: ['cssmin'],
                options: {
                    spawn: false,
                    livereload: true,
                }
            },
            html: {
                files: ['../../**/*.php'],
                options: {
                    livereload: true,
                }
            },
            mustache : {
                files: ['../../bapi/partials/**/*.tmpl'],
                tasks: ['concat'],
                options: {
                    spawn: false,
                    livereload: true,
                }
            },
            admin: {
                files: ['../../kd-common/js/admin/**/*.js'],
                tasks: ['uglify', 'concat'],
                options: {
                    spawn: false,
                    livereload: true, /*  <script src="//localhost:35729/livereload.js"></script>  */
                }
            },
            bapitemplates: {
                files: ['../../bapi/partials/**/*.tmpl'],
                tasks: ['concat'],
                options: {
                    spawn: false,
                    livereload: true, /*  <script src="//localhost:35729/livereload.js"></script>  */
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-jshint');



    grunt.registerTask('default', ['sass', 'autoprefixer', 'concat', 'cssmin', 'uglify', 'watch']);
}

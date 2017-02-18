module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        //----------------------------------------
        compass: {
            dist_admin_metabox: {
                options: {
                    config: 'assets-dev/sass-admin/config.rb',
                    specify: [
                        'assets-dev/sass-admin/wizzaro-gallery.scss',
                    ],
                    outputStyle: 'compressed',
                    environment: 'production'
                }
            }
        },
        //----------------------------------------
        concat: {
            dist_admin_metabox: {
                src: [
                    'assets-dev/js-admin/*.js',
                    'assets-dev/js-admin/*/*.js',
                    'assets-dev/js-admin/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*/*.js',
                ],
                dest: 'assets/js/admin/wizzaro-gallery.js'
            },
        },
        //----------------------------------------
        uglify: {
            gallery: {
                files: {
                    'assets/js/admin/wizzaro-gallery.js': [
                        'assets-dev/js-admin/*.js',
                        'assets-dev/js-admin/*/*.js',
                        'assets-dev/js-admin/*/*/*.js',
                        'assets-dev/js-admin/*/*/*/*.js',
                        'assets-dev/js-admin/*/*/*/*/*.js',
                    ],
                },
            }
        },
        //----------------------------------------
        watch: {
            js_dist_admin_metabox: {
                files: [
                    'assets-dev/js-admin/*.js',
                    'assets-dev/js-admin/*/*.js',
                    'assets-dev/js-admin/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*/*.js',
                ],
                tasks: ['concat:dist_admin_metabox']
            },
            css_dist_admin_metabox: {
                files: [
                    'assets-dev/sass-admin/*.scss',
                    'assets-dev/sass-admin/*/*.scss',
                    'assets-dev/sass-admin/*/*/*.scss',
                    'assets-dev/sass-admin/*/*/*/*.scss',
                    'assets-dev/sass-admin/*/*/*/*/*.scss',
                ],
                tasks: ['compass:dist_admin_metabox']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['compass', 'uglify']);
    grunt.registerTask('liveupdate', ['watch']);
};

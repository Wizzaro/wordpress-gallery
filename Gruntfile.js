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
            dist_wizzaro_gallery_admin: {
                src: [
                    'assets-dev/js-admin/*.js',
                    'assets-dev/js-admin/*/*.js',
                    'assets-dev/js-admin/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*/*.js',
                ],
                dest: 'assets/js/admin/wizzaro-gallery.js'
            },
            dist_wizzaro_gallery: {
                src: [
                    'assets-dev/js/*.js',
                ],
                dest: 'assets/js/wizzaro-gallery.js'
            }
        },
        //----------------------------------------
        uglify: {
            js_wizzaro_gallery: {
                files: {
                    'assets/js/admin/wizzaro-gallery.js': [
                        'assets-dev/js-admin/*.js',
                        'assets-dev/js-admin/*/*.js',
                        'assets-dev/js-admin/*/*/*.js',
                        'assets-dev/js-admin/*/*/*/*.js',
                        'assets-dev/js-admin/*/*/*/*/*.js',
                    ],
                    'assets/js/wizzaro-gallery.js': [
                        'assets-dev/js/*.js',
                    ]
                },
            }
        },
        //----------------------------------------
        watch: {
            js_plugin_wizzaro_gallery: {
                files: [
                    'assets-dev/js/*.js',
                ],
                tasks: ['concat:dist_wizzaro_gallery']
            },
            js_plugin_wizzaro_gallery_admin: {
                files: [
                    'assets-dev/js-admin/*.js',
                    'assets-dev/js-admin/*/*.js',
                    'assets-dev/js-admin/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*.js',
                    'assets-dev/js-admin/*/*/*/*/*.js',
                ],
                tasks: ['concat:dist_wizzaro_gallery_admin']
            },
            css_plugin_wizzaro_gallery: {
                files: [
                    'assets-dev/sass-admin/*.scss',
                    'assets-dev/sass-admin/*/*.scss',
                    'assets-dev/sass-admin/*/*/*.scss',
                    'assets-dev/sass-admin/*/*/*/*.scss',
                    'assets-dev/sass-admin/*/*/*/*/*.scss',
                ],
                tasks: ['compass:dist_wizzaro_gallery']
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

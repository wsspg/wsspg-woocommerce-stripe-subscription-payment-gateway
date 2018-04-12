module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            my_target: {
                files: {
                    'assets/js/wsspg-admin.min.js': ['assets/js/wsspg-admin.js'],
                    'assets/js/wsspg-inline-cc.min.js': ['assets/js/wsspg-inline-cc.js'],
                    'assets/js/wsspg-stripe-checkout.min.js': ['assets/js/wsspg-stripe-checkout.js']
                }
            }
        },
        version: {
            defaults: {
                src: ['wsspg.php']
            },
            wsspg: {
                options: {
                    prefix: '@version\\s*'
                },
                src: ['wsspg.php']
            },
            readme: {
                options: {
                    prefix: 'Stable tag:\\s*'
                },
                src: ['readme.txt']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-version');

    grunt.registerTask('default', []);
    grunt.registerTask('release', ['uglify', 'version']);
};

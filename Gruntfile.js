module.exports = function(grunt) {

    require('time-grunt')(grunt);
    require('load-grunt-tasks')(grunt);
    var pkg = grunt.file.readJSON('package.json');

    grunt.initConfig({
      pkg: grunt.file.readJSON('package.json'),
      clean: pkg.cleanFolders,
      copy: {
        main: {
          files: [
            // includes files within path
            {
              expand: true,
              src: [ '**', pkg.copyIgnores ],
              dest: 'src/' + pkg.slug + '/',
              filter: 'isFile'
            },
          ],
        }
      },
      compress: {
        main: {
          options: {
            mode: 'zip',
            archive: 'dist/' + pkg.slug + '.zip'
          },
          expand: true,
          cwd: 'src/',
          src: ['**']
        }
      },
      "github-release": {
        options: {
          auth: {
            user: grunt.option('gh_user'),
            pass: grunt.option('gh_pass')
          },
          repository: 'PageLines-Team/' + pkg.slug,
          release: {
            tag_name: 'latest',
            name: 'Latest',
            body: 'Latest Build',
            draft: false,
            prerelease: false
          }
        },
        files: {
          'src': [ 'dist/' + pkg.slug + '.zip' ]
        },
      },

        shell: {
                options: {
                    stderr: false,
                    failOnError: false
                },
                remove_latest_tag: {
                    command: 'git push origin :latest'
                }
            }

    });

    /** Run on GRUNT initialization */
    grunt.registerTask( 'default', 	[ 'clean'] );

    grunt.registerTask('release', [
      'clean',          // clean the folders
      'copy',           // copy the files we need
      'compress',       // create out zip
      'shell',          // delete latest release
      'github-release',  // create latest release
    ]);
}

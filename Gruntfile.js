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
        },
		    cssmin: {
		      options: {
		        shorthandCompacting: false,
		        roundingPrecision: -1,
						keepSpecialComments: 1
		      },
		      main: {
		        src: 'style.css',
		        dest: 'style.css'
		      }
		    },
		    concurrent: {
		        all: {
		          //  tasks: ['watch:js', 'watch:less', 'watch:php'],
		            tasks: [ 'watch:lessMain', 'watch:lessSections'],
		            options: {
		                logConcurrentOutput: true
		            }
		        }
		    },
		    less: {

				compileMain: {
		            src: 'build.less',
	              dest: 'style.css',
	                options: {
	                    strictMath: true,
	                    sourceMap: false                }
		        },
	      compileSections: {
	                src:    'sections/*/build.less', //'sections/*/build.less',
	                dest:   '',
	                expand: true,
	                rename: function(dest, src) {
	                    return src.replace("build.less", "build.css");
	                },

	            }
		    },
        watch: {

            /** Watch Sections' build.less files */
            lessSections: {
                files: [ 'sections/*/build.less' ], // files to watch
                tasks: [ 'less:compileSections' ],  // what to do on change
                options: { nospawn: true }          // No child process - 500ms faster
            },
            lessMain: {
                // what files/folder we watching?
                files: [ 'build.less' ],
                // tasks to run in order when something changes
                tasks: ['less:compileMain'],
                options: {
                    nospawn: true,
                }
            }
          }

    });

    /** Run on GRUNT initialization */
    grunt.registerTask( 'default', 	[ 'clean', 'less', 'concurrent' ] );

    grunt.registerTask('release', [
      'clean',          // clean the folders
			'less',						// build that less
      'copy',           // copy the files we need
      'compress',       // create out zip
      'shell',          // delete latest release
      'github-release',  // create latest release
    ]);
}

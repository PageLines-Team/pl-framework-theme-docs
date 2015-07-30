/*

	Generic gruntfile for building zips and running less if needed.

*/
module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    var pkg = grunt.file.readJSON('package.json');
		var slug = process.cwd().substr(process.cwd().lastIndexOf('/') + 1);

    grunt.initConfig({
      pkg: grunt.file.readJSON('package.json'),
      clean: ['dist','src'],
      copy: {
        main: {
          files: [
            // includes files within path
            {
              expand: true,
              src: [ '**', pkg.copyIgnores ],
              dest: 'src/' + slug + '/',
              filter: 'isFile'
            }
          ]
        }
      },
      compress: {
        main: {
          options: {
            mode: 'zip',
            archive: 'dist/' + slug + '.zip'
          },
          expand: true,
          cwd: 'src/',
          src: ['**']
        }
      },
      changelog: {
        release: {
          options: {
            version: 'Changelog (' + grunt.template.today('yyyy-mm-dd') + ')' ,
            template: 'labeled',
            changelog: 'CHANGELOG.md',
            labels: ['feature', 'bugfix', 'chore', 'refactor'],
            branch: 'master'
          }
        }
      }
    });

    /** Run on GRUNT initialization */
    grunt.registerTask('release', [
			'clean',
      'copy',           // copy the files we need
      'compress'        // create out zip
    ]);
}

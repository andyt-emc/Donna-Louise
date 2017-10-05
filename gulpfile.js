var gulp         = require('gulp');
var less         = require('gulp-less');
var autoprefixer = require('gulp-autoprefixer');
var cleanCSS     = require('gulp-clean-css');
var gutil        = require('gulp-util');
var concat       = require('gulp-concat');  
var rename       = require('gulp-rename');  
var uglify       = require('gulp-uglify');  
var browserSync  = require('browser-sync').create();
var imagemin     = require('gulp-imagemin');
var pngquant     = require('imagemin-pngquant');
var ftp          = require('vinyl-ftp');

var sourcePath    = 'assets/less';
var targetPath    = 'css/';
var jsSourcePath  = 'assets/js/**/*.js';
var jsTargetPath  = 'js/';
var websitePath   = 'dlt';
 
gulp.task('less', function (done) {

  return gulp.src([sourcePath + '/template.less'])
    .pipe(less({compress: true}).on('error', function(error) { done(error); }))
    .pipe(autoprefixer('last 20 versions', 'ie 9'))
    .pipe(cleanCSS({keepBreaks: false}))    
    .pipe(gulp.dest(targetPath))    
    // .pipe(browserSync.stream());

});

gulp.task('js', function() {

  return gulp.src(jsSourcePath)
    .pipe(concat('scripts.js'))
    .pipe(rename('scripts.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(jsTargetPath))
    // .pipe(browserSync.stream());

});

// Livereload will up local server 
// and inject all changes made
gulp.task('browser-sync', function() {
  
  browserSync.init({
    proxy: "localhost/" + websitePath + "/",
    // browser: ["chrome", "firefox", "opera", "safari"] // useful for multibrowser testing
  });

  gulp.watch(sourcePath + '/**/*.less', ['less']);
  gulp.watch(jsSourcePath, ['js']).on('change', browserSync.reload);

});

// Instead of live reload upload files to server destination
gulp.task('watch', function() {

  gulp.watch(sourcePath + '/**/*.less', ['less', 'deploy']);
  gulp.watch(jsSourcePath, ['js', 'deploy']); 

});

// upload website files to server
gulp.task('deploy', ['less','js'], function() {

  var conn = ftp.create( {
      host:     '176.74.20.8',
      user:     'dlt',
      password: '0@X]R?K0+i5!',
      log:      gutil.log
  });

  var globs = [
        'css/**',
        'js/**'
    ];
  
  return gulp.src( globs, { base: '.', buffer: false } )
      .pipe( conn.newer( '/public_html/latest/templates/emcbasetheme' ) ) // only upload newer files 
      .pipe( conn.dest( '/public_html/latest/templates/emcbasetheme' ) );

});




// watch for changes in LESS and JS files. 
// run the appropriate task when changes detected and then refresh the browser
// gulp.task('default', ['browser-sync']);
gulp.task('default', ['watch']);


// compress images
gulp.task('imagemin', function () {
    return gulp.src('./images/uncompressed/**/*')
        .pipe(imagemin({
            progressive: true,
            use: [pngquant()]
        }))
        .pipe(gulp.dest('./images/'));
});


// gulp.task('default', ['clean'], function() {
//     gulp.start('watch', 'scripts', 'images');
// });
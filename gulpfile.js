let gulp = require('gulp'),
    sass = require('gulp-sass'),
    browserSync = require('browser-sync'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    del = require('del'),
    autoprefixer = require('gulp-autoprefixer');


gulp.task('clean', async function(){
  del.sync('dist')
})

gulp.task('scss', function(){
  return gulp.src('app/scss/**/*.scss')
    .pipe(sass({outputStyle: ''}))
    .pipe(autoprefixer({
      overrideBrowserslist: ['last 8 versions']
    }))
    // .pipe(rename(
    //   {suffix: '.min'}
    //   ))
    .pipe(gulp.dest('app/css'))
    .pipe(browserSync.reload({stream: true}))
});

gulp.task('css', function(){
  return gulp.src([
    'node_modules/normalize.css/normalize.css',
    // 'node_modules/swiper/swiper-bundle.css',
    // 'node_modules/rateyo/lib/es/rateyo.css',
    // 'node_modules/baguettebox.js/dist/baguetteBox.css',
    // 'node_modules/magnific-popup/dist/magnific-popup.css',
    // 'assest/css/jquery.rateyo.css',
    // 'assest/css/nice-select.css',
    // 'assest/css/validation.css',
    // 'node_modules/ion-rangeslider/css/ion.rangeSlider.min.css',
    // 'node_modules/jquery-form-styler/dist/jquery.formstyler.theme.css',
  ])
    .pipe(concat('_libs.scss'))
    .pipe(gulp.dest('app/scss'))
    .pipe(browserSync.reload({stream: true}))
});

gulp.task('html', function(){
  return gulp.src('app/*.html')
  .pipe(browserSync.reload({stream: true}))
});

gulp.task('script', function(){
  return gulp.src('app/js/*.js')
  .pipe(browserSync.reload({stream: true}))
});

gulp.task('js', function(){
  return gulp.src([
    // 'node_modules/swiper/swiper-bundle.js',
    // 'node_modules/baguettebox.js/dist/baguetteBox.js',
    // 'node_modules/magnific-popup/dist/jquery.magnific-popup.js',
    'assest/js/mask.js',
    // 'assest/js/jquery.validate.min.js',
    'assest/js/jquery.arcticmodal.js',
    // 'assest/js/jquery.rateyo.js',
    // 'assest/js/jquery.nice-select.min.js',
    // 'assest/js/galleria.js',
    // 'node_modules/ion-rangeslider/js/ion.rangeSlider.min.js',
  ])
    
    .pipe(concat('libs.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('app/js'))
    .pipe(browserSync.reload({stream: true}))
});

gulp.task('browser-sync', function() {
  browserSync.init({
      server: {
          baseDir: "app/"
      }
  });
});

gulp.task('export', function(){
  let buildHtml = gulp.src('app/**/*.html')
    .pipe(gulp.dest('dist'));

  let BuildCss = gulp.src('app/css/**/*.css')
    .pipe(gulp.dest('dist/css'));

  let BuildJs = gulp.src('app/js/**/*.js')
    .pipe(gulp.dest('dist/js'));
    
  let BuildFonts = gulp.src('app/fonts/**/*.*')
    .pipe(gulp.dest('dist/fonts'));

  let BuildImg = gulp.src('app/img/**/*.*')
    .pipe(gulp.dest('dist/img'));   
});

gulp.task('watch', function(){
  gulp.watch('app/scss/**/*.scss', gulp.parallel('scss'));
  gulp.watch('app/*.html', gulp.parallel('html'))
  gulp.watch('app/js/*.js', gulp.parallel('script'))
});

gulp.task('build', gulp.series('clean', 'export'))

gulp.task('default', gulp.parallel('css' ,'scss', 'js', 'browser-sync', 'watch'));
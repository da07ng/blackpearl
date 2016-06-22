var gulp = require('gulp');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');
var minifycss = require('gulp-minify-css');
var imagemin = require('gulp-imagemin');
// var del = require('del');

var paths = {
  scripts: ['src/scripts/jquery-1.12.4.min.js',
            'src/scripts/prism.js',
            'src/scripts/theme.js'],
  styles: 'src/styles/*.scss',
  images: 'src/images/*'
};

// gulp.task('clean', function(cb) {
//   del(['dist'], cb);
// });

// Scripts
gulp.task('scripts', function() {
  return gulp.src(paths.scripts)
    .pipe(concat('main.js'))
    .pipe(gulp.dest('./dist'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('./dist'));
});

// Styles
gulp.task('styles', function () {
  return gulp.src('src/styles/main.scss')
    .pipe(sass())
    .pipe(gulp.dest('./dist'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss())
    .pipe(gulp.dest('./dist'))
});

// Copy all static images
gulp.task('images', function() {
  return gulp.src(paths.images)
    .pipe(imagemin())
    .pipe(gulp.dest('./dist/images'));
});

// Rerun the task when a file changes
gulp.task('watch', function() {
  gulp.watch(paths.scripts, ['scripts']);
  gulp.watch(paths.styles, ['styles']);
  gulp.watch(paths.images, ['images']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['watch', 'scripts', 'styles', 'images']);

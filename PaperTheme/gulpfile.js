var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');



gulp.task('default',['sassMain','font'], function() {
});

gulp.task("font", function () {
    gulp.src("./sass/font.scss")
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(sourcemaps.write("./css"))
    .pipe(gulp.dest("./css"));
});

gulp.task('concatMain', function() {
  return gulp.src(['./sass/style.scss', './bower_components/bootswatch/bootstrap.css','./bower_components/font-awesome/css/font-awesome.css'])
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(concat('style.css'))
    .pipe(cleanCSS())
    .pipe(sourcemaps.write("./css"))
    .pipe(gulp.dest('./css'));
});

gulp.task('concatJS', function() {
  return gulp.src(['./bower_components/jquery/dist/jquery.min.js', './bower_components/bootstrap/js/*.js'])
    .pipe(concat('all.js'))
    .pipe(uglify())
    .pipe(sourcemaps.write("./js"))
    .pipe(gulp.dest('./js'));
});

gulp.task('watch', function(){
  gulp.watch('./sass/*.scss', ['concatMain','font','concatJS']);
  // Other watchers
});

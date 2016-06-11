var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');



gulp.task('default',['sass'], function() {
});

gulp.task("sass", function () {
    gulp.src("./sass/*.scss")
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(sourcemaps.write("./css"))
    .pipe(gulp.dest("./css"));
});

gulp.task('concat', function() {
  return gulp.src(['./sass/*.scss', './bower_components/bootswatch/bootstrap.css','./bower_components/font-awesome/css/font-awesome.css'])
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(concat('style.css'))
    .pipe(gulp.dest('./css'));
});

gulp.task('watch', function(){
  gulp.watch('./sass/*.scss', ['concat']);
  // Other watchers
});

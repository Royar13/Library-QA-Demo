var gulp = require('gulp'),
        sass = require('gulp-sass'),
        rename = require("gulp-rename"),
        concat = require('gulp-concat'),
        uglify = require('gulp-uglify'),
        plumber = require('gulp-plumber'),
        browserSync = require('browser-sync').create(),
        autoprefixer = require('gulp-autoprefixer'),
        sourcemaps = require('gulp-sourcemaps'),
        imagemin = require('gulp-imagemin');
var onError = function (err) {
    console.log(err);
    this.emit("end");
};
var renameFunc = function (path) {
    //path.dirname = path.dirname.replace("src/", "");
    path.dirname = path.dirname.replace("scss", "css");
};

gulp.task("copy", function() {
    return gulp.src("app/**/*", {base:"."})
            .pipe(gulp.dest("dist"));
});

gulp.task("sass", function () {
    return gulp.src("assets/scss/*.scss", {base:"."})
            .pipe(plumber(onError))
            .pipe(sass({outputStyle: "compressed"}))
            .pipe(autoprefixer())
            .pipe(rename(renameFunc))
            .pipe(gulp.dest("dist"))
            .pipe(browserSync.stream());
});

gulp.task("images", function () {
    return gulp.src("assets/img/*", {base:"."})
            .pipe(imagemin())
            .pipe(rename(renameFunc))
            .pipe(gulp.dest("dist"));
});

gulp.task("browser-sync", function () {
    browserSync.init({
        proxy: "localhost/library-qa-demo/index.html"
    });
});

gulp.task('bs-reload', function () {
    browserSync.reload();
});

gulp.task("watch", function () {
    gulp.watch("*.html", ["bs-reload"]);
    //gulp.watch("**/*.php", ["bs-reload"]);
    gulp.watch("app/**/*", ["copy", "bs-reload"]);
    gulp.watch("assets/scss/*", ["sass"]);
    gulp.watch("assets/img/*", ["images"]);
});

gulp.task("default", ["browser-sync", "copy", "sass", "images", "watch"]);
var gulp = require('gulp'),
        sass = require('gulp-sass'),
        rename = require("gulp-rename"),
        concat = require('gulp-concat'),
        uglify = require('gulp-uglify'),
        plumber = require('gulp-plumber'),
        browserSync = require('browser-sync').create(),
        autoprefixer = require('gulp-autoprefixer'),
        sourcemaps = require('gulp-sourcemaps'),
        imagemin = require('gulp-imagemin'),
        ngAnnotate = require('gulp-ng-annotate');
var onError = function (err) {
    console.log(err);
    this.emit("end");
};
var renameFunc = function (path) {
    //path.dirname = path.dirname.replace("src/", "");
    path.dirname = path.dirname.replace("scss", "css");
};


gulp.task("uglify", function () {
    return gulp.src("app/**/*.js")
            .pipe(plumber(onError))
            .pipe(sourcemaps.init())
            .pipe(concat('script.js'))
            //.pipe(ngAnnotate())
            //.pipe(uglify())
            .pipe(sourcemaps.write("maps"))
            //.pipe(rename(renameFunc))
            .pipe(gulp.dest("dist"));
});

gulp.task("sass", function () {
    return gulp.src("assets/scss/*.scss")
            .pipe(plumber(onError))
            .pipe(sass({outputStyle: "compressed"}))
            .pipe(autoprefixer())
            //.pipe(rename(renameFunc))
            .pipe(concat('style.css'))
            .pipe(gulp.dest("dist"))
            .pipe(browserSync.stream());
});

gulp.task("images", function () {
    return gulp.src("assets/img/*")
            .pipe(imagemin())
            .pipe(gulp.dest("dist/img"));
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
    gulp.watch("app/**/*.html", ["bs-reload"]);
    //gulp.watch("**/*.php", ["bs-reload"]);
    gulp.watch("app/**/*.js", ["uglify", "bs-reload"]);
    gulp.watch("assets/scss/*", ["sass"]);
    gulp.watch("assets/img/*", ["images"]);

});

gulp.task("default", ["browser-sync", "uglify", "sass", "images", "watch"]);
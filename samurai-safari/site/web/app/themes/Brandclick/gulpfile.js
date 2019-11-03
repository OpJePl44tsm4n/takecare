'use strict';

var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var rename = require('gulp-rename');
var imagemin = require('gulp-imagemin');
var cache = require('gulp-cache');
var uncss = require('gulp-uncss');
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var fontAwesome = require('node-font-awesome');
var browserSync = require('browser-sync').create();
var sourcemaps = require('gulp-sourcemaps');

gulp.task('styles', function (done) {
    gulp.src(['assets/src/styles/**/*.scss'])
        .pipe(sourcemaps.init())
        .pipe(plumber({
            errorHandler: function (error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 5 versions']
        }))
        .pipe(gulp.dest('assets/dist/styles/'))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest('assets/dist/styles'))
        .pipe(browserSync.stream({ match: '**/*.css' }));

    console.log("Styles cleaned and build");
    done();   
});

gulp.task('js', function (done) {
    gulp.src(['assets/src/js/main.js', 'assets/src/js/vendor/bootstrap/*'])
        .pipe(uglify())
        .pipe(concat('main.min.js'))
        .pipe(gulp.dest('assets/dist/js/'));

    gulp.src('assets/src/js/wp-api.js')
        .pipe(uglify())
        .pipe(concat('wp-api.min.js'))
        .pipe(gulp.dest('assets/dist/js/'));

    gulp.src('assets/src/js/audio-app.js')
        .pipe(uglify())
        .pipe(concat('audio.min.js'))
        .pipe(gulp.dest('assets/dist/js/'));    

    console.log("Scripts cleaned and build");
    done();   
});


gulp.task('images', function (done) {
    gulp.src('assets/src/img/**/*')
        .pipe(cache(imagemin({
            optimizationLevel: 3,
            progressive: true,
            interlaced: true
        })))
        .pipe(gulp.dest('assets/dist/img/'));

    console.log("Images optimized");
    done();   

});

gulp.task('fonts', function (done) {
    gulp.src(['assets/src/fonts/**/*', fontAwesome.fonts])
        .pipe(gulp.dest('assets/dist/fonts/'));

    console.log("Fonts optimized");
    done();
});


gulp.task('make-bootstrap-js', function (done) {
    gulp.src('node_modules/bootstrap/dist/js/bootstrap.min.js')
        .pipe(gulp.dest('assets/src/js/vendor/bootstrap'));

    console.log("Just moved bootstrap javascript to src folder");
    done();
});


gulp.task('watch', function () {
    
    browserSync.init({
        proxy: "https://knops.test",
        https: true
    });

    gulp.watch('assets/src/styles/**/*.scss', {
        interval: 500
    }, gulp.series('styles') ).on('change', browserSync.reload);
    gulp.watch('assets/src/js/**/*.js', {
        interval: 500
    }, gulp.series('js') );
    gulp.watch('assets/src/images/**/*', {
        interval: 500
    }, gulp.series('images') ).on('change', browserSync.reload);
    gulp.watch('assets/src/fonts/**/*', {
        interval: 500
    }, gulp.series('fonts') ).on('change', browserSync.reload);
});

gulp.task('build', gulp.series('styles', 'js', 'images', 'fonts'));

gulp.task('default', gulp.series('build', 'watch'));

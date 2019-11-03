<div id="audio-app">
    <div data-v-14e332a6="" id="start_experience" class="audio-component">
        <div data-v-0223664c="" data-v-14e332a6="" class="info audio-component__info" >
            <p data-v-0223664c="" class="db-meter step-1" style="margin-top: 0px"><span data-v-0223664c="" class="db-count">-0</span>DB
            </p>
            <p data-v-0223664c="" class="h2">
                Step 1
            </p>
            <p data-v-0223664c="" class="h4">
                Clear sound
            </p>
            <ul data-v-0223664c="" class="indicator">
                <li data-v-0223664c="" title="Step 1 —&nbsp;Clear sound" class="step active"></li>
                <li data-v-0223664c="" title="Step 2 —&nbsp;City noise" class="step"></li>
                <li data-v-0223664c="" title="Step 3 —&nbsp;Live music" class="step"></li>
                <li data-v-0223664c="" title="Step 4 —&nbsp;Isolation" class="step"></li>
            </ul>
        </div>
        <div data-v-6376865e="" data-v-14e332a6="" style="position:relative;" class="visual audio-component__visual">
            <button class="btn btn-color-xsdn btn-circle" style="position: absolute;left: 40%;top: 49%;transform: translate(-50%,-50%);">Start experience</button>

            <div data-v-6376865e="" style="opacity: 0.2;" class="visual-container">
                <img data-v-6376865e="" src="/static/img/knobs.98ce26c.jpg" class="visual-container__img">
                <svg data-v-6376865e="" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 200 200" class="visual-container__svg">
                    <defs data-v-6376865e="">
                        <linearGradient data-v-6376865e="" id="gradientGreen">
                            <stop data-v-6376865e="" offset="0%" stop-color="rgba(156, 249, 157, 1)"></stop>
                            <stop data-v-6376865e="" offset="70%" stop-color="rgba(156, 249, 157, 0.0)"></stop>
                        </linearGradient>
                        <linearGradient data-v-6376865e="" id="gradientSeaGreen">
                            <stop data-v-6376865e="" offset="0%" stop-color="rgba(0, 244, 212, 0.2)"></stop>
                            <stop data-v-6376865e="" offset="50%" stop-color="rgba(0, 190, 206, 1)"></stop>
                        </linearGradient>
                        <linearGradient data-v-6376865e="" id="gradientPink">
                            <stop data-v-6376865e="" offset="0%" stop-color="rgba(243, 151, 174, 0.8)"></stop>
                            <stop data-v-6376865e="" offset="100%" stop-color="rgba(227, 44, 94, 0.3)"></stop>
                        </linearGradient>
                        <linearGradient data-v-6376865e="" id="gradientDeepBlue">
                            <stop data-v-6376865e="" offset="0%" stop-color="rgba(0, 88, 213, 0.95)"></stop>
                            <stop data-v-6376865e="" offset="50%" stop-color="rgba(0, 5, 173, 0.8)"></stop>
                        </linearGradient>
                        <clipPath data-v-6376865e="" id="clip-15-percent">
                            <path data-v-6376865e="" d="M0 0 L0 100 L100 100 L80 0 Z" fill="#000"></path>
                        </clipPath>
                        <clipPath data-v-6376865e="" id="clip-25-percent">
                            <path data-v-6376865e="" d="M0 0 L0 100 L100 100 L100 0 Z" fill="#000"></path>
                        </clipPath>
                        <clipPath data-v-6376865e="" id="clip-35-percent">
                            <path data-v-6376865e="" d="M0 0 L0 100 L100 100 L200 0 Z" fill="#000"></path>
                        </clipPath>
                        <clipPath data-v-6376865e="" id="clip-45-percent">
                            <path data-v-6376865e="" d="M0 0 L0 100 L100 100 L200 25 L200 0 Z" fill="#000"></path>
                        </clipPath>
                        <clipPath data-v-6376865e="" id="clip-50-percent">
                            <path data-v-6376865e="" d="M0 0 L0 100 L200 100 L200 0 Z" fill="#000"></path>
                        </clipPath>
                    </defs>
                    <circle data-v-6376865e="" cx="100" cy="100" r="47.95294117647059" fill="url(#gradientDeepBlue)" clip-path="url(#clip-35-percent)" transform="rotate(145 100 100)" style="opacity: 1;"></circle>
                    <circle data-v-6376865e="" cx="100" cy="100" r="67.27843137254902" fill="url(#gradientPink)" clip-path="url(#clip-45-percent)" transform="rotate(36 100 100)" style="opacity: 1;"></circle>
                    <circle data-v-6376865e="" cx="100" cy="100" r="44.29019607843137" fill="url(#gradientSeaGreen)" clip-path="url(#clip-25-percent)" transform="rotate(120 100 100)" style="opacity: 1;"></circle>
                    <circle data-v-6376865e="" cx="100" cy="100" r="83.2470588235294" fill="url(#gradientGreen)" clip-path="url(#clip-35-percent)" transform="rotate(-55 100 100)" style="opacity: 1;"></circle>
                    Sorry, your browser does not support inline SVG.
                </svg>
                <img data-v-6376865e="" src="/static/img/knobs-overlay.c242110.png" class="visual-container__img-overlay">
                <div data-v-6376865e="" class="visual-container__img-overlay-button"></div>
                <div data-v-6376865e="" class="pointer">
                    to adjust
                </div>
                <!---->
            </div>
        </div>
    </div>
</div>

<script>
    var myLink = document.getElementById('start_experience');
    if (myLink) {
        myLink.onclick = function(){
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src =  "<?php echo get_stylesheet_directory_uri() . '/assets/dist/js/audio.min.js'; ?>";
            document.getElementsByTagName("head")[0].appendChild(script);
            return false;
        }
    }
</script>

<style type="text/css">
    .audio-component[data-v-14e332a6]{display:-webkit-box;display:-ms-flexbox;display:flex;overflow:hidden;margin:0 auto;max-width:960px;font-size:10px;font-family:Brown,Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;color:#000;margin-top:60px;line-height:1}@media (max-width:768px){.audio-component[data-v-14e332a6]{-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center}}@media (max-width:767px){.audio-component__info[data-v-14e332a6]{-webkit-box-ordinal-group:3;-ms-flex-order:2;order:2;position:relative;z-index:2;margin-top:-48px;margin-left:0;width:174px}}@media (min-width:768px){.audio-component__info[data-v-14e332a6]{-webkit-box-flex:0;-ms-flex:0 0 26%;flex:0 0 26%;margin:0 0 0 26px}}@media (min-width:768px) and (max-width:999px){.audio-component__info[data-v-14e332a6]{padding-top:10vw}}@media (min-width:1000px){.audio-component__info[data-v-14e332a6]{padding-top:122px}}@media (max-width:767px){.audio-component__visual[data-v-14e332a6]{-webkit-box-ordinal-group:2;-ms-flex-order:1;order:1;-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;position:relative;left:3%;margin:0 0 0 26px}}@media (min-width:768px){.audio-component__visual[data-v-14e332a6]{-webkit-box-flex:0;-ms-flex:0 0 74%;flex:0 0 74%}}.h2[data-v-0223664c]{margin:4px 0 0;padding:0;width:126%;font-weight:400;font-size:41px;letter-spacing:.2em;text-transform:uppercase}@media (min-width:768px){.h2[data-v-0223664c]{margin:7px 0 0;font-weight:400;}}.h4[data-v-0223664c]{margin:4px 0 0;padding:0;width:126%;font-weight:200;text-transform:uppercase;letter-spacing:.2em;font-size:18px}@media (min-width:768px){.h4[data-v-0223664c]{margin:2px 0 0;font-size:22px}}.step-text[data-v-0223664c]{letter-spacing:.2em;font-size:2.3em}.db-meter[data-v-0223664c]{color:#fff;display:inline-block;padding:6px 8px 0;margin-bottom:12px;margin-left:2px;font-weight:600;font-size:16px}@media (min-width:768px){.db-meter[data-v-0223664c]{padding:8px 10px 2px;font-size:20px}}.db-meter.step-1[data-v-0223664c]{background:-webkit-linear-gradient(left,#e32c5e,#f197ab);background:linear-gradient(90deg,#e32c5e,#f197ab)}.db-meter.step-2[data-v-0223664c]{background:-webkit-linear-gradient(left,#0005ad,rgba(0,88,213,.85));background:linear-gradient(90deg,#0005ad,rgba(0,88,213,.85))}.db-meter.step-3[data-v-0223664c]{background:-webkit-linear-gradient(left,#00f46d,rgba(25,212,114,.55));background:linear-gradient(90deg,#00f46d,rgba(25,212,114,.55))}.db-meter.step-4[data-v-0223664c]{background:-webkit-linear-gradient(left,#85f1d3,rgba(104,187,205,.5));background:linear-gradient(90deg,#85f1d3,rgba(104,187,205,.5))}.indicator[data-v-0223664c]{padding:10px 0 0}@media (min-width:960px){.indicator[data-v-0223664c]{padding-top:11px}}.indicator li[data-v-0223664c]{list-style:none;width:10px;height:10px;position:relative;float:left;margin:10px 8px 10px 2px;cursor:pointer}.indicator li[data-v-0223664c]:before{content:"";position:absolute;display:block;z-index:2;top:0;left:0;width:10px;height:10px;background:#c1c1c1;border-radius:100%;-webkit-transition:.1s ease-out;transition:.1s ease-out}.indicator li[data-v-0223664c]:after{content:"";position:absolute;display:block;z-index:2;top:-16px;bottom:-16px;right:-10px;left:-10px}.indicator li[data-v-0223664c]:hover:before{-webkit-transform:scale(1.16);transform:scale(1.16);background:#000}.indicator li[data-v-0223664c]:active:before{background:#000;-webkit-transform:scale(.9);transform:scale(.9)}.indicator li.active[data-v-0223664c]:before{background:#000}circle[data-v-6376865e]{-webkit-transition:.1s ease-out,-webkit-transform .38s ease-out;transition:.1s ease-out,-webkit-transform .38s ease-out;transition:.1s ease-out,transform .38s ease-out;transition:.1s ease-out,transform .38s ease-out,-webkit-transform .38s ease-out}.visual.safari-ios circle[data-v-6376865e]{-webkit-transition:-webkit-transform .38s ease-out;transition:-webkit-transform .38s ease-out;transition:transform .38s ease-out;transition:transform .38s ease-out,-webkit-transform .38s ease-out}.visual[data-v-6376865e]{position:relative;will-change:transition,transform}.visual-container[data-v-6376865e]{position:relative}@media (min-width:768px){.visual-container[data-v-6376865e]{left:20px}}.visual-container__img[data-v-6376865e]{display:block;max-width:100%;height:auto}.visual-container__svg[data-v-6376865e]{position:absolute;top:0;bottom:20%;left:0;width:auto;height:100%}@media (max-width:767px){.visual-container__svg[data-v-6376865e]{top:-5px;left:0}}.visual-container__img-overlay[data-v-6376865e]{opacity:1;position:absolute;top:0;right:0;left:0;display:block;max-width:100%;height:auto;-webkit-transition:.1s ease-out;transition:.1s ease-out}@media (max-width:767px){.visual-container__img-overlay[data-v-6376865e]{top:0}}.visual-container__img-overlay-button[data-v-6376865e]{cursor:pointer;position:absolute;top:27%;left:22%;border-radius:110px;width:220px;height:220px;-webkit-tap-highlight-color:transparent}.pointer[data-v-6376865e]{display:block;position:absolute;top:10%;left:66%;font-size:14px;text-transform:uppercase;text-align:center;line-height:1.3em;letter-spacing:.1em}@media (max-width:768px){.pointer[data-v-6376865e]{font-size:12px;top:2%}}.pointer[data-v-6376865e]:before{content:"tap";display:block}@media (min-width:960px){.pointer[data-v-6376865e]:before{content:"click"}}.pointer[data-v-6376865e]:after{content:"";position:absolute;display:block;width:36px;height:27px;top:43px;left:-14px;background:url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIxLjAuMiwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMCIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHdpZHRoPSIzNS44cHgiIGhlaWdodD0iMjYuNHB4IiB2aWV3Qm94PSIwIDAgMzUuOCAyNi40IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAzNS44IDI2LjQiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnPgoJCTxwYXRoIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgZD0iTTM0LjgsMQoJCQlDMjYuNyw5LjksMTcuMSwxNy4yLDYuNSwyMi41Ii8+CgkJPGc+CgkJCTxwb2x5Z29uIHBvaW50cz0iNS43LDE3LjQgMCwyNS41IDkuOSwyNi40IAkJCSIvPgoJCTwvZz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K) no-repeat}@media (max-width:768px){.pointer[data-v-6376865e]:after{top:37px;left:-1px}}.ios-start-overlay[data-v-6376865e]{position:absolute;top:0;right:0;bottom:0;left:0;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-tap-highlight-color:transparent}.ios-start-overlay[data-v-6376865e]:before{content:"";opacity:.82;position:absolute;display:block;top:0;right:0;bottom:0;left:0;background:#fff}.ios-start-button[data-v-6376865e]{position:relative;margin-right:26%;padding:12px 26px;border:1px solid #000;border-radius:99px;background:hsla(0,0%,100%,.94);font-size:14px;color:#000}

#audio-app:hover {
    cursor: pointer;
}
</style>
<?php
// public/maintenance.php

// This file is served by Laravel when the application is in maintenance mode.
// It's placed directly in the 'public' directory.
// All asset paths have been updated to point to a 'maintenance_assets' subfolder.
?>
<!DOCTYPE html>
<html class="no-js" lang="en-GB" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
    <meta name="theme-color" content="#000707">
    <script>
      document.documentElement.className = document.documentElement.className.replace("no-js", "js");

      function sniff() {
        this.version = "1.3", this.browserType, this.browserVersion, this.mobile, this.browserVersionExtended = null;
        for (var e, i = !1, s = navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/iPhone|iPad|iPod/i) || navigator.userAgent.match(/Opera Mini/i) || navigator.userAgent.match(/IEMobile/i) || null, o = navigator.appName, n = navigator.userAgent, t = n.match(/(opera|chrome|safari|firefox|msie|trident|edge)\/?\s*(\.?\d+(\.\d+)*)/i), r = n.match(/(opera|chrome|safari|firefox|msie|trident|edge)\/?\s*(\.?\d+(\.\d+)*)/gi), a = 0; a < r.length; a++)
          if (r[a].toLowerCase().indexOf("edge") >= 0) {
            var d = r[a].split("index.html"),
              m = d[0].toLowerCase(),
              f = d[1];
            i = !0;
          } if (!i) {
          t && null != (e = n.match(/version\/([\.\d]+)/i)) && (t[2] = e[1]), t = t ? [t[1], t[2]] : [o, navigator.appVersion, "-?"];
          var m = t[0].toLowerCase(),
            f = t[1];
          if ("trident" == t[0].toLowerCase()) {
            m = "msie";
            f = n.match(/(rv)\:?(\.?\d+(\.\d+)*)/i);
            for (var l = 0; l < f.length; l++)
              if (!isNaN(parseInt(f[l]))) {
                f = f[l];
                break
              }
          }
        }
        this.mobile = s, this.browserType = m, this.browserVersion = parseInt(f), this.browserVersionExtended = f
      }
      var sniff = new sniff;
      "undefined" != typeof document.documentElement.classList ? (document.documentElement.classList.add(sniff.browserType), document.documentElement.classList.add("v" + sniff.browserVersion), null != sniff.mobile && document.documentElement.classList.add(sniff.mobile.toString().toLowerCase())) : (document.documentElement.className += " " + sniff.browserType + " v" + sniff.browserVersion, null != sniff.mobile && (document.documentElement.className += " " + sniff.mobile.toString().toLowerCase()));
    </script>
    <link type="text/css" media="screen" rel="stylesheet" href="maintenance_assets/css/style.css">
    <link rel="shortcut icon" type="image/png" href="maintenance_assets/images/favicon.png">
    <title>Download Festival Schedule</title>
  </head>
  <body>
    <div class="hero-panel" id="page-content">
      <div class="hero-detail"></div>
      <div class="title-wrap">
        <div class="hero-pane splash" id="maintenance-splash">
          <h1>
            <img class="animation-slideUp" src="maintenance_assets/images/dl-logo.svg" alt="Download Festival" />
          </h1>
          <div class="hp-panel tag-line">The UK's Premier Rock Festival</div>
          <div class="hp-panel details animation-fadeIn">
            <span>See you next year...</span>
            <span class="splash-link-wrap ">
              <a class="splash-link" href="#">Discord</a>
            </span>
          </div>
        </div>
        <div class="hero-pane splash hidden" id="maintenance-login-form">
            <h1>
            <img class="animation-slideUp" src="maintenance_assets/images/dl-logo.svg" alt="Download Festival" />
          </h1>
          <div class="hp-panel tag-line">Admin Login</div>
          <div class="hp-panel details animation-fadeIn">
            <form id="admin-login-form" method="POST" action="#"> 
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email Address" required autocomplete="email">
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                </div>
                <button type="submit" class="button-submit">Login</button>
                <button id="back-to-splash" class="button-submit">Back</button>
                <div class="login-error-message hidden"></div>
            </form>
        </div>
    </div>
      </div>
      <div class="hero-vid">
        <video class="hero-vid" id="hero-vid" autoplay muted playsinline loop>
          <source src="maintenance_assets/videos/DL-HomeLoop-01.mp4" type="video/mp4">
        </video>
      </div>
      <button id="hero-pause-play" class="playing" aria-label="Pause background video">
        <span class="text visually-hidden">Pause background video</span>
        <span class="icon play"></span>
        <span class="icon pause"></span>
      </button>
    </div>
    <footer>
      <a href="#" class="dl-dog-detail" id="dl-dog-trigger" aria-label="Toggle Admin Login"></a>
      <div class="say-hello"></div>
    </footer>
    <div class="footer-detailing-back">
      <div class="fade"></div>
    </div>
    <div class="footer-detailing-front"></div>
    </div>
    <script type="text/javascript" src="maintenance_assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="maintenance_assets/js/script.js"></script>
    <noscript>
      <div class="no-script-notification">
        <h2>Website Compatibility</h2>
        <p>It looks like Javascript is currently disabled. <br>Please enable it to use all the features of this site </p>
      </div>
    </noscript>
  </body>
</html>
<template>
    <div>
        <div class="hero-panel" id="page-content">
            <div class="hero-detail"></div>
            <div class="title-wrap">
                <div class="hero-pane splash">
                    <h1>
                        <img
                            class="animation-slideUp"
                            src="/maintenance_assets/images/dl-logo.svg"
                            alt="Download Festival"
                        />
                    </h1>
                    <div class="hp-panel tag-line">
                        The UK's Premier Rock Festival
                    </div>
                    <div class="hp-panel details animation-fadeIn">
                        <span class="splash-link">See you next year...</span>
                    </div>
                </div>
            </div>
            <div class="hero-vid">
                <video
                    class="hero-vid"
                    id="hero-vid"
                    autoplay
                    muted
                    playsinline
                    loop
                >
                    <source
                        src="/maintenance_assets/videos/DL-HomeLoop-01.mp4"
                        type="video/mp4"
                    />
                </video>
            </div>
            <button
                id="hero-pause-play"
                class="playing"
                aria-label="Pause background video"
            >
                <span class="text visually-hidden">Pause background video</span>
                <span class="icon play"></span>
                <span class="icon pause"></span>
            </button>
        </div>
        <footer>
            <div
                class="visually-hidden"
                role="region"
                id="hidden-aria-alerts-polite"
                aria-live="polite"
            ></div>
            <div
                class="visually-hidden"
                role="region"
                id="hidden-aria-alerts-assertive"
                aria-live="assertive"
            ></div>
            <span class="dl-dog-detail"></span>
            <div class="say-hello"></div>
        </footer>
        <div class="footer-detailing-back">
            <div class="fade"></div>
        </div>
        <div class="footer-detailing-front"></div>
    </div>
</template>

<script setup>
document.documentElement.className = document.documentElement.className.replace(
    "no-js",
    "js"
);

function sniff() {
    (this.version = "1.3"),
        this.browserType,
        this.browserVersion,
        this.mobile,
        (this.browserVersionExtended = null);
    for (
        var e,
            i = !1,
            s =
                navigator.userAgent.match(/Android/i) ||
                navigator.userAgent.match(/BlackBerry/i) ||
                navigator.userAgent.match(/iPhone|iPad|iPod/i) ||
                navigator.userAgent.match(/Opera Mini/i) ||
                navigator.userAgent.match(/IEMobile/i) ||
                null,
            o = navigator.appName,
            n = navigator.userAgent,
            t = n.match(
                /(opera|chrome|safari|firefox|msie|trident|edge)\/?\s*(\.?\d+(\.\d+)*)/i
            ),
            r = n.match(
                /(opera|chrome|safari|firefox|msie|trident|edge)\/?\s*(\.?\d+(\.\d+)*)/gi
            ),
            a = 0;
        a < r.length;
        a++
    )
        if (r[a].toLowerCase().indexOf("edge") >= 0) {
            var d = r[a].split("index.html"),
                m = d[0].toLowerCase(),
                f = d[1];
            (i = !0), console.log("so qui");
        }
    if (!i) {
        t && null != (e = n.match(/version\/([\.\d]+)/i)) && (t[2] = e[1]),
            (t = t ? [t[1], t[2]] : [o, navigator.appVersion, "-?"]);
        var m = t[0].toLowerCase(),
            f = t[1];
        if ("trident" == t[0].toLowerCase()) {
            (m = "msie"),
                console.log(n),
                (f = n.match(/(rv)\:?(\.?\d+(\.\d+)*)/i));
            for (var l = 0; l < f.length; l++)
                if (!isNaN(parseInt(f[l]))) {
                    f = f[l];
                    break;
                }
        }
    }
    (this.mobile = s),
        (this.browserType = m),
        (this.browserVersion = parseInt(f)),
        (this.browserVersionExtended = f);
}
var sniffInstance = new sniff();
"undefined" != typeof document.documentElement.classList
    ? (document.documentElement.classList.add(sniffInstance.browserType),
      document.documentElement.classList.add(
          "v" + sniffInstance.browserVersion
      ),
      null != sniffInstance.mobile &&
          document.documentElement.classList.add(
              sniffInstance.mobile.toString().toLowerCase()
          ))
    : ((document.documentElement.className +=
          " " +
          sniffInstance.browserType +
          " v" +
          sniffInstance.browserVersion),
      null != sniffInstance.mobile &&
          (document.documentElement.className +=
              " " + sniffInstance.mobile.toString().toLowerCase()));
</script>

<style src="../../css/maintenance-style.css"></style>
<style src="../../css/maintenance-print.css" media="print"></style>

var Lazy = {
        settings: {
            class_lazy: "lazy",
            class_transparency: "transparency",
            placeholder: "/assets/images/lazy-holding.png"
        },
        init: function() {
            $(".div.lazy, .video-thumb.lazy").lazyload({
                load: function() {
                    $(this).removeClass(Lazy.settings.class_lazy)
                }
            }), $("div.lazy").lazyload({
                load: function() {
                    $(this).removeClass(Lazy.settings.class_lazy)
                }
            }), $(".author img.lazy, .lightbox img, .image-container img, #poster-block img, .g-image img").lazyload({
                placeholder: Lazy.settings.placeholder,
                load: function() {
                    $(this).removeClass(Lazy.settings.class_lazy)
                }
            }), $(".block-link .feature-image img.lazy, .news-block .feature-image img.lazy, .artist-listings img.lazy").lazyload({
                placeholder: Lazy.settings.placeholder,
                load: function() {
                    $(this).removeClass(Lazy.settings.class_lazy), $(this).parent(".feature-image").removeClass(Lazy.settings.class_transparency)
                }
            })
        }
    },
    General = {
        settings: {
            $body: $("body"),
            $html_body: $("html,body"),
            $header: $("header"),
            $footer: $("footer"),
            $countdown: $(".countdown"),
            $menu: $(".menu"),
            $modal: $(".pop-modal"),
            $hi: $(".say-hello"),
            $home_scroll: $('a.scroll-to-content[href*="#"]:not([href="#"]'),
            $isotope: $(".whatson-block, .news-block, .gallery-block"),
            $news_slider: $(".news-slider"),
            $news_lazy: $(".feature-image img.lazy"),
            news_titles: ".news-article .link-wrap a h2 span, .article-title h1",
            $social_link: $(".social-links a"),
            $partner_link: $(".partner-strip a"),
            $outbound_links: $(".outbound-links a"),
            $login_form: $("form.page-login"),
            class_lazy: "lazy",
            class_transparency: "transparency",
            placeholder: "/assets/images/lazy-holding.png",
            class_wiff: "woof",
            class_nav_open: "nav-open",
            class_main_nav: "main-nav",
            class_touch: "touch-enabled",
            $alert: $(".alert-banner"),
            $alert_dismiss: $(".dismiss"),
            cookie_name: "dl-site-alert",
            cookie_value: 1,
            cookie_length: 30,
            countdown_interval: null
        },
        init: function() {
            this.bindEvents(), this.initCountdown(), this.initIsotope(), this.initModal(), this.initNewsSlides(), this.initWiff(), this.initTouch(), this.alertCheck(), this.setTogglePanels(), this.setFeatureToggle(), this.setGalleryToggle()
        },
        initTouch: function() {
            "ontouchstart" in document.documentElement && General.settings.$body.addClass(General.settings.class_touch)
        },
        initCountdown: function() {
            General.updateCountdown(), General.settings.countdown_interval = setInterval((function() {
                General.updateCountdown()
            }), 3e4)
        },
        updateCountdown: function() {
            var t = Date.parse(new Date),
                e = Date.parse(General.settings.$countdown.data("timer")) - t;
            if (e < 0) null != General.settings.countdown_interval && (General.settings.countdown_interval = clearInterval()), $(General.settings.$countdown).empty().hide();
            else {
                var s = Math.floor(e / 1e3 / 60 % 60),
                    i = Math.floor(e / 36e5 % 24),
                    a = Math.floor(e / 864e5);
                $(General.settings.$countdown).html('<div class="cd-wrap" aria-hidden="true"><div><div class="inner" >' + a + '</div> days</div><div class="centre"><div class="inner">' + i + '</div> hrs</div><div><div class="inner">' + s + '</div> mins</div></div><span class="visually-hidden">' + a + " days, " + i + " hours and " + s + " minutes until Download Festival</span>")
            }
        },
        initIsotope: function() {
            General.settings.$isotope.isotope({
                itemSelector: ".grid-item",
                percentPosition: !0,
                masonry: {
                    columnWidth: ".grid-item"
                }
            })
        },
        initModal: function() {
            if ($(".block-gallery").length) {
                new Tobii({
                    zoom: !1,
                    captions: !1,
                    nav: !0
                })
            }
        },
        initNewsSlides: function() {
            $(".news-slider").slick({
                slidesToShow: 2,
                lazyLoad: "ondemand",
                slidesToScroll: 1,
                autoplay: !1,
                autoplaySpeed: 1800,
                speed: 600,
                easing: "ease",
                dots: !0,
                infinite: !1,
                arrows: !0,
                responsive: [{
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            })
        },
        initWiff: function() {
            General.settings.$hi.waypoint((function() {
                General.settings.$footer.toggleClass(General.settings.class_wiff)
            }), {
                offset: "bottom-in-view"
            })
        },
        bindEvents: function() {
            General.settings.$menu.on("click", General.menuToggle), General.settings.$home_scroll.on("click", General.homeScroll), General.settings.$alert_dismiss.on("click", General.alertApprove)
        },
        menuToggle: function() {
            General.settings.$header.toggleClass(General.settings.class_nav_open), $("header").hasClass("nav-open") ? $(this).attr("aria-expanded", "true") : $(this).attr("aria-expanded", "false")
        },
        homeScroll: function(t) {
            if (t.preventDefault(), location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
                var e = $(this.hash);
                if ((e = e.length ? e : $("[name=" + this.hash.slice(1) + "]")).length) return $("html, body").animate({
                    scrollTop: e.offset().top - 100
                }, 1e3, "easeInOutQuint"), e.focus(), e.is(":focus") || (e.attr("tabindex", "-1"), e.focus()), !1
            }
        },
        alertCheck: function() {
            var t = General.alertGet();
            null != t && "" != t && null != t || General.settings.$alert.removeClass("hidden")
        },
        alertGet: function() {
            var t, e, s, i = document.cookie.split(";");
            for (t = 0; t < i.length; t++)
                if (e = i[t].substr(0, i[t].indexOf("=")), s = i[t].substr(i[t].indexOf("=") + 1), (e = e.replace(/^\s+|\s+$/g, "")) == General.settings.cookie_name) return unescape(s)
        },
        alertApprove: function(t) {
            var e = new Date;
            e.setDate(e.getDate() + General.settings.cookie_length);
            var s = escape(General.settings.cookie_value) + (null == General.settings.cookie_length ? "" : "; expires=" + e.toUTCString());
            document.cookie = General.settings.cookie_name + "=" + s + "; path=/", General.settings.$alert.addClass("hidden")
        },
        setTogglePanels: function() {
            $(".toggle-pane .toggle-feature").click((function() {
                $(this).closest(".toggle-pane").toggleClass("open")
            }))
        },
        setFeatureToggle: function() {
            $("button.ft-button").click((function() {
                $(this).closest(".tf-feature-panel").toggleClass("open"), $(this).closest(".tf-feature-panel").hasClass("open") ? ($(this).closest(".tf-feature-panel").find(".panel-content").removeAttr("hidden"), $(this).find(".b-text").text("Less info "), $(this).attr("aria-expanded", "true")) : ($(this).closest(".tf-feature-panel").find(".panel-content").attr("hidden", ""), $(this).attr("aria-expanded", "false"), $(this).find(".b-text").text("More info "))
            }))
        },
        setGalleryToggle: function() {
            $("button.g-button-toggle").click((function() {
                $(this).closest(".g-image.grid-item").hasClass("enlarged") ? ($(this).closest(".g-image.grid-item").removeClass("enlarged"), $(this).find("span").text("Maximize image size"), $(this).attr("aria-label", "Maximize image size")) : ($(this).closest(".g-image.grid-item").addClass("enlarged"), $(this).find("span").text("Minimize image size"), $(this).attr("aria-label", "Minimize image size")), $(".gallery-block").isotope("layout")
            }))
        }
    },
    Artists = {
        settings: {
            $artist_filter: $(".artist-filter"),
            $artist_filter_toggle: $(".artist-filter button.filter-toggle"),
            $artist_filter_overlay: $("span.overlay-close"),
            $artist_filter_reset: $("button.reset-filters"),
            $artist_filter_apply: $(".apply-filters"),
            $artist_filter_checkbox: $(".filters input:checkbox"),
            $artist_filter_types_div: $("#artist-filter-types div"),
            $artist_filter_types_div_first: $("#artist-filter-types div:first"),
            $artist_filter_types_li: $("#artist-filter-types ul li"),
            $artist_filter_types_li_first: $("#artist-filter-types ul li:first"),
            $artist_filter_types_li_first_button: $("#artist-filter-types ul li:first button"),
            $artist_filter_types_li_button: $("#artist-filter-types ul li button"),
            $artist_filter_genre_default: $("#by-genre").find("input[type=checkbox]:checked"),
            $artist_filter_genre: $("#by-genre input:checkbox"),
            $artist_filter_day: $("#by-day input:checkbox"),
            $artist_filter_stage: $("#by-stage input:checkbox"),
            $artist_filter_name: $("#by-name input:checkbox"),
            $isotope: $(".artist-listings"),
            artist_lazy_load: ".artist-listings img.lazy",
            artist_visible: ".artist:visible",
            placeholder: "/assets/images/lazy-holding.png",
            check_groups: ["day", "stage", "name", "genre"],
            class_open: "open",
            class_lazy: "lazy",
            class_alert: "alert",
            class_feature_image: "feature-image",
            class_transparency: "transparency",
            class_current: "current",
            class_nav_open: "nav-open"
        },
        init: function() {
            enquire.register("screen and (max-width: 900px)", {
                match: function() {
                    Artists.settings.$artist_filter_types_div.hide().attr("hidden", ""), Artists.settings.$artist_filter_types_div_first.show().removeAttr("hidden"), Artists.settings.$artist_filter_types_li_first.addClass(Artists.settings.class_current), Artists.settings.$artist_filter_types_li_button.attr("aria-expanded", "false"), Artists.settings.$artist_filter_types_li_first_button.attr("aria-expanded", "true"), Artists.settings.$artist_filter_types_li_button.on("click", (function() {
                        var t = $(this).attr("data-tab-target");
                        return Artists.settings.$artist_filter_types_li.removeClass(Artists.settings.class_current), Artists.settings.$artist_filter_types_li_button.attr("aria-expanded", "false"), $(this).attr("aria-expanded", "true"), $(this).parent().addClass(Artists.settings.class_current), Artists.settings.$artist_filter_types_div.hide().removeClass(Artists.settings.class_current).attr("hidden", ""), $(t).show().addClass(Artists.settings.class_current).removeAttr("hidden"), !1
                    }))
                },
                unmatch: function() {
                    Artists.settings.$artist_filter_types_div.show().removeClass(Artists.settings.class_current).removeAttr("hidden"), Artists.settings.$artist_filter_types_li_button.removeAttr("aria-expanded"), Artists.settings.$artist_filter_types_li.removeClass(Artists.settings.class_current)
                }
            }), Artists.bindEvents(), Artists.artistFilterGo(!0)
        },
        bindEvents: function() {
            Artists.settings.$artist_filter_checkbox.on("click", Artists.artistFilter), Artists.settings.$artist_filter_reset.on("click", Artists.artistFilterReset), Artists.settings.$artist_filter_apply.on("click", Artists.artistFilterApply)
        },
        artistFilter: function(t) {
            var e = $(this),
                s = e.prop("name").substring(0, e.prop("name").indexOf("-")),
                i = e.prop("name").substr(e.prop("name").indexOf("-") + 1),
                a = $("#by-" + s).find("input:checkbox").length - 1;
            "all" === i ? $("#by-" + s).find('input[type=checkbox]:checked:not("#' + s + '-all")').removeAttr("checked") : $("#" + s + "-all").removeAttr("checked"), 0 === $("#by-" + s).find("input[type=checkbox]:checked").length ? $("#" + s + "-all").prop("checked", !0) : $("#by-" + s).find('input[type=checkbox]:checked:not("#' + s + '-all")').length === a && ($("#by-" + s).find("input[type=checkbox]").removeAttr("checked"), $("#" + s + "-all").prop("checked", !0))
        },
        artistFilterReset: function(t) {
            $.each(Artists.settings.check_groups, (function(t, e) {
                $("#by-" + e).find("input[type=checkbox]").removeAttr("checked"), "genre" !== e ? $("#" + e + "-all").prop("checked", !0) : Artists.settings.$artist_filter_genre_default.prop("checked", !0)
            })), $("#hidden-aria-alerts-polite").empty().text("Any selected filters have been reset")
        },
        artistFilterApply: function(t) {
            var e = "",
                s = {};
            $.each(Artists.settings.check_groups, (function(t, e) {
                var i = $("#by-" + e).find("input[type=checkbox]:checked"),
                    a = i.map((function() {
                        return i.length > 6 ? 0 : $(this).val().replace(/^(genre-|day-|stage-|name-)/, "").replace(/-/g, " ")
                    })).get();
                a.length > 0 && (s[e] = a)
            })), e = "Selected filters applied - ", $.each(s, (function(t, s) {
                s.length > 6 ? e += `${t}: ${s.length} filters applied. ` : e += `${t}: ${s.join(", ")}. `
            })), $("#hidden-aria-alerts-polite").empty().text(e), Artists.artistFilterGo()
        },
        artistFilterBuildArray: function(t, e) {
            var s = [],
                i = [];
            return t.each((function() {
                this.checked && (s = $(this).prop("class").split(" "), $.each(s, (function(t, s) {
                    "" !== s && s !== e + "-all" && i.push(s)
                })))
            })), i.length <= 0 && i.push("*"), i
        },
        artistFilterGo: function(t) {
            var e, s = Artists.artistFilterBuildArray(Artists.settings.$artist_filter_genre, "genre"),
                i = Artists.artistFilterBuildArray(Artists.settings.$artist_filter_day, "day"),
                a = Artists.artistFilterBuildArray(Artists.settings.$artist_filter_stage, "stage"),
                n = Artists.artistFilterBuildArray(Artists.settings.$artist_filter_name, "name"),
                o = "";
            $.each(n, (function(t, n) {
                $.each(a, (function(t, a) {
                    $.each(i, (function(t, i) {
                        $.each(s, (function(t, s) {
                            e = "", "*" !== n && (e = "." + n), "*" !== a && (e += "." + a), "*" !== i && (e += "." + i), "*" !== s && (e += "." + s), o += e + ", "
                        }))
                    }))
                }))
            })), "" !== (o = o.replace(/,\s*$/, "")) && null !== o && "undefined" !== o || (o = "*"), Artists.settings.$isotope.isotope({
                filter: o
            }), Artists.settings.$isotope.on("arrangeComplete", Artists.artistPostFilter)
        },
        artistPostFilter: function(t, e) {
            if ($(Artists.settings.artist_visible).length <= 0) !$('#hidden-aria-alerts-polite:contains("No artists found")').length > 0 && $("#hidden-aria-alerts-polite").append("No artists found"), $("#no-result").removeAttr("hidden").show();
            else {
                $("#no-result").attr("hidden", "").hide();
                var s = $(Artists.settings.artist_visible).length;
                !$('#hidden-aria-alerts-polite:contains("artist(s) found")').length > 0 && $("#hidden-aria-alerts-polite").append(s + " artist(s) found"), $(Artists.settings.artist_lazy_load).lazyload({
                    placeholder: Artists.settings.placeholder,
                    load: function() {
                        $(this).removeClass(Artists.settings.class_lazy), $(this).parent("." + Artists.settings.class_feature_image).removeClass(Artists.settings.class_transparency)
                    }
                })
            }
        }
    },
    News = {
        settings: {
            $news: $(".news-block"),
            $news_load_more: $(".load-more"),
            $news_current_taxonmy: $(".news-categories a.active").data("term"),
            $news_categories: $(".type-list li a"),
            $news_loader: $(".loading-animation"),
            $news_load_more_note: $(".end-notification"),
            type_list_li: ".type-list li",
            type_list_li_a: ".type-list li a",
            news_lazy_load: ".news-block .feature-image img.lazy",
            class_feature_image: "feature-image",
            class_transparency: "transparency",
            class_news_article: "news-article",
            class_active: "active",
            class_inactive: "inactive",
            class_lazy: "lazy",
            class_visible: "visible",
            placeholder: "/assets/images/lazy-holding.png",
            thats_all: "That's all folks!"
        },
        init: function() {
            News.bindEvents()
        },
        bindEvents: function() {
            News.settings.$news_load_more.on("click", News.newsAjax), News.settings.$news_categories.on("click", News.newsCategoryChange)
        },
        newsCategoryChange: function(t) {
            t.preventDefault(), News.settings.$news_categories.removeClass(News.settings.class_active), $(t.target).addClass(News.settings.class_active), News.settings.$news_current_taxonmy = $(t.target).data("term"), News.settings.$news.isotope("remove", $("." + News.settings.class_news_article)), history.pushState ? window.history.pushState(News.settings.$news_current_taxonmy, null, t.target.href) : document.location.href = t.target.href, News.settings.$news.on("removeComplete", (function(t) {
                News.newsAjax(t)
            }))
        },
        newsAjax: function(t) {
            var e = "";
            null == News.settings.$news_current_taxonmy && (News.settings.$news_current_taxonmy = $(".news-categories a.active").data("term")), "" !== News.settings.$news_current_taxonmy && (e = News.settings.$news_current_taxonmy), News.newsToggleLoader(), News.newsToggleLoadMore("hide");
            var s = $(".news-article").length;
            return $.ajax({
                url: ajaxurl,
                data: {
                    action: "newsAjax",
                    offset: $("." + News.settings.class_news_article).length,
                    terms: e
                },
                success: function(e) {
                    var i = e.split("~~~"),
                        a = $(i[0]),
                        n = i[1];
                    return News.settings.$news.off("removeComplete"), News.settings.$news.isotope().append(a).isotope("appended", a).isotope("layout"), News.settings.$news.on("layoutComplete", News.newsLazyLoad), News.newsToggleLoader(), $(".news-article").length > s && setTimeout((function(t) {
                        $(".news-article:nth-child(" + (t + 1) + ") a:first").focus(), $("#hidden-aria-alerts-polite").empty().text("New articles loaded")
                    }), 100, s), $("." + News.settings.class_news_article).length >= n ? (News.newsToggleLoadMore("hide"), null != t && ("load-more inactive" === t.target.className ? News.settings.$news_load_more_note.text(News.settings.thats_all).addClass(News.settings.class_visible) : News.settings.$news_load_more_note.empty())) : (News.newsToggleLoadMore("show"), News.settings.$news_load_more_note.empty().removeClass(News.settings.class_visible)), !1
                }
            }), !1
        },
        newsPopState: function(t) {
            News.settings.$news_categories.removeClass(News.settings.class_active), null == t.state ? $(News.settings.type_list_li_a).not("[data-term]").addClass(News.settings.class_active) : $(News.settings.type_list_li).find("a[data-term='" + t.state + "']").addClass(News.settings.class_active), News.settings.$news_current_taxonmy = $("a.active").data("term"), News.settings.$news.isotope("remove", $("." + News.settings.class_news_article)), News.settings.$news.on("removeComplete", (function() {
                News.newsAjax()
            }))
        },
        newsToggleLoader: function() {
            News.settings.$news_loader.toggleClass(News.settings.class_active), $("#hidden-aria-alerts-polite").empty().text("Loading more news articles")
        },
        newsToggleLoadMore: function(t) {
            "hide" === t ? News.settings.$news_load_more.addClass(News.settings.class_inactive).hide() : News.settings.$news_load_more.removeClass(News.settings.class_inactive).show()
        },
        newsLazyLoad: function() {
            $(News.settings.news_lazy_load).lazyload({
                placeholder: News.settings.placeholder,
                load: function() {
                    $(this).removeClass(News.settings.class_lazy), $(this).parent("." + News.settings.class_feature_image).removeClass(News.settings.class_transparency)
                }
            }), News.settings.$news.off("layoutComplete")
        }
    },
    Info = {
        settings: {
            $info: $(".info-articles"),
            $info_articles: $(".info-articles"),
            $info_dropdown: $(".info-navigation"),
            $info_dropdown_button: $("#info-category-selector"),
            $info_toggleall_button: $(".t-all-i-panels"),
            $info_dropdown_li_a: $(".info-navigation li a"),
            $info_category_title: $(".info-category-title h2"),
            $info_category_title_span: $(".info-category-title span"),
            $info_current_taxonmy: $("li.current").data("term"),
            $info_loader: $(".loading-animation"),
            $info_search_form: $(".info-search"),
            info_search: ".search-input",
            info_panel: ".info-panel",
            info_current: ".current",
            class_current: "current",
            class_active: "active",
            class_open: "open",
            text_viewing: "CURRENTLY VIEWING:"
        },
        init: function() {
            if (Info.bindEvents(), window.location.hash) {
                const e = window.location.hash;
                var t = $(e);
                General.settings.$html_body.animate({
                    scrollTop: $(t).offset().top - 100
                }, 100, "easeInOutQuint"), t.length && t.find("button").first().click()
            }
        },
        bindEvents: function() {
            Info.settings.$info_toggleall_button.on("click", Info.infoCategoryToggleAll), Info.settings.$info_articles.on("click", ".info-title button", Info.infoItemToggle)
        },
        infoItemToggle: function(t) {
            $(t.target).parents(Info.settings.info_panel).toggleClass(Info.settings.class_open), $(t.target).parents(Info.settings.info_panel).hasClass("open") ? ($(t.target).closest(".info-panel").find(".info-toggle").removeAttr("hidden"), $(t.target).closest(".info-panel").find(".info-title button").attr("aria-expanded", "true")) : ($(t.target).closest(".info-panel").find(".info-toggle").attr("hidden", ""), $(t.target).closest(".info-panel").find(".info-title button").attr("aria-expanded", "false"))
        },
        infoCategoryToggleAll: function(t) {
            $(t.target).toggleClass("all-open"), $(t.target).hasClass("all-open") ? ($(".t-all-i-panels span").text("closed"), $(".info-panel").addClass("open"), $(".info-panel").find(".info-toggle").removeAttr("hidden"), $(".info-panel").find(".info-title button").attr("aria-expanded", "true")) : ($(".t-all-i-panels span").text("open"), $(".info-panel").removeClass("open"), $(".info-panel").find(".info-toggle").attr("hidden", ""), $(".info-panel").find(".info-title button").attr("aria-expanded", "false"))
        }
    },
    Share = {
        settings: {
            $facebook_button: $(".share-links a.facebook"),
            $twitter_button: $(".share-links a.twitter"),
            $whatsapp_button: $(".share-links a.whatsapp"),
            $clip_button: $(".share-links button.link"),
            $clip_notify: $(".copy-notified"),
            $info_clip_button: $(".anchor-grab button"),
            clip_text: "Link copied to clipboard",
            info_clip_text: "Link copied",
            class_tooltip: "tooltip",
            share_loc: $('meta[property="og:url"]').attr("content"),
            share_title: encodeURIComponent($('meta[property="og:title"]').attr("content")),
            share_img: $('meta[property="og:image"]').attr("content"),
            share_via_twitter: $("footer a.twitter").attr("href")
        },
        init: function() {
            Share.settings.share_loc || (Share.settings.share_loc = window.location.href), Share.settings.$facebook_button.on("click", this.facebookShare), Share.settings.$twitter_button.on("click", this.twitterShare), Share.settings.$whatsapp_button.on("click", this.whatsappShare), Share.settings.$clip_button.on("click", this.clipboardShare), Share.settings.$info_clip_button.on("click", this.infoClipboardShare)
        },
        facebookShare: function(t) {
            t.preventDefault(), window.open("https://www.facebook.com/sharer/sharer.php?u=" + Share.settings.share_loc, "dl_facebook_share", "height=600, width=600, top=" + ($(window).height() / 2 - 300) + ", left=" + $(window).width() / 2 + ",toolbar=0, location=0, menubar=0, directories=0, scrollbars=0")
        },
        twitterShare: function(t) {
            var e;
            t.preventDefault(), void 0 !== Share.settings.share_via_twitter ? e = "&via=" + (e = Share.settings.share_via_twitter.match(/https?:\/\/(www\.)?twitter\.com\/(#!\/)?@?([^\/]*)/)[3]) : Share.settings.share_via_twitter_name = "", window.open("http://twitter.com/share?url=" + Share.settings.share_loc + e + "&text=" + Share.settings.share_title + "&", "twitterwindow", "height=450, width=550, top=" + ($(window).height() / 2 - 225) + ", left=" + $(window).width() / 2 + ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0")
        },
        whatsappShare: function(t) {
            t.preventDefault(), window.location.replace("whatsapp://send?text=" + encodeURI(Share.settings.share_loc))
        },
        clipboardShare: function(t) {
            var e = document.location.href;
            $clipboard = new Clipboard("button.link", {
                text: function() {
                    return e
                }
            }), $clipboard = null, Share.settings.$clip_notify.html(Share.settings.clip_text).addClass(Share.settings.class_tooltip), $("#hidden-aria-alerts-polite").empty().text("Page URL copied to clipboard"), setTimeout((function() {
                Share.settings.$clip_notify.empty().removeClass(Share.settings.class_tooltip)
            }), 5e3)
        },
        infoClipboardShare: function(t) {
            var e = $(this).attr("data-share-location");
            $clipboard = new Clipboard(".anchor-grab button", {
                text: function() {
                    return e
                }
            }), $clipboard = null, $(this).html(Share.settings.info_clip_text).addClass(Share.settings.class_tooltip), $("#hidden-aria-alerts-polite").empty().text("Information link copied to clipboard"), setTimeout((function() {
                Share.settings.$info_clip_button.html("Copy link to clipboard").removeClass(Share.settings.class_tooltip)
            }), 3e3)
        }
    },
    Video = {
        settings: {
            player_iframe: $("#main-video iframe"),
            player_title: $(".video-details h2 span"),
            playlist_wrapper: $(".playlists"),
            playlist_toggle: $(".playlists h2 button"),
            playlist_selector: $(".nav-wrapper li"),
            playlist_link: $(".nav-wrapper li button"),
            video_selector: $(".switch-vid"),
            video_item: $(".playlist-item")
        },
        init: function() {
            this.settings.playlist_toggle.click(this.toggleMenu), this.settings.playlist_selector.click(this.selectPlaylist), this.settings.video_selector.click(this.switchVideo), this.settings.playlist_link.click(this.prevJump), this.filterPlaylist()
        },
        toggleMenu: function() {
            $(this).parents(".playlists").toggleClass("open"), $(".playlists").hasClass("open") ? ($("#video-playlist-selector").removeAttr("hidden"), $(this).attr("aria-expanded", "true")) : ($("#video-playlist-selector").attr("hidden", ""), $(this).attr("aria-expanded", "false"))
        },
        selectPlaylist: function() {
            Video.settings.playlist_selector.removeClass("current"), $(this).addClass("current"), Video.settings.playlist_wrapper.removeClass("open"), Video.filterPlaylist()
        },
        switchVideo: function(t) {
            t.target;
            t.preventDefault(), Video.settings.video_item.removeClass("playing"), Video.settings.player_iframe.attr("src", $(this).data("vid-src")), $(".video-details h2 span").text($(this).data("vid-title")), $(this).parents(".playlist-item").addClass("playing"), $("html, body").animate({
                scrollTop: $("#main-video").offset().top - 120
            }, 900, "easeInOutQuint")
        },
        prevJump: function(t) {
            t.target;
            t.preventDefault()
        },
        filterPlaylist: function() {
            var t = $(".nav-wrapper li.current").data("playlist");
            $(".video-list li").addClass("not-visible"), $(".video-list li." + t).removeClass("not-visible")
        }
    };
class TabsManual {
    constructor(t) {
        this.tablistNode = t, this.tabs = [], this.selectedTab = null, this.lastTab = null;
        var e = window.location.hash.replace(/^(#)/, "");
        this.tabs = Array.from(this.tablistNode.querySelectorAll("[role=tab]")), this.tabpanels = [];
        for (var s = 0; s < this.tabs.length; s += 1) {
            var i = this.tabs[s],
                a = document.getElementById(i.getAttribute("aria-controls"));
            i.tabIndex = -1, i.setAttribute("aria-selected", "false"), this.tabpanels.push(a), i.addEventListener("keydown", this.onKeydown.bind(this)), i.addEventListener("click", this.onClick.bind(this)), e != i.getAttribute("data-anchor").replace(/^(#)/, "") && !i.classList.contains("active") && this.selectedTab || (this.selectedTab = i), this.lastTab = i
        }
        this.setSelectedTab(this.selectedTab, !1)
    }
    setTabToMatchHash = function(t) {
        for (var e = window.location.hash.replace(/^(#)/, ""), s = Array.from($("button[data-anchor]")), i = 0; i < s.length; i += 1) {
            var a = s[i];
            e == a.getAttribute("data-anchor").replace(/^(#)/, "") && (this.setSelectedTab(a, !1), this.moveFocusToTab(a))
        }
    };
    setSelectedTab = function(t, e = !0) {
        var s = t.getAttribute("data-anchor").replace(/^(#)/, "");
        e && window.history.pushState(null, null, "#" + s);
        for (var i = 0; i < this.tabs.length; i += 1) {
            var a = this.tabs[i];
            t === a ? (a.setAttribute("aria-selected", "true"), a.removeAttribute("tabindex"), this.tabpanels[i].classList.remove("is-hidden"), $("img.lazy").lazyload({
                load: function() {
                    $(this).removeClass(Lazy.settings.class_lazy)
                }
            })) : (a.setAttribute("aria-selected", "false"), a.tabIndex = -1, this.tabpanels[i].classList.add("is-hidden"))
        }
    };
    moveFocusToTab(t) {
        t.focus()
    }
    moveFocusToPreviousTab(t) {
        var e;
        t === this.firstTab ? this.moveFocusToTab(this.lastTab) : (e = this.tabs.indexOf(t), this.moveFocusToTab(this.tabs[e - 1]))
    }
    moveFocusToNextTab(t) {
        var e;
        t === this.lastTab ? this.moveFocusToTab(this.firstTab) : (e = this.tabs.indexOf(t), this.moveFocusToTab(this.tabs[e + 1]))
    }
    onKeydown(t) {
        var e = t.currentTarget,
            s = !1;
        switch (t.key) {
            case "ArrowLeft":
                this.moveFocusToPreviousTab(e), s = !0;
                break;
            case "ArrowRight":
                this.moveFocusToNextTab(e), s = !0;
                break;
            case "Home":
                this.moveFocusToTab(this.firstTab), s = !0;
                break;
            case "End":
                this.moveFocusToTab(this.lastTab), s = !0
        }
        s && (t.stopPropagation(), t.preventDefault())
    }
    onClick(t) {
        this.setSelectedTab(t.currentTarget)
    }
}
$((function() {
    Lazy.init(), General.init(), $(".info-articles").length && Info.init(), $(".news-block").length && News.init(), $(".artist-filter").length && Artists.init(), $("#main-video").length && Video.init(), Share.init()
})), $("#hero-pause-play").click((function() {
    var t = $("#hero-vid").get(0);
    t.paused ? (t.play(), $("#hero-pause-play span.text").text("Pause background video"), $(this).attr("aria-label", "Pause background video"), $(this).toggleClass("playing")) : (t.pause(), $("#hero-pause-play span.text").text("Play background video"), $(this).attr("aria-label", "Play background video"), $(this).toggleClass("playing"))
})), window.addEventListener("load", (function() {
    for (var t, e = document.querySelectorAll("[role=tablist].tab-initiation"), s = 0; s < e.length; s++) t = new TabsManual(e[s]);
    t && window.addEventListener("popstate", (e => {
        t.setTabToMatchHash()
    }))
})), document.addEventListener("DOMContentLoaded", (function() {
    document.querySelectorAll(".drawer-content a").forEach((t => {
        t.tabIndex = -1
    }));
    document.querySelectorAll(".mn-close-button").forEach((t => {
        t.tabIndex = -1
    }));
    document.querySelectorAll(".filter-close-button").forEach((t => {
        t.tabIndex = -1
    }));
    const t = document.querySelectorAll(".mn-button"),
        e = document.querySelectorAll(".mn-close-button"),
        s = document.querySelectorAll(".filter-button"),
        i = document.querySelectorAll(".filter-close-button"),
        a = document.querySelectorAll(".apply-filters");

    function n() {
        document.querySelectorAll(".drawer").forEach((t => {
            t.setAttribute("aria-hidden", "true"), t.classList.remove("panel-open")
        }))
    }

    function o(t) {
        t.setAttribute("aria-hidden", "true"), t.classList.remove("panel-open")
    }

    function l(t) {
        const e = t.currentTarget.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'),
            s = e[0],
            i = e[e.length - 1];
        "Tab" === t.key && (t.shiftKey && document.activeElement === s ? (i.focus(), t.preventDefault()) : t.shiftKey || document.activeElement !== i || (s.focus(), t.preventDefault()))
    }
    t.forEach((t => {
        t.addEventListener("click", (function() {
            n();
            const e = t.getAttribute("data-contollclass"),
                s = document.querySelector(`.drawer.${e}`);
            s.setAttribute("aria-hidden", "false"), s.classList.add("panel-open"), s.querySelector("a").focus();
            s.querySelectorAll(".drawer-content a").forEach((t => {
                t.tabIndex = 0
            }));
            s.querySelector(".mn-close-button").tabIndex = 0, document.body.classList.add("menu-open"), document.body.classList.remove("filter-open"), s.addEventListener("keydown", l)
        }))
    })), e.forEach((t => {
        t.addEventListener("click", (function() {
            const e = t.closest(".drawer"),
                s = document.querySelector(`.mn-button[data-contollclass="${e.classList[1]}"]`);
            o(e);
            e.querySelectorAll(".drawer-content a").forEach((t => {
                t.tabIndex = -1
            })), t.tabIndex = -1, s.focus(), document.body.classList.remove("menu-open"), document.body.classList.remove("filter-open"), e.removeEventListener("keydown", l)
        }))
    })), s.forEach((t => {
        t.addEventListener("click", (function() {
            n();
            const e = t.getAttribute("data-contollclass"),
                s = document.querySelector(`.drawer.${e}`);
            s.setAttribute("aria-hidden", "false"), s.classList.add("panel-open"), s.querySelector("button").focus();
            s.querySelector(".filter-close-button").tabIndex = 0, document.body.classList.add("filter-open"), s.addEventListener("keydown", l)
        }))
    })), i.forEach((t => {
        t.addEventListener("click", (function() {
            const e = t.closest(".drawer"),
                s = document.querySelector(`.filter-button[data-contollclass="${e.classList[1]}"]`);
            o(e), t.tabIndex = -1, s.focus(), document.body.classList.remove("filter-open"), document.body.classList.remove("menu-open"), e.removeEventListener("keydown", l)
        }))
    })), a.forEach((t => {
        t.addEventListener("click", (function() {
            const e = t.closest(".drawer"),
                s = document.querySelector(`.filter-button[data-contollclass="${e.classList[1]}"]`);
            o(e), s.focus(), document.body.classList.remove("filter-open"), document.body.classList.remove("menu-open"), e.removeEventListener("keydown", l)
        }))
    })), document.onkeydown = function(t) {
        if (("key" in (t = t || window.event) ? "Escape" === t.key || "Esc" === t.key : 27 === t.keyCode) && ($("body").hasClass("filter-open") || $("body").hasClass("menu-open"))) {
            const t = document.activeElement.closest(".drawer");
            document.querySelector(`button[data-contollclass="${t.classList[1]}"]`).focus(), o(t), document.body.classList.remove("filter-open"), document.body.classList.remove("menu-open"), t.removeEventListener("keydown", l)
        }
    }
}));
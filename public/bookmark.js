(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);
b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init("0c06e22671690f1006e02a4d071839e9");

/** Anonymous self-contained jQuery */
(function (window, document, version, callback) {
    var j, d;
    var loaded = false;
    if (!(j = window.jQuery) || version > j.fn.jquery || callback(j, loaded)) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://code.jquery.com/jquery-1.9.1.min.js";
        script.onload = script.onreadystatechange = function () {
            if (!loaded && (!(d = this.readyState) || d === "loaded" || d === "complete")) {
                callback((j = window.jQuery).noConflict(1), loaded = true);
                j(script).remove();
            }
        };

        document.body.appendChild(script);
    }
})(window, document, "1.9.1", function ($) {
    var inline = {

        /* Set object to send to child. */
        info: {
            description: '',
            domain: '',
            images: [],
            name: '',
            url: ''
        },

        /* Set script variables. */
        iframe: null,
        script_domain: '',

        /* Open child iframe. */
        open: function () {
            console.log('inline.open()');

            /* Fix strange prototype bug. */
            delete Array.prototype.toJSON;

            /** Set script domain. */
            if ($("#itemnation-script").attr('src').indexOf('shopgab.dev') >= 0) {
                inline.script_domain = 'shopgab.dev';
            } else if ($("#itemnation-script").attr('src').indexOf('test.shopgab.com') >= 0) {
                inline.script_domain = 'test.shopgab.com';
            } else {
                inline.script_domain = 'shopgab.com';
            }

            /* Open child iframe. */
            if ($('#itemnation-frame').length === 0) {
                inline.iframe = document.createElement('iframe');

                /* Set child iframe attributes. */
                $(inline.iframe).attr({
                    id: 'itemnation-frame',
                    src: 'http://' +
                         inline.script_domain +
                         '/bookmark/view?cacheblock=' +
                         Math.floor(Math.random() * 99999999999999999999999),
                    style: 'width: 290px !important;' +
                           'height: 760px !important;' +
                           'position: fixed !important;' +
                           'top: 10px !important;' +
                           'right: 20px !important;' +
                           'border: 0 !important;' +
                           'z-index: 99999999999999999 !important;'
                });

                /* Add child iframe to document. */
                document.getElementsByTagName('body')[0].appendChild(inline.iframe);

                /* Listen for messages from child. */
                window.addEventListener("message", function (e) {
                    inline.receive(e.data);
                });

                window.onerror = function(errorMsg, uri, lineNumber) {
                console.log('ItemNation Error!');
                var error_log = jQuery.parseJSON({
                    url: uri,
                    error: errorMsg + ':' + lineNumber
                });

               /*$.ajax({
                    url: '/bookmark/log',
                    data: error_log,
                    type: 'POST',
                    timeout: 30000,
                    dataType: 'text',
                    complete: function () {
                        child.terminate();
                    }
                });*/
            }
            }
        },

        /* Observe page information. */
        observe: function () {
            console.log('inline.observe()');

            /* Scan body for price with regex. */
            inline.info.price = $('body').text().match(/(\$[0-9,]+(\.[0-9]{2})?)/g);

            /* Save price to info object. */
            if (inline.info.price === null) {
                inline.info.price = [];
                inline.info.price[0] = '';
            }

            /* Scan meta description. */
            inline.info.description = $('meta[name="description"]').attr('content');
            if (typeof inline.info.description !== 'undefined') {
                inline.info.description = inline.info.description.replace(/(<.*?>)/ig, '');
            } else {
                inline.info.description = '';
            }

            /* Take every image ... */
            $('img').each(function () {
                /* ... that has already been downloaded ... */
                if (this.complete) {
                    /* ... with a natural width/height > 60px ... */
                    if (($(this)[0].naturalWidth > '60') && ($(this)[0].naturalHeight > '60')) {
                        /* ... with no w:h or h:w ratio > 1:4 */
                        if ((($(this)[0].naturalWidth * 4) > $(this)[0].naturalHeight) && (($(this)[0].naturalHeight * 4) > $(this)[0].naturalWidth)) {
                            /* ... and save it to the info object. */
                            var image = {
                                height: $(this)[0].naturalHeight,
                                src: $(this).prop('src'),
                                width: $(this)[0].naturalWidth
                            };

                            inline.info.images.push(image);
                        }
                    }
                }
            });

            /* Save domain, title, and URL to the info object */
            $.extend(inline.info, {
                domain: document.domain,
                name: document.title.substring(0, 50),
                url: document.URL
            });

            /* Site-specific code. */
            if (window.location.hostname === 'amazon.com') {
                console.log('amazon.com');
            }
            
            inline.open();
        },

        /* Report page information to child. */
        report: function () {
            console.log('inline.report()');

            inline.talk(JSON.stringify(inline.info));
        },

        /* Receive messages from child. */
        receive: function (data) {
            console.log('inline.receive(' + data + ')');

            /* Respond to client requests. */
            switch (data) {
                /* Send report when child is ready. */
                case 'ready':
                inline.report();
                break;

                /* Iframe assisted suicide. */
                case 'terminate':
                inline.close();
                break;
            }
        },

        /* Close and terminate child. */
        close: function () {
            console.log('inline.close()');

            $(inline.iframe).fadeOut(500, function () {
                $(inline.iframe).remove();
            });
        },

        /** Send message to child */
        talk: function (message) {
            console.log('inline.talk(' + message + ')');

            document.getElementById('itemnation-frame').contentWindow.postMessage(
                message,
                "http://" + inline.script_domain + "/assets/js/child.js"
                );
        }
    };

    inline.observe();
});

mixpanel.track('Activate extension')

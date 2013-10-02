/*!
 * Expander - v1.4.5 - 2013-03-24
 * http://plugins.learningjquery.com/expander/
 * Copyright (c) 2013 Karl Swedberg
 * Licensed MIT (http://www.opensource.org/licenses/mit-license.php)
 */

(function(e){e.expander={version:"1.4.5",defaults:{slicePoint:100,preserveWords:!0,widow:4,expandText:"read more",expandPrefix:"&hellip; ",expandAfterSummary:!1,summaryClass:"summary",detailClass:"details",moreClass:"read-more",lessClass:"read-less",collapseTimer:0,expandEffect:"slideDown",expandSpeed:250,collapseEffect:"slideUp",collapseSpeed:200,userCollapse:!0,userCollapseText:"read less",userCollapsePrefix:" ",onSlice:null,beforeExpand:null,afterExpand:null,onCollapse:null,afterCollapse:null}},e.fn.expander=function(a){function l(e,a){var l="span",s=e.summary;return a?(l="div",x.test(s)&&!e.expandAfterSummary?s=s.replace(x,e.moreLabel+"$1"):s+=e.moreLabel,s='<div class="'+e.summaryClass+'">'+s+"</div>"):s+=e.moreLabel,[s,"<",l+' class="'+e.detailClass+'"',">",e.details,"</"+l+">"].join("")}function s(e){var a='<span class="'+e.moreClass+'">'+e.expandPrefix;return a+='<a href="#">'+e.expandText+"</a></span>"}function n(a,l){return a.lastIndexOf("<")>a.lastIndexOf(">")&&(a=a.slice(0,a.lastIndexOf("<"))),l&&(a=a.replace(c,"")),e.trim(a)}function t(e,a){a.stop(!0,!0)[e.collapseEffect](e.collapseSpeed,function(){var l=a.prev("span."+e.moreClass).show();l.length||a.parent().children("div."+e.summaryClass).show().find("span."+e.moreClass).show(),e.afterCollapse&&e.afterCollapse.call(a)})}function r(a,l,s){a.collapseTimer&&(o=setTimeout(function(){t(a,l),e.isFunction(a.onCollapse)&&a.onCollapse.call(s,!1)},a.collapseTimer))}var i="init";"string"==typeof a&&(i=a,a={});var o,d=e.extend({},e.expander.defaults,a),p=/^<(?:area|br|col|embed|hr|img|input|link|meta|param).*>$/i,c=d.wordEnd||/(&(?:[^;]+;)?|[a-zA-Z\u00C0-\u0100]+)$/,f=/<\/?(\w+)[^>]*>/g,u=/<(\w+)[^>]*>/g,m=/<\/(\w+)>/g,x=/(<\/[^>]+>)\s*$/,h=/^(<[^>]+>)+.?/,C={init:function(){this.each(function(){var a,i,c,x,C,v,S,g,b,y,E,w,T,I,P=[],j=[],k={},D=this,$=e(this),A=e([]),L=e.extend({},d,$.data("expander")||e.meta&&$.data()||{}),O=!!$.find("."+L.detailClass).length,z=!!$.find("*").filter(function(){var a=e(this).css("display");return/^block|table|list/.test(a)}).length,F=z?"div":"span",U=F+"."+L.detailClass,W=L.moreClass+"",Q=L.lessClass+"",Z=L.expandSpeed||0,q=e.trim($.html()),B=(e.trim($.text()),q.slice(0,L.slicePoint));if(L.moreSelector="span."+W.split(" ").join("."),L.lessSelector="span."+Q.split(" ").join("."),!e.data(this,"expanderInit")){for(e.data(this,"expanderInit",!0),e.data(this,"expander",L),e.each(["onSlice","beforeExpand","afterExpand","onCollapse","afterCollapse"],function(a,l){k[l]=e.isFunction(L[l])}),B=n(B),C=B.replace(f,"").length;L.slicePoint>C;)x=q.charAt(B.length),"<"===x&&(x=q.slice(B.length).match(h)[0]),B+=x,C++;for(B=n(B,L.preserveWords),v=B.match(u)||[],S=B.match(m)||[],c=[],e.each(v,function(e,a){p.test(a)||c.push(a)}),v=c,i=S.length,a=0;i>a;a++)S[a]=S[a].replace(m,"$1");if(e.each(v,function(a,l){var s=l.replace(u,"$1"),n=e.inArray(s,S);-1===n?(P.push(l),j.push("</"+s+">")):S.splice(n,1)}),j.reverse(),O)b=$.find(U).remove().html(),B=$.html(),q=B+b,g="";else{if(b=q.slice(B.length),y=e.trim(b.replace(f,"")),""===y||y.split(/\s+/).length<L.widow)return;g=j.pop()||"",B+=j.join(""),b=P.join("")+b}L.moreLabel=$.find(L.moreSelector).length?"":s(L),z&&(b=q),B+=g,L.summary=B,L.details=b,L.lastCloseTag=g,k.onSlice&&(c=L.onSlice.call(D,L),L=c&&c.details?c:L),E=l(L,z),$.html(E),T=$.find(U),I=$.find(L.moreSelector),"slideUp"===L.collapseEffect&&"slideDown"!==L.expandEffect||$.is(":hidden")?T.css({display:"none"}):T[L.collapseEffect](0),A=$.find("div."+L.summaryClass),w=function(e){e.preventDefault(),I.hide(),A.hide(),k.beforeExpand&&L.beforeExpand.call(D),T.stop(!1,!0)[L.expandEffect](Z,function(){T.css({zoom:""}),k.afterExpand&&L.afterExpand.call(D),r(L,T,D)})},I.find("a").unbind("click.expander").bind("click.expander",w),L.userCollapse&&!$.find(L.lessSelector).length&&$.find(U).append('<span class="'+L.lessClass+'">'+L.userCollapsePrefix+'<a href="#">'+L.userCollapseText+"</a></span>"),$.find(L.lessSelector+" a").unbind("click.expander").bind("click.expander",function(a){a.preventDefault(),clearTimeout(o);var l=e(this).closest(U);t(L,l),k.onCollapse&&L.onCollapse.call(D,!0)})}})},destroy:function(){this.each(function(){var a,l,s=e(this);s.data("expanderInit")&&(a=e.extend({},s.data("expander")||{},d),l=s.find("."+a.detailClass).contents(),s.removeData("expanderInit"),s.removeData("expander"),s.find(a.moreSelector).remove(),s.find("."+a.summaryClass).remove(),s.find("."+a.detailClass).after(l).remove(),s.find(a.lessSelector).remove())})}};return C[i]&&C[i].call(this),this},e.fn.expander.defaults=e.expander.defaults})(jQuery);


// Start here
$(".start-here").click(function() {
    mixpanel.track("Start here");
});

// Sign up
$(".header-sign-up, .landing-sign-up").click(function() {
    mixpanel.track("Sign Up");
});

// Login
$(".header-log-in, .landing-log-in, .social-login a").click(function(event) {
    event.preventDefault();
    newURL = $(this).attr('href');
    mixpanel.track("Login", null, function() {
        console.log(newURL);
        window.location = newURL;
    });
});

// New Quest
$(".tour-quest-btn, .new-quest-dotted").click(function() {
    mixpanel.track("New Quest");
});

$(".dash-search-select").click(function() {
    mixpanel.track("Quest sort (Dashboard)");
});

$("#fb_share").click(function() {
    mixpanel.track("Facebook post");
});

$("#fb_invite").click(function() {
    mixpanel.track("Facebook message");
});

$(".mx-install-extension").click(function() {
    mixpanel.track("Install extension");
});

mixpanel.track_forms(".mx-chat-form", "Chat");

mixpanel.track_forms(".mx-comment-form", "Comment");


$(".mx-rate").click(function() {
    event.preventDefault();
    newURL = $(this).attr('href');
    mixpanel.track("Rate", null, function() {
        console.log(newURL);
        window.location = newURL;
    });
});


// ********************************************

$('.product-block .description').expander({
  slicePoint: 40
});

$(".switch-to-signup").click(function() {
    $("#loginModal").modal('hide');
    $("#registerModal").modal('show');
});

$(".switch-to-signin").click(function() {
    $("#registerModal").modal('hide');
    $("#loginModal").modal('show');
});

$('.btn-google-search').click(function() {
    $("#addProductModal").modal('hide');
});

$('.del-add-product-video').click(function() {
    $('.add-product-video').attr('src', '');
});

$('.add-product').click(function() {
    $('.add-product-video').attr('src', 'http://www.youtube.com/embed/qDf4w9spx5U?showinfo=0');
});

$(".new-parent").click(function() {
    $(".landing-video").attr('src', 'http://www.youtube.com/embed/pk1fqq9Wxdk?rel=0&showinfo=0&controls=0');
});

$(".expert-opinion").click(function() {
    $(".landing-video").attr('src', 'http://www.youtube.com/embed/F4mw8tMdhEM?rel=0&showinfo=0&controls=0');
});

$(".fashion-and-fun").click(function() {
    $(".landing-video").attr('src', 'http://www.youtube.com/embed/i7lRIbnKIHY?rel=0&showinfo=0&controls=0');
});

$(".remove").click(function() {
    $(".remove").html('Show')
});

$(".invite-friend-btn").click(function() {
    $(".invite-friend-btn").html('Pending...');
});


// emergency js cookie tour resolution
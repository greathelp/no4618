// for IE
jQuery(function() {
    if(navigator.userAgent.indexOf("MSIE") != -1) {
        jQuery('img').each(function() {
            if(jQuery(this).attr('src').indexOf('.png') != -1) {
                jQuery(this).css({
                    'filter': 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' + jQuery(this).attr('src') + '", sizingMethod="scale");'
                });
            }
        });
    }
});


// 画像ロールオーバー切り替え

$(function(){
	$("img.hvChange").mouseover(function(){
		$(this).attr("src",$(this).attr("src").replace(/^(.+)(\.[a-z]+)$/, "$1_on$2"))
	}).mouseout(function(){
		$(this).attr("src",$(this).attr("src").replace(/^(.+)_on(\.[a-z]+)$/, "$1$2"));
	})
})

// スムーズスクロール

jQuery.easing.quart = function (x, t, b, c, d) {
    return -c * ((t=t/d-1)*t*t*t - 1) + b;
};  

$(function(){
    $('a[href^=#header],a[href^=#idxHeader],a.scrl').click(function(){
		//#headerへのリンクのみに限定（ページの先頭以外でスムージングさせる場合はscrlクラスを追加）
        var target;
        target = $( $(this).attr('href') );
        if (target.length == 0) {
            return;
        }
        $($.browser.opera ? document.compatMode == 'BackCompat' ? 'body' : 'html' :'html,body').animate({scrollTop: target.offset().top}, 800, 'quart');
        return false;
    });
});

// ページトップボタン

$(function() {
	var topBtn = $('#page_top');	
	topBtn.hide();
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			topBtn.fadeIn();
		} else {
			topBtn.fadeOut();
		}
	});
	//スクロールしてトップ
	topBtn.click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 500);
		return false;
	});
});


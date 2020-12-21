//ドロワー自前
$(function () {

    //ドロワー初回対応
    var vw = $(".drawer-nav").width();
    //隠す
    $("nav.drawer-nav").css("right", -1 * vw);

    //ハンバーガークリック
    $(".drawer-hamburger").click(function () {
        //右端を画面右側（表示するって事）
        $("nav.drawer-nav").css("right", "0");
        // $("#main").css("overflow", "hidden");
        // $("#main").css("height", "calc(100vh - 55px )");
        $("#main").css("display", "fixed");


        //メニュー表示時は下部のボタンは無効
        $(".mobile-fixed").fadeOut("slow");
    });

    //クローズクリック
    $("#close-drawer").click(function () {
        //クローズボタンをクリックしているので閉じる
        menu_close();
    });

    //メニュー以外の場所でも閉じる
    $("html, body").click(function (e) {
        //メニューが開いてなければ虫
        var dnav_right = $(".drawer-nav").css("right");
        if (dnav_right === "0px") {
            //メニューが開いている
            if (!$(e.target).closest('nav').length) {
                //nav以外をクリックしているので閉じる
                menu_close();
            }
        }
    });

    //マウスホイール感知
    scrLength = 200;
    scrSpeed = 500;
    scrEasing = 'easeOutCirc';

    var mousewheelevent = 'onwheel' in document ? 'wheel' : 'onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll';
    $(document).on(mousewheelevent, function (e) {


        //メニューが出ているときのみ処理するためにドロワーの右位置で判定する
        // var dnav_right = $(".drawer-nav").css("right");
        // console.log("dnav_right:" + dnav_right);
        // if (dnav_right === "0px") {
        //     e.preventDefault();
        //     var delta = e.originalEvent.deltaY ? -(e.originalEvent.deltaY) : e.originalEvent.wheelDelta ? e.originalEvent.wheelDelta : -(e.originalEvent.detail);
        //     if (delta < 0) {
        //         scrSet = $(".inner").scrollTop() + scrLength;
        //     } else {
        //         scrSet = $(".inner").scrollTop() - scrLength;
        //     }
        //
        //     $(".inner").stop().animate({scrollTop: scrSet}, scrSpeed, scrEasing);
        //     return false;
        // }
        $('#primary').mousewheel(function (eo, delta, deltaX, deltaY) {
            if (deltaY < 0) {
                scrSet = $(document).scrollTop() + scrLength;
            } else {
                scrSet = $(document).scrollTop() - scrLength;
            }
            $('html,body').stop().animate({scrollTop: scrSet}, scrSpeed, scrEasing);
            return false;
        });

    });

});

//メニュー閉じる処理
function menu_close() {
    var vw = $(".drawer-nav").width();

    $("nav.drawer-nav").css("right", -1 * vw);
    // $('#main').css({"overflow": "visible", "height": "auto"});
    $('#main').css("display","block");

    //下部の電話とメールボタンを有効にする
    $(".mobile-fixed").fadeIn("slow");
}

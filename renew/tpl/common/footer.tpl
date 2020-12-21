<footer>
    <div class="wrap">
      <div class="pc">
        <div class="ootaka">
          <img src="{$rt}/img/logo.png" alt="流山おおたかの森歯科ロゴ">
          <p>〒270-0138　千葉県流山市おおたかの森東3丁目33-1 LYSEBLA 1階<br>
          （区画変更前の住所：千葉県流山市駒木356）</p>
          <p>
            流山おおたかの森駅より徒歩17分<br>
            柏の葉キャンパス・流山セントラルパークからもアクセス可<br>
            駐車場：5台まで完備（駐車位置自由）
          </p>
        </div>
      </div>
      <div class="sp">
        <div class="ootaka">
          <img src="{$rt}/img/logo.png" alt="流山おおたかの森歯科ロゴ">
          <p>〒270-0138<br>千葉県流山市おおたかの森東3丁目33-1 LYSEBLA 1階<br>
          （区画変更前の住所：千葉県流山市駒木356）<br>
            流山おおたかの森駅より徒歩17分<br>
            柏の葉キャンパス・流山セントラルパークからもアクセス可<br>
            駐車場：5台まで完備（駐車位置自由）
          </p>
        </div>

      </div>
      <div class="contact_cover">
          <p class="txt">ご予約・お問い合わせ<span>お気軽にお問い合わせください。</span></p>
          <div class="contact">
            <div class="footer_tel">
              <img src="{$rt}/img/tel_icon.png" alt="telアイコン画像">
              <p class="number">04-7128-4108</p>
            </div>
            <div class="open_time">
              <p>平日 9:30-20:00　土日祝 9:30-18:30</p>
            </div>
            <!-- <div class="open_time">
              <p>平日 9:00-20:00</p>
              <p>土日祝 9:00-18:30</p>
            </div> -->
          </div><!-- contact -->
          <p class="sub_txt">休診日はございません。代わりにGW・お盆・年末年始がお休みになります。</p>
          <!-- <p class="sub_txt">休診日はございません。<br>代わりにGW、お盆、年末年始がお休みになります。</p> -->
        </div><!-- contact_cover -->

    </div><!-- wrap -->
    <div class="copyright">Copyright © NAGAREYAMA OTAKA-NO-MORI
      DENTAL CLINIC, All Rights Reserved</div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/e9b228cd3c.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.js"></script>
<script src="{$rt}/js/hamburger/draws_sebon.js"></script>
<script src="{$rt}/js/slick/slick.min.js"></script>
<script src="{$rt}/js/main.js"></script>
<script>
{literal}
  $(function(){
  var showTop = 100;/*100と定義*/

  $('body').append('<a href="javascript:void(0);" id="fixedTop"><div></div></a>'); /*bodyに加える*//*href="javascript:void(0);"→ページ遷移を無効にする。*/
  var fixedTop = $('#fixedTop'); /*定義する*/
    $(window).on('load scroll resize',function(){ /*ロード、スクロール、リサイズした時*/
        if($(window).scrollTop() >= showTop){ /*移動量が100以上になったら*/
            fixedTop.fadeIn('normal'); /*出現*/
        } else if($(window).scrollTop() < showTop){
            fixedTop.fadeOut('normal'); /*消える*/
        }
      });
    fixedTop.on('click',function(){ /*クリックしたら*/
      $('html,body').animate({scrollTop:'0'},500); /* http://semooh.jp/jquery/api/effects/animate/params,+[duration],+[easing],+[callback]/ */
    });
  });
  {/literal}
</script>
<!-- <script type="text/javascript" src="http://hp-tools-home.com/gap/?i=mmn1"></script> -->
<script type="text/javascript" src="https://hp-tools-home.com/gap/?i=mmn1&jq=off"></script>

<!DOCTYPE html>
<html lang="ja">

    <head>
        <!-- 文字指定コード -->
        <meta charset="utf-8">
        <!-- タグに表示される -->
        <title>診療案内・診療時間・アクセス｜流山おおたかの森歯科・矯正歯科</title>
        <meta name="description" content="流山おおたかの森歯科・矯正歯科のアクセス・診療時間・定休日・駐車場の案内です。土日祝日も診療しています。平日は夜20時まで。流山おおたかの森駅より徒歩17分、駐車場５台完備しています。">
        <!-- ↑上部のコードを別読み込みシートに入れないのは、各ページ毎でタイトルなどを変更したい際に変更しやすくするため -->
        {include file="common/head.tpl"}
        <!-- 読み込み -->
    </head>

    <!-- スマーティで作成する場合で個別ページ数が多い場合↓ -->

    <body class="drawer drawer--right">
        {include file="common/header.tpl"}
        <!-- 読み込み -->

        <main>
            <!-- ここまで header -->

            <!-- ここからmain -->
            <section id="access">

                <!-- <div class="pc"> -->
                <div class="kobetsu_header">
                    <!-- <img src="{$rt}/img/access_header.jpg" alt="アクセスページのヘッダー画像" width="100%"> -->
                    <div class="kobetsu_page_title">
                        <img src="{$rt}/img/access_page_title.png" alt="アクセス">
                        <p>Information & Access</p>
                    </div>
                </div>
                <!-- </div> -->
                <!-- <div class="sp">
        <div class="kobetsu_header">
          <img src="{$rt}/img/access_header_sp.jpg" alt="アクセスページのヘッダー画像">
          <div class="kobetsu_page_title">
            <img src="{$rt}/img/access_page_title.png" alt="アクセス">
            <p>Information & Access</p>
          </div>
        </div>
      </div> -->

                <div class="wrap">
                    <div class="title_middle">
                        <p>
                            診療時間のご案内
                        </p>
                    </div>
                    <!-- <div class="green_middle_txt">
          <p>
            流山おおたかの森歯科・矯正歯科
          </p>
        </div> -->
                    <div class="info_cover">
                        <div class="info">
                            <div class="open_info">
                                <div class="dot_line">　</div>
                                <p class="weight_bold">診療日</p>
                                <p>平日　09:30-13:00 / 15:00-20:00</p>
                                <p>土日祝　09:30-13:00 / 14:30-18:30</p>
                            </div>

                            <div class="close_info">
                                <div class="dot_line">　</div>
                                <p class="weight_bold">休診日</p>
                                <p>休診日はございません。</p>
                                <p>GW・お盆・年末年始はお休みになります。</p>
                                <div class="dot_line">　</div>
                            </div>
                        </div><!-- info -->
                        <div class="kobetsu_contents_img access_photo1 right_position">
                            <!-- cssでbackground-imageで指定 -->
                        </div>
                    </div><!-- info_cover -->

                    <div class="flex_between">
                        <div style="width:550px;">
                            <div class="title_txt">
                                <h2>アクセスのご案内</h2>
                            </div>
                            {* <img src="../{$rt}/img/access_map.png" alt="アクセスマップ"> *}
                            <img src="{$rt}/img/access_map.png" alt="アクセスマップ">
                        </div>
                        <div class="pc">
                            <div class="access_txt">
                                <p>
                                    〒270-0138<br>千葉県流山市おおたかの森東3丁目33-1 LYSEBLA 1階<br>
                                    （区画変更前の住所：千葉県流山市駒木356）
                                </p>
                                <p>
                                    流山おおたかの森駅より徒歩17分<br>
                                    柏の葉キャンパス・流山セントラルパークからもアクセス可<br>
                                    駐車場：5台まで完備（駐車位置自由）<br>
                                </p>
                                <p class="p_margin">
                                    千葉県流山市にある当院は、平日は夜20時まで、土日や祝日も、ご利用頂く皆様が通いやすいように診療を行っております。<br>その他、駐車場も(最大5台)完備していますので、ぜひ、お気軽にご来院ください。
                                </p>
                            </div>
                        </div>
                        <div class="sp">
                            <div class="access_txt">
                                <p>
                                    〒270-0138<br>千葉県流山市おおたかの森東3丁目33-1 LYSEBLA 1階<br>
                                    （区画変更前の住所：千葉県流山市駒木356）
                                </p>
                                <p>
                                    流山おおたかの森駅より徒歩17分<br>
                                    柏の葉キャンパスからもアクセス可<br>
                                    駐車場：5台まで完備<br>
                                </p>
                                <p class="p_margin">
                                    千葉県流山市にある当院は、平日は夜20時まで、土日や祝日も、ご利用頂く皆様が通いやすいように診療を行っております。<br>その他、駐車場も(最大5台)完備していますので、ぜひ、お気軽にご来院ください。
                                </p>
                            </div>
                        </div>
                    </div><!-- flex_between -->
                    <a href="https://goo.gl/maps/kawXg" target="_blank">
                        <div class="google_btn">
                            <div class="google_btn_txt">
                                <img src="{$rt}/img/pin_mark.png" alt="マップピン画像">
                                <p>
                                    Google mapで地図を表示
                                </p>
                            </div>
                        </div>
                    </a>
                    <div class="mapbox2">
                        <div class="maptitle">柏市・つくばエクスプレス柏の葉キャンパス駅方面からお越しの方
                        </div>
                        <img class="googlemap" src="{$rt}/img/access/ootaka_001.jpg" alt="アクセスマップ2">
                        <div class="map4box">
                            <img src="{$rt}/img/access/ootaka_002.jpg" alt="柏の葉キャンパス駅西口">
                            <img src="{$rt}/img/access/ootaka_003.jpg" alt="カスミ フードスクエア流山おおたかの森店">
                            <img src="{$rt}/img/access/ootaka_004.jpg" alt="交差点脇のセブンイレブン流山駒木西店">
                            <img src="{$rt}/img/access/ootaka_005.jpg" alt="おおたかの森歯科・矯正歯科">
                        </div>
                        <div class="map-p">
                            つくばエクスプレスの線路沿いの都市軸道路をまっすぐ進み(1)、カスミフードスクエア流山おおたかの森店が右手に見える交差点を左折して(2)、
                            そのまま直進。交差点脇のセブンイレブン流山駒木西店(3)の奥が当院です(4)。

                        </div>
                    </div>

                    <div class="mapbox">
                        <div class="maptitle">流山市役所・つくばエクスプレス流山セントラルパーク駅方面からお越しの方
                        </div>
                        <img class="googlemap" src="{$rt}/img/access/ootaka_006.jpg" alt="アクセスマップ3">
                        <div class="map4box">
                            <img src="{$rt}/img/access/ootaka_007.jpg" alt=流山市生涯学習センター">
                            <img src="{$rt}/img/access/ootaka_008.jpg" alt="流山おおたかの森S・C">
                            <img src="{$rt}/img/access/ootaka_009.jpg" alt="セブンイレブン流山駒木西店">
                            <img src="{$rt}/img/access/ootaka_010.jpg" alt="おおたかの森歯科・矯正歯科">
                        </div>
                        <div class="map-p">
                        流山市生涯学習センターを左折(1)。流山市総合運動公園を右手に、そのまま直進。柏市方面へ。
                        流山おおたかの森S・C(2)を左手に、そのまま直進。
                        豊四季霊園を越えて直進した先の、セブンイレブン流山駒木西店が右手に見える交差点(3)を右折。
                        セブンイレブン流山駒木西店を越えてすぐが当院です(P5)。
                        
                        </div>

                    </div>

                    <div class="mapbox">
                        <div class="maptitle">JR、東武野田線柏駅・豊四季駅方面からお越しの方
                        </div>
                        <img class="googlemap" src="{$rt}/img/access/ootaka_011.jpg" alt="アクセスマップ3">
                        <div class="map4box">
                            <img src="{$rt}/img/access/ootaka_012.jpg" alt=旭町交番">
                            <img src="{$rt}/img/access/ootaka_013.jpg" alt="豊四季駅入口">
                            <img src="{$rt}/img/access/ootaka_014.jpg" alt="成顕寺入口">
                            <img src="{$rt}/img/access/ootaka_015.jpg" alt="おおたかの森歯科・矯正歯科">
                        </div>
                        <div class="map-p">
                        県道278号（柏流山線）旭町交番前の交差点を流山市方面へ。旭町交番を右手に、そのまま直進(1)。
                        豊四季駅入口の交差点を右折して県道279号へ（2）。大堀川を越え、成顕寺入口の交差点を左折し(3)、そのまま直進。
                        2つ目の丁字路を過ぎてすぐ右手、きみどり色の看板が当院の目印です(4)。
                        

                        </div>

                    </div>

                    <div class="mapbox">
                    <div class="maptitle">東武野田線江戸川台駅方面からお越しの方
                    </div>
                    <img class="googlemap" src="{$rt}/img/access/ootaka_016.jpg" alt="アクセスマップ4">
                    <div class="map4box">
                        <img src="{$rt}/img/access/ootaka_017.jpg" alt=県立流山高校方面">
                        <img src="{$rt}/img/access/ootaka_018.jpg" alt="都市軸道路">
                        <img src="{$rt}/img/access/ootaka_019.jpg" alt="カスミフードスクエア流山おおたかの森店">
                        <img src="{$rt}/img/access/ootaka_020.jpg" alt="おおたかの森歯科・矯正歯科">
                    </div>
                    <div class="map-p">
                    南東に進み(1)、県立流山高校方面へ。流山中央病院を右手に、そのまま直進。
                    左折して都市軸道路に入ります(2)。
                    カスミフードスクエア流山おおたかの森店が左手に見える交差点を右折して(3)、そのまま直進。
                    セブンイレブン流山駒木西店の奥が当院です(4)。
                    

                    </div>

                </div>
                </div><!-- wrap -->







            </section>
            <!-- ここまでmain -->

            <!-- ここから footer -->
        </main>
        {include file="common/footer.tpl"}
        <!-- 読み込み -->
        <!-- ↓ボディの閉じタグを読み込みシートに入れないわけは、jsの読み込みで単独ページで入れたいjsがあった際に他の全ページに反映されるとページの読み込みが遅くなるため -->
    </body>

</html>
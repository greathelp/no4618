<?php

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title></title>
        <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/javascript" src="/js/jquery.fancybox.pack.js"></script>
        <link rel="stylesheet" href="/css/jquery.fancybox.css" />
        <script type="text/javascript">
            $(function(){
                //fancybox設定
                $(".fancybox").fancybox({
                    openEffect: 'elastic',
                    closeEffect: 'elastic',
                    helpers : {
                        title : {
                            type : 'inside'
                        }
                    }
                });
            })
        </script>
        <style type="text/css">
            body{
                font-size:13px;
            }
            .topic_title{
                /*トピックボード自体の題名*/
                font-size:1.5em;
                text-align:center;
            }
            .title{
                margin:0 0 0 10px;
                font-size:14px;
                background:none repeat scroll 0 0 #DCDCED; 
                border:1px solid #5F5FAF;
            }
            .topic_body{
                border-bottom: 1px solid #5F5FAF;
                border-left: 1px solid #5F5FAF;
                border-right: 1px solid #5F5FAF;
                margin:0 0 10px 10px;
                padding:10px 0 0 10px;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <td class="topic_title"><?= $topic_title ?></td>
            </tr>
            <td>
                <?php
                while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {
                    //タイトル・店名
                    echo '<div class="title">' . $rows['title'] . '&nbsp&nbsp（' . GetShopNameForID($rows['shop_id']) . '）&nbsp&nbsp' . $rows['date1'] . '</div>';
                    echo '<div class="topic_body">' . base64_decode($rows['body']);
                    //写真があれば表示
                    //ファイル名作成
                    $tmp_filename = sprintf("%05d", $rows['id']) . ".jpg";
                    //FB::log("tmp_filename:" . $tmp_filename);
                    //存在の確認
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $img_path . $tmp_filename)) {
                        //画像があるから表示
                        echo '<a class="fancybox" href="' . $img_path . $tmp_filename . '">';
                        echo '<img style="margin-top:10px;" src="' . $img_path . $tmp_filename . '" alt="" width="' . $img_width . '" />';
                        echo '</a>';
                    }
                    echo'</div>';
                }
                ?>
            </td>
        </tr>
    </table>
</body>
</html>
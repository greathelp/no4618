<!-- Global site tag (gtag.js) - Google Analytics -->
{literal}
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-149484574-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'UA-149484574-1');
</script>
{/literal}

<!-- <link rel="icon" href="favicon.ico"> -->
<!-- CSSのシートを読み込む -->
<!-- <link rel="stylesheet" href="css/normalize.css"> -->
<!-- ↑cssの上におく -->
<link rel="stylesheet" href="{$rt}/css/style.css">
<!-- 表には表示されないが、検索エンジンに有効なので、適切に書いておく -->
<link rel="stylesheet" href="{$rt}/js/hamburger/drawer/css/drawer.min.css">
<link rel="stylesheet" href="{$rt}/js/hamburger/drawer/css/drawer.min2.css">
<link rel="stylesheet" type="text/css" href="{$rt}/js/slick/slick.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$rt}/js/slick/slick-theme.css" media="screen" />
<link rel="icon" type="image/gif" href="/favicon.gif">
<!-- スマホ対応コード -->
 <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
 {if $device=="mobile"}
 <meta name="viewport" content="width=375">
 {else}
 <meta name="viewport" content="width=1200">
 {/if}

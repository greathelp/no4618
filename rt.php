<?php

// $rt = "/renew";
$rt = "";

$tpl = $_SERVER["DOCUMENT_ROOT"] . "/renew/tpl";
$templates_c =  $_SERVER["DOCUMENT_ROOT"] . $rt . "/templates_c";
$cache = $_SERVER["DOCUMENT_ROOT"] . $rt . "/cache";
//
//スマホ・タブレット・PC判定
//
function chk_device()
{

  $ua = $_SERVER['HTTP_USER_AGENT'];

  if ((strpos($ua, 'Android') !== false) &&
    (strpos($ua, 'Mobile') !== false) ||
    (strpos($ua, 'iPhone') !== false) ||
    (strpos($ua, 'Windows Phone') !== false)) {
    // スマホからのアクセス
    $check_device = "mobile";
  } elseif ((strpos($ua, 'Android') !== false) ||
    (strpos($ua, 'iPad') !== false)) {
    // タブレットからのアクセス
    $check_device = "tablet";
  } elseif ((strpos($ua, 'DoCoMo') !== false) ||
    (strpos($ua, 'KDDI') !== false) ||
    (strpos($ua, 'SoftBank') !== false) ||
    (strpos($ua, 'Vodafone') !== false) ||
    (strpos($ua, 'J-PHONE') !== false)) {
    // 携帯からのアクセス
    $check_device = "old-phone";
  } else {
    // PCからのアクセス
    $check_device = "pc";
  }

  return $check_device;
}

$device = chk_device();

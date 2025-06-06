<?php
function get_domain_url(){

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $page_current_name = $protocol.basename($_SERVER['HTTP_HOST']);
  if ($page_current_name=='http://localhost' || $page_current_name =='http://127.0.0.1') {
    $get_page_link = $page_current_name."/rawayaunet2";
  }
  else{
    $get_page_link = $page_current_name;
  }

  return $get_page_link;
}
?>
<?php

if( !function_exists('d') ) {
  function d($tmp){
    echo "<pre>";
    var_dump($tmp);
    echo "</pre>";
  }
}

if( !function_exists('dd') ) {
  function dd($tmp){
    d($tmp);
    die();
  }
}

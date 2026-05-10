<?php

if (!function_exists('moneyFormat')) {
  /*
  moneyFormat
  @param mixed $str
  @return string
  hal 30
  */
    function moneyFormat($str) {
        return 'Rp.' . number_format($str, 0, ',', '.');
    }
}
<?php
function regressione($X,$Y)
{
  if (!is_array($X) && !is_array($Y)) return false;
  if (count($X) <> count($Y)) return false;
  if (empty($X) || empty($Y)) return false;
 
  $regres = array();
  $n = count($X);
  $mx = array_sum($X)/$n; // media delle x
  $my = array_sum($Y)/$n; // media delle y
  $sxy = 0;
  $sxsqr = 0;
 
  for ($i=0;$i<$n;$i++){
    $sxy += ($X[$i] - $mx) * ($Y[$i] - $my);
    $sxsqr += pow(($X[$i] - $mx),2); // somma degli scarti quadratici medi
  }
 
  $m = $sxy / $sxsqr; // coefficiente angolare
  $q = $my - $m * $mx; // termine noto
 
  for ($i=0;$i<$n;$i++){
    $regres[$i] = $m * $X[$i] + $q;
  }
 
  return $regres;
}
?>
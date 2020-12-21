<?php
$time = time();
$start_of_week = 1; // Calendar starts from Monday
$first_day_of_the_week = date('w', strtotime(sprintf('%s-%s-%s', date('Y', $time), date('m', $time), '01')));

$start_of_week = ($start_of_week % 7);
$days = array_merge(array(0), range(1, date("t", $time)));
$start = $first_day_of_the_week - $start_of_week;
if ($start < 0) $start += 7;

$index = array();
if ($start > 0) {
  for ($i = 0; $i < $start; $i++) {
    $index[] = NULL;
  }
}
for ($i = 1; $i < count($days); $i++) {
  $index[] = $days[$i];
}

echo "<pre>";
var_dump($index);
echo "</pre>";
<?php

function checkiprange($needle, $start, $end) {
  if((ip2long($needle) >= ip2long($start)) && (ip2long($needle) <= ip2long($end))) {
    return true;
  }
  return false;
}

function iprange2cidr($ipStart, $ipEnd){
    if (is_string($ipStart) || is_string($ipEnd)){
        $start = ip2long($ipStart);
        $end = ip2long($ipEnd);
    }
    else{
        $start = $ipStart;
        $end = $ipEnd;
    }
    $result = array();
    while($end >= $start){
        $maxSize = 32;
        while ($maxSize > 0){
            $mask = hexdec(iMask($maxSize - 1));
            $maskBase = $start & $mask;
            if($maskBase != $start) break;
            $maxSize--;
        }
        $x = log($end - $start + 1)/log(2);
        $maxDiff = floor(32 - floor($x));

        if($maxSize < $maxDiff){
            $maxSize = $maxDiff;
        }

        $ip = long2ip($start);
        array_push($result, "$ip/$maxSize");
        $start += pow(2, (32-$maxSize));
    }
    return $result[0];
}
function iMask($s){
    return base_convert((pow(2, 32) - pow(2, (32-$s))), 10, 16);
}

function ip_range($ip1, $ip2){
    $ip1l = ip2long( $ip1 );
    $ip2l = ip2long( $ip2 );
    return 1 + Math.abs( $ip2l - $ip1l );
}


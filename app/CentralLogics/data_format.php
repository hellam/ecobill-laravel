<?php

use Carbon\Carbon;

function format_date($date,$is_timestamp=false){
    $format = auth('user')->user()->date_format;
    $sep = auth('user')->user()->date_sep;

    if($format=='DDMMYYYY')
        $format = 'DD'.$sep.'MM'.$sep.'YYYY';
    elseif($format=='MMDDYYYY')
        $format = 'MM'.$sep.'DD'.$sep.'YYYY';
    elseif($format=='YYYYMMDD')
        $format = 'YYYY'.$sep.'MMM'.$sep.'DD';

    if ($is_timestamp)
        $format =$format.' H:m:s';

    return Carbon::parse($date)->isoFormat($format);
}

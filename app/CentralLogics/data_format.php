<?php

use Carbon\Carbon;

function format_date($date,$is_timestamp=false){
    $format = get_company_setting('date_format');
    $sep = get_company_setting('date_sep');

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

function get_date_format($is_timestamp=false){
    $format = get_company_setting('date_format');
    $sep = get_company_setting('date_sep');

    if($format=='DDMMYYYY')
        $format = 'DD'.$sep.'MM'.$sep.'YYYY';
    elseif($format=='MMDDYYYY')
        $format = 'MM'.$sep.'DD'.$sep.'YYYY';
    elseif($format=='YYYYMMDD')
        $format = 'YYYY'.$sep.'MM'.$sep.'DD';

    if ($is_timestamp)
        $format =$format.' H:m:s';

    return $format;
}

function get_js_date_format($is_timestamp=false){
    $format = get_company_setting('date_format');
    $sep = get_company_setting('date_sep');

    if($format=='DDMMYYYY')
        $format = 'DD'.$sep.'MM'.$sep.'YYYY';
    elseif($format=='MMDDYYYY')
        $format = 'MM'.$sep.'DD'.$sep.'YYYY';
    elseif($format=='YYYYMMDD')
        $format = 'YYYY'.$sep.'MM'.$sep.'DD';
    elseif($format=='DD MMM YYYY')
        $format = 'DD MMMM YYYY';
    elseif($format=='MMM DD YYYY')
        $format = 'MMMM DD YYYY';
    elseif($format=='YYYY MMM DD')
        $format = 'YYYY MMMM DD';


    if ($is_timestamp)
        $format =$format.' H:m:s';

    return $format;
}

function toPriceDecimal($value, ): string
{
    return number_format($value, (get_company_setting('price_dec')/1) ?? 2, get_company_setting('dec_sep'), get_company_setting('tho_sep'));
}
function toRateDecimal($value): string
{
    return number_format($value, (get_company_setting('rates_dec')/1) ?? 2, '.', '');
}
function toQtyDecimal($value): string
{
    return number_format($value, (get_company_setting('qty_dec')/1) ?? 2, '.', '');
}

<?php

if (!function_exists('setActive')) {
    function setActive($path)
    {
        return Request::is($path . '*') ? 'active' : '';
    }
}

if (!function_exists('TanggalID')) {
    function TanggalID($tanggal){
        $value = Carbon\Carbon::parse($tanggal);
        $parse = $value->locale('id');

        return $parse->translatedFormat('l', 'd F Y');
    }
}

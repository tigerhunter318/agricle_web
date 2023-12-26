<?php

if(!function_exists('format_date')) {
    function format_date($date, $mode='symbol') {
        if($mode == 'symbol') return date_format(date_create($date), 'Y/m/d');
        else return date_format(date_create($date), 'Y年m月d日');
    }
}

if(!function_exists('format_time')) {
    function format_time($date, $has_second = false) {
        if($has_second) return date_format(date_create($date), 'H時i分s秒');
        else return date_format(date_create($date), 'H時i分');
    }
}

if(!function_exists('format_day')) {
    function format_day($date, $mode = "long") {
        $weekMap = [
            0 => '日',
            1 => '月',
            2 => '火',
            3 => '水',
            4 => '木',
            5 => '金',
            6 => '土'
        ];
        $dayOfTheWeek = date('w', strtotime($date));
        $weekday = $weekMap[$dayOfTheWeek];
        if($mode == "long") $weekday = $weekday . '曜日';
        return $weekday;
    }
}

if(!function_exists('format_workplace')) {
    function format_address($post_number, $prefectures, $city, $address) {
        for($k = 0; $k < count(config('global.pref_city')); $k++) {
            if($prefectures == config('global.pref_city')[$k]['id']) $prefectures = config('global.pref_city')[$k]['pref'];
        }
        return $post_number.'-'.$prefectures.' '.$city.' '.$address;
    }
}

if(!function_exists('get_prefectures_name')) {
    function get_prefectures_name($prefectures) {
        for($k = 0; $k < count(config('global.pref_city')); $k++) {
            if($prefectures == config('global.pref_city')[$k]['id']) return config('global.pref_city')[$k]['pref'];
        }
        return $prefectures;
    }
}

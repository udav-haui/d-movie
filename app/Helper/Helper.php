<?php

/**
 * @param string $text
 * @param bool $isReplaceSpaceToMinus
 * @param bool $isConvertUpperToLower
 * @return string
 */
function convert_vi_to_en(string $text, bool $isReplaceSpaceToMinus = true, bool $isConvertUpperToLower = true)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $text);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
    $str = preg_replace("/(đ)/", "d", $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
    $str = preg_replace("/(Đ)/", "D", $str);
    if ($isReplaceSpaceToMinus) {
        $str = preg_replace("/(\s+)/", "-", $str);
    }
    if ($isConvertUpperToLower) {
        $str = strtolower($str);
    }
    return $str;
}

/**
 * Get day of week by locale
 *
 * @param int $dayOfWeek
 * @param bool $isShort
 * @param bool $isUpper
 * @return string
 */
function convert_locale_day_of_week(int $dayOfWeek, bool $isShort = true, bool $isUpper = true)
{
    if ($isShort) {
        $weekMap = [
            "en" => [
                0 => 'SU',
                1 => 'MO',
                2 => 'TU',
                3 => 'WE',
                4 => 'TH',
                5 => 'FR',
                6 => 'SA',
            ],
            "vi" => [
                0 => 'CN',
                1 => 'T2',
                2 => 'T3',
                3 => 'T4',
                4 => 'T5',
                5 => 'T6',
                6 => 'T7',
            ]
        ];
    } else {
        $weekMap = [
            "en" => [
                0 => 'Sunday',
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
            ],
            "vi" => [
                0 => 'Chủ Nhật',
                1 => 'Thứ Hai',
                2 => 'Thứ Ba',
                3 => 'Thứ Tư',
                4 => 'Thứ Năm',
                5 => 'Thứ Sáu',
                6 => 'Thứ Bảy',
            ]
        ];
    }

    return $isUpper ? strtoupper($weekMap[app()->getLocale()][$dayOfWeek]) : $weekMap[app()->getLocale()][$dayOfWeek];
}

/**
 * Get visible by status field
 *
 * @return \Illuminate\Database\Query\Builder
 */
function get_visible($query)
{
    return $query->where('status', 1);
}

/**
 * @param $query
 * @param array $filterFields
 * @return mixed
 * @throws Exception
 */
function get_filter($query, $filterFields = [])
{
    try {
        if (!empty($filterFields)) {
            foreach ($filterFields as $key => $value) {
                $query = $query->where($key, $value);
            }
        }

        return $query;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

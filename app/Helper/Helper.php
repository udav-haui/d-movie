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
 * Unique array by key
 *
 * @param $array
 * @param $key
 * @return array
 */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

/**
 * Get visible by status field
 *
 * @param \Illuminate\Database\Query\Builder $query
 * @return \Illuminate\Database\Query\Builder
 */
function get_visible($query)
{
    return $query->where('status', 1);
}

function make_instance($path)
{
    return new $path();
}

function get_format_day_date_string($date, $format = "Do MMMM YYYY, h:mm:ss A")
{
    return \Carbon\Carbon::make($date)->isoFormat($format);
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

/**
 * Edit .env file
 *
 * @param string $name
 * @param string $value
 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
 */
function setEnv($name, $value)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        if (!file_put_contents($path, str_replace(
            $name . '=' . env($name),
            $name . '=' . $value,
            file_get_contents($path)
        ))) {
            throw new \Illuminate\Contracts\Filesystem\FileNotFoundException(__("Can not save your configuration!"));
        }
    }
}

function changeEnv($data = array())
{
    if (count($data) > 0) {
        // Read .env-file
        $env = file_get_contents(base_path() . '/.env');

        // Split string on every " " and write into array
        $env = preg_split('/\s+/', $env);
        ;

        // Loop through given data
        foreach ((array)$data as $key => $value) {
            // Loop through .env-data
            foreach ($env as $env_key => $env_value) {
                // Turn the value into an array and stop after the first split
                // So it's not possible to split e.g. the App-Key by accident
                $entry = explode("=", $env_value, 2);

                // Check, if new key fits the actual .env-key
                if ($entry[0] == $key) {
                    // If yes, overwrite it with the new one
                    $env[$env_key] = $key . "=" . $value;
                } else {
                    // If not, keep the old one
                    $env[$env_key] = $env_value;
                }
            }
        }

        // Turn the array back to an String
        $env = implode("\n", $env);

        // And overwrite the .env with the new data
        file_put_contents(base_path() . '/.env', $env);

        return true;
    } else {
        return false;
    }
}

/**
 * Get changed value from two array
 *
 * @param array $oldArray
 * @param array $newArray
 * @param bool $isLogChildOldData
 * @return array
 */
function array_diff_recursive($oldArray, $newArray, $isLogChildOldData = true)
{
    $messages = [];
    $removedKeyFromNew = array_diff_key($oldArray, $newArray);
    foreach ($removedKeyFromNew as $key => $value) {
        $messages[$key] = [
            'key_name' => $key,
            'action' => 'removed',
            'new_value' => null,
            'old_value' => $value
        ];
    }
    $updatedKeyFromNew = array_udiff_assoc($newArray, $oldArray, "compare_array");
    $addedNewKeyFromNew = array_diff_key($newArray, $oldArray);
    foreach ($addedNewKeyFromNew as $key => $value) {
        if (is_array($value)) {
            $newValue = array_diff_recursive([], $value, false);
        } else {
            $newValue = null;
        }
        $messages[$key] = [
            'key_name' => $key,
            'action' => 'updated',
            'new_value' => $newValue ?? $value,
            'old_value' => null
        ];
        unset($updatedKeyFromNew[$key]);
    }
    foreach ($updatedKeyFromNew as $key => $value) {
        $action = 'updated';
        if ($value == null || $value == '') {
            $action = 'removed';
        }
        if (is_array($value)) {
            $newValue = array_diff_recursive($oldArray[$key], $value, false);
        } else {
            $newValue = null;
        }
        $messages[$key] = [
            'key_name' => $key,
            'action' => $action,
            'new_value' => $newValue ?? $value,
            'old_value' => $isLogChildOldData ? $oldArray[$key] : null
        ];
    }
    return $messages;
}

/**
 * For array_diff_recursive
 *
 * @param mixed $firstArrVal
 * @param mixed $secondArrVal
 * @return int
 */
function compare_array($firstArrVal, $secondArrVal)
{
//        if (is_array($firstArrVal) || is_array($secondArrVal)) {
//            dump($firstArrVal, $secondArrVal);
//            dd($firstArrVal == $secondArrVal);
//        }
    return $firstArrVal == $secondArrVal ? 0 : -1;
}

function unset_no_compare_field($array, $fields)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = unset_no_compare_field($value, $fields);
        } else {
            if (in_array($key, $fields)) {
                unset($array[$key]);
            }
        }
    }
    return $array;
}

/**
 * @param array $inputLogArray
 * @param \App\Log $log
 * @param object $targetModel
 * @return string
 */
function echo_log_recursive($inputLogArray, $log, $targetModel = null)
{

    dd($log->getTargetModel(), \App\Config::class);
    if (!$targetModel) {
        $targetModel = $log->getTargetModel();
        $targetModel = new $targetModel();
    }
    if ($targetModel instanceof \App\Role) {
        return $targetModel::renderLogHtml($inputLogArray, $log);
    }
    $rawHtml = '<ul>';
    foreach ($inputLogArray as $key => $item) {
        if ($item['key_name'] != 'updated_at') {
            $rawHtml .= '<li>';
            if (is_array($item['new_value'])) {
                if ($key == $log->getTargetId()) {
                    $targetModelLogText = __(
                        "<code>:modelName</code>with identifier<code>:id</code>",
                        [
                            'modelName' => $targetModel::getModelName($item['old_value']['account_type'] ?? null),
                            'id' => $targetModel instanceof \App\Config ? $item['old_value']['section_id'] : $key
                        ]
                    );
                } else {
                    $targetModelLogText = null;
                }
                $changedKey = $targetModelLogText ?? __('<code>:key</code>', ['key' => ($targetModel::mappedAttributeLabel()[$key] ?? $key)]);
                $rawHtml .= __(mb_strtoupper($item['action'])) . $changedKey;
                $rawHtml .= echo_log_recursive($item['new_value'], $log, $targetModel);
            } else {
                $rawHtml .= __($item['action']) . '&nbsp;';
                $rawHtml .= "<d-mark-update>";
                $rawHtml .= $targetModel::mappedAttributeLabel()[$item['key_name']] ?? $item['key_name'];
                $rawHtml .= "</d-mark-update>";
                if ($targetModel instanceof \App\Role) {
                    if ($item['key_name'] != 'permissions') {
                        $oldVal = $targetModel::mappedValue($item['key_name'])[$item['old_value']] ?? $item['old_value'];
                        $newVal = $targetModel::mappedValue($item['key_name'])[$item['new_value']] ?? $item['new_value'];
                    }
                    if ($item['key_name'] == 'permissions') {
                        if ($item['action'] == 'updated') {
                            $newVal = $targetModel::mappedValue($item['key_name'])[$item['new_value']] ?? $item['new_value'];
                            $oldVal = '';
                        } elseif ($item['action'] == 'removed') {
                            $oldVal = $targetModel::mappedValue($item['key_name'])[$item['old_value']['permission_code']] ?? $item['old_value']['permission_code'];
                            $newVal = '';
                        }
                    }
                } else {
                    $oldVal = $targetModel::mappedValue($item['key_name'])[$item['old_value']] ?? $item['old_value'];
                    $newVal = $targetModel::mappedValue($item['key_name'])[$item['new_value']] ?? $item['new_value'];
                }
                $newDataLog = __(
                    "&nbsp;from&nbsp;<d-mark-delete>:oldVal</d-mark-delete>&nbsp;to&nbsp;<d-mark-create>:newVal</d-mark-create>",
                    [
                        'oldVal' => $oldVal,
                        'newVal' => $newVal
                    ]
                );
                $rawHtml .= $newDataLog;
//                $rawHtml .= "&nbsp;" . __('') . "";
//                $rawHtml .= ;
//                $rawHtml .= "&nbsp;" . __('to') . "&nbsp;";
//                $rawHtml .= $item['new_value'];
            }
            $rawHtml .= '</li>';
        }
    }
    $rawHtml .= '</ul>';
    return $rawHtml;
}

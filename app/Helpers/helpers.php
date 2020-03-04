<?php

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

function yen($amount)
{
    setlocale(LC_MONETARY, "ja_JP.UTF8");

    return number_format($amount);
}

/**
 *
 * @return array
 */
function lastSixMonthsName()
{
    $first  = strtotime('first day this month');
    $months = [];

    for ($i = 6; $i >= 1; $i--) {
        array_push($months, '"' . date('F', strtotime("-$i month", $first)) . '"');
    }

    return $months;
}

/**
 * to convert stdClass objects to multidimensional arrays
 *
 * @param object $object
 */
function objectToArray($object)
{
    if (is_object($object)) {
        $object = get_object_vars($object);
    }

    if (is_array($object)) {

        return array_map(__FUNCTION__, $object);

    } else {

        // XXX: IMPORTANT - Return array
        return $object;
    }
}

/**
 * to convert multidimensional arrays to stdClass objects
 *
 * @param array $array
 */
function arrayToObject($array)
{
    if (is_array($array)) {

        return (object)array_map(__FUNCTION__, $array);

    } else {

        // XXX: IMPORTANT - Return object
        return $array;
    }
}

function arrayToObjectCollection($array)
{

    if (isAssoc($array) && !empty($array)) {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = arrayToObjectCollection($value);
            }
            $object->$key = $value;
        }
    } else {
        $object = new Illuminate\Support\Collection();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = arrayToObjectCollection($value);
            }
            $object->push($value);
        }
    }

    return $object;
}

function isAssoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function arrayToMap($array, $keyName)
{
    $assocArray = array_reduce($array, function ($result, $item) use ($keyName) {
        $result[$item[$keyName]] = $item;

        return $result;
    }, []);

    return $assocArray;
}

/**
 *
 * @param string $srchvalue
 * @param array  $array
 * @return integer parent array key
 */
function searchMultiDimension($srchvalue, $array)
{
    if (is_array($array) && count($array) > 0) {

        $foundkey = array_search($srchvalue, $array);

        if ($foundkey === false) {

            foreach ($array as $key => $value) {

                if (is_array($value) && count($value) > 0) {

                    $foundkey = searchMultiDimension($srchvalue, $value);
                    if ($foundkey != false) {
                        return $key;
                    }
                }
            }

        } else
            return $foundkey;
    }
}

/**
 * Creating date collection between two dates
 *
 * <code>
 * <?php
 * # Example 1
 * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
 *
 * # Example 2. you can use even time
 * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
 * </code>
 *
 * @param string since any date, time or datetime format
 * @param string until any date, time or datetime format
 * @param string for key name on array
 * @param string step
 * @param string date of output format
 * @return array
 */
function date_range($first, $last, $key = null, $step = '+1 day', $output_format = 'Y-m-d')
{

    $dates   = [];
    $current = strtotime($first);
    $last    = strtotime($last);

    if ($key !== null) {

        while ($current <= $last) {

            $date_formated               = date($output_format, $current);
            $dates[$date_formated][$key] = $date_formated;
            $current                     = strtotime($step, $current);
        }

    } else {

        while ($current <= $last) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
    }

    return $dates;
}

function array_fill_add($arrays, $value)
{
    foreach ($arrays as $key => $array) {
        $arrays[$key] = array_merge($array, $value);
    }

    return $arrays;
}

function json_pretty($json, $html = false)
{

    $out   = '';
    $nl    = "\n";
    $cnt   = 0;
    $tab   = 4;
    $len   = strlen($json);
    $space = ' ';

    if ($html) {
        $space = '&nbsp;';
        $nl    = '<br/>';
    }

    $k = strlen($space) ? strlen($space) : 1;

    for ($i = 0; $i <= $len; $i++) {

        $char = substr($json, $i, 1);

        if ($char == '}' || $char == ']') {
            $cnt--;
            $out .= $nl . str_pad('', ($tab * $cnt * $k), $space);
        } else if ($char == '{' || $char == '[') {
            $cnt++;
        }

        $out .= $char;

        if ($char == ',' || $char == '{' || $char == '[') {
            $out .= $nl . str_pad('', ($tab * $cnt * $k), $space);
        }

        if ($char == ':') {
            $out .= ' ';
        }
    }

    return $out;
}

function dot_to_array(&$array, $composite_key, $value)
{
    $keys = explode('.', $composite_key);

    while (count($keys) > 1) {

        $key = array_shift($keys);

        if (!isset($array[$key]))
            $array[$key] = [];

        $array = &$array[$key];
    }

    $key = reset($keys);

    $array[$key] = $value;
}

function get_public_page_locale()
{
    $locale = LaravelLocalization::getCurrentLocale();

    /*
    if (Session::has('locale')) {

        $locale = Session::get('locale', 'en');

    } else {

        $languages = Agent::languages();

        if (check_if_in_array_string($languages, 'en')) {

            $locale = 'en';

        } else if (check_if_in_array_string($languages, 'ja')) {

            $locale = 'ja';

        } else if (check_if_in_array_string($languages, 'zh')) {

            $locale = 'ch';

        } else if (check_if_in_array_string($languages, 'zht')) {

            $locale = 'cht';

        } else {

            $locale = 'en';
        }
    }
    */

    return $locale;
}

function get_error_page_locale()
{
    $locale = '';

    $languages = Agent::languages();

    if (check_if_in_array_string('en', $languages)) {

        $locale = 'en';

    } else if (check_if_in_array_string('ja', $languages)) {

        $locale = 'ja';

    } else if (check_if_in_array_string('zh', $languages)) {

        $locale = 'ch';

    } else if (check_if_in_array_string('ko', $languages)) {

        $locale = 'ko';

    } else {

        $locale = 'en';
    }

    return $locale;
}

function check_if_in_array_string($needle, $haystack)
{
    $found = array_reduce($haystack, function ($isfound, $value) use ($needle) {

        return $isfound || false !== strpos($value, $needle);

    }, false);

    return $found;
}

function postdata_strip_tags($array, $except = [])
{
    $result = [];

    foreach ($array as $key => $value) {

        if (in_array($key, $except)) {

            $result[$key] = $value;
            continue;
        }

        // Don't allow tags on key either, maybe useful for dynamic forms
        $key = strip_tags($key);

        if (is_array($value)) {

            $result[$key] = postdata_strip_tags($value);

        } else if (is_string($value)) {

            // I am using strip_tags(), you may use htmlentities(),
            // also I am doing trim() here, you may remove it, if you wish.
            $result[$key] = trim(strip_tags($value));

        } else {

            $result[$key] = $value;
        }
    }

    return $result;
}

function opposite_direction($filter)
{
    $opposite_direction = (isset($filter['direction']) && $filter['direction'] == 'ASC') ? 'DESC' : 'ASC';

    return $opposite_direction;
}

function is_all_multibyte($string)
{
    if (mb_check_encoding($string, 'UTF-8') === false)
        return false;

    $length = mb_strlen($string, 'UTF-8');

    for ($i = 0; $i < $length; $i += 1) {

        $char = mb_substr($string, $i, 1, 'UTF-8');

        if (mb_check_encoding($char, 'ASCII')) {
            return false;
        }
    }

    return true;
}

function contains_any_multibyte($string)
{
    return !mb_check_encoding($string, 'ASCII') && mb_check_encoding($string, 'UTF-8');
}


function string_truncate($string, $multi_byte_limit = 20, $single_byte_limit = 30, $end = '...')
{
    $char_limit = contains_any_multibyte($string) ? $multi_byte_limit : $single_byte_limit;

    if (strlen($string) > $char_limit) {
        $string = mb_substr($string, 0, $char_limit, 'utf-8') . $end;
    }

    return $string;
}

function str_truncate_middle($text, $maxChars = 16, $filler = '...')
{
    $length       = strlen($text);
    $fillerLength = strlen($filler);

    return ($length > $maxChars)
        ? substr_replace($text, $filler, ($maxChars - $fillerLength) / 2, $length - $maxChars + $fillerLength)
        : $text;
}


function string_pattern_replace($string, $search)
{
    $placeholders = array_keys($search);
    foreach ($placeholders as &$placeholder) {
        $placeholder = "{{$placeholder}}";
    }

    return str_replace($placeholders, array_values($search), $string);
}

function thousandsCurrencyFormat($val)
{
    if ($val < 1000)
        return $val;

    $result        = round($val / 1000, 1);
    $decimal_point = explode('.', $result);

    if (!empty($decimal_point[1])) {

        if ($decimal_point[1] < 1) {
            $result = round($val / 1000);
        }
    }

    return $result;
}

function convert_number_into_kanji($string)
{
    $kanji_numbers_word = [
        '',        // Blank for ease of use.  Going to use Array Index to grab the proper character
        '一',    // 1
        '二',    // 2
        '三',    // 3
        '四',    // 4
        '五',    // 5
        '六',    // 6
        '七',    // 7
        '八',    // 8
        '九',    // 9
        '十'        // 10
    ];

    $hiragana_numbers = ['０', '１', '２', '３', '４', '５', '６', '７', '８', '９', '１０'];
    $katakana_numbers = ['０', '１', '２', '３', '４', '５', '６', '７', '８', '９', '１０'];
    $english_numbers  = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];

    $string = str_replace($hiragana_numbers, $kanji_numbers_word, $string);
    $string = str_replace($katakana_numbers, $kanji_numbers_word, $string);
    $string = str_replace($english_numbers, $kanji_numbers_word, $string);

    return $string;
}

function convert_number_into_katakana($string)
{
    $katakana_numbers_word = [
        '',        // Blank for ease of use.  Going to use Array Index to grab the proper character
        'イチ',    // 1
        'ニ',    // 2
        'サン',    // 3
        'ヨン',    // 4
        'ゴ',    // 5
        'ロク',    // 6
        'シチ',    // 7
        'ハチ',    // 8
        'キュウ',    // 9
        'ジュウ'    // 10
    ];

    $hiragana_numbers = ['０', '１', '２', '３', '４', '５', '６', '７', '８', '９', '１０'];
    $katakana_numbers = ['０', '１', '２', '３', '４', '５', '６', '７', '８', '９', '１０'];
    $english_numbers  = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];

    $string = str_replace($hiragana_numbers, $katakana_numbers_word, $string);
    $string = str_replace($katakana_numbers, $katakana_numbers_word, $string);
    $string = str_replace($english_numbers, $katakana_numbers_word, $string);

    return $string;
}

function get_app_access_token_from_header()
{
    $access_token = Request::header('Authorization');
    $access_token = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $access_token));

    return ($access_token === 'Bearer') ? '' : $access_token;
}

function in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

function get_client_credential_from_header()
{
    $client_id     = Request::header('Client-Id');
    $client_secret = Request::header('Client-Secret');

    return ['client_id' => $client_id, 'client_secret' => $client_secret];
}

function getRemoteIp()
{
    $remote_ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');

    return $remote_ip;
}

function isValidTimeStamp($timestamp)
{
    return ((string)(int)$timestamp === $timestamp)
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX);
}

function filter_admin_profile_for_api_response($profile)
{
    $profile->pReal = ImageHelper::cdnGlobalPath('admin', 'pReal', $profile->pReal);
    $profile->p0    = ImageHelper::cdnGlobalPath('admin', 'p0', $profile->p0);
    $profile->p1    = ImageHelper::cdnGlobalPath('admin', 'p1', $profile->p1);

    unset(
        $profile->userid,
        $profile->id,
        $profile->admin_type,
        $profile->hash_id,
        $profile->state,
        $profile->country,
        $profile->postcode,
        $profile->timezone,
        $profile->p2,
        $profile->created_by,
        $profile->updated_by,
        $profile->created_at,
        $profile->updated_at
    );

    return $profile;
}

function filter_user_profile_for_api_response($profile)
{
    $profile->invitation_url = Config::get('yogurt.domain') . '/i/u/' . $profile->referral_code;
    $profile->pReal          = ImageHelper::cdnGlobalPath('user', 'pReal', $profile->pReal);
    $profile->p0             = ImageHelper::cdnGlobalPath('user', 'p0', $profile->p0);
    $profile->p1             = ImageHelper::cdnGlobalPath('user', 'p1', $profile->p1);
    $profile->cReal          = ImageHelper::cdnGlobalPath('user', 'cReal', $profile->cReal);
    $profile->c0             = ImageHelper::cdnGlobalPath('user', 'c0', $profile->c0);
    $profile->c1             = ImageHelper::cdnGlobalPath('user', 'c1', $profile->c1);

    unset(
        $profile->userid,
        $profile->user_type,
        $profile->user_status,
        $profile->id,
        $profile->language,
        $profile->hash_id,
        $profile->state,
        $profile->country,
        $profile->postcode,
        $profile->timezone,
        $profile->p2,
        $profile->c2,
        $profile->referral_code,
        $profile->referred_by,
        $profile->operating_system,
        $profile->is_affiliate_participant,
        $profile->is_whitelist_participant,
        $profile->whitelist_ethereum_address,
        $profile->whitelist_bitcoin_address,
        $profile->participated_crypto_type,
        $profile->participated_crypto_amount,
        $profile->created_by,
        $profile->updated_by,
        $profile->created_at,
        $profile->updated_at
    );

    return $profile;
}

function invitation_url($referral_code)
{
    return Config::get('yogurt.domain') . '/i/u/' . $referral_code;
}

function cmp($a, $b)
{
    if ($a[1] == $b[1]) {
        return 0;
    }

    return ($a[1] < $b[1]) ? -1 : 1;
}

function array_sort_by_display_order($data, $indexes_to_fill = [])
{
    uasort($data, 'cmp');

    if (count($indexes_to_fill) > 0) {
        foreach ($data as $key => $value) {
            $data_index = 0;
            $data       = [];
            foreach ($indexes_to_fill as $index) {
                $data[$data_index++] = $value[$index];
            }
            $new[$key] = $data;
        }
    } else {
        foreach ($data as $key => $value) {
            $new[$key] = $value[0];
        }
    }

    return $new;
}

function array_sort_by_an_index($data, $sort_by_index = 0, $indexes_to_fill = [])
{
    uasort($data, function ($a, $b) use ($sort_by_index) {
        if ($a[$sort_by_index] == $b[$sort_by_index]) {
            return 0;
        }

        return ($a[$sort_by_index] < $b[$sort_by_index]) ? -1 : 1;
    });

    if (count($indexes_to_fill) > 0) {
        foreach ($data as $key => $value) {
            $data_index = 0;
            $data       = [];
            foreach ($indexes_to_fill as $index) {
                $data[$data_index++] = $value[$index];
            }
            $new[$key] = $data;
        }
    } else {
        foreach ($data as $key => $value) {
            $new[$key] = $value[0];
        }
    }

    return $new;
}

function is_timestamp($timestamp)
{
    if ($timestamp === null) return false;

    if (strtotime(date('Y-m-d H:i:s', $timestamp)) === (int)$timestamp) return true;
    else return false;
}

function is_approval_button_enabled($project_profile)
{
    $is_admin_approved = $project_profile->is_admin_approved;

    return UserPermission::isWrite() &&
        (
            $is_admin_approved == ProjectApproval::REJECTED ||
            $is_admin_approved == ProjectApproval::WORK_IN_PROGRESS
        );
}

function check_permission($permission)
{
    $admin_profile = View::shared('admin_profile');

    if (empty($admin_profile)) {
        return false;
    } else {
        return $admin_profile->can($permission);
    }
}

function check_permission_for_filter($permission_name)
{

    $allowed = check_permission($permission_name);

    if (!$allowed) {

        if (Request::ajax()) {
            return RestResponse::createResponseAccessDenied(ErrorCodes::P400005);
        }

        return Redirect::guest(action('UserTypeDeniedController@getDenied'));
    }

}

if (!function_exists('user_name_identity')) {

    /**
     * @param UserInfo $user_profile
     * @return string $name
     */
    function user_name_identity($user_profile)
    {
        if (empty($user_profile)) {
            return '';
        }

        if (!empty($user_profile->firstname) || !empty($user_profile->lastname)) {
            $name = $user_profile->firstname;

            if (!empty($user_profile->lastname)) {
                if (!empty($name)) {
                    $name .= ' ';
                }

                $name .= $user_profile->lastname;
            }
        } else if (!empty($user_profile->screenname)) {
            $name = $user_profile->screenname;
        } else {
            $name = $user_profile->email;
        }

        return $name;
    }
}

if (!function_exists('createSlug')) {

    function createSlug($str, $delimiter = '_')
    {

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));

        return $slug;

    }
}


if (!function_exists('name_or_screenname')) {
    function name_or_screenname($user_profile)
    {
        if (empty($user_profile)) {
            return '';
        }

        $name = '';

        if (!empty($user_profile->firstname) || !empty($user_profile->lastname)) {
            $name = $user_profile->firstname;

            if (!empty($user_profile->lastname)) {
                if (!empty($name)) {
                    $name .= ' ';
                }

                $name .= $user_profile->lastname;
            }
        } else if (!empty($user_profile->screenname)) {
            $name = $user_profile->screenname;
        }

        return $name;
    }
}


if (!function_exists('name_or_email')) {
    function name_or_email($user_profile)
    {
        if (empty($user_profile)) {
            return '';
        }

        $name = '';

        if (!empty($user_profile->firstname) || !empty($user_profile->lastname)) {
            $name = $user_profile->firstname;

            if (!empty($user_profile->lastname)) {
                if (!empty($name)) {
                    $name .= ' ';
                }

                $name .= $user_profile->lastname;
            }
        } else if (!empty($user_profile->email)) {
            $name = $user_profile->email;
        }

        return $name;
    }
}

if (!function_exists('now')) {
    function now()
    {
        $time_target = Carbon::now();

        if (Auth::check()) {

            $my_profile = View::shared('my_profile');

            if (!empty($my_profile->timezone)) {

                $time_target->timezone($my_profile->timezone);
            }
        }

        return $time_target;
    }
}

if (!function_exists('validate_per_page')) {
    function validate_per_page($per_page = null)
    {
        $system_setting_repository = App::make(App\Repositories\SystemSettingRepositoryEloquent::class);
        $paginations               = explode(',', $system_setting_repository->getSystemSettingValueByName(Zla\Saffron\Helpers\Constants\YogurtSystemSetting::DEFAULT_PAGINATION_PAGES, true)[0]->value_text);

        if (empty($per_page)) {
            $my_profile = View::shared('my_profile');
            $per_page   = Config::get('yogurt.pagination.cms');
            if ($my_profile->user_type == UserType::ADMIN) {
                $admin_various_settings = AdminVariousSetting::getAdminVariousSetting($my_profile->userid);

                if (is_array($admin_various_settings) && count($admin_various_settings) > 0) {
                    $per_page = current($admin_various_settings)->pagination_default;
                }
            } else {
                $user_various_settings = UserVariousSetting::getUserVariousSetting($my_profile->userid);

                if (is_array($user_various_settings) && count($user_various_settings) > 0) {
                    $per_page = current($user_various_settings)->pagination_default;
                }
            }
        }

        View::share('paginations', $paginations);
        View::share('per_page', $per_page);

        foreach ($paginations as $pagination) {
            if ($per_page <= $pagination) {
                return $pagination;
            }
        }

        return $paginations[0];
    }
}

if (!function_exists('get_iso_locale')) {
    function get_iso_locale($locale)
    {
        if ($locale == 'ch') {
            return 'zh_cn';
        }

        return $locale;
    }
}

if (!function_exists('domain_exists')) {
    function domain_exists($email, $record = 'MX')
    {
        list($user, $domain) = explode('@', $email);

        if (empty($domain)) {
            return false;
        }

        //        return checkdnsrr($domain, $record);
        // we remove mx record check because it give inconsistency result
        // and we only check if domain exist or not.
        return checkdnsrr($domain);
    }
}

if (!function_exists('generate_otp_secret_code')) {
    function generate_otp_secret_code($salt, $code_length)
    {
        $min         = pow(10, $code_length);
        $max         = $min * 10 - 1;
        $random_code = mt_rand($min, $max);

        $code['encrypt'] = Crypt::encrypt($salt . $random_code);
        $code['real']    = $random_code;

        return $code;
    }
}

if (!function_exists('subject_prefix')) {
    function subject_prefix($subject)
    {
        if (App::environment('staging', 'local')) {
            $subject = 'DEVELOPMENT: ' . $subject;
        }

        return $subject;
    }
}

if (!function_exists('decode_base_64_upload')) {
    function decode_base_64_upload($base_64_string)
    {
        if (!empty($base_64_string)) {
            if (strpos($base_64_string, ';') === false) {
                return ['', ''];
            }

            list($type, $base_64_string) = explode(';', $base_64_string);

            if (strpos($base_64_string, ',') === false) {
                return ['', ''];
            }

            list(, $base_64_string) = explode(',', $base_64_string);

            $mime_type = str_replace('data:', '', $type);

            return [base64_decode($base_64_string), $mime_type];
        }

        return ['', ''];
    }
}

if (!function_exists('day_diff')) {
    function day_diff($start_date, $end_date)
    {
        $start    = Carbon::parse($start_date);
        $day_diff = $start->diffInDays($end_date);

        return $day_diff;
    }
}

if (!function_exists('time_diff_for_human')) {

    /**
     * @param Carbon/Carbon $time
     * @return string
     */
    function time_diff_for_human($time)
    {
        if ($time->diffInSeconds() < 60) {
            return Lang::choice('user/general.time_for_human.secondly', $time->diffInSeconds());
        }

        if ($time->diffInMinutes() < 60) {
            return Lang::choice('user/general.time_for_human.minutely', $time->diffInMinutes());
        }

        if ($time->diffInHours() < 24) {
            $jumped_time = Carbon::now()->subHours($time->diffInHours());
            if ($time->diffInMinutes($jumped_time) <= 1) {
                return Lang::choice('user/general.time_for_human.hourly', $time->diffInHours());
            }

            return Lang::choice('user/general.time_for_human.hour_minutely', $time->diffInHours()) . ' ' . Lang::choice('user/general.time_for_human.minutely', $time->diffInMinutes($jumped_time));
        }

        if ($time->diffInDays() >= 1) {
            $jumped_time = Carbon::now()->subDays($time->diffInDays());
            if ($time->diffInHours($jumped_time) <= 1) {
                return Lang::choice('user/general.time_for_human.daily', $time->diffInDays(), ['count' => $time->diffInDays()]);
            }

            return Lang::choice('user/general.time_for_human.day_hourly', $time->diffInDays(), ['count' => $time->diffInDays()]) . ' ' . Lang::choice('user/general.time_for_human.hourly', $time->diffInHours($jumped_time), ['count' => $time->diffInHours($jumped_time)]);
        }
    }
}

if (!function_exists('increase_version')) {
    function increase_version($version)
    {
        $versions = explode('.', $version);
        for ($i = count($versions) - 1; $i > -1; --$i) {
            if (++$versions[$i] < 1000 || !$i) break;
            $versions[$i] = 0;
        }

        return implode('.', $versions);
    }
}

if (!function_exists('dates_format')) {
    /**
     * @param DateTime|Carbon|String $date
     * @param bool                   $show_times
     * @param string                 $locale
     * @return false|string dates_format
     * @throws Exception
     */
    function dates_format($date, $show_times = false, $locale = null)
    {
        if (empty($date)) {
            return '';
        }

//        if (empty($locale)) {
//            $locale = LaravelLocalization::getCurrentLocale();
//        }

        switch ($locale) {
            case 'ja':
            case 'ch':
            {
                $format = 'Y年m月d日';
                break;
            }
            case 'ko' :
            {
                $format = 'Y년 m월 d일';
                break;
            }
            default :
                $format = 'Y M d';
        }

        if ($show_times) {
            $format .= ' H:i:s';
        }

        if (!is_object($date)) {
            $dtime = new DateTime($date);

            return $dtime->format($format);
        }

        return $date->format($format);
    }
}

if (!function_exists('count_after_decimal_points')) {
    /**
     * @param float  $float
     * @param string $decimal_point
     * @return int
     */
    function count_after_decimal_points($float, $decimal_point = '.')
    {
        $floats = explode($decimal_point, rtrim($float, '0'));

        return !empty($floats[1]) ? strlen($floats[1]) : 0;
    }
}

if (!function_exists('float_readable')) {

    /**
     * @param double $amount
     * @param int    $decimals
     * @param bool   $is_round
     * @return string
     */
    function float_readable($amount, $decimals = 8, $is_round = true)
    {
        if ($is_round) {
            $amount = round($amount, $decimals);
        }

        return rtrim(rtrim(number_format($amount, $decimals), 0), '.');
    }
}

if (!function_exists('url_exists')) {
    /**
     * @param string $url
     * @return bool
     */
    function url_exists($url)
    {
        $headers = get_headers($url);

        return stripos($headers[0], "200 OK") ? true : false;
    }
}

if (!function_exists('round_up')) {
    /**
     * @param float   $value
     * @param integer $precision
     * @return float|int
     */
    function round_up($value, $precision = 0)
    {
        $pow = pow(10, $precision);

        return ceil($value * $pow) / $pow;
    }
}

if (!function_exists('round_down')) {
    /**
     * @param float   $value
     * @param integer $precision
     * @return float|int
     */
    function round_down($value, $precision = 0)
    {
        $pow = pow(10, $precision);

        return floor($value * $pow) / $pow;
    }
}

if (!function_exists('remove_email_alias')) {
    /**
     * @param string $email
     * @param string $pattern
     * @param array  $email_providers
     * @return float|int
     */
    function remove_email_alias($email)
    {
        if (empty($email) || !is_string($email)) return false;

        $email_alias_pattern = Config::get('yogurt.email_alias_pattern');

        $is_email_provider_valid = false;
        foreach ($email_alias_pattern as $pattern) {
            $email_providers = $pattern['email_providers'];
            if (!is_array($email_providers) || count($pattern['email_providers']) <= 0) {
                continue;
            }
            if (is_substr_array(strtolower($email), $email_providers)) {
                $is_email_provider_valid = true;
            }
        }
        if (!$is_email_provider_valid) {
            return false;
        }

        foreach ($email_alias_pattern as $pattern) {
            $email_providers = $pattern['email_providers'];
            if (count($email_providers) > 1 && is_substr_array(strtolower($email), $email_providers)) {
                $email = str_replace($email_providers, $email_providers[0], $email);
            }
            // remove all dots
            if (is_substr_array(strtolower($email), $email_providers) && $pattern['remove_dot'] === true) {
                $email_array = explode('@', $email);
                if (count($email_array) == 2 && stripos($email_array[0], '.')) {
                    $email_array[0] = preg_replace('/\./i', '', $email_array[0]);
                    $email          = $email_array[0] . '@' . $email_array[1];
                }
            }

            // remove any characters starts with + sign
            if (is_substr_array(strtolower($email), $email_providers) && $pattern['remove_plus'] === true) {
                if (stripos($email, $email_providers[0])) {
                    preg_match_all($pattern['plus_pattern'], $email, $out);
                    $out = array_unique(array_flatten($out));

                    $email = str_replace($out, '', $email);
                    break;
                }
            }
        }

        return $email;
    }
}

if (!function_exists('is_substr_array')) {

    /**
     * @param mixed $needle
     * @param array $haystack array value that need to check
     * @return boolean
     */
    function is_substr_array($needle, array $haystack)
    {
        foreach ($haystack as $item) {
            if (false !== strpos($needle, $item)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('mask_phone_number')) {

    function mask_phone_number($number, $rest_number_length = 4)
    {
        if (empty($number)) return '';

        $phone_number_util = PhoneNumberUtil::getInstance();
        try {
            $phone_number_proto  = $phone_number_util->parse($number);
            $country_code        = '+' . $phone_number_proto->getCountryCode();
            $country_code_length = strlen($country_code);
            $mask_number         = substr($number, 0, $country_code_length) . str_repeat("*", strlen($number) - ($rest_number_length + $country_code_length)) . substr($number, -$rest_number_length);
        } catch (NumberParseException $e) {
            Log::error('Helper | Error on masking phone number', [
                'error_type'   => $e->getErrorType(),
                'phone_number' => $number,
            ]);

            $mask_length = strlen($number) - $rest_number_length;
            if ($mask_length < 0) {
                $mask_length = 0;
            }

            $mask_number = str_repeat("*", $mask_length) . substr($number, -$rest_number_length);
        }

        return $mask_number;
    }
}

if (!function_exists('str_replace_key_value')) {
    /**
     * Replace a given key with a value in the string.
     *
     * @param string $search
     * @param array  $replace
     * @param string $subject
     * @return string
     */
    function str_replace_key_value(array $pairs, $subject)
    {
        foreach ($pairs as $key => $value) {
            $subject = preg_replace('/' . $key . '/', $value, $subject, 1);
        }

        return $subject;
    }
}

if (!function_exists('str_ordinal')) {
    /**
     * Append an ordinal indicator to a numeric value.
     *
     * @param string|int $value
     * @param bool       $superscript
     * @return string
     */
    function str_ordinal($value, $superscript = false)
    {
        $number = abs($value);

        $indicators = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        $suffix = $superscript ? '<sup>' . $indicators[$number % 10] . '</sup>' : $indicators[$number % 10];
        if ($number % 100 >= 11 && $number % 100 <= 13) {
            $suffix = $superscript ? '<sup>th</sup>' : 'th';
        }

        return number_format($number) . $suffix;
    }
}

if (!function_exists('file_get_contents_curl')) {
    /**
     * get file contents via curl
     *
     * @param string $search
     * @param array  $replace
     * @param string $subject
     * @return string
     * @throws Exception
     */
    function file_get_contents_curl($url)
    {
        $ch = curl_init();
        try {
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

            $data = curl_exec($ch);
        } catch (Exception $e) {
            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }
        } finally {
            curl_close($ch);
        }

        return $data;
    }
}

if(!function_exists('number_shorten')){
    function number_shorten($number, $precision = 3, $divisors = null) {

        // Setup default $divisors if not provided
        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '', // 1000^0 == 1
                pow(1000, 1) => 'K', // Thousand
                pow(1000, 2) => 'M', // Million
                pow(1000, 3) => 'B', // Billion
                pow(1000, 4) => 'T', // Trillion
                pow(1000, 5) => 'Qa', // Quadrillion
                pow(1000, 6) => 'Qi', // Quintillion
            );
        }

        // Loop through each $divisor and find the
        // lowest amount that matches
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                // We found a match!
                break;
            }
        }

        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return number_format($number / $divisor, $precision) . $shorthand;
    }
}

if(!function_exists('generateActiveIcon')) {
    function generateActiveIcon($is_active)
    {
        if ($is_active) {
            return "<small class='label label-success'>Yes</small>";
        } else {
            return "<small class='label label-danger'>No</small>";
        }
    }
}

if(!function_exists('generateActiveList')) {
    function generateActiveList()
    {
        return [
            'all' => 'All',
            0     => 'Not Active',
            1     => 'Active'
        ];
    }
}
<?php

namespace Tlab\Libraries;

class Utils
{
    public static function getVarType($var)
    {
        if ($var === '_BNULL') {
            return _VARTYPE_NULL;
        }

        if (is_bool($var)) {
            return _VARTYPE_BOOLEAN;
        }

        if (is_float($var)) {
            return _VARTYPE_DOUBLE;
        }

        if (is_array($var)) {
            return _VARTYPE_ARRAY;
        }

        if (is_int($var)) {
            return _VARTYPE_INTEGER;
        }

        if (is_object($var)) {
            return _VARTYPE_OBJECT;
        }

        if (is_string($var)) {
            return _VARTYPE_STRING;
        }

        if (is_null($var)) {
            return _VARTYPE_NULL;
        }

        if (is_resource($var)) {
            return _VARTYPE_RESOURCE;
        }

        return _VARTYPE_UNKNOWN;
    }

/**
 * Valida um valor inteiro.
 *
 * @param mixed $value
 * @param int   $default
 */
public static function ValidateInt($value, $default = null)
{
    if ($value != strval(intval($value))) {
        return $default;
    } else {
        return  intval($value);
    }
}

/**
 * Valida um valor decimal.
 *
 * @param mixed $value
 * @param float $default
 *
 * @return float
 */
public static function ValidateFloat($value, $default = null)
{
    if ($value != strval(floatval($value))) {
        return $default;
    } else {
        return  floatval($value);
    }
}

/**
 * Format date in the form dd/mm.
 *
 * @param unknown_type $date
 */
public static function formatDateDM($date)
{
    if ($date == '0000-00-00') {
        return '';
    }

    $n = sscanf($date, '%04d-%02d-%02d', $year, $month, $day);
    if ($n != 3) {
        return;
    }

    return sprintf('%02d/%02d', $day, $month);
}

    public static function formatDateRFC822($date)
    {
        $n = sscanf($date, '%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second);
        if ($n != 6) {
            return;
        }

        $result = date('r', mktime($hour, $minute, $second, $month, $day, $year));

        return $result;
    }

//GET DIFF BETWEEN 2 DATES IN MINUTES
public static function diffDateTime($dtime1, $dtime2)
{
    $n1 = sscanf($dtime1, '%04d-%02d-%02d %02d:%02d:%02d', $year1, $month1, $day1, $hour1, $minute1, $second1);
    $n2 = sscanf($dtime2, '%04d-%02d-%02d %02d:%02d:%02d', $year2, $month2, $day2, $hour2, $minute2, $second2);

    if ($n1 != 6 || $n2 != 6) {
        return;
    }

    $timestamp1 = mktime($hour1, $minute1, $second1, $month1, $day1, $year1);
    $timestamp2 = mktime($hour2, $minute2, $second2, $month2, $day2, $year2);

    $diff = round(($timestamp2 - $timestamp1) / 60, 0);

    return $diff;
}

/**
 * @param string $url_from
 * @param string $path_to
 */
public static function curlHTTPCopy($url_from, $path_to)
{
    if (file_exists($path_to._DS.basename($url_from))) {
        return $path_to._DS.basename($url_from);
    }

    $lfile = @fopen($path_to._DS.basename($url_from), 'w');
    if ($lfile === false) {
        return false;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_from);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
    curl_setopt($ch, CURLOPT_FILE, $lfile);
    curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    fclose($lfile);

    if ($status != 200) {
        unlink($path_to._DS.basename($url_from));

        return false;
    }

    return $path_to._DS.basename($url_from);
}

// RESIZE AND CROP AN IMAGE TO CACHE
/**
 * @param string $source_path    Caminho absoluto da imagem original
 * @param string $cache_path     Caminho absoluto da CACHE
 * @param int    $desired_width  Largura
 * @param int    $desired_height Altura
 *
 * @return nome do ficheiro que se encontra na CACHE
 */
public static function crop2fit($source_path, $cache_path, $desired_width = 150, $desired_height = 150)
{
    if (!file_exists($source_path) || !is_file($source_path)) {
        return false;
    }

    //EXTRACT FILE NAME
    $parts = explode('/', $source_path);
    $file_name = $parts[count($parts) - 1];

    //EXTRACT NAME AND EXT
    $ext = strrchr($file_name, '.');
    $name = ($ext === false) ? $file_name : substr($file_name, 0, -strlen($ext));

    if (file_exists($cache_path._DS.'crop'.$desired_width.'x'.$desired_height.'-'.$name.$ext)) {
        return 'crop'.$desired_width.'x'.$desired_height.'-'.$name.$ext;
    }

    list($source_width, $source_height, $source_type) = getimagesize($source_path);

    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gdim = imagecreatefromgif($source_path);
            break;

        case IMAGETYPE_JPEG:
            $source_gdim = imagecreatefromjpeg($source_path);
            break;

        case IMAGETYPE_PNG:
            $source_gdim = imagecreatefrompng($source_path);
            break;

        default:
            return false;
    }

    $source_aspect_ratio = $source_width / $source_height;
    $desired_aspect_ratio = $desired_width / $desired_height;

    if ($source_aspect_ratio > $desired_aspect_ratio) {
        //
        // Triggered when source image is wider
        //
        $temp_height = $desired_height;
        $temp_width = (int) ($desired_height * $source_aspect_ratio);
    } else {
        //
        // Triggered otherwise (i.e. source image is similar or taller)
        //
        $temp_width = $desired_width;
        $temp_height = (int) ($desired_width / $source_aspect_ratio);
    }

    //
    //Resize the image into a temporary GD image
    //

    $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled(
    $temp_gdim,
    $source_gdim,
    0, 0,
    0, 0,
    $temp_width, $temp_height,
    $source_width, $source_height
    );

    //
    // Copy cropped region from temporary image into the desired GD image
    //

    $x0 = ($temp_width - $desired_width) / 2;
    $y0 = ($temp_height - $desired_height) / 2;

    $desired_gdim = imagecreatetruecolor($desired_width, $desired_height);
    imagecopy(
    $desired_gdim,
    $temp_gdim,
    0, 0,
    $x0, $y0,
    $desired_width, $desired_height
    );

    //
    // Render the image
    // Alternatively, you can save the image in file-system or database
    //
    switch ($source_type) {
        case IMAGETYPE_GIF:
            imagegif($desired_gdim, $cache_path.'/crop'.$desired_width.'x'.$desired_height.'-'.$name.'.gif');

            return 'crop'.$desired_width.'x'.$desired_height.'-'.$name.'.gif';
            break;

        case IMAGETYPE_JPEG:
            imagejpeg($desired_gdim, $cache_path.'/crop'.$desired_width.'x'.$desired_height.'-'.$name.'.jpg');

            return 'crop'.$desired_width.'x'.$desired_height.'-'.$name.'.jpg';
            break;

        case IMAGETYPE_PNG:
            imagepng($desired_gdim, $cache_path.'/crop'.$desired_width.'x'.$desired_height.'-'.$name.'.png');

            return 'crop'.$desired_width.'x'.$desired_height.'-'.$name.'.png';
            break;
    }

    return false;
}

/**
 * Return URL-Friendly string slug.
 *
 * @param string $string
 *
 * @return string
 */
public static function seoUrl($string)
{
    $string = friendly_url($string);

    return '-'.$string;

    /*
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return '-'.$string;
    */
}

    public static function seoUrlDecode($url)
    {
        $parts = explode('-', $url);
        if (count($parts)) {
            $id = ValidateInt($parts[0], 0);
        } else {
            $id = 0;
        }

        return $id;
    }

    public static function friendly_url($url)
    {

    //replace accent characters, depends your language is needed
    $url = replace_accents($url);

    // everything to lower and no spaces begin or end
    $url = strtolower(trim($url));

    // decode html maybe needed if there's html I normally don't use this
    //$url = html_entity_decode($url,ENT_QUOTES,'UTF8');

    // adding - for spaces and union characters
    $find = array(' ', '&', '+', ',');
        $url = str_replace($find, '-', $url);

    //delete and replace rest of special chars
    $find = array('/[^a-z0-9-<>]/', '/[-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace($find, $repl, $url);

    //return the friendly url
    return $url;
    }

    public static function replace_accents($var)
    { //replace for accents catalan spanish and more
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        $var = str_replace($a, $b, $var);

        return $var;
    }
}

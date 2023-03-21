<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modul
 *
 * @author Dicky
 */
class Modul
{

    //put your code here
    public function enkrip_url($string)
    {
        $secret_key = "1111111111111111";
        $secret_iv = "2456378494765431";
        $encrypt_method = "aes-256-cbc";
        // hash
        $key = hash("sha256", $secret_key);
        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
        //do the encryption given text/string/number
        $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);
        return $output;
    }

    public function dekrip_url($string)
    {
        $secret_key = "1111111111111111";
        $secret_iv = "2456378494765431";
        $encrypt_method = "aes-256-cbc";
        // hash
        $key = hash("sha256", $secret_key);
        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash("sha256", $secret_iv), 0, 16);
        //do the decryption given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    public function EnkripId($string)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = 'DiamMBYGeoV3'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    function DekripId($string)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = 'DiamMBYGeoV3'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }

    public function enkrip($string)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = '5fgf5HJ5g27'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    public function dekrip($string)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = '5fgf5HJ5g27'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }

    public function enkrip_pass($string_normal)
    {
        require_once 'Chiper.php';
        $cipher = new Chiper(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $kunci = "BismillahUF2022";
        $en = $cipher->encrypt($string_normal, $kunci);
        return $en;
    }

    public function dekrip_pass($string_terenkrip)
    {
        require_once 'Chiper.php';
        $cipher = new Chiper(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $kunci = "BismillahUF2022";
        $de = $cipher->decrypt($string_terenkrip, $kunci);
        return $de;
    }

    public function DateTimeNowDB()
    {
        date_default_timezone_set("Asia/Jakarta");
        return date("Y-m-d H:i:s");
    }

    public function DateTimeNowID()
    {
        date_default_timezone_set("Asia/Jakarta");
        return date("d-m-Y H:i:s");
    }

    public function DateNowDB()
    {
        date_default_timezone_set("Asia/Jakarta");
        return date("Y-m-d");
    }

    public function TimeNowDB()
    {
        date_default_timezone_set("Asia/Jakarta");
        return date("H:i:s");
    }

    public function Tahun()
    {
        date_default_timezone_set("Asia/Jakarta");
        return date("Y");
    }

    public function Month()
    {
        date_default_timezone_set("Asia/Jakarta");
        return date("F");
    }

    public function MonthNumber()
    {
        date_default_timezone_set("Asia/Jakarta");
        return date("m");
    }

    public function DurationTime($work_time, $save_time)
    {
        date_default_timezone_set("Asia/Jakarta");

        $work = strtotime($work_time);
        $save = strtotime($save_time);

        $result = $save - $work;
        return $result;
    }

    public function FirstDay($date)
    {
        $first_second = date('1', strtotime($date));

        return $first_second;
    }

    public function LastDay($date)
    {
        $last_second = date('t', strtotime($date));

        return $last_second;
    }

    public function ConvertMonth($month)
    {
        $newDate = date('F', mktime(0, 0, 0, $month, 10));

        return $newDate;
    }

    public function ExpiredPOI()
    {
        date_default_timezone_set("Asia/Jakarta");

        $tanggal = date('Y-m-d', strtotime('-2 day'));
        return $tanggal;
    }

    public function Periode($ddate)
    {
        $date = new DateTime($ddate);
        $week = $date->format("W");
        $year = $date->format("y");
        return "W" . $week . $year;
    }

    public function PeriodeNew($date)
    {
        // 1. Convert input to $year, $month, $day
        $dateset = strtotime($date);
        $year = date("Y", $dateset);
        $month = date("m", $dateset);
        $day = date("d", $dateset);

        // 2. check if $year is a  leapyear
        if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) {
            $leapyear = true;
        } else {
            $leapyear = false;
        }

        // 3. check if $year-1 is a  leapyear
        if ((($year - 1) % 4 == 0 && ($year - 1) % 100 != 0) || ($year - 1) % 400 == 0) {
            $leapyearprev = true;
        } else {
            $leapyearprev = false;
        }

        // 4. find the dayofyearnumber for y m d
        $mnth = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
        $dayofyearnumber = $day + $mnth[$month - 1];
        if ($leapyear && $month > 2) {
            $dayofyearnumber++;
        }

        // 5. find the jan1weekday for y (monday=1, sunday=7)
        $yy = ($year - 1) % 100;
        $c = ($year - 1) - $yy;
        $g = $yy + intval($yy / 4);
        $jan1weekday = 1 + ((((intval($c / 100) % 4) * 5) + $g) % 7);

        // 6. find the weekday for y m d
        $h = $dayofyearnumber + ($jan1weekday - 1);
        $weekday = 1 + (($h - 1) % 7);

        // 7. find if y m d falls in yearnumber y-1, weeknumber 52 or 53
        $foundweeknum = false;
        if ($dayofyearnumber <= (8 - $jan1weekday) && $jan1weekday > 4) {
            $yearnumber = $year - 1;
            if ($jan1weekday == 5 || ($jan1weekday == 6 && $leapyearprev)) {
                $weeknumber = 53;
            } else {
                $weeknumber = 52;
            }
            $foundweeknum = true;
        } else {
            $yearnumber = $year;
        }

        // 8. find if y m d falls in yearnumber y+1, weeknumber 1
        if ($yearnumber == $year && !$foundweeknum) {
            if ($leapyear) {
                $i = 366;
            } else {
                $i = 365;
            }
            if (($i - $dayofyearnumber) < (4 - $weekday)) {
                $yearnumber = $year + 1;
                $weeknumber = 1;
                $foundweeknum = true;
            }
        }

        // 9. find if y m d falls in yearnumber y, weeknumber 1 through 53
        if ($yearnumber == $year && !$foundweeknum) {
            $j = $dayofyearnumber + (7 - $weekday) + ($jan1weekday - 1);
            $weeknumber = intval($j / 7);
            if ($jan1weekday > 4) {
                $weeknumber--;
            }
        }

        // 10. output iso week number (YYWW)
        $result = ($yearnumber - 2000) * 100 + $weeknumber;

        $hasil_week = substr($result, 2); //get week
        $hasil_year = substr($result, 0, 2); //get year

        return 'W' . $hasil_week . $hasil_year;
    }

    public function PeriodeYear($year)
    {
        $data = date("W", strtotime('28th December ' . $year));
        return $data;
    }

    public function ChangeInputPetik($string)
    {
        $data = addslashes($string);
        return $data;
    }

    public function ChangeDBPetik($string)
    {
        //check data
        if (strpos($string, "\'")) {
            $data = str_replace("\'", "'", $string);
        } elseif (strpos($string, '\"')) {
            $data = str_replace('\"', '"', $string);
        } else {
            $data = $string;
        }
        return $data;
    }

    public function time_elapsed($datenow, $datetime)
    {
        $startDate = new DateTime($datenow);
        $endDate = new DateTime($datetime);

        $interval = $startDate->diff($endDate);
        return $interval->days + 1 . " Hari";
    }

    public function Halaman($halaman)
    {
        $string_pesan = "<script type='text/javascript'> ";
        $string_pesan .= "window.location = '" . base_url() . $halaman . "';</script>";
        echo $string_pesan;
    }

    public function HalamanLogin()
    {
        $string_pesan = "<script type='text/javascript'> 
                            window.location = 'https://mapbyyou.com/geolancerv3/';
                        </script>";
        echo $string_pesan;
    }

    public function Distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
            return ($miles * 1.609344);
        }
    }

    public function DetectBrowser()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
            return 'Internet explorer';
        } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) {
            return 'Internet explorer';
        } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
            return 'Mozilla Firefox';
        } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
            return 'Google Chrome';
        } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) {
            return "Safari";
        } else {
            return 'Something else';
        }
    }

    public function IP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from remote address
        else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        return $ip_address;
    }
}

<?php

class PW {
    public static function check($pw) {
        $flags = array(0, 0, 0);
        $pwarray = str_split($pw);
        if(strlen($pw)<8) {
            return -1;
        }
        foreach($pwarray as $letter) {
            if($letter>='a' && $letter<='z') {
                $flags[0] = 1;
            }
            else if($letter>='A' && $letter<='Z') {
                $flags[1] = 1;
            }
            else if($letter>='0' && $letter<='9') {
                $flags[2] = 1;
            } 
        }
        if ($flags[0] == 0) {
            return -2;
        }
        if ($flags[1] == 0) {
            return -3;
        }
        if ($flags[2] == 0) {
            return -4;
        }
        if ($flags[0] + $flags[1] + $flags[2] == 3) {
            return 0;
        }
    }
    public static function generate() {
        $arrayString = null;
        while (true) {
            for ($i=0; $i<16;$i++) {
                $random = random_int(0,2);
                switch ($random) {
                    case 0:
                        $arrayString[] = chr(random_int(ord('a'), ord('z')));
                    break;
                    case 1:
                        $arrayString[] = chr(random_int(ord('A'), ord('Z')));
                    break;
                    case 2:
                        $arrayString[] = chr(random_int(ord('0'), ord('9')));
                    break;
                }
            }
            $password = implode("", $arrayString);
            if (self::check($password)==0) {
                return $password;
            }   
        }
    }

    public static function isValidUsername($string) {
        //return true;
        if (strlen($string)<6) {
            return false;
        }
        return !preg_match('/[^A-Za-z0-9.\-_]|[.\-_]$|^[.\-_]/', $string);
    }

    public static function isValidPassword($string) {
        //return true;
        if (PW::check($string) != 0) {
            return false;
        }
        return true;
        // return !preg_match('/[^A-Za-z0-9\.\-_]/', $string);
    }

    public static function myhash($value) {
        return password_hash($value, PASSWORD_BCRYPT);
        //hash('sha256', $value."super99SeCrEtHa5hu1traHypeR");
    }
}

?>
<?php

class DateTimeExt extends DateTime {

    public function __toString() {
        return $this->format('d.m.Y H:i:s');
    }

    public function agoFormat($datetime=NULL) {
        if ($datetime == NULL) {
            $datetime = new DateTimeExt(date("Y-m-d H:i:s"));
        }
        $diff = $this->diff($datetime);
        if ($diff->y > 0) {
            if ($diff->y==1) {
                return $diff->y . " year";
            }
            return $diff->y . " years";
        }
        else if ($diff->m > 0) {
            if ($diff->m==1) {
                return $diff->m . " month";
            }
            return $diff->m . " months";
        }
        else if ($diff->d > 0) {
            if ($diff->d < 7) {
                if ($diff->d==1) {
                    return $diff->d . " day";
                }
                return $diff->d . " days";
            }
            else {
                if (floor($diff->d/7)==1) {
                    return floor($diff->d/7) . " week";
                }
                return floor($diff->d/7) . " weeks";
            }
        }
        else if ($diff->h > 0) {
            if ($diff->h==1) {
                return $diff->h . " hour";
            }
            return $diff->h . " hours";
        }
        else if ($diff->i > 0) {
            if ($diff->i==1) {
                return $diff->i . " minute";
            }
            return $diff->i . " minutes";
        }
        else {
            return ">1 minute";
        }
    }
}

?>
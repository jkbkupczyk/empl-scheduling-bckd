<?php

class Utils
{
    public static function sanitizeInput($data)
    {
        foreach ($data as $k => $val) {
            $val = htmlspecialchars(strip_tags($val));
        }

        return $data;
    }

    public static function validateInput($data)
    {
    }

    public function validatePassword($password): bool
    {
        $regex = '^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$';

        return preg_match($regex, $password) ? true : false;
    }
}

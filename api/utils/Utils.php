<?php

class Utils
{
    public static function sanitizeInput($data)
    {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $k => $val) {
                $val = htmlspecialchars($val);
            }
        }

        return $data;
    }

    public static function validateInput($data)
    {
    }

    public function validateEmail($email): bool
    {
        return filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
    }

    public function validatePassword($password): bool
    {
        $regex = '^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$';

        return preg_match($regex, $password) ? true : false;
    }
}

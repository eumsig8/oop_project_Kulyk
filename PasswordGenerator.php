<?php
class PasswordGenerator {
    public static function generate($length, $upper, $lower, $number, $special) {
        $result = [];
        $result[] = self::randomChars('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $upper);
        $result[] = self::randomChars('abcdefghijklmnopqrstuvwxyz', $lower);
        $result[] = self::randomChars('0123456789', $number);
        $result[] = self::randomChars('!@#$%^&*()-_=', $special);

        $all = implode('', $result);
        $remaining = $length - strlen($all);
        if ($remaining > 0) {
            $all .= self::randomChars('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+', $remaining);
        }

        return str_shuffle($all);
    }

    private static function randomChars($chars, $count) {
        $result = '';
        for ($i = 0; $i < $count; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $result;
    }
}
?>

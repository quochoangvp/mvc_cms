<?php

/**
 * Session
 */
class Session {

    /**
     * Khởi tại session
     */
    public static function init()
    {
        @session_start();
    }

    /**
     * Thêm dữ liệu vào session
     * @param string $key   index của dữ liệu trong session
     * @param string $value Dữ liệu
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Lấy dữ liệu từ session
     * @param  string $key Index của dữ liệu trong session
     * @return string|boolean      Dữ liệu
     */
    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : FALSE;
    }

    /**
     * Xóa dữ liệu trong session
     */
    public static function destroy()
    {
        unset($_SESSION);
        session_destroy();
    }
}
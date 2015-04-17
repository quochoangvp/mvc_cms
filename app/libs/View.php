<?php

/**
 * Quản lý view
 */
class View {

    /**
     * Gọi view
     * @param  string  $name   Tên view
     * @param  array   $data   Dữ liệu truyền qua view
     * @param  boolean $return Hiển thị view hay trả dữ liệu của view về 1 biến
     */
    public function load($name, $data = array(), $return = FALSE)
    {
        /**
         * Extract mảng dữ liệu truyền vào view
         */
        extract($data);

        /**
         * Tách lấy phần từ đầu tiên của tiên view
         */
        $admin_view = explode('/', $name)[0];
        /**
         * Đường dẫn đến view
         */
        $path = VIEWPATH.$name.'.php';

        /**
         * Gọi view admin
         */
        if ($admin_view == 'admin')
        {
            /**
             * Định nghĩa các biến dướng dẫn view
             */
            $header_path = VIEWPATH.'admin'.DS.'components'.DS.'header.php';
            $body_path   = VIEWPATH.'admin'.DS.'components'.DS.'body.php';
            $footer_path = VIEWPATH.'admin'.DS.'components'.DS.'footer.php';

            /**
             * Nếu tồn tại view thì load view
             */
            if (file_exists($path) && is_file($path))
            {
                /**
                 * Nếu chỉ gọi view đơn thuần
                 * không bao gồm header và footer
                 */
                if (isset($text_only) && $text_only == TRUE)
                {
                    /**
                     * Trả dữ liệu của view về biến $result
                     */
                    if ($return == TRUE)
                    {
                        ob_start();
                        require_once $body_path;
                        $result = ob_get_contents();
                        ob_end_clean();
                    }
                    /**
                     * Gọi view
                     */
                    else
                    {
                        require_once $body_path;
                    }
                }
                /**
                 * Gọi view bao gồm header và footer
                 */
                else
                {
                    require_once $header_path;
                    require_once $body_path;
                    require_once $footer_path;
                }
            }
            /**
             * Load view báo lỗi nếu không tồn tại view
             */
            else
            {
                $this->load('error/index', array('title' => '404 Not Found'), FALSE);
            }
        }
        /**
         * Gọi view frontend
         */
        else
        {
            /**
             * Định nghĩa các biến dướng dẫn view
             */
            $header_path = VIEWPATH.'components'.DS.'header.php';
            $body_path   = VIEWPATH.'components'.DS.'body.php';
            $footer_path = VIEWPATH.'components'.DS.'footer.php';

            if (file_exists($path) && is_file($path))
            {
                if (isset($text_only) && $text_only == TRUE)
                {
                    if ($return == TRUE)
                    {
                        ob_start();
                        require_once $body_path;
                        $result = ob_get_contents();
                        ob_end_clean();
                    }
                    else
                    {
                        require_once $body_path;
                    }
                }
                else
                {
                    require_once $header_path;
                    require_once $body_path;
                    require_once $footer_path;
                }
            }
            /**
             * Load view báo lỗi nếu không tồn tại view
             */
            else
            {
                $this->load('error/index', array('title' => '404 Not Found'), FALSE);
            }
        }

        /**
         * Nếu dữ liệu của view được lưu vào biến thì trả về biến đó
         */
        if (isset($result)) return $result;
    }
}
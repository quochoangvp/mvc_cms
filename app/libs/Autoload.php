<?php

/**
 * Class sẽ tự động được gọi khi truy cập vào trang web
 */
class Autoload {

    function __construct()
    {
        /**
         * Đặt biến đếm
         * @var integer
         */
        $this->_count = 1;
        /**
         * Phân tích url
         */
        $GLOBALS['rz'] = isset($_GET['rz']) ? $_GET['rz'] : DEFAUT_CONTROLLER;
        $GLOBALS['rz'] = rtrim($GLOBALS['rz'], '/');
        $GLOBALS['rz'] = explode('/', $GLOBALS['rz']);

        /**
         * Nếu đường dẫn đến chi tiết bài viết hoặc thể loại
         * thì định hướng đến phương thức `detail`
         */
        switch ($GLOBALS['rz'][0])
        {
            case 'article':
            case 'category':
            case 'author':
                /**
                 * http://domain.com/category/1/news
                 * ->
                 * http://domain.com/category/detail/1/news
                 */
                $article_method = array('detail');
                array_splice($GLOBALS['rz'], 1, 0, $article_method);    // splice in at position 1
                break;

            default:
                break;
        }

        /**
         * Nếu phần tử đầu tiên là thư mục thì xóa bỏ
         */
        if (is_dir(CONTROLLERPATH.$GLOBALS['rz'][0]))
        {
            $parent_path = $GLOBALS['rz'][0].DS;
            array_shift($GLOBALS['rz']);
        }

        if (isset($parent_path))
        {
            /**
             * Đường dẫn controller khi nằm trong một thư mục con
             * @var string
             */
            $controller_path = CONTROLLERPATH.$parent_path.$GLOBALS['rz'][0].'.php';
        }
        else
        {
            /**
             * Đường dẫn controller khi chỉ nằm trong CONTROLLERPATH
             * @var string
             */
            $controller_path = CONTROLLERPATH.$GLOBALS['rz'][0].'.php';
        }

        /**
         * Gọi controller
         */
        if (file_exists($controller_path) && is_file($controller_path))
        {
            require_once $controller_path;
            $controller = new $GLOBALS['rz'][0];

            /**
             * Gọi phương thức với các tham số truyền vào nếu tồn tại các tham số
             */
            if (isset($GLOBALS['rz'][2]))
            {
                if (method_exists($controller, $GLOBALS['rz'][1]))
                {
                    /**
                     * Gọi phương thức với 3 tham số
                     */
                    if (isset($GLOBALS['rz'][4]))
                    {
                        $controller->{$GLOBALS['rz'][1]}($GLOBALS['rz'][2], $GLOBALS['rz'][3], $GLOBALS['rz'][4]);
                    }
                    /**
                     * Gọi phương thức với 2 tham số
                     */
                    elseif (isset($GLOBALS['rz'][3]))
                    {
                        $controller->{$GLOBALS['rz'][1]}($GLOBALS['rz'][2], $GLOBALS['rz'][3]);
                    }
                    /**
                     * Gọi phương thức với 1 tham số
                     */
                    else
                    {
                        $controller->{$GLOBALS['rz'][1]}($GLOBALS['rz'][2]);
                    }
                }
                /**
                 * Không tồn tại phương thức, hiển thì lỗi
                 */
                else
                {
                    $this->error();
                }
            }
            /**
             * Gọi phương thức nếu không có các tham số và tồn tại phương thức
             */
            else
            {
                if (isset($GLOBALS['rz'][1]))
                {
                    if (method_exists($controller, $GLOBALS['rz'][1]))
                    {
                        $controller->{$GLOBALS['rz'][1]}();
                    }
                    else
                    {
                        $this->error();
                    }
                }
                /**
                 * Nếu địa chỉ nhập vào không có phương thức
                 * thì hiện phương thức `index` của controller
                 */
                else
                {
                    if (method_exists($controller, 'index'))
                    {
                        $controller->index();
                    }
                    else
                    {
                        $this->error();
                    }
                }
            }
        }
        /**
         * Nếu không tồn tại controller thì hiển thị lỗi
         */
        else
        {
            $this->error();
        }
    }

    /**
     * Hiển thị trang báo lỗi
     */
    public function error()
    {
        /**
         * Nếu phương thức lỗi được gọi lần đầu
         */
        if ($this->_count <= 1) {
            /**
             * Tăng biến đếm để báo phương thức lỗi này đã được gọi 1 lần
             */
            $this->_count++;
            /**
             * Chuyển router thành page/detail/{uri}
             */
            $_GET['rz'] = 'page/detail/'.$GLOBALS['rz'][0];
            /**
             * Chạy lại Class Autoload
             */
            $this->__construct();
        }
        /**
         * Sau khi kiểm tra không có page nào như địa chỉ nhập vào thì báo lỗi
         */
        else {
            $error_path = CONTROLLERPATH.'error.php';
            if (file_exists($error_path) && is_file($error_path))
            {
                require_once $error_path;
                $controller = new Error();
                $controller->index();
                exit();
            }
            else
            {
                throw new Exception("Đường dẫn không tồn tại!");
            }
        }

    }
}
<?php

/**
 * Controller quản lý toàn bộ ứng dụng
 */
class Controller {

    function __construct()
    {
        /**
         * Tạo đối tượng view
         */
        $this->call_view();
        /**
         * Tạo đối tượng model
         */
        $this->call_model();
        /**
         * Bật session
         */
        Session::init();
    }

    /**
     * Gọi thư viện View để quản lý các view
     */
    public function call_view()
    {
        require_once LIBSPATH.'View.php';
        $this->view = new View();
    }

    /**
     * Gọi thư viện Model để quản lý việc truy xuất CSDL
     */
    public function call_model()
    {
        require_once LIBSPATH.'Model.php';
        $this->model = new Model();
    }
}
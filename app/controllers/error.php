<?php

/**
 * Lớp thông báo lỗi
 */
class Error extends Frontend_Controller {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Trang chính của controller error
     */
    public function index()
    {
        /**
         * Gọi view
         */
        $this->data['title'] = '404 Not Found';
        $this->view->load('error/index', $this->data, 2);
    }
}
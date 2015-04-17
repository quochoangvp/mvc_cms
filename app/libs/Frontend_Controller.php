<?php

/**
 * Controller chính để quản lý các trang người dùng
 */
class Frontend_Controller extends Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function get_sidebar_categories()
    {
        /**
         * Gọi model quản lý thể loại
         */
        if (!isset($this->model->category_m))
        {
            $this->model->load('category_m');
        }
        /**
         * Danh sách thể loại hiển thị trên sidebar
         */
        return $this->model->category_m->list_categories();
    }

    public function get_nav_pages()
    {
        /**
         * Gọi model quản lý trang
         */
        if (!isset($this->model->page_m))
        {
            $this->model->load('page_m');
        }
        /**
         * Danh sách trang hiển thị trên navigation
         */
        return $this->model->page_m->list_pages();
    }

    /**
     * Tạo captcha
     */
    public function get_captcha()
    {
        require_once LIBSPATH.'Recaptcha.php';

        // Get a key from https://www.google.com/recaptcha/admin/create
        $this->publickey = PUBLICKEY;
        $this->privatekey = PRIVATEKEY;
        $this->recaptcha = new Recaptcha($this->publickey, $this->privatekey);

        # the response from reCAPTCHA
        $this->resp = null;
        # the error code from reCAPTCHA, if any
        $this->error = null;
    }
}
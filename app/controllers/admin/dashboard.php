<?php

/**
 * Dashboard Controller
 */
class Dashboard extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model article_m, làm việc với bảng "articles"
         */
        $this->model->load('article_m');
    }

    /**
     * Trang chính của controller Dashboard
     */
    public function index()
    {
        /**
         * Lấy danh sách bài viết từ CSDL với 5 bài ở trang 1
         */
        $this->data['articles'] = $this->model->article_m->homepage_articles(5,1, TRUE);
        /**
         * Kích hoạt navigation
         */
        $this->data['navi'] = TRUE;
        /**
         * Kích hoạt sidebar
         */
        $this->data['sidebar'] = TRUE;
        /**
         * Gọi view
         */
        $this->data['title'] = 'Bảng điều khiển';
        $this->view->load('admin/dashboard/index', $this->data);
    }
}
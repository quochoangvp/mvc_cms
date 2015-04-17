<?php

/**
 * Quản lý trang hiển thị các bài đăng của thành viên
 */
class Author extends Frontend_Controller {
    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model làm việc với 2 bảng CSDL users và articles
         */
        $this->model->load('user_m');
        $this->model->load('article_m');
    }

    /**
     * Trang hiển thị các bài viết của người dùng
     * @param  int  $id       ID của người dùng
     * @param  string  $username Tên người dùng
     * @param  integer $page     Trang hiển thị hiện tại
     */
    public function detail($id, $username, $page = 1)
    {
        /**
         * Số bài viết trên 1 trang
         */
        $this->data['num_per_page'] = 2;

        /**
         * Trang hiện tại
         */
        $this->data['current_page'] = $page;
        /**
         * Nếu $id và $page không phải dạng số nguyên dương thì chuyển đến trang chủ
         */
        if ( ! is_numeric($id) || (int) $id <= 0 ||  ! is_numeric($this->data['current_page']) || (int) $this->data['current_page'] <= 0)
        {
            Helper::redirect();
        }
        /**
         * Lấy chi tiết người dùng theo ID
         */
        $this->data['user'] = $this->model->user_m->user_detail($id);
        /**
         * Nếu không có người dùng nào có ID như vậy thì chuyến đến trang chủ
         */
        if (count($this->data['user']) < 1)
        {
            Helper::redirect();
        }

        /**
         * Nếu đường dẫn không đúng thì chuyển hướng đến đường dẫn đúng
         */
        if ($username != $this->data['user']['name'])
        {
            Helper::redirect('author/'.$id.'/'.$this->data['user']['name'], '301');
        }
        /**
         * Tổng số bài viết
         */
        $this->data['total_articles'] = count($this->model->article_m->list_articles_by_author($id, $this->data['num_per_page']));
        /**
         * Tống số trang
         */
        $this->data['total_page'] = ceil($this->data['total_articles']/$this->data['num_per_page']);
        /**
         * Nếu trang hiện tại vượt quá tổng số trang thì chuyển đến trang đầu
         */
        if ($this->data['current_page'] > $this->data['total_page'] && $this->data['total_articles'] > 0)
        {
            Helper::redirect('author/'.$id.'/'.$this->data['user']['name']);
        }
        /**
         * Danh sách các bài viết của người dùng
         */
        $this->data['articles'] = $this->model->article_m->list_articles_by_author($id, $this->data['num_per_page'], $this->data['current_page']);
        /**
         * Kích hoạt navigation
         */
        $this->data['navi'] = TRUE;
        $this->data['nav_pages'] = parent::get_nav_pages();
        /**
         * Kích hoạt sidebar
         */
        $this->data['sidebar'] = TRUE;
        $this->data['sidebar_categories'] = parent::get_sidebar_categories();
        /**
         * Hiển thị nội dung footer
         */
        $this->data['tail'] = TRUE;
        /**
         * Gọi view
         */
        $this->data['title'] = 'Tất cả bài viết của "'.$this->data['user']['name'].'"';
        $this->view->load('author/detail', $this->data);
    }
}
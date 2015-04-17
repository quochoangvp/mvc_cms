<?php

/**
 * Quản lý hiển thị thể loại
 */
class Category extends Frontend_Controller {

    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model làm việc với bảng articles
         */
        $this->model->load('article_m');
        /**
         * Gọi model làm việc với bảng categories
         */
        $this->model->load('category_m');
    }

    /**
     * Các bài viết trong thể loại này
     * @param  int  $id   ID của thể loại
     * @param  string  $slug Đường dẫn của thể loại
     * @param  integer $page Trang hiện thị hiện tại
     */
    public function detail($id, $slug, $page = 1)
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
         * Lấy chi tiết thể loại theo ID
         */
        $this->data['category'] = $this->model->category_m->category_detail($id);
        /**
         * Nếu không có thể loại nào có ID như vậy thì chuyến đến trang chủ
         */
        if (count($this->data['category']) < 1)
        {
            Helper::redirect();
        }

        /**
         * Nếu đường dẫn không đúng thì chuyển hướng đến đường dẫn đúng
         */
        if ($slug != $this->data['category']['slug'])
        {
            Helper::redirect('category/'.$id.'/'.$this->data['category']['slug'], '301');
        }
        /**
         * Tổng số bài viết của thể loại
         */
        $this->data['total_articles'] = count($this->model->article_m->list_articles_by_category($id, $this->data['num_per_page']));
        /**
         * Tổng số trang
         */
        $this->data['total_page'] = ceil($this->data['total_articles']/$this->data['num_per_page']);
        /**
         * Nếu trang hiện tại lớn hơn tổng số trang thì chuyến đến trang 1
         */
        if ($this->data['current_page'] > $this->data['total_page'] && $this->data['total_articles'] > 0)
        {
            Helper::redirect('category/'.$id.'/'.$this->data['category']['slug']);
        }
        /**
         * Danh sách các bài viết của thể loại
         */
        $this->data['articles'] = $this->model->article_m->list_articles_by_category($id, $this->data['num_per_page'], $this->data['current_page']);
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
        $this->data['title'] = 'Tất cả bài viết cho "'.$this->data['category']['title'].'"';
        $this->view->load('category/detail', $this->data);
    }
}
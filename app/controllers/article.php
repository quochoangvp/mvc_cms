<?php

/**
 * Quản lý trang chi tiết bài viết
 */
class Article extends Frontend_Controller {

    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model làm việc mới CSDL của bài viết
         */
        $this->model->load('article_m');
    }

    /**
     * Chi tiết bài viết
     * @param  int $id   ID của bài viết
     * @param  string $slug Đường dẫn của bài viết
     */
    public function detail($id, $slug)
    {
        /**
         * Nếu $id không phải dạng số nguyên dương thì chuyển đến trang chủ
         */
        if ( ! is_numeric($id) || (int) $id <= 0)
        {
            Helper::redirect();
        }
        /**
         * Lấy dữ liệu bài viết từ model
         */
        $this->data['articles'] = $this->model->article_m->article_detail_with_category($id);
        /**
         * Nếu không tồn tại bài viết thì chuyển đến trang chủ
         */
        if (count($this->data['articles']) < 1)
        {
            Helper::redirect();
        }
        /**
         * Nếu đường dẫn không đúng thì chuyển hướng đến đường dẫn đúng
         */
        if ($slug != $this->data['articles'][0]['slug'])
        {
            Helper::redirect('article/'.$id.'/'.$this->data['articles'][0]['slug'], '301');
        }
        /**
         * Kích hoạt mã captcha cho bình luận
         */
        parent::get_captcha();
        /**
         * Hiển thị mã captcha
         */
        $this->data['recaptcha'] = $this->recaptcha->get_html($this->error);

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
        $this->data['title'] = $this->data['articles'][0]['title'];
        $this->view->load('article/detail', $this->data);
    }
}
<?php

/**
 * Quản lý trang chi tiết
 */
class Page extends Frontend_Controller {

	function __construct() {
		parent::__construct();
		/**
		 * Gọi model làm việc với bảng `pages`
		 */
		$this->model->load('page_m');
	}

	/**
	 * Chi tiết của trang
	 * @param  string $slug Đường dẫn của trang
	 */
	public function detail($slug) {
		/**
		 * Lấy chi tiết bài viết từ model
		 */
		$this->data['page'] = $this->model->page_m->page_detail($slug, 'slug');
		/**
		 * Nếu không tồn tại bài viết thì chuyển đến trang chủ
		 */
		if (count($this->data['page']) < 1) {
			Helper::redirect();
		}

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
		$this->data['title'] = $this->data['page']['title'];
		$this->view->load('page/detail', $this->data);
	}
}
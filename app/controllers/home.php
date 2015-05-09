<?php

class Home extends Frontend_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Trang chính của controller home
	 */
	public function index() {
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
		$this->data['title'] = 'Trang chủ';
		$this->view->load('home/index', $this->data);
	}

	/**
	 * Danh sách bài viết mới nhất
	 * @param  integer $page Trang hiện tại
	 */
	public function lastest_articles($page = 1) {
		/**
		 * Số bài viết trên trang
		 * @var integer
		 */
		$number_per_page = 3;
		/**
		 * GỌi model làm việc với bảng `article`
		 */
		$this->model->load('article_m');

		/**
		 * Danh sách bài viết mới nhất từ model
		 */
		$this->data['lastest_articles'] = $this->model->article_m->homepage_articles($number_per_page, $page);
		/**
		 * Gọi view
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('home/articles_ajax', $this->data);
	}
}
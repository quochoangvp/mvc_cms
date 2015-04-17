<?php

/**
 * Controller chính quản lý các trang Admin
 */
class Admin_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();

		/**
		 * Kiểm tra xem người dùng có phải admin không
		 */
		Helper::admin_check();
	}
}
<?php

/**
 * Controller User
 */
class User extends Frontend_Controller {

	function __construct() {
		parent::__construct();

		/**
		 * Gọi model để thao tác với CSDL
		 */
		$this->model->load('user_m');
	}

	/**
	 * Trang chính của controller User
	 */
	public function index() {
		$this->profile();
	}

	/**
	 * Trang đăng nhập
	 */
	public function login() {
		/**
		 * Kiểm tra người dùng đã đăng nhập chưa
		 */
		Helper::is_logged();
		/**
		 * Load style login
		 */
		$this->data['login'] = TRUE;
		/**
		 * Load login view
		 */
		$this->data['title'] = 'Đăng nhập';
		$this->view->load('user/login', $this->data);
	}

	/**
	 * Đăng nhập khi người dùng submit form
	 * AJAX
	 */
	public function login_submit() {
		/**
		 * Dữ liệu lấy từ model
		 */
		$result = $this->model->user_m->login($_POST['user_data']);

		/**
		 * Nếu có kết quả trong CSDL, tiến hành đăng nhập
		 */
		if ($result) {
			/**
			 * Thêm thông tin đăng nhập vào phiên làm việc
			 */
			Session::set('uid', $result['id']);
			Session::set('uname', $result['name']);
			Session::set('fullname', $result['fullname']);
			Session::set('uemail', $result['email']);
			Session::set('ulevel', $result['level']);

			/**
			 * Gán dữ liệu vào biến cho view
			 */
			$this->data['status'] = 'success';

			/**
			 * Nếu là admin sẽ chuyển đến Bảng điều khiển
			 */
			if (Helper::is_admin()) {
				$this->data['url'] = 'admin/dashboard';
			}
			/**
			 * Nếu không là admin sẽ chuyển đến trang chủ
			 */
			else {
				$this->data['url'] = '';
			}

			$this->data['msg'] = Helper::message('p', 'success', 'Đăng nhập thành công!');
		}
		/**
		 * Không có kết quả từ CSDL, thông báo lỗi
		 */
		else {
			$this->data = array(
				'status' => 'error',
				'url' => '',
				'msg' => Helper::message('p', 'error', 'Sai tên tài khoản hoặc mật khẩu. Hãy thử lại!'),
			);
		}
		/**
		 * Gọi view hiển thi dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Đăng xuất người dùng
	 */
	public function logout() {
		/**
		 * Nếu có thông tin người dùng từ phiên làm việc, đăng xuất
		 */
		if (Session::get('uid')) {
			Session::destroy();
			Helper::redirect();
		}
		/**
		 * Không có thông tin từ phiên làm việc, chuyển đến trang đăng nhập
		 */
		else {
			Helper::redirect('user/login');
		}
	}

	/**
	 * Trang đăng ký
	 */
	public function register() {
		/**
		 * Kiểm tra người dùng đã đăng nhập chưa
		 */
		Helper::is_logged();
		/**
		 * Load style login
		 */
		$this->data['login'] = TRUE;
		/**
		 * Gọi view
		 */
		$this->data['title'] = 'Đăng ký';
		$this->view->load('user/register', $this->data);
	}

	/**
	 * Kiểm tra tên người dùng
	 * AJAX
	 */
	public function username_check() {
		/**
		 * Lấy dữ liệu từ model
		 * @var boolean
		 */
		$result = $this->model->user_m->check_data_exists('name', $_POST['data']);

		/**
		 * Nếu Username có độ dài nhỏ hơn 2 thì thông báo lỗi
		 */
		if (strlen($_POST['data']) < 2) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Tên người dùng phải có độ dài hơn 1'),
			);
		} else {
			/**
			 * Kiểm tra Username có đúng định dạng không
			 */
			if (Helper::vali_username($_POST['data'])) {
				/**
				 * Kiểm tra username đã có trong hệ thống chưa
				 */
				if ($result) {
					$this->data = array(
						'status' => 'error',
						'msg' => Helper::message('p', 'error', 'Tên này đã có người đăng ký'),
					);
				} else {
					$this->data = array(
						'status' => 'success',
						'msg' => '',
					);
				}
			}
			/**
			 * Username không đúng định dạng, thông báo lỗi
			 */
			else {
				$this->data = array(
					'status' => 'error',
					'msg' => Helper::message('p', 'error', 'Tên người dùng chỉ gồm các ký tự [a-z|A-Z]'),
				);
			}

		}
		/**
		 * Gọi view hiển thị dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Kiểm tra email của người dùng
	 * AJAX
	 */
	public function email_check() {
		/**
		 * Lấy dữ liệu từ model
		 */
		$result = $this->model->user_m->check_data_exists('email', $_POST['data']);

		/**
		 * Nếu email có độ dài nhỏ hơn 5 thì thông báo lỗi
		 */
		if (strlen($_POST['data']) < 5) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Email phải có độ dài hơn 5'),
			);
		} else {
			/**
			 * Kiểm tra Email có đúng định dạng không
			 */
			if (Helper::vali_email($_POST['data'])) {
				/**
				 * Kiểm tra email đã có trong hệ thống chưa
				 */
				if ($result) {
					$this->data = array(
						'status' => 'error',
						'msg' => Helper::message('p', 'error', 'Email này đã có người đăng ký'),
					);
				} else {
					$this->data = array(
						'status' => 'success',
						'msg' => '',
					);
				}
			}
			/**
			 * Email không đúng định dạng, thông báo lỗi
			 */
			else {
				$this->data = array(
					'status' => 'error',
					'msg' => Helper::message('p', 'error', 'Email không đúng định dạng, hãy nhập lại'),
				);
			}

		}
		/**
		 * Gọi view hiển thị dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Đăng ký khi người dùng submit form
	 * AJAX
	 */
	public function register_submit() {
		/**
		 * Nếu dữ liệu được nhập đủ và không tồn tại dữ liệu thì đăng ký
		 */
		if (!empty($_POST['data']['fullname']) && !empty($_POST['data']['password'])) {
			if (!$this->model->user_m->check_data_exists('email', $_POST['data']['email']) &&
				!$this->model->user_m->check_data_exists('name', $_POST['data']['username'])) {
				$result = $this->model->user_m->register($_POST['data']);
			}
		}
		/**
		 * Dữ liệu nhập vào trống
		 */
		else {
			$this->dataEmpty = TRUE;
		}
		/**
		 * Kiểm tra kết quả nhận từ model
		 */
		if (isset($result)) {
			$this->data = array(
				'status' => 'success',
				'msg' => Helper::message('p', 'success', 'Đăng ký thành công!'),
			);
		} elseif ($this->dataEmpty === TRUE) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Hãy nhập đủ các trường yêu cầu!'),
			);
		} else {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Có lỗi xảy ra, xin kiểm tra lại!'),
			);
		}
		/**
		 * Gọi view hiển thị dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Trang hồ sơ người dùng
	 */
	public function profile() {
		/**
		 * Xem trang cá nhân của thành viên khác
		 */
		if (isset($GLOBALS['rz'][2])) {
			/**
			 * Nếu tồn tại thành viên có $id = $GLOBALS['rz'][2]
			 */
			$uid = (int) $GLOBALS['rz'][2];
			if ($this->model->user_m->check_data_exists('id', $uid)) {
				$id = $uid;
			}
			/**
			 * Không tồn tại người dùng này sẽ chuyển đến trang cá nhân của người xem
			 */
			else {
				Helper::redirect('user/profile');
			}
		}
		/**
		 * Xem trang cá nhân của bản thân
		 */
		else {
			/**
			 * Kiểm tra người xem đã đăng nhập chưa
			 */
			if (Session::get('uid')) {
				$id = (int) Session::get('uid');
			} else {
				Helper::redirect();
			}

		}

		/**
		 * Lấy thông tin từ model
		 */
		$this->data['udata'] = $this->model->user_m->user_detail($id);
		/**
		 * Nếu không tồn tại người dùng thì chuyển hướng
		 */
		if (!$this->data['udata']) {
			Helper::redirect();
		}
		$this->model->load('article_m');
		/**
		 * Danh sách các bài viết của người dùng
		 */
		$this->data['articles'] = $this->model->article_m->list_articles_by_author($id, 5, 1);
		/**
		 * Kích hoạt navigation
		 */
		$this->data['navi'] = TRUE;
		$this->data['nav_pages'] = parent::get_nav_pages();
		/**
		 * Hiển thị nội dung footer
		 */
		$this->data['tail'] = TRUE;
		/**
		 * Gọi view
		 */
		$this->data['title'] = $this->data['udata']['name'] . '\' profile';
		$this->view->load('user/profile', $this->data);
	}

	/**
	 * Kiểm tra email này đã tồn tại trong CSLD chưa
	 * (Không kiểm tra email của người dùng hiện tại)
	 * AJAX
	 */
	public function email_check_not_current() {
		/**
		 * Lấy dữ liệu từ model
		 */
		$result = $this->model->user_m->check_data_exists_not_current('email', $_POST['data']);

		/**
		 * Nếu email có độ dài nhỏ hơn 5 thì thông báo lỗi
		 */
		if (strlen($_POST['data']['data']) < 5) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Email phải có độ dài hơn 5'),
			);
		} else {
			/**
			 * Kiểm tra Email có đúng định dạng không
			 */
			if (Helper::vali_email($_POST['data']['data'])) {
				/**
				 * Kiểm tra email đã có trong hệ thống chưa
				 */
				if ($result) {
					$this->data = array(
						'status' => 'error',
						'msg' => Helper::message('p', 'error', 'Email này đã có người đăng ký'),
					);
				} else {
					$this->data = array(
						'status' => 'success',
						'msg' => '',
					);
				}
			}
			/**
			 * Email không đúng định dạng, thông báo lỗi
			 */
			else {
				$this->data = array(
					'status' => 'error',
					'msg' => Helper::message('p', 'error', 'Email không đúng định dạng, hãy nhập lại'),
				);
			}

		}
		/**
		 * Gọi view hiển thị dữ liệu từ model
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Sửa hồ sơ khi người dùng submit form
	 * AJAX
	 */
	public function edit_profile_submit() {
		/**
		 * Email trống, thông báo lỗi
		 */
		if (empty($_POST['data']['data'])) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Hãy nhập email!'),
			);
		} else {
			/**
			 * Nếu email chưa có người khác dùng thì truyền dữ liệu cho model
			 */
			if (!$this->model->user_m->check_data_exists_not_current('email', $_POST['data'])) {
				$result = $this->model->user_m->edit_profile($_POST['data']);
				/**
				 * Kiểm tra kết quả nhận được từ model
				 */
				if ($result) {
					$this->data = array(
						'status' => 'success',
						'msg' => Helper::message('p', 'success', 'Sửa thành công!'),
					);
				} else {
					$this->data = array(
						'status' => 'error',
						'msg' => Helper::message('p', 'error', 'Sửa thất bại, hãy thử lại!'),
					);
				}
			} else {
				$this->data = array(
					'status' => 'error',
					'msg' => Helper::message('p', 'error', 'Email đã tồn tại trong hệ thống!'),
				);
			}
		}
		/**
		 * Gọi view hiển thị dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Sửa thông tin bản thân khi người dùng submit form
	 * AJAX
	 */
	public function edit_bio_submit() {
		/**
		 * Nếu dữ liệu trống, thông báo lỗi
		 */
		if (empty($_POST['bio'])) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Hãy nhập ghi chú của bạn!'),
			);
		} else {
			/**
			 * Gửi dữ liệu từ form qua model
			 */
			$result = $this->model->user_m->edit_bio($_POST['bio']);
			/**
			 * Kiểm tra kết quả nhận được từ model
			 */
			if ($result) {
				$this->data = array(
					'status' => 'success',
					'msg' => Helper::message('p', 'success', 'Sửa thành công!'),
				);
			} else {
				$this->data = array(
					'status' => 'error',
					'msg' => Helper::message('p', 'error', 'Sửa thất bại, hãy thử lại!'),
				);
			}
		}
		/**
		 * Gọi view hiển thị dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Kiểm tra mật khẩu hiện tại
	 * AJAX
	 */
	public function check_current_password() {
		/**
		 * Nếu dữ liệu trống, thông báo lỗi
		 */
		if (empty($_POST['current_password'])) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('p', 'error', 'Hãy nhập mật khẩu hiện tại!'),
				'eid' => 'cur_pw_msg',
			);
		} else {
			/**
			 * Lấy dữ liệu từ model
			 */
			$current_password = $this->model->user_m->current_password(Session::get('uid'));

			if (Helper::hash($_POST['current_password']) == $current_password) {
				$this->data = array(
					'status' => 'success',
					'msg' => '',
				);
			} else {
				$this->data = array(
					'status' => 'error',
					'msg' => Helper::message('span', 'error', 'Sai mật khẩu hiện tại!'),
				);
			}
		}
		/**
		 * Gọi view hiển thị dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}

	/**
	 * Đổi mật khẩu
	 * AJAX
	 */
	public function change_password() {
		/**
		 * Nếu dữ liệu trống, thông báo lỗi
		 */
		if (empty($_POST['password']['current_password'])) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('span', 'error', 'Hãy nhập mật khẩu hiện tại!'),
				'eid' => 'cur_pw_msg',
			);
		} elseif (empty($_POST['password']['new_password'])) {
			$this->data = array(
				'status' => 'error',
				'msg' => Helper::message('span', 'error', 'Hãy nhập mật khẩu mới!'),
				'eid' => 'new_pw_msg',
			);
		} else {
			/**
			 * Gửi dữ liệu từ form qua model
			 */
			$result = $this->model->user_m->change_password($_POST['password']);
			/**
			 * Kiểm tra kết quả nhận được từ model
			 */
			if ($result) {
				$this->data = array(
					'status' => 'success',
					'msg' => Helper::message('span', 'success', 'Sửa thành công!'),
				);
			} else {
				$this->data = array(
					'status' => 'error',
					'msg' => Helper::message('span', 'error', 'Sửa thất bại, hãy thử lại!'),
				);
			}
		}
		/**
		 * Gọi view hiển thị dữ liệu json
		 */
		$this->data['text_only'] = TRUE;
		$this->view->load('json/messages', $this->data);
	}
}

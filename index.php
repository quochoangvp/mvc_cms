<?php
/**
 * Cài đặt múi giờ +7
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');

/**
 * Cài đặt hiển thị thông báo lỗi
 */
if (dirname(__FILE__) == '/media/MultiMedia/Dropbox/xampp/htdocs/mvc_cms') {
	/**
	 * Thông báo lỗi nếu ứng dụng ở đường dẫn /media/MultiMedia/Dropbox/xampp/htdocs/mvc_cms
	 */
	error_reporting(E_ALL);
} else {
	/**
	 * Không thông báo lỗi khi ứng dụng ở các đường dẫn khác
	 */
	error_reporting(0);
}

/**
 * Định nghĩa dấu gạch đường dẫn trong hệ thống
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Định nghĩa đường dẫn tuyệt đối của project
 */
define('ROOTPATH', dirname(__FILE__) . DS);

/**
 * Định nghĩa đường dẫn của thư mục app/
 */
define('APPPATH', ROOTPATH . 'app' . DS);

/**
 * Định nghĩa đường dẫn thư mục controllers/
 */
define('CONTROLLERPATH', APPPATH . 'controllers' . DS);

/**
 * Định nghĩa đường dẫn thư mục models/
 */
define('MODELPATH', APPPATH . 'models' . DS);

/**
 * Định nghĩa đường dẫn thư mục views/
 */
define('VIEWPATH', APPPATH . 'views' . DS);

/**
 * Định nghĩa đường dẫn thư mục libs
 */
define('LIBSPATH', APPPATH . 'libs' . DS);

/**
 * Định nghĩa địa chỉ tuyệt đối
 */
define('BASE_URL', 'http://localhost/mvc_cms/');

/**
 * Định nghĩa địa chỉ thư mục quản trị
 */
define('ADM_URL', BASE_URL . 'admin/');

/**
 * Định nghĩa đường dẫn đến thư mục css
 */
define('CSS_URL', BASE_URL . 'public/css/');

/**
 * Định nghĩa đường dẫn đến thư mục image
 */
define('IMG_URL', BASE_URL . 'public/img/');

/**
 * Định nghĩa đường dẫn đến thư mục javascript
 */
define('JS_URL', BASE_URL . 'public/js/');

/**
 * Controller mặt định được gọi
 */
define('DEFAUT_CONTROLLER', 'home');

/**
 * Random string
 */
define('HASH_KEY', 'sBuXLAshcaiVL1v12EzgO6wWrAGPq4RaM2TfWLbq');

/**
 * Racaptcha
 */
define('PUBLICKEY', '6LcR0AMTAAAAAEw1ANL5Cn7PDlLcLtAqDO-mGsyx');
define('PRIVATEKEY', '6LcR0AMTAAAAAIdA4sGVTOL_ln3a8Zi78TL65SZ9');

/**
 * Thiết lập thông số CSDL
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '12345');
define('DB_TABLE', 'db_cms');

/**
 * Tự động load các file có tên class tương ứng được gọi
 * @param  string $className Tên class
 */
function __autoload($className) {
	$path = LIBSPATH . strtolower($className) . '.php';

	if (file_exists($path) && is_file($path)) {
		require_once $path;
	}
}

/**
 * Gọi thư viện Autoload để chạy ứng dụng
 * @var Autoload
 */
$app = new Autoload();

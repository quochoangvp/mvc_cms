<?php

/**
 * Quản lý các thể loại
 */
class Category extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model quản lý thể loại
         */
        $this->model->load('category_m');
    }

    /**
     * Trang chính của controller category
     */
    public function index()
    {
        /**
         * Kích hoạt navigation
         */
        $this->data['navi'] = TRUE;
        /**
         * Kích hoạt sidebar
         */
        $this->data['sidebar'] = TRUE;
        /**
         * Kích hoạt plugin sắp xếp bảng
         */
        $this->data['sortable'] = TRUE;
        /**
         * Gọi view
         */
        $this->data['title'] = 'Quản lý thể loại';
        $this->view->load('admin/category/index', $this->data);
    }

    /**
     * Dang sách thể loại
     */
    public function list_categories()
    {
        /**
         * Lấy dữ liệu các thể loại từ model
         */
        $this->data['categories'] = $this->model->category_m->list_categories_with_parent();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/category/list_categories', $this->data);
    }

    /**
     * Thêm thể loại vào CSDL
     */
    public function add()
    {
        /**
         * Kích hoạt navigation
         */
        $this->data['navi'] = TRUE;
        /**
         * Kích hoạt sidebar
         */
        $this->data['sidebar'] = TRUE;
        /**
         * Dữ liệu tất cả thể loại mẹ từ model
         */
        $this->data['list_parent_categories'] = $this->model->category_m->list_parent_categories();
        /**
         * Gọi view
         */
        $this->data['title'] = 'Thêm thể loại mới';
        $this->view->load('admin/category/add', $this->data);
    }

    /**
     * Kiểm tra tiêu đề của thể loại
     * AJAX
     */
    public function title_check()
    {
        /**
         * Nếu Tiêu đề có độ dài nhỏ hơn 2 thì thông báo lỗi
         */
        if (strlen($_POST['data']) < 2)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('span', 'error', 'Tiêu đề phải có độ dài hơn 1'),
            );
        }
        else
        {
            /**
             * Lấy dữ liệu từ model
             * @var boolean
             */
            $result = $this->model->category_m->check_data_exists('title', $_POST['data']);
            /**
             * Kiểm tra Tiêu đề đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'Thể loại này đã có trên hệ thống'),
                );
            }
            else
            {
                $this->data = array(
                    'status' => 'success',
                    'msg'    => '',
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
     * Kiểm tra đường dẫn của thể loại
     * AJAX
     */
    public function slug_check()
    {
        /**
         * Nếu Tiêu đề có độ dài nhỏ hơn 2 thì thông báo lỗi
         */
        if (strlen($_POST['data']) < 2)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('span', 'error', 'Đường liên kết phải có độ dài hơn 1'),
            );
        }
        else
        {
            /**
             * Lấy dữ liệu từ model
             * @var boolean
             */
            $result = $this->model->category_m->check_data_exists('slug', $_POST['data']);
            /**
             * Kiểm tra Đường liên kết đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'Đường liên kết này đã có trên hệ thống'),
                );
            }
            else
            {
                $this->data = array(
                    'status' => 'success',
                    'msg'    => '',
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
     * Thêm dữ liệu vào CSDL khi người dùng bấm nút Lưu
     * AJAX
     */
    public function add_submit()
    {
        /**
         * Kiểm tra dữ liệu đầu vào trống hay không
         */
        if ( ! empty($_POST['data']['title']) &&  ! empty($_POST['data']['slug']))
        {
            /**
             * Nếu tiêu đề và đường liên kết chưa có trong hệ thống thì chèn dữ liệu
             */
            if ( ! $this->model->category_m->check_data_exists('title', $_POST['data']['title']) &&
                 ! $this->model->category_m->check_data_exists('slug', $_POST['data']['slug']))
            {
                $result = $this->model->category_m->add_category($_POST['data']);
            }

        }
        /**
         * Dữ liệu đầu vào trống
         */
        else
        {
            $this->dataEmpty = TRUE;
        }
        /**
         * Chèn dữ liệu thành công
         */
        if (isset($result))
        {
            $this->data = array(
                'status' => 'success',
                'msg'    => Helper::message('p', 'success', 'Thêm thể loại thành công!'),
            );
            /**
             * Lấy id của thể loại vừa chèn vào
             * @var int
             */
            $cat_id = $this->model->category_m->category_detail($_POST['data']['slug'], 'slug')['id'];

            if ($cat_id)
            {
                $this->data['id'] = $cat_id;
            }
        }
        /**
         * Nếu dữ liệu đầu vào trống, thông báo lỗi
         */
        elseif (isset($this->dataEmpty) && $this->dataEmpty === TRUE)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('p', 'error', 'Hãy nhập đủ các trường yêu cầu!'),
            );
        }
        /**
         * Có lỗi khác
         */
        else
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('p', 'error', 'Có lỗi xảy ra, xin kiểm tra lại!'),
            );
        }
        /**
         * Gọi view hiển thị dữ liệu json
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('json/messages', $this->data);
    }

    /**
     * Xóa thể loại
     * AJAX
     */
    public function delete_ajax()
    {
        /**
         * Gọi kết quả xóa thể loại từ model
         * @var boolean
         */
        $result = $this->model->category_m->delete((int) $_POST['id']);
        /**
         * Báo thành công hay không
         */
        if ($result)
        {
            $this->data['message'] = Helper::message('p', 'success', 'Xóa thành công!');
        }
        else
        {
            $this->data['message'] = Helper::message('p', 'success', 'Xóa thất bại, hãy thử lại!');
        }
        /**
         * Gọi model danh sách người dùng
         */
        $this->data['categories'] = $this->model->category_m->list_categories_with_parent();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/category/list_categories', $this->data);
    }

    /**
     * Sửa thể loại của ID = $id
     * @param  int $id ID của thể loại
     */
    public function edit($id)
    {
        /**
         * Nếu đường dẫn nhập vào không có $id thì chuyển đến trang thêm mới
         */
        if ( ! isset($id))
        {
            Helper::redirect('admin/category/add');
        }
        /**
         * Dữ liệu chi tiết thể loại và các thể loại mẹ từ model
         */
        $this->data['category_detail'] = $this->model->category_m->category_detail($id);
        $this->data['list_parent_categories'] = $this->model->category_m->list_parent_categories();
        /**
         * Nếu trang này không tồn tại, chuyển đến trang danh sách
         */
        if ( ! $this->data['category_detail'])
        {
            Helper::redirect('admin/category/index');
        }
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
        $this->data['title'] = 'Sửa thể loại: '.$this->data['category_detail']['title'];
        $this->view->load('admin/category/edit', $this->data);
    }

    /**
     * Kiểm tra tiêu đề của thể loại đang sửa
     * AJAX
     */
    public function title_check_not_current()
    {
        /**
         * Nếu Tiêu đề có độ dài nhỏ hơn 2 thì thông báo lỗi
         */
        if (strlen($_POST['data']['data']) < 2)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('span', 'error', 'Tiêu đề phải có độ dài hơn 1'),
            );
        }
        else
        {
            /**
             * Lấy dữ liệu từ model
             * @var boolean
             */
            $result = $this->model->category_m->check_data_exists_not_current('title', $_POST['data']);
            /**
             * Kiểm tra Tiêu đề đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'Thể loại này đã có trên hệ thống'),
                );
            }
            else
            {
                $this->data = array(
                    'status' => 'success',
                    'msg'    => '',
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
     * Kiểm tra đường dẫn của thể loại đang sửa
     * AJAX
     */
    public function slug_check_not_current()
    {
        /**
         * Nếu đường liên kết có độ dài nhỏ hơn 2 thì thông báo lỗi
         */
        if (strlen($_POST['data']['data']) < 2)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('span', 'error', 'Đường liên kết phải có độ dài hơn 1'),
            );
        }
        else
        {
            /**
             * Lấy dữ liệu từ model
             * @var boolean
             */
            $result = $this->model->category_m->check_data_exists_not_current('slug', $_POST['data']);
            /**
             * Kiểm tra đường liên kết đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'Đường liên kết này đã có trên hệ thống'),
                );
            }
            else
            {
                $this->data = array(
                    'status' => 'success',
                    'msg'    => '',
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
     * Lưu dữ liệu khi người dùng submit form
     * AJAX
     */
    public function edit_submit()
    {
        /**
         * Gọi model và truyền dữ liệu cho nó
         */
        $result = $this->model->category_m->edit_category($_POST['data']);
        /**
         * Kiểm tra kết quả trả về từ model
         */
        if ($result)
        {
            $this->data = array(
                'status' => 'success',
                'msg'    => Helper::message('p', 'success', 'Cập nhật thành công!'),
            );
        }
        else
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('p', 'error', 'Cập nhật thất bại, hãy thử lại!'),
            );
        }
        /**
         * Gọi view hiển thị dữ liệu json
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('json/messages', $this->data);
    }

    /**
     * Sắp xếp thể loại
     */
    public function order()
    {
        /**
         * Kích hoạt navigation
         */
        $this->data['navi'] = TRUE;
        /**
         * Kích hoạt sidebar
         */
        $this->data['sidebar'] = TRUE;
        /**
         * Kích hoạt nested sortable
         */
        $this->data['nested'] = TRUE;
        /**
         * Gọi view
         */
        $this->data['title'] = 'Sắp xếp thể loại';
        $this->view->load('admin/category/order', $this->data);
    }

    /**
     * Hiển thị thể loại đã được sắp xếp theo thứ tự
     * AJAX
     */
    public function order_ajax()
    {
        /**
         * Nếu có dữ liệu được gửi thì lưu vào CSDL
         */
        if (isset($_POST['categories']))
        {
            $this->model->category_m->save_order($_POST['categories']);
        }
        /**
         * Lấy danh sách thể loại
         */
        $this->data['categories'] = $this->model->category_m->list_categories();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/category/order_ajax', $this->data);
    }
}
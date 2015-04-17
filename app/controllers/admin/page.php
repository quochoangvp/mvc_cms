<?php

/**
 * Quản lý các trang tĩnh
 */
class Page extends Admin_Controller {
    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model quản lý trang
         */
        $this->model->load('page_m');
    }

    /**
     * Trang chính của controller page
     */
    public function index()
    {
        /**
         * Kích hoạt plugin sắp xếp bảng
         */
        $this->data['sortable'] = TRUE;
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
        $this->data['title'] = 'Quản lý trang';
        $this->view->load('admin/page/index', $this->data);
    }

    /**
     * Danh sách các trang
     */
    public function list_pages()
    {
        /**
         * Danh sách trang với trang mẹ từ model
         */
        $this->data['pages'] = $this->model->page_m->list_pages_with_parent();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/page/list_pages', $this->data);
    }

    /**
     * Thêm trang
     */
    public function add()
    {
        /**
         * Danh sách các trang mẹ
         */
        $this->data['list_parent_pages'] = $this->model->page_m->list_parent_pages();
        /**
         * Kích hoạt TinyMCE plugin
         */
        $this->data['tinymce'] = TRUE;
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
        $this->data['title'] = 'Thêm trang mới';
        $this->view->load('admin/page/add', $this->data);
    }

    /**
     * Kiểm tra tiêu đề khi thêm trang
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
            $result = $this->model->page_m->check_data_exists('title', $_POST['data']);
            /**
             * Kiểm tra Tiêu đề đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'Trang này đã có trên hệ thống'),
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
     * Kiểm tra đường dẫn khi thêm trang
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
             */
            $result = $this->model->page_m->check_data_exists('slug', $_POST['data']);
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
     * Thêm dữ liệu vào CSDL khi người dùng submit form
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
            if ( ! $this->model->page_m->check_data_exists('title', $_POST['data']['title']) &&
                 ! $this->model->page_m->check_data_exists('slug', $_POST['data']['slug']))
            {
                $result = $this->model->page_m->add_page($_POST['data']);
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
                'msg'    => Helper::message('p', 'success', 'Thêm trang thành công!'),
            );
            /**
             * Lấy id của trang vừa chèn vào
             * @var int
             */
            $page_id = $this->model->page_m->page_detail($_POST['data']['slug'], 'slug')['id'];

            if ($page_id)
            {
                $this->data['id'] = $page_id;
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
     * Sửa trang
     * @param  int $id ID của trang
     */
    public function edit($id)
    {
        /**
         * Nếu đường dẫn nhập vào không có $id thì chuyển đến trang thêm mới
         */
        if ( ! isset($id))
        {
            Helper::redirect('admin/page/add');
        }
        /**
         * Lấy dữ liệu từ model
         */
        $this->data['page_detail'] = $this->model->page_m->page_detail($id);
        $this->data['list_parent_pages'] = $this->model->page_m->list_parent_pages();
        /**
         * Nếu không tồn tại trang này thì chuyển hướng đến trang danh sách
         */
        if ( ! $this->data['page_detail'])
        {
            Helper::redirect('admin/page/index');
        }
        /**
         * Kích hoạt TinyMCE plugin
         */
        $this->data['tinymce'] = TRUE;
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
        $this->data['title'] = 'Sửa trang: '.$this->data['page_detail']['title'];
        $this->view->load('admin/page/edit', $this->data);
    }

    /**
     * Kiểm tra tiêu đề của trang đang sửa
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
            $result = $this->model->page_m->check_data_exists_not_current('title', $_POST['data']);
            /**
             * Kiểm tra Tiêu đề đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'Trang này đã có trên hệ thống'),
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
     * Kiểm tra đường dẫn của trang đang sửa
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
            $result = $this->model->page_m->check_data_exists_not_current('slug', $_POST['data']);
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
         * Gửi dữ liệu qua model và nhận lại kết quả
         */
        $result = $this->model->page_m->edit_page($_POST['data']);
        /**
         * Kiểm tra kết quả nhận được từ model
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
     * Xóa trang
     * AJAX
     */
    public function delete_ajax()
    {
        /**
         * Gọi kết quả xóa trang từ model
         * @var boolean
         */
        $result = $this->model->page_m->delete((int) $_POST['id']);
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
        $this->data['pages'] = $this->model->page_m->list_pages_with_parent();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/page/list_pages', $this->data);
    }

    /**
     * Sắp xếp trang
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
        $this->data['title'] = 'Sắp xếp trang';
        $this->view->load('admin/page/order', $this->data);
    }

    /**
     * Hiển thị danh sách trang đã được sắp xếp
     * AJAX
     */
    public function order_ajax()
    {
        /**
         * Nếu có dữ liệu được gửi qua thì lưu vào CSDL
         */
        if (isset($_POST['pages']))
        {
            $this->model->page_m->save_order($_POST['pages']);
        }
        /**
         * Lấy danh sách trang từ CSDL
         */
        $this->data['pages'] = $this->model->page_m->list_pages();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/page/order_ajax', $this->data);
    }
}
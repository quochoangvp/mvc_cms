<?php
/**
 * Quản lý người dùng
 */
class User extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model quản lý người dùng
         */
       $this->model->load('user_m');
    }

    /**
     * Trang chính của controller User
     */
    public function index()
    {
        /**
         * Kích hoạt chức năng sắp xếp bảng
         */
        $this->data['sortable'] = TRUE; /**
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
        $this->data['title'] = 'Quản lý người dùng';
        $this->view->load('admin/user/index', $this->data);
    }

    /**
     * Danh sách người dùng
     */
    public function list_users()
    {
        /**
         * Gọi model danh sách người dùng
         */
        $this->data['users'] = $this->model->user_m->list_users();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/user/list_users', $this->data);
    }

    /**
     * Thêm người dùng
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
         * Gọi view
         */
        $this->data['title'] = 'Thêm người dùng mới';
        $this->view->load('admin/user/add', $this->data);
    }

    /**
     * Thêm dữ liệu vào CSDL khi người dùng submit form
     * AJAX
     */
    public function add_submit()
    {
        /**
         * Nếu các trường yên cầu không trống và không tồn tại trong CSDL thì gọi model
         */
        if ( ! empty($_POST['data']['fullname']) &&  ! empty($_POST['data']['password']) &&  isset($_POST['data']['level']))
        {
            if ( ! $this->model->user_m->check_data_exists('email', $_POST['data']['email']) &&
                 ! $this->model->user_m->check_data_exists('name', $_POST['data']['username']))
            {
                $result = $this->model->user_m->add_user($_POST['data']);
            }

        }
        /**
         * Dữ liệu yêu cầu trống
         */
        else
        {
            $this->dataEmpty = TRUE;
        }

        /**
         * Kiểm tra kết quả trả về từ model
         */
        if (isset($result))
        {
            $this->data = array(
                'status' => 'success',
                'msg'    => Helper::message('p', 'success', 'Thêm người dùng thành công!'),
            );
        }
        elseif (isset($this->dataEmpty) && $this->dataEmpty === TRUE)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('p', 'error', 'Hãy nhập đủ các trường yêu cầu!'),
            );
        }
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
     * Xóa người dùng
     * AJAX
     */
    public function delete_ajax()
    {
        /**
         * Gọi kết quả xóa người dùng từ model
         * @var boolean
         */
        $result = $this->model->user_m->delete((int) $_POST['id']);
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
        $this->data['users'] = $this->model->user_m->list_users();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/user/list_users', $this->data);
    }

    /**
     * Sửa người dùng có ID = $id
     * @param  int $id ID của người dùng
     */
    public function edit($id)
    {
        /**
         * Nếu không có $id được nhập thì chuyển đến trang thêm
         */
        if ( ! isset($id))
        {
            Helper::redirect('admin/user/add');
        }
        /**
         * Lấy dữ liệu người dùng từ model
         */
        $this->data['udata'] = $this->model->user_m->user_detail((int) $id);
        /**
         * Nến người dùng không tồn tại, chuyển đến trang danh sách
         */
        if ( ! $this->data['udata'])
        {
            Helper::redirect('admin/user/index');
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
        $this->data['title'] = 'Sửa thông tin người dùng: '.$this->data['udata']['name'];
        $this->view->load('admin/user/edit', $this->data);
    }

    /**
     * Thêm dữ liệu vào CSDL khi người dùng submit form
     * AJAX
     */
    public function edit_submit()
    {
        /**
         * Truyền dữ liệu qua model và nhận lại kết quả
         */
        $result = $this->model->user_m->edit_user($_POST['data']);
        /**
         * Kiểm tra kết quả nhập được từ model
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
     * Kiểm tra tên người dùng
     * AJAX
     */
    public function username_check()
    {
        /**
         * Lấy dữ liệu từ model
         */
        $result = $this->model->user_m->check_data_exists_not_current('name', $_POST['data']);

        /**
         * Nếu Username có độ dài nhỏ hơn 2 thì thông báo lỗi
         */
        if (strlen($_POST['data']['data']) < 2)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('p', 'error', 'Tên người dùng phải có độ dài hơn 1'),
            );
        }
        else
        {
            /**
             * Kiểm tra Username có đúng định dạng không
             */
            if (Helper::vali_username($_POST['data']['data']))
            {
                /**
                 * Kiểm tra username đã có trong hệ thống chưa
                 */
                if ($result)
                {
                    $this->data = array(
                        'status' => 'error',
                        'msg'    => Helper::message('p', 'error', 'Tên này đã có người đăng ký'),
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
             * Username không đúng định dạng, thông báo lỗi
             */
            else
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('p', 'error', 'Tên người dùng chỉ gồm các ký tự [a-z|A-Z]'),
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
     * Kiểm tra email của người dùng
     * AJAX
     */
    public function email_check()
    {
        /**
         * Lấy dữ liệu từ model
         */
        $result = $this->model->user_m->check_data_exists_not_current('email', $_POST['data']);

        /**
         * Nếu email có độ dài nhỏ hơn 5 thì thông báo lỗi
         */
        if (strlen($_POST['data']['data']) < 5)
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('p', 'error', 'Email phải có độ dài hơn 5'),
            );
        }
        else
        {
            /**
             * Kiểm tra Email có đúng định dạng không
             */
            if (Helper::vali_email($_POST['data']['data']))
            {
                /**
                 * Kiểm tra email đã có trong hệ thống chưa
                 */
                if ($result)
                {
                    $this->data = array(
                        'status' => 'error',
                        'msg'    => Helper::message('p', 'error', 'Email này đã có người đăng ký'),
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
             * Email không đúng định dạng, thông báo lỗi
             */
            else
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('p', 'error', 'Email không đúng định dạng, hãy nhập lại'),
                );
            }

        }
        /**
         * Gọi view hiển thị dữ liệu từ model
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('json/messages', $this->data);
    }
}
<?php

/**
 * Quản lý các bài viết
 */
class Article extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model quản lý bài viết và thể loại
         */
        $this->model->load('article_m');
        $this->model->load('category_m');
    }

    /**
     * Trang chính của controller article
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
        $this->data['title'] = 'Quản lý bài viết';
        $this->view->load('admin/article/index', $this->data);
    }

    /**
     * Danh sách bài viết
     */
    public function list_articles()
    {
        /**
         * Lấy dữ liệu danh sách bào viết với thể loại từ model
         */
        $this->data['articles'] = $this->model->article_m->list_articles_with_category();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/article/list_articles', $this->data);
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
         * Lấy danh sách tất cả thể loại từ model
         */
        $this->data['list_categories'] = $this->model->category_m->list_categories();
        /**
         * Kích hoạt TinyMCE Plugin
         */
        $this->data['tinymce'] = TRUE;
        /**
         * Gọi view
         */
        $this->data['title'] = 'Thêm bài viết mới';
        $this->view->load('admin/article/add', $this->data);
    }

    /**
     * Kiểm tra tiêu đề của bài viết
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
            $result = $this->model->article_m->check_data_exists('title', $_POST['data']);
            /**
             * Kiểm tra Tiêu đề đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'Bài viết này đã có trên hệ thống'),
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
     * Kiểm tra đường dẫn của bài viết
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
            $result = $this->model->article_m->check_data_exists('slug', $_POST['data']);
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
     * Thêm bài viết vào CSDL khi người dùng bấm nút Lưu
     * AJAX
     */
    public function add_submit()
    {
        /**
         * Kiểm tra dữ liệu đầu vào trống hay không
         */
        if ( ! empty($_POST['data']['title']) &&  ! empty($_POST['data']['slug']) &&  ! empty($_POST['data']['cid']))
        {
            /**
             * Nếu tiêu đề và đường liên kết chưa có trong hệ thống thì chèn dữ liệu
             */
            if ( ! $this->model->article_m->check_data_exists('title', $_POST['data']['title']) &&
                 ! $this->model->article_m->check_data_exists('slug', $_POST['data']['slug']))
            {
                $result = $this->model->article_m->add_article($_POST['data']);
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
                'msg'    => Helper::message('p', 'success', 'Thêm bài viết thành công!'),
            );
            /**
             * Lấy id của bài viết vừa chèn vào
             * @var int
             */
            $cat_id = $this->model->article_m->article_detail($_POST['data']['slug'], 'slug')['id'];

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
     * Xóa bài viết
     * AJAX
     */
    public function delete_ajax()
    {
        /**
         * Gọi kết quả xóa bài viết từ model
         * @var boolean
         */
        $result = $this->model->article_m->delete($_POST['id']);
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
        $this->data['articles'] = $this->model->article_m->list_articles_with_category();
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('admin/article/list_articles', $this->data);
    }

    /**
     * Sửa bài viết
     * @param  int $id ID của bài viết
     */
    public function edit($id)
    {
        /**
         * Nếu địa chỉ nhập vào không có tham số $id thì chuyển hướng đến trang thêm
         */
        if ( ! isset($id))
        {
            Helper::redirect('admin/article/add');
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
         * Lấy dữ liệu từ model
         */
        $this->data['article_detail'] = $this->model->article_m->article_detail($id);
        $this->data['list_categories'] = $this->model->category_m->list_categories();
        /**
         * Nếu không tồn tại bài viết có $id này thì chuyển đến trang danh sách
         */
        if ( ! count($this->data['article_detail']))
        {
            Helper::redirect('admin/article/index');
        }
        /**
         * Kích hoạt TinyMCE plugin
         */
        $this->data['tinymce'] = TRUE;
        /**
         * Gọi view
         */
        $this->data['title'] = 'Sửa bài viết: '.$this->data['article_detail']['title'];
        $this->view->load('admin/article/edit', $this->data);
    }

    /**
     * Kiểm tra tiêu đề khi sửa bài viết
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
            $result = $this->model->article_m->check_data_exists_not_current('title', $_POST['data']);
            /**
             * Kiểm tra Tiêu đề đã có trong hệ thống chưa
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('span', 'error', 'bài viết này đã có trên hệ thống'),
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
     * Kiểm tra đường dẫn khi sửa bài viết
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
            $result = $this->model->article_m->check_data_exists_not_current('slug', $_POST['data']);
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
     * Thêm dữ liệu vào CSDL khi người dùng bấm Lưu
     * AJAX
     */
    public function edit_submit()
    {
        /**
         * Truyền dữ liệu từ form cho model
         */
        $result = $this->model->article_m->edit_article($_POST['data']);
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
}
<?php

/**
 * Quản lý bình luận của các bình luận
 */
class Comment extends Admin_Controller {
    function __construct()
    {
        parent::__construct();
        /**
         * Tạo đối tượng làm việc với model comment
         */
        $this->model->load('comment_m');
    }

    /**
     * Xóa bình luận
     * AJAX
     */
    public function delete_ajax()
    {
        /**
         * Kiểm tra ID truyền vào
         */
        if (isset($_POST['id']) && is_numeric($_POST['id']))
        {
            /**
             * Xóa bình luận có ID = $_POST['id']
             */
            $result = $this->model->comment_m->delete($_POST['id']);
            /**
             * Kiểm tra kết quả từ model
             */
            if ($result)
            {
                $this->data = array(
                    'status' => 'success',
                    'msg'    => Helper::message('p', 'success', 'Xóa bình luận thành công!'),
                );
            }
            else
            {
                $this->data = array(
                    'status' => 'error',
                    'msg'    => Helper::message('p', 'error', 'Xóa thất bại, hãy thử lại!'),
                );
            }
        }
        /**
         * ID không đúng, thông báo lỗi
         */
        else
        {
            $this->data = array(
                'status' => 'error',
                'msg'    => Helper::message('p', 'error', 'Có lỗi xảy ra, hãy thử lại!'),
            );
        }
        /**
         * Gọi view hiển thị dữ liệu json
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('json/messages', $this->data);
    }
}
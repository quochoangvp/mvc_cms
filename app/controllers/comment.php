<?php

/**
 * Quản lý bình luận của các bình luận
 */
class Comment extends Frontend_Controller {

    function __construct()
    {
        parent::__construct();
        /**
         * Gọi model quản lý CSDL của bảng `comments`
         */
        $this->model->load('comment_m');

    }

    /**
     * Hiển thị bình luận
     */
    public function comment_ajax()
    {
        /**
         * Kích hoạt captcha
         */
        parent::get_captcha();
        /**
         * Đã nhập đủ nội dung và mã bảo mật
         */
        if (isset($_POST['data']['message']) && strlen($_POST['data']['message']) != 0 && isset($_POST['data']["recaptcha_challenge_field"]) && isset($_POST['data']["recaptcha_response_field"]))
        {
            /**
             * Kiểm tra mã captcha
             */
            $this->resp = $this->recaptcha->check_answer(
                $_SERVER["REMOTE_ADDR"],
                $_POST['data']["recaptcha_challenge_field"],
                $_POST['data']["recaptcha_response_field"]
            );

            /**
             * Nhập đúng mã captcha
             */
            if ($this->resp->is_valid)
            {
                /**
                 * Thê dữ liệu vào CSDL
                 */
                $result = $this->data['comments'] = $this->model->comment_m->add_comment($_POST['data']);
                /**
                 * Kiểm tra kết quả trả về từ model
                 */
                if ($result)
                {
                    $this->data['response'] = Helper::message('p', 'success', 'Đăng bình luận thành công!');
                    $this->data['status'] = 'success';
                }
                else
                {
                    $this->data['response'] = Helper::message('p', 'error', 'Đăng bình luận thất bại!');
                }
            }
            /**
             * Nhập sai mã captcha
             */
            else
            {
                // $this->error = $this->resp->error;
                $this->data['response'] = Helper::message('p', 'error', 'Bạn đã nhập sai mã bảo mật, hãy thử lại!');
            }
        }
        /**
         * Lấy danh sách bình luận từ CSDL
         */
        $article_id = intval($_POST['data']['aid']);
        $this->data['comments'] = $this->model->comment_m->get_comments($article_id);
        /**
         * Gọi view
         */
        $this->data['text_only'] = TRUE;
        $this->view->load('comment/comment_ajax', $this->data);
    }
}
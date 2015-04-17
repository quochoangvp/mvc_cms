<?php

/**
 * Model quản lý bảng `comments`
 */
class Comment_m extends Model {

    protected $_table_name = 'comments';
    protected $_order_by   = 'date';
    protected $_primary_key = 'id';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy dữ liệu của các bình luận của bài viết
     * @param  integer $article_id ID của bài viết
     * @return array             Mảng dữ liệu của các bình luận
     */
    public function get_comments($article_id)
    {
        $query = 'SELECT * FROM `'.$this->_table_name.'`
            WHERE `article_id` = '.$article_id.'
            ORDER BY `'.$this->_order_by.'` DESC';
        return parent::read($query);
    }

    /**
     * Thêm bình luận khi người dùng submit form
     * @param array $data Dữ liệu nhận được từ form
     * @return boolean Thêm thành công hay không
     */
    public function add_comment($data)
    {
        $sth = $this->db->prepare('INSERT INTO `'.$this->_table_name.'`
            (`article_id`, `author`, `email`, `content`, `date`) VALUES
            (:article_id,:author,:email,:content,:postdate)');
        $result = $sth->execute(array(
            ':article_id' => $data['aid'],
            ':author'     => $data['author'],
            ':email'      => $data['email'],
            ':content'    => Helper::clean_text($data['message']),
            ':postdate'   => date('Y-m-d H:i:s'),
        ));

        return $result;
    }

    /**
     * Xóa bình luận
     * @param  integer $id ID của bình luận
     * @return boolean     Xóa thành công hay không
     */
    public function delete($id)
    {
        return parent::delete($id);
    }
}
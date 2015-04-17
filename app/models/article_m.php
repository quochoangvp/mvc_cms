<?php

/**
 * Class quản lý truy xuất CSDL bảng articles
 */
class Article_m extends Model {

    protected $_table_name = 'articles';
    protected $_order_by   = 'pubdate';
    protected $_primary_key = 'id';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Danh sách bài viết với không có tên thể loại
     * @return array Mảng dữ liệu bài viết
     */
    public function list_articles_without_category()
    {
        $query = 'SELECT id, title, content, cat_id, user_id, pubdate
            FROM `'.$this->_table_name.'`
            ORDER BY `title` ASC';
        return parent::read($query);
    }

    /**
     * Danh sách bài viết với tên thể loại
     * @return array Mảng dữ liệu bài viết
     */
    public function list_articles_with_category()
    {
        $query = 'SELECT '.$this->_table_name.'.*,
                    c.id AS cat_id,
                    c.title AS cat_title,
                    c.slug AS cat_slug,
                    u.id AS author_id,
                    u.name AS author
                    FROM `'.$this->_table_name.'`
                    INNER JOIN `categories` AS c
                    ON '.$this->_table_name.'.cat_id = c.id
                    INNER JOIN `users` AS u
                    ON '.$this->_table_name.'.user_id = u.id
                    ORDER BY `title` ASC';
        return parent::read($query);
    }

    /**
     * Kiểm tra dữ liệu đã có trong CSDL chưa
     * @param  string $column Cột cần kiểm tra
     * @param  string $data   Dữ liệu cần kiểm tra
     * @return boolean         Nếu đã tồn tại thì trả về TRUE
     */
    public function check_data_exists($column, $data)
    {
        $sth = $this->db->prepare('SELECT id
                FROM `'.$this->_table_name.'`
                WHERE `'.$column.'` = :data
                LIMIT 1');
        $sth->execute(array(
            ':data' => $data,
        ));

        $count = $sth->rowCount();

        if ($count == 1) return TRUE;

        else return FALSE;
    }

    /**
     * Kiểm tra dữ liệu đã tồn tại trong CSDL chưa
     * Ngoại trừ ID hiện tại
     * @param  string $column Cột cần kiểm tra
     * @param  string $data   Dữ liệu cần kiểm tra
     * @return boolean         Nếu tồn tại trả về TRUE
     */
    public function check_data_exists_not_current($column, $data)
    {
        $sth = $this->db->prepare('SELECT id
                FROM `'.$this->_table_name.'`
                WHERE `'.$column.'` = :data AND `id` != :id
                LIMIT 1');
        $sth->execute(array(
            ':data' => $data['data'],
            ':id'   => $data['id'],
        ));

        $count = $sth->rowCount();

        if ($count == 1) return TRUE;

        else return FALSE;
    }

    /**
     * Thêm bài viết vào CSDL
     * @param  array   $data Mảng dữ liệu của bài viết
     * @return boolean       Chèn dữ liệu thành công hay không
     */
    public function add_article($data)
    {
        $sth = $this->db->prepare('INSERT INTO `'.$this->_table_name.'`
            (`title`, `slug`, `pubdate`, `content`, `cat_id`, `user_id`, `created`)
            VALUES (:title,:slug,:pubdate,:content,:cat_id,:user_id,:created)');
        $result = $sth->execute(array(
            ':title'   => $data['title'],
            ':slug'    => $data['slug'],
            ':pubdate' => $data['pubdate'],
            ':content' => htmlentities($data['content']),
            ':cat_id'  => $data['cid'],
            ':user_id' => Session::get('uid'),
            ':created' => date('Y-m-d H:i:s'),
        ));

        return $result;
    }

    /**
     * Lấy dữ liệu chi tiết của bài viết
     * @param  integer|string $param ID hoặc Đường dẫn của bài viết
     * @param  string $by    Lấy dữ liệu bởi ID|SLUG
     * @return array        Mảng dữ liệu
     */
    public function article_detail($param, $by = 'id')
    {
        if ($by == 'slug')
        {
            $query = 'SELECT * FROM `'.$this->_table_name.'` WHERE slug = "'.$param.'"';
        }
        else
        {
            $query = 'SELECT * FROM `'.$this->_table_name.'` WHERE id = "'.$param.'"';
        }

        return parent::read($query)[0];
    }

    /**
     * Frontend::
     * Chi tiết bài viết gồm cả thể loại của nó
     * @param  integer $id ID của bài viết
     * @return array     Mảng dữ liệu
     */
    public function article_detail_with_category($id)
    {
        $query = 'SELECT '.$this->_table_name.'.*,
                    c.id AS cat_id,
                    c.title AS cat_title,
                    c.slug AS cat_slug,
                    u.id AS author_id,
                    u.name AS author
                    FROM `'.$this->_table_name.'`
                    INNER JOIN `categories` AS c
                    ON '.$this->_table_name.'.cat_id = c.id
                    INNER JOIN `users` AS u
                    ON '.$this->_table_name.'.user_id = u.id
                    WHERE '.$this->_table_name.'.id = '.$id.'
                    AND '.$this->_table_name.'.pubdate <= "'.date('Y-m-d').'"
                    LIMIT 1';
        return parent::read($query);
    }

    /**
     * Cập nhật dữ liệu lên CSDL
     * @param  array $data Dữ liệu truyền vào
     * @return boolean       Cập nhật thành công hay không
     */
    public function edit_article($data)
    {
        $query = 'UPDATE `'.$this->_table_name.'` SET
            `title`="'.$data['title'].'",
            `slug`="'.$data['slug'].'",
            `pubdate`="'.$data['pubdate'].'",
            `content`="'.htmlentities($data['content']).'",
            `cat_id`='.$data['cid'].',
            `user_id`='.Session::get('uid').',
            `modified`="'.date('Y-m-d H:i:s').'"
            WHERE id = '.$data['id'].' LIMIT 1';
        return parent::update($query);
    }

    /**
     * Xóa dữ liệu
     * @param  integer $id ID của bài viết
     * @return boolean     Xóa thành công hay không
     */
    public function delete($id)
    {
        return parent::delete($id);
    }

    /**
     * Fontend($show_all == FALSE)|Backend($show_all == TRUE)
     * Danh sách bài viết mới nhất trên trang chủ
     * @param  integer $num_per_page Số bài viết trên 1 trang
     * @param  integer  $page         Trang hiện tại
     * @param  boolean  $show_all     TRUE == Hiển thị tất cả, FALSE = Chỉ hiển thị những bài viết trong quá khứ
     * @return array                Mảng dữ liệu của các bài viết
     */
    public function homepage_articles($num_per_page = 3, $page = NULL, $show_all = FALSE)
    {
        $start = (($page - 1) * $num_per_page);
        $query = 'SELECT '.$this->_table_name.'.*,
                    c.id AS cat_id,
                    c.title AS cat_title,
                    c.slug AS cat_slug,
                    u.id AS author_id,
                    u.name AS author
                    FROM `'.$this->_table_name.'`
                    INNER JOIN `categories` AS c
                    ON '.$this->_table_name.'.cat_id = c.id
                    INNER JOIN `users` AS u
                    ON '.$this->_table_name.'.user_id = u.id';

        if ($show_all == FALSE)
            $query .= ' WHERE '.$this->_table_name.'.pubdate <= "'.date('Y-m-d').'"';

        $query .= ' ORDER BY `'.$this->_order_by.'` DESC';

        if ($page)
            $query .= ' LIMIT '.$start.','.$num_per_page;

        return parent::read($query);
    }

    /**
     * Frontend::
     * Danh sách bài viết của thể loại
     * @param  integer  $cat_id       ID của thể loại hiện tại
     * @param  integer $num_per_page Số bài viết trên trang
     * @param  integer  $page         Trang hiện tại
     * @return array                Mảng dữ liệu của các bài viết
     */
    public function list_articles_by_category($cat_id, $num_per_page = 3, $page = NULL)
    {
        $start = (($page - 1) * $num_per_page);
        $query = 'SELECT '.$this->_table_name.'.*,
                    u.id AS author_id,
                    u.name AS author
                    FROM `'.$this->_table_name.'`
                    INNER JOIN `users` AS u
                    ON '.$this->_table_name.'.user_id = u.id
                    WHERE '.$this->_table_name.'.cat_id = '.$cat_id.'
                    AND '.$this->_table_name.'.pubdate <= "'.date('Y-m-d').'"
                    ORDER BY `'.$this->_order_by.'` DESC';
        if ($page != NULL)
            $query .= ' LIMIT '.$start.', '.$num_per_page;
        return parent::read($query);
    }

    /**
     * Frontend::
     * Danh sách bài viết của người dùng
     * @param  integer  $user_id      ID của người dùng
     * @param  integer $num_per_page Số bài viết trên 1 trang
     * @param  integer  $page         Trang hiện tại
     * @return array                Mảng dữ liệu của các bài viết
     */
    public function list_articles_by_author($user_id, $num_per_page = 3, $page = NULL)
    {
        $start = (($page - 1) * $num_per_page);
        $query = 'SELECT '.$this->_table_name.'.*,
                    c.id AS cat_id,
                    c.title AS cat_title,
                    c.slug AS cat_slug,
                    u.id AS author_id,
                    u.name AS author
                    FROM `'.$this->_table_name.'`
                    INNER JOIN `categories` AS c
                    ON '.$this->_table_name.'.cat_id = c.id
                    INNER JOIN `users` AS u
                    ON '.$this->_table_name.'.user_id = u.id
                    WHERE u.id = '.$user_id.'
                    AND '.$this->_table_name.'.pubdate <= "'.date('Y-m-d').'"
                    ORDER BY `'.$this->_order_by.'` DESC';
        if ($page)
            $query .= ' LIMIT '.$start.', '.$num_per_page;
        return parent::read($query);
    }

}
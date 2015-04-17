<?php

/**
 * Class quản lý truy xuất CSDL bảng pages
 */
class Page_m extends Model {

    protected $_table_name = 'pages';
    protected $_order_by   = 'title';
    protected $_primary_key = 'id';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Danh sách các trang
     * @return array Mảng dữ liệu các trang
     */
    public function list_pages_without_parent()
    {
        $query = 'SELECT id, title, content, parent_id
                    FROM `'.$this->_table_name.'`
                    ORDER BY `'.$this->_primary_key.'` ASC';
        return parent::read($query);
    }

    /**
     * Danh sách các trang với trang cha của nó
     * @return array Mảng dữ liệu các trang
     */
    public function list_pages_with_parent()
    {
        $query = 'SELECT '.$this->_table_name.'.*,
                    p.id AS parent_id,
                    p.slug AS parent_slug
                    FROM `'.$this->_table_name.'`
                    LEFT JOIN `'.$this->_table_name.'` AS p
                    ON '.$this->_table_name.'.parent_id = p.id
                    ORDER BY `'.$this->_primary_key.'` ASC';
        return parent::read($query);
    }

    /**
     * Danh sách các trang cha
     * @return array Mảng dữ liệu các trang
     */
    public function list_parent_pages()
    {
        $query = 'SELECT id, title, content, parent_id
                    FROM `'.$this->_table_name.'` WHERE `parent_id` = 0
                    ORDER BY `'.$this->_primary_key.'` ASC';
        return parent::read($query);
    }

    /**
     * Danh sách các trang với nhiều cấp độ
     * @return array Mảng dữ liệu các trang
     */
    public function list_pages()
    {
        $query = 'SELECT id, title, slug, parent_id
                    FROM `'.$this->_table_name.'`
                    ORDER BY `position`';
        $pages = parent::read($query);
        $array = array();
        /**
         * Vòng lặp 1:
         * Thêm dữ liệu các trang cha vào mảng
         */
        foreach ($pages as $page) {
            if (!$page['parent_id']) {
                $array[$page['id']] = $page;
            }
        }
        /**
         * Vòng lặp 2:
         * Thêm dữ liệu các trang con vào mảng `children` của trang cha
         */
        foreach ($pages as $page) {
            if ($page['parent_id']) {
                $array[$page['parent_id']]['children'][] = $page;
            }
        }
        return $array;
    }

    /**
     * Lưu vị trí của các trang
     * @param  array $pages Các trang vừa được người dùng sắp xếp
     */
    public function save_order($pages)
    {
        if (count($pages)) {
            foreach ($pages as $position => $page) {
                if (!empty($page['item_id'])) {
                    $query = 'UPDATE `'.$this->_table_name.'` SET
                            `position`="'.$position.'",
                            `parent_id`="'.$page['parent_id'].'"
                            WHERE `id` = '.$page['item_id'].'
                            LIMIT 1';
                    parent::update($query);
                }
            }
        }
    }

    /**
     * Kiểm tra dữ liệu có tồn tại trong CSDL không
     * @param  string $column Cột cần kiểm tra
     * @param  string $data   Dữ liệu cần kiểm tra
     * @return boolean         TRUE == Đã có trong CSDL, FALSE == Chưa
     */
    public function check_data_exists($column, $data)
    {
        $sth = $this->db->prepare('SELECT id FROM `'.$this->_table_name.'`
                                    WHERE `'.$column.'` = :data LIMIT 1');
        $sth->execute(array(
            ':data' => $data,
        ));

        $count = $sth->rowCount();

        if ($count == 1) return TRUE;

        else return FALSE;
    }

    /**
     * Kiểm tra dữ liệu có tồn tại trong CSDL không
     * Ngoại trừ dữ liệu của trang có ID hiện tại
     * @param  string $column Cột cần kiểm tra
     * @param  string $data   Dữ liệu cần kiểm tra
     * @return boolean         TRUE == Đã có trong CSDL, FALSE == Chưa
     */
    public function check_data_exists_not_current($column, $data)
    {
        $sth = $this->db->prepare('SELECT id FROM `'.$this->_table_name.'`
                                    WHERE `'.$column.'` = :data AND `id` != :id LIMIT 1');
        $sth->execute(array(
            ':data' => $data['data'],
            ':id'   => $data['id'],
        ));

        $count = $sth->rowCount();

        if ($count == 1) return TRUE;

        else return FALSE;
    }

    /**
     * Thêm trang vào CSDL
     * @param array $data Mảng dữ liệu nhận được từ form
     * @return boolean Chèn thành công hay không
     */
    public function add_page($data)
    {
        $sth = $this->db->prepare('INSERT INTO `'.$this->_table_name.'`
            (`title`, `slug`, `content`, `parent_id`)
            VALUES (:title,:slug,:content,:parent_id)');
        $result = $sth->execute(array(
            ':title'     => $data['title'],
            ':slug'      => $data['slug'],
            ':content'   => $data['content'],
            ':parent_id' => $data['parent'],
        ));

        return $result;
    }

    /**
     * Lấy thông tin chi tiết của trang
     * @param  string|integer $param Đường dẫn hoặc ID của trang
     * @param  string $by    Lấy thông tin bằng ID hoặc Đường dẫn
     * @return array        Mảng dữ liệu của trang
     */
    public function page_detail($param, $by = 'id')
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
     * Cập nhật dữ liệu của trang lên CSDL
     * @param  array $data Mảng dữ liệu nhận được từ form
     * @return boolean       Sửa thành công hay không
     */
    public function edit_page($data)
    {
        $query = 'UPDATE `'.$this->_table_name.'` SET
            `title`    = "'.$data['title'].'",
            `slug`     = "'.$data['slug'].'",
            `content`  = "'.$data['content'].'",
            `parent_id`='.$data['parent'].'
            WHERE `id` = '.$data['id'].'
            LIMIT 1';
        return parent::update($query);
    }

    /**
     * Xóa trang khỏi CSDL
     * @param  integer $id ID của trang cần xóa
     * @return boolean     Xóa thành công hay không
     */
    public function delete($id)
    {
        return parent::delete($id);
    }

}
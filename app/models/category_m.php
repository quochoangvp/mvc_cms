<?php

/**
 * Class quản lý truy xuất CSDL bảng categories
 */
class Category_m extends Model {

    protected $_table_name = 'categories';
    protected $_order_by   = 'title';
    protected $_primary_key = 'id';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Danh sách thể loại
     * @return array Mảng dữ liệu của các thể loại
     */
    public function list_categories_without_parent()
    {
        $query = 'SELECT id, title, parent_id
                    FROM `'.$this->_table_name.'`
                    ORDER BY `title` ASC';
        return parent::read($query);
    }

    /**
     * Danh sách thể loại với thể loại cha của nó
     * @return array Mảng dữ liệu của các thể loại
     */
    public function list_categories_with_parent()
    {
        $query = 'SELECT '.$this->_table_name.'.*,
                    c.id AS parent_id,
                    c.slug AS parent_slug
                    FROM `'.$this->_table_name.'`
                    LEFT JOIN `'.$this->_table_name.'` AS c
                    ON '.$this->_table_name.'.parent_id = c.id
                    ORDER BY `'.$this->_order_by.'` ASC';
        return parent::read($query);
    }

    /**
     * Danh sách thể loại cha
     * @return array Mảng dữ liệu các thể loại cha
     */
    public function list_parent_categories()
    {
        $query = 'SELECT id, title, parent_id
            FROM `'.$this->_table_name.'`
            WHERE `parent_id` = 0
            ORDER BY `'.$this->_order_by.'` ASC';
        return parent::read($query);
    }

    /**
     * Danh sách tất cả thể loại với nhiều cấp
     * @return array Mảng dữ liệu các thể loại
     */
    public function list_categories()
    {
        $query = 'SELECT id, title, slug, parent_id
            FROM `'.$this->_table_name.'`
            ORDER BY `position`';
        $categories = parent::read($query);
        $array = array();
        /**
         * Quét lần 1:
         * Thêm các thể loại cha vào mảng
         */
        foreach ($categories as $category) {
            if (!$category['parent_id']) {
                $array[$category['id']] = $category;
            }
        }
        /**
         * Quét lần 2:
         * Thê các thể loại con vào mảng con `children` của mảng cha
         */
        foreach ($categories as $category) {
            if ($category['parent_id']) {
                $array[$category['parent_id']]['children'][] = $category;
            }
        }
        return $array;
    }

    /**
     * Lưu vị trí các thể loại vào CSDL
     * @param  array $categories Mảng dữ liệu của các thể loại sau khi đã được sắp xếp
     * AJAX
     */
    public function save_order($categories)
    {
        if (count($categories)) {
            foreach ($categories as $position => $category) {
                if (!empty($category['item_id'])) {
                    $query = 'UPDATE `'.$this->_table_name.'` SET
                        `position`="'.$position.'",
                        `parent_id`="'.$category['parent_id'].'"
                        WHERE `id` = '.$category['item_id'].'
                        LIMIT 1';
                    parent::update($query);
                }
            }
        }
    }

    /**
     * Kiểm tra dữ liệu đã tồn tại trong CSDL chưa
     * @param  string $column Cột cần kiểm tra
     * @param  string $data   Dữ liệu cần kiểm tra
     * @return boolean         TRUE == Tồn tại, FALSE == Chưa có trong CSDL
     * AJAX
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
     * Kiểm tra dữ liệu đã tồn tại trong CSDL chưa
     * Ngoại trừ dữ liệu có ID = $data['id']
     * @param  string $column Cột cần kiểm tra
     * @param  array $data   Dữ liệu cần kiểm tra
     * @return boolean         TRUE == Tồn tại, FALSE == Chưa có trong CSDL
     * AJAX
     */
    public function check_data_exists_not_current($column, $data)
    {
        $sth = $this->db->prepare('SELECT id FROM `'.$this->_table_name.'`
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
     * Thêm thể loại vào CSDL khi người dùng submit form
     * @param array $data Dữ liệu người dùng nhập
     * AJAX
     */
    public function add_category($data)
    {
        $sth = $this->db->prepare('INSERT INTO `'.$this->_table_name.'`
            (`title`, `slug`, `parent_id`)
            VALUES (:title,:slug,:parent_id)');
        $result = $sth->execute(array(
            ':title'     => $data['title'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent'],
        ));

        return $result;
    }

    /**
     * Lấy chi tiết của một thể loại theo ID hoặc đường dẫn
     * @param  string $param ID hoặc Đường dẫn của thể loại
     * @param  string $by    id|slug
     * @return array        Mảng dữ liệu của thể loại
     */
    public function category_detail($param, $by = 'id')
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
     * Cập nhập dữ liệu của thể loại khi người dùng submit form
     * @param  array $data Mảng dữ liệu nhận được từ form
     * @return boolean       Thành công hay không
     * AJAX
     */
    public function edit_category($data)
    {
        $query = 'UPDATE `'.$this->_table_name.'` SET
                `title`    = "'.$data['title'].'",
                `slug`     = "'.$data['slug'].'",
                `parent_id`= '.$data['parent'].'
                WHERE `id` = '.$data['id'].'
                LIMIT 1';
        return parent::update($query);
    }

    /**
     * Xóa thể loại có ID = $id
     * @param  integer $id ID của thể loại
     * @return boolean     Xóa thành công hay không
     */
    public function delete($id)
    {
        return parent::delete($id);
    }

}
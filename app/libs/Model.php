<?php

/**
 * Lớp quản lý việc truy xuất CSDL
 */
class Model {

    protected $_table_name  = '';
    protected $_order_by    = 'id';
    protected $_primary_key = 'id';

    function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Load model với controller tương ứng
     * @param  string $name Tên model
     */
    public function load($name)
    {
        $path = MODELPATH.$name.'.php';
        if (file_exists($path) && is_file($path))
        {
            require_once $path;
            $this->{$name} = new $name();
        }
        /**
         * Ngắt kết nối đến CSDL
         */
        $this->db = null;
    }

    /**
     * Thêm dữ liệu vào CSDL
     */
    public function create()
    {
        # Not use
    }

    /**
     * Đọc dữ liệu từ CSDL
     * @param  string $query Câu lệnh truy vấn
     * @return array        Mảng dữ liệu
     */
    public function read($query)
    {
        $sth = $this->db->prepare($query);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Cập nhật dữ liệu lên CSDL
     * @param  string $query Câu lệnh truy vấn
     * @return boolean       Cập nhật thành công không
     */
    public function update($query)
    {
        $sth = $this->db->prepare($query);
        $result = $sth->execute();
        return $result;
    }

    /**
     * Xóa dữ liệu
     * @param  integer $id ID của hàng cần xóa
     * @return boolean     Xóa thành công hay không
     */
    public function delete($id)
    {
        $sth = $this->db->prepare('DELETE FROM `'.$this->_table_name.'`
                                    WHERE '.$this->_primary_key.' = '.$id);
        $result = $sth->execute();

        return $result;
    }
}
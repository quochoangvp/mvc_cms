<?php

/**
 * Class quản lý truy xuất CSDL bảng users
 */
class User_m extends Model {

    protected $_table_name = 'users';
    protected $_order_by   = 'name';
    protected $_primary_key = 'id';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Đăng nhập người dùng
     * @param  array $data Dữ liệu nhận được từ form
     * @return array|boolean       Dữ liệu người dùng hoặc trả về FALSE nếu không tìm thấy
     */
    public function login($data)
    {
        $sth = $this->db->prepare('SELECT * FROM `'.$this->_table_name.'`
            WHERE `email` = :email AND
            `password` = :password LIMIT 1');
        $sth->execute(array(
            ':email'    => $data['email'],
            ':password' => Helper::hash($data['password']),
        ));

        $data = $sth->fetch(PDO::FETCH_ASSOC);

        $count = $sth->rowCount();

        if ($count == 1) return $data;

        else return FALSE;
    }

    /**
     * Kiểm tra dữ liệu đã tồn tại trong CSDL chưa
     * @param  string $column Cột cần kiểm tra
     * @param  string $data   Dữ liệu cần kiểm tra
     * @return boolean         TRUE == Đã tồn tại, FALSE == Chưa
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
     * Kiểm tra dữ liệu đã tồn tại chưa
     * Ngoại trừ dữ liệu của hàng có ID hiện tại
     * @param  string $column Cột cần kiểm tra
     * @param  array $data   Dữ liệu cần kiểm tra
     * @return boolean         TRUE == Tồn tại, FALSE == Chưa
     */
    public function check_data_exists_not_current($column, $data)
    {
        $sth = $this->db->prepare('SELECT id FROM `'.$this->_table_name.'`
                                    WHERE `'.$column.'` = :data AND `id` != :id
                                    LIMIT 1');
        $sth->execute(array(
            ':data' => $data['data'],
            ':id'   => $data['uid'],
        ));

        $count = $sth->rowCount();

        if ($count == 1) return TRUE;

        else return FALSE;
    }

    /**
     * Đăng ký người dùng
     * @param  array $data Dữ liệu nhận được từ form
     * @return booelan       Đăng ký thành công hay không
     */
    public function register($data)
    {
        $sth = $this->db->prepare('INSERT INTO `'.$this->_table_name.'`
            (`name`, `fullname`, `email`, `password`, `level`, `regdate`)
            VALUES (:name,:fullname,:email,:password,:level,:regdate)');
        $result = $sth->execute(array(
            ':name'     => $data['username'],
            ':fullname' => $data['fullname'],
            ':email'    => $data['email'],
            ':password' => Helper::hash($data['password']),
            ':level'    => 0,
            ':regdate'  => date('Y-m-d H:i:s'),
        ));

        return $result;
    }

    /**
     * Lấy thông tin chi tiết của người dùng
     * @param  integer $uid ID của người dùng
     * @return array      Mảng dữ liệu của người dùng
     */
    public function user_detail($uid)
    {
        $query = 'SELECT id, name, email, fullname, level, regdate, bio
                    FROM `'.$this->_table_name.'`
                    WHERE id = '.$uid;
        return parent::read($query)[0];
    }

    /**
     * Sửa thông tin người dùng (Họ tê và email)
     * @return boolean Sửa thành công hay không
     */
    public function edit_profile()
    {
        $query = 'UPDATE `'.$this->_table_name.'` SET
            `fullname`="'.$_POST['data']['fullname'].'",
            `email`   ="'.$_POST['data']['data'].'"
            WHERE id  = '.Session::get('uid').' LIMIT 1';
        return parent::update($query);
    }

    /**
     * Sửa ghi chú bản thân của người dùng
     * @param  string $data Dữ liệu nhận được từ form
     * @return boolean       Cập nhật thành công hay không
     */
    public function edit_bio($data)
    {
        $query = 'UPDATE `'.$this->_table_name.'` SET
            `bio`="'.$data.'"
            WHERE id = '.Session::get('uid').' LIMIT 1';
        return parent::update($query);
    }

    /**
     * Lấy mật khẩu hiện tại của người dùng
     * @param  integer $uid ID của người dùng
     * @return string      Mật khẩu của người dùng
     */
    public function current_password($uid)
    {
        $query = 'SELECT `password`
                    FROM `'.$this->_table_name.'`
                    WHERE `id` = '.$uid.'
                    LIMIT 1';
        return parent::read($query)[0]['password'];
    }

    /**
     * Đổi mật khẩu người dùng
     * @param  array $data Mật khẩu nhận được từ form
     * @return boolean       Cập nhật thành công hay không
     */
    public function change_password($data)
    {
        $query = 'UPDATE `'.$this->_table_name.'` SET
            `password`="'.Helper::hash($data['new_password']).'"
            WHERE id = '.Session::get('uid').' LIMIT 1';
        return parent::update($query);
    }

    /**
     * Admin user manager
     */

    /**
     * Danh sách người dùng
     * @return array Mảng dữ liệu các người dùng
     */
    public function list_users()
    {
        $query = 'SELECT id, name, email, fullname, level, regdate
                    FROM `'.$this->_table_name.'`
                    ORDER BY `'.$this->_order_by.'` ASC';
        return parent::read($query);
    }

    /**
     * Thêm người dùng
     * @param array $data Mảng dữ liệu nhận được từ form
     * @return boolean Chèn dữ liệu thành công hay không
     */
    public function add_user($data)
    {
        $sth = $this->db->prepare('INSERT INTO `'.$this->_table_name.'`
            (`name`, `fullname`, `email`, `password`, `level`, `regdate`)
            VALUES (:name,:fullname,:email,:password,:level,:regdate)');
        $result = $sth->execute(array(
            ':name'     => $data['username'],
            ':fullname' => $data['fullname'],
            ':email'    => $data['email'],
            ':password' => Helper::hash($data['password']),
            ':level'    => $data['level'],
            ':regdate'  => date('Y-m-d H:i:s'),
        ));

        return $result;
    }

    /**
     * Xóa người dùng
     * @param  integer $id ID của người dùng
     * @return boolean     Xóa thành công hay không
     */
    public function delete($id)
    {
        return parent::delete($id);
    }

    /**
     * Cập nhật dữ liệu của người dùng
     * @param  array $data Dữ liệu nhận được từ form
     * @return boolean       Cập nhật thành công hay không
     */
    public function edit_user($data)
    {
        $query = 'UPDATE `'.$this->_table_name.'` SET
            `name`="'.$data['username'].'",
            `fullname`="'.$data['fullname'].'",
            `email`="'.$data['email'].'",
            `password`="'.Helper::hash($data['username']).'",
            `level`='.$data['level'].'
            WHERE id = '.$data['uid'].' LIMIT 1';
        return parent::update($query);
    }
}
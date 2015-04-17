<?php

/**
 * Quản lý các helper
 */
class Helper {

    /**
     * Nếu người dùng không phải admin thì chuyển đến trang chủ
     */
    public static function admin_check()
    {
        if (Session::get('ulevel') < 4)
        {
            self::redirect();
        }
    }

    /**
     * Kiểm tra người dùng có phải admin không
     * @return boolean TRUE == ADMIN
     */
    public static function is_admin()
    {
        if (Session::get('ulevel') > 3)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     */
    public static function is_logged()
    {
        if (isset($_SESSION['uid']))
        {
            self::redirect();
        }
    }

    /**
     * Chuyển hướng trang
     * @param  string $page đường dẫn cần chuyển đến
     */
    public static function redirect($page = '', $type = '')
    {
        switch ($type)
        {
            case '301':
                header("HTTP/1.1 301 Moved Permanently");
                break;

            default:
                header("HTTP/1.0 404 Not Found");
                break;
        }
        header('Location: '.BASE_URL.$page);
        exit();
    }

    /**
     * Kiểm tra chuỗi nhập vào có đúng định dạng email
     * @param  string $str Chuỗi cần kiểm tra
     * @return boolean      TRUE == chuỗi nhập vào là email
     */
    public static function vali_email($str)
    {
        if (filter_var($str, FILTER_VALIDATE_EMAIL))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Kiếm tra tên người dùng, chỉ chấp nhận kí tự từ a-z
     * @param  string $str Chuỗi cần kiểm tra
     * @return boolean     TRUE == đúng
     */
    public static function vali_username($str)
    {
        if (preg_match('/[a-z|A-Z]{2,20}/', $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Đưa thông báo vào thẻ html và lớp style
     * @param  string $tag  Thẻ
     * @param  string $type Loại thông báo (success == thành công | error == Thất bại)
     * @param  string $msg  Nội dung thông báo
     * @return string       Nội dung thông báo đã nằm trong thẻ
     */
    public static function message($tag = 'p', $type = 'success', $msg = '')
    {
        if ($type == 'success')
        {
            return "<{$tag} class=\"text-success\">{$msg}</{$tag}>";
        }
        elseif ($type == 'error')
        {
            return "<{$tag} class=\"text-danger\">{$msg}</{$tag}>";
        }
        else
        {
            return "<{$tag} class=\"text-warning\">{$msg}</{$tag}>";
        }
    }

    /**
     * Mã hóa mật khẩu
     * @param  string $str Mật khẩu người dùng nhập vào
     * @return string      Mật khẩu sau khi mã hóa
     */
    public static function hash($str)
    {
        return SHA1(MD5(SHA1($str).HASH_KEY).HASH_KEY);
    }

    public static function clean_text($str)
    {
        $str = htmlentities($str);
        return $str;
    }

    /**
     * Hiển thị chức vụ theo level
     * @param  int $level Level
     * @return string     Chức vụ
     */
    public static function level($level)
    {
        switch ($level)
        {
            case '4':
                $lv = 'Owner';
                break;
            case '3':
                $lv = 'Admin';
                break;
            case '2':
                $lv = 'Super Mod';
                break;
            case '1':
                $lv = 'Mod';
                break;
            case '0':
                $lv = 'Normal';
                break;
            default:
                $lv = 'Normal';
                break;
        }
        return $lv;
    }

    /**
     * Cắt chuỗi văn bản theo số chữ cái
     * @param  string  $str      Văn bản
     * @param  integer $numwords Số từ
     * @return string            Văn bản sau khi cắt
     */
    public static function limit_to_numwords($str, $numwords = 50)
    {
        $str = strip_tags($str);
        $excerpt = explode(' ', $str, $numwords + 1);

        if (count($excerpt) >= $numwords)
        {
            array_pop($excerpt);
        }

        $excerpt = implode(' ', $excerpt).'...';

        return $excerpt;
    }

    /**
     * Hiển thị dữ liệu được sắp xếp dưới dạng list đa cấp
     * @param  array  $array Mảng dữ liệu
     * @param  boolean $child Màng là phần tử con hay không
     * @return string         List dữ liệu dưới dạng html
     */
    public static function get_order_ajax($array, $child = FALSE)
    {
        $str = '';
        if (count($array)) {
            $str .= ($child == FALSE) ? '<ol class="sortable">'.PHP_EOL : '<ol>'.PHP_EOL;
            foreach ($array as $item) {
                $str .= '<li id="list_'.$item['id'].'">'.PHP_EOL;
                $str .= '<div>'.$item['title'].'</div>'.PHP_EOL;

                if (isset($item['children']) && count($item['children'])) {
                    $str .= self::get_order_ajax($item['children'], TRUE);
                }
                $str .= '</li>'.PHP_EOL;
            }
            $str .= '</ol>'.PHP_EOL;
        }
        return $str;
    }

    /**
     * Tạo select menu thể loại
     * @param  array  $categories Mảng dữ liệu của thể loại
     * @param  boolean $child      Mảng là phần tử con không
     * @param  integer $current_id ID của thể loại hiện tại
     * @return string              Html của mảng dưới dạng select
     */
    public static function get_select_categories($categories, $child = FALSE, $current_id = 0)
    {
        $str = '';

        if (count($categories)) {
            foreach ($categories as $key => $category)
            {
                $str .= '<option value="'.$category['id'].'" ';
                if ($category['id'] == $current_id)
                {
                    $str .= 'selected="selected"';
                }
                $str .= '>';
                $str .= ($child) ? '--' : '';
                $str .= $category['title'].'</option>'.PHP_EOL;
                if (isset($category['children']))
                {
                    $str .= self::get_select_categories($category['children'], TRUE, $current_id);
                }
            }
        }
        return $str;
    }

    /**
     * Hiển thị danh sách thể loại trên sidebar
     * @param  array  $categories Mảng dữ liệu của thể loại
     * @param  boolean $child      Mảng là phần tử con không
     * @return string              Danh sách thể loại dưới dạng html
     */
    public static function sidebar_categories($categories, $child = FALSE)
    {
        $str = '<ol class="list-unstyled">';

        if (count($categories)) {
            foreach ($categories as $category)
            {
                $str .= '<li>';
                $str .= ($child) ? '-- ' : '';
                $str .= '<a href="'.BASE_URL.'category/'.$category['id'].'/'.$category['slug'].'">';
                $str .= $category['title'].'</a>'.PHP_EOL;
                if (isset($category['children']))
                {
                    $str .= self::sidebar_categories($category['children'], TRUE);
                }
                $str .= '</li>'.PHP_EOL;
            }
        }
        $str .= '</ol>'.PHP_EOL;
        return $str;
    }

    /**
     * Hiển thị phân trang
     * @param  string $uri           Đường dẫn cần phân trang
     * @param  integer $total_item    Tổng phần tử
     * @param  integer $item_per_page Số phần tử / 1 trang
     * @param  integer $current_page  Trang hiện tại
     * @return string                Dạng html hiển thị danh sách trang
     */
    public static function pagination($uri, $total_item, $item_per_page, $current_page)
    {
        $total_page = ceil($total_item / $item_per_page);

        if ($total_page < 2)
        {
            return FALSE;
        }

        if ($current_page > $total_page)
        {
            self::redirect($uri.'/'.$total_page);
        }
        $str = '<ul class="pagination">';
        if ($current_page != 1)
        {
            $str .= '<li><a href="'.BASE_URL.$uri.'/'.($current_page - 1).'">&laquo;</a></li>';
        }
        for ($i = 1; $i <= $total_page; $i++)
        {
            if (($i > 2 && $i < $current_page - 1) || ($i > $current_page + 1 && $i < $total_page - 1))
            {
                if ($i == $current_page - 2 || $i == $current_page + 2)
                {
                    $str .= '<li class="disabled"><a href="'.BASE_URL.$uri.'/'.$i.'">...</a></li>';
                }
            }
            else
            {
                $str .= '<li ';
                if ($i == $current_page)
                {
                    $str .= 'class="active"';
                }
                $str .= '><a href="'.BASE_URL.$uri.'/'.$i.'">'.$i.'</a></li>';
            }

        }
        if ($current_page != $total_page)
        {
            $str .= '<li><a href="'.BASE_URL.$uri.'/'.($current_page + 1).'">&raquo;</a></li>';
        }
        $str .= '</ul>';
        return $str;
    }

    /**
     * Chuyển thời gian về dạng `x giây trước, x phút trước,...`
     * @param  string $comment_time Thời gian
     * @return string               Thời gian say khi chuyển đổi
     */
    public static function time_format($comment_time)
    {
        $now  = array(date('Y-m-d'), date('H:i:s'));
        $time = explode(' ', $comment_time);
        $msg  = '';

        if ($now[0] == $time[0])
        {
            // Trong ngay
            $ex_time = explode(':', $time[1]);
            $s_time = $ex_time[0] * 60 * 60 + $ex_time[1] * 60 + $ex_time[2];

            $ex_now = explode(':', $now[1]);
            $s_now  = $ex_now[0] * 60 * 60 + $ex_now[1] * 60 + $ex_now[2];

            $seconds = $s_now - $s_time;

            $hour   = (int) ($seconds / 3600);
            $minute = (int) (($seconds % 3600) / 60);
            $second = (int) (($seconds % 3600) % 60);

            if ($hour > 0)
            {
                $msg = 'cách đây '.$hour.' giờ '.$minute.' phút';
            }
            elseif ($minute > 0)
            {
                $msg = 'cách đây '.$minute.' phút '.$second.' giây';
            }
            elseif ($second > 0)
            {
                $msg = 'cách đây '.$second.' giây';
            }
            else
            {
                $msg = 'Vừa xong';
            }
        }
        else
        {
            // Ngay khac
            $msg = 'vào '.$time[0];
        }

        return $msg;
    }

    /**
     * Lặp lại hiển thị trang trên navigation,
     * Phục vụ cho self::get_nav()
     * @param  array  $pages Mảng trang
     * @param  boolean $child Mảng có là phần tử con
     * @return string         Dạng list html
     */
    protected static function pages_to_nav($pages, $child = FALSE)
    {
        $str = '';
        $str .= ($child == FALSE) ? '<ul class="nav navbar-nav">'.PHP_EOL : '<ul class="dropdown-menu">'.PHP_EOL;
        if (self::is_admin() && $child == FALSE) {
            $str .= '<li><a class="blog-nav-item" href="'.BASE_URL.'admin/dashboard">Bảng điều khiển</a></li>'.PHP_EOL;
        }
        foreach ($pages as $page) {
            if (isset($page['children'])) {
                $str .= '<li class="dropdown">'.PHP_EOL;
                $str .= '<a href="#" class="blog-nav-item dropdown-toggle" data-toggle="dropdown">'.$page['title'].' <b class="caret"></b></a>'.PHP_EOL;
                $str .= self::pages_to_nav($page['children'], TRUE);
            } else {
                $str .= '<li><a class="blog-nav-item ';
                $str .=
                (
                    (
                        (!$page['slug'] && !isset($GLOBALS['rz']['2'])) ||
                        (isset($GLOBALS['rz']['2']) && $GLOBALS['rz']['2'] == $page['slug'])
                    ) && $child == FALSE
                ) ? 'active' : '';

                $str .='" href="';
                $str .= ($page['slug']) ? BASE_URL.$page['slug'] : BASE_URL;
                $str .= '">'.$page['title'].'</a>';
            }
            $str .= '</li>'.PHP_EOL;
        }

        $str .= '</ul>'.PHP_EOL;
        return $str;
    }

    /**
     * Tạo list trang hiển thị trên navigation
     * @param  array $pages Mảng trang
     * @return string        Dạng list html
     */
    public static function get_nav($pages)
    {
        $str  = '<nav class="blog-nav">';
        $str .= self::pages_to_nav($pages);
        $str .= '<ul class="nav navbar-nav navbar-right">';
        $str .= '<li class="dropdown">';
        if (Session::get('uid')) {
            $str .= '<a href="#" class="blog-nav-item dropdown-toggle" data-toggle="dropdown">';
            $str .= Session::get('uname').' <b class="caret"></b></a>';
            $str .= '<ul class="dropdown-menu">';
            $str .= '<li><a class="blog-nav-item" href="'.BASE_URL.'user/profile">Hồ sơ</a></li>';
            $str .= '<li><a class="blog-nav-item" href="'.BASE_URL.'user/logout">Thoát</a></li>';
            $str .= '</ul>';
        } else {
            $str .= '<a href="#" class="blog-nav-item dropdown-toggle" data-toggle="dropdown">Đăng nhập <b class="caret"></b></a>';
            $str .= '<ul class="dropdown-menu">';
            $str .= '<li><a class="blog-nav-item" href="'.BASE_URL.'user/login">Đăng nhập</a></li>';
            $str .= '<li><a class="blog-nav-item" href="'.BASE_URL.'user/register">Đăng ký</a></li>';
            $str .= '</ul>';
        }
        $str .= '</li>
            </ul>
        </nav>';
        return $str;
    }
}
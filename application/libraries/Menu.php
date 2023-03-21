<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Menu
 *
 * @author dickyhikam
 */
class Menu {

    //put your code here
    public function __construct() {
        $this->CI = & get_instance();
    }

    public function ShowMenu($level, $link_menu) {
        $menu = '';

        $access_menu = $this->CI->Mglobals->getAllQ("SELECT *, a.id AS menunya FROM tb_geo_menu a, tb_geolancer_users_access b WHERE a.id = b.id_menu AND a.type = 'Main Menu' AND b.id_level = '" . $level . "' ORDER BY a.order;");
        foreach ($access_menu->result() as $row) {
            //check sub menu
            if ($row->link == '#') {
                $menu_sub = '';
                //get sub menu
                $access_submenu = $this->CI->Mglobals->getAllQ("SELECT * FROM tb_geo_menu a, tb_geolancer_users_access b WHERE a.id = b.id_menu AND a.id_menu = '" . $row->menunya . "' AND b.id_level = '" . $level . "' ORDER BY a.order;");
                foreach ($access_submenu->result() as $row2) {
                    $menu_sub .= '<li><a href="' . base_url() . $row2->link . '">' . $row2->name . '</a></li>';
                }
                $menu .= '<li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="' . $row->icon . '"></i> <span> ' . $row->name . ' </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                ' . $menu_sub . '
                            </ul>
                        </li>';
            } else {
                $menu .= '<li class="has_sub">
                            <a href="' . base_url() . $row->link . '" class="waves-effect"><i class="' . $row->icon . '"></i><span> ' . $row->name . ' </span> </a>
                        </li>';
            }
        }

        return $menu;
    }
    
    public function Access($level, $link_menu) {
        //check access menu
        $check_access = $this->CI->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geo_menu a, tb_geolancer_users_access b WHERE a.id = b.id_menu AND a.link = '" . $link_menu . "' AND b.id_level = '" . $level . "';");
        if($check_access->jml > 0){
            $con = 'found';
        } else {
            $con = 'not found';
        }
        
        return $con;
    }

}

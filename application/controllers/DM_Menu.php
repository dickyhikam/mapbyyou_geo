<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of DM_Menu
 *
 * @author dickyhikam
 */
class DM_Menu extends CI_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Modul');
        $this->load->library('Status');
        $this->load->library('Menu');

        //table primary
        $this->load->model('Mglobals');
    }

    public function index()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //get version
        $get_version = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_version WHERE status = '" . $this->status->VersionActive() . "';");

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];
            $level_user = $session_data['level'];
            $link_menu = $this->uri->segment(1);

            //check session status
            if ($login_status == 'succeed login') {
                $status_access = $this->menu->Access($level_user, $link_menu);
                //check access menu
                if ($status_access == 'found') {
                    //get data user login
                    $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");
                    //get data menu
                    $get_menu = $this->Mglobals->getAllQR("SELECT * FROM tb_geo_menu WHERE link = '" . $link_menu . "';");

                    //mandatory data
                    $data['tahun'] = $this->modul->Tahun();
                    $data['login_name'] = $get_user_login->full_name;
                    $data['menu_name'] = $get_menu->name;
                    $data['page_menu'] = $this->menu->ShowMenu($level_user, $link_menu);
                    $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;
                    //check photo
                    if (isset($get_user_login->foto)) {
                        $data['profil_photo'] = base_url() . $get_user_login->foto;
                    } else {
                        $data['profil_photo'] = base_url() . 'assets/images/profil/SampleUser.jpg';
                    }

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('data_master/menu/index');
                    $this->load->view('template/4_footer');
                    $this->load->view('template/5_script');
                } else {
                    $this->modul->Halaman("AccessDenied");
                }
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function table()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $data = array();
                $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geo_menu ORDER BY `order` ASC;");
                foreach ($list->result() as $row) {
                    $val = array();

                    //get data menu
                    $data_menu = $this->Mglobals->getAllQR("SELECT *, COUNT(*) AS jml FROM tb_geo_menu WHERE id = '" . $row->id_menu . "';");

                    //check type
                    if ($row->type == 'Main Menu') {
                        $type = $row->type;
                    } else {
                        $type = $row->type . ' (' . $data_menu->name . ')';
                    }

                    //check link
                    if ($row->link == '#') {
                        $btn_sub = '<button class="btn waves-effect waves-light btn-info btn-sm" onclick="modal_add_sub(' . "'" . $row->id . "'" . ')"> <i class="fa fa-plus"></i> </button>';
                    } else {
                        $btn_sub = '';
                    }

                    //check icon
                    if ($row->icon == '') {
                        $icon = '';
                    } else {
                        $icon = '<i class="' . $row->icon . '"></i>';
                    }

                    $val[] = $row->name;
                    $val[] = $type;
                    $val[] = $row->link;
                    $val[] = $icon;
                    $val[] = $row->order;
                    $val[] = '<button class="btn waves-effect waves-light btn-warning btn-sm" onclick="modal_edit(' . "'" . $row->id . "'" . ')" style="margin-right: 10px;"> <i class="fa fa-edit"></i> </button>'
                        . '<button class="btn waves-effect waves-light btn-danger btn-sm" onclick="modal_delete(' . "'" . $row->id . "'" . ', ' . "'" . $row->name . "'" . ')" style="margin-right: 10px;"> <i class="fa fa-trash"></i> </button>' .
                        $btn_sub;
                    $data[] = $val;
                }
                $output = array("data" => $data);
                echo json_encode($output);
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function rowdata()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->uri->segment(3);

                $tampil = $this->Mglobals->getAllQR("SELECT * FROM tb_geo_menu WHERE id = '" . $id . "';");
                echo json_encode($tampil);
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function add()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $name = $this->input->post('name');
                $id_sub = $this->input->post('id_sub');
                $link = $this->input->post('link');
                $icon = $this->input->post('icon');
                $order = $this->input->post('order');

                //check type
                if ($id_sub == '') {
                    $type = 'Main Menu';
                } else {
                    $type = 'Sub Menu';
                }

                //save to database
                $data = array(
                    'name' => $name,
                    'type' => $type,
                    'icon' => $icon,
                    'link' => $link,
                    'order' => $order,
                    'id_menu' => $id_sub
                );
                $simpan = $this->Mglobals->add("tb_geo_menu", $data);
                if ($simpan) {
                    $status = "success";
                } else {
                    $status = "failed";
                }

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function update()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->input->post('id');
                $name = $this->input->post('name');
                $link = $this->input->post('link');
                $icon = $this->input->post('icon');
                $order = $this->input->post('order');

                //save to database
                $data = array(
                    'name' => $name,
                    'icon' => $icon,
                    'link' => $link,
                    'order' => $order
                );
                $condition['id'] = $id;
                $update = $this->Mglobals->update("tb_geo_menu", $data, $condition);
                if ($update) {
                    $status = "success";
                } else {
                    $status = "failed";
                }

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function delete()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->input->post('id_confirm');

                //delete to database
                $condition['id'] = $id;
                $delete = $this->Mglobals->delete("tb_geo_menu", $condition);
                if ($delete) {
                    $status = "success";
                } else {
                    $status = "failed";
                }

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }
}

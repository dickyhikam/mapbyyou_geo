<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ST_AccessMenu
 *
 * @author dickyhikam
 */
class ST_AccessMenu extends CI_Controller
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

                    //get level
                    $list_level = '';
                    $get_level = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_level ORDER BY `name`;");
                    foreach ($get_level->result() as $row) {
                        $list_level .= '<option value="' . $row->id . '">' . $row->name . '</option>';
                    }
                    $data['list_level'] = $list_level;

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('setting/access_menu/index');
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
                $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_level ORDER BY `name`;");
                foreach ($list->result() as $row) {
                    $val = array();

                    //get menu yang dapat diakses
                    $tabel_menu = '<table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                    $data_akses = $this->Mglobals->getAllQ("SELECT *, a.id AS idnya FROM tb_geolancer_users_access a, tb_geo_menu b WHERE a.id_menu = b.id AND id_level = '" . $row->id . "';");
                    foreach ($data_akses->result() as $row2) {
                        //chek jenis menu
                        if ($row2->type == 'Sub Menu') {
                            $data_menu_sub = $this->Mglobals->getAllQR("SELECT * FROM tb_geo_menu WHERE id = '" . $row2->id_menu . "';");
                            $namanya = '(' . $data_menu_sub->name . ')';
                        } else {
                            $namanya = '';
                        }
                        $nama_menu = $row2->name . ' ' . $namanya;
                        $tabel_menu .= '<tr>
                                            <td>' . $nama_menu . '</td>
                                            <td style="text-align: center;"><button class="btn waves-effect waves-light btn-danger btn-sm" onclick="modal_delete(' . "'" . $row2->idnya . "'" . ', ' . "'" . $nama_menu . "'" . ')"> <i class="fa fa-trash"></i> </button></td>
                                        </tr>';
                    }
                    $tabel_menu .= '</tbody>
                            </table>';

                    $check_access = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users_access WHERE id_level = '" . $row->id . "';");
                    if ($check_access->jml > 0) {
                        $btn = '<button class="btn waves-effect waves-light btn-danger btn-sm" onclick="modal_delete_all(' . "'" . $row->id . "'" . ', ' . "'" . $row->name . "'" . ')"> <i class="fa fa-trash"></i> </button>';
                    } else {
                        $btn = '<button class="btn waves-effect waves-light btn-danger btn-sm" disabled> <i class="fa fa-trash"></i> </button>';
                    }

                    $val[] = $row->name;
                    $val[] = $tabel_menu;
                    $val[] = $btn;
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

    public function add()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $level = $this->input->post('level');
                $menu = $this->input->post('menu');

                //delete data
                $this->Mglobals->delete("tb_geolancer_users_access", "id_level = '" . $level . "'");

                foreach ($menu as $value) {
                    //save to database
                    $data = array(
                        'id_level' => $level,
                        'id_menu' => $value
                    );
                    $this->Mglobals->add("tb_geolancer_users_access", $data);
                }

                $status = "success";

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function show_menu()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $level = $this->uri->segment(3);

                //get menu
                $list_menu = '<div class="row">';
                $list_menu2 = '';
                $get_menu = $this->Mglobals->getAllQ("SELECT * FROM tb_geo_menu ORDER BY `order`;");
                foreach ($get_menu->result() as $row) {
                    //check type
                    if ($row->type == 'Main Menu') {
                        if ($row->link == '#') {
                            $list_menu2 .= '<br><div class="row">';

                            //check access menu
                            $get_access = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users_access WHERE id_level = '" . $level . "' AND id_menu = '" . $row->id . "';");
                            if ($get_access->jml > 0) {
                                $list_menu2 .= '<div class="col-sm-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="menu_' . $row->id . '" name="menu[]" checked value="' . $row->id . '">
                                                        <label class="custom-control-label" for="menu_' . $row->id . '">' . $row->name . ' (' . $row->type . ')</label>
                                                    </div>
                                                </div>';
                            } else {
                                $list_menu2 .= '<div class="col-sm-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="menu_' . $row->id . '" name="menu[]" value="' . $row->id . '">
                                                        <label class="custom-control-label" for="menu_' . $row->id . '">' . $row->name . ' (' . $row->type . ')</label>
                                                    </div>
                                                </div>';
                            }

                            $get_menu_sub = $this->Mglobals->getAllQ("SELECT * FROM tb_geo_menu WHERE id_menu = '" . $row->id . "';");
                            foreach ($get_menu_sub->result() as $row2) {
                                //check access menu
                                $get_access = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users_access WHERE id_level = '" . $level . "' AND id_menu = '" . $row2->id . "';");
                                if ($get_access->jml > 0) {
                                    $list_menu2 .= '<div class="col-sm-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="menu_' . $row2->id . '" name="menu[]" checked value="' . $row2->id . '">
                                                            <label class="custom-control-label" for="menu_' . $row2->id . '">' . $row2->name . ' (' . $row2->type . ')</label>
                                                        </div>
                                                    </div>';
                                } else {
                                    $list_menu2 .= '<div class="col-sm-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="menu_' . $row2->id . '" name="menu[]" value="' . $row2->id . '">
                                                            <label class="custom-control-label" for="menu_' . $row2->id . '">' . $row2->name . ' (' . $row2->type . ')</label>
                                                        </div>
                                                    </div>';
                                }
                            }
                            $list_menu2 .= '</div>';
                        } else {
                            //check access menu
                            $get_access = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users_access WHERE id_level = '" . $level . "' AND id_menu = '" . $row->id . "';");
                            if ($get_access->jml > 0) {
                                $list_menu .= '<div class="col-sm-4">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="menu_' . $row->id . '" name="menu[]" checked value="' . $row->id . '">
                                                        <label class="custom-control-label" for="menu_' . $row->id . '">' . $row->name . ' (' . $row->type . ')</label>
                                                    </div>
                                                </div>';
                            } else {
                                $list_menu .= '<div class="col-sm-4">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="menu_' . $row->id . '" name="menu[]" value="' . $row->id . '">
                                                        <label class="custom-control-label" for="menu_' . $row->id . '">' . $row->name . ' (' . $row->type . ')</label>
                                                    </div>
                                                </div>';
                            }
                        }
                    }
                }
                $list_menu .= '</div>';

                echo json_encode(array("list_menu" => $list_menu . $list_menu2));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function delete_all()
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
                $condition['id_level'] = $id;
                $delete = $this->Mglobals->delete("tb_geolancer_users_access", $condition);
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
                $delete = $this->Mglobals->delete("tb_geolancer_users_access", $condition);
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

<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of DM_Version
 *
 * @author dickyhikam
 */
class DM_Version extends CI_Controller
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
                    $this->load->view('data_master/version/index');
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
                $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_version;");
                foreach ($list->result() as $row) {
                    $val = array();

                    $ver = $row->version . '.' . $row->sub_version;

                    //check status
                    if ($row->status == $this->status->VersionActive()) {
                        $statusnya = '<span class="badge badge-success">Active</span>';
                        $btn = '';
                    } else {
                        $statusnya = '<span class="badge badge-danger">Inactive</span>';
                        $btn = '<button class="btn waves-effect waves-light btn-danger btn-sm" onclick="modal_delete(' . "'" . $row->id . "'" . ', ' . "'" . $ver . "'" . ')"> <i class="fa fa-trash"></i> </button>';
                    }

                    $val[] = $row->version;
                    $val[] = $row->sub_version;
                    $val[] = $row->descrip;
                    $val[] = $statusnya;
                    $val[] = '<button class="btn waves-effect waves-light btn-warning btn-sm" onclick="modal_edit(' . "'" . $row->id . "'" . ')" style="margin-right: 10px;"> <i class="fa fa-edit"></i> </button>'
                        . $btn;
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

                $tampil = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_version WHERE id = '" . $id . "';");
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
                $version = $this->input->post('version');
                $sub = $this->input->post('sub');
                $descrip = $this->input->post('descrip');
                $statusnya = $this->status->VersionActive();

                //check version activ
                $check_data = $this->Mglobals->getAllQR("SELECT *, COUNT(*) AS jml FROM tb_geolancer_version WHERE status = '" . $statusnya . "';");
                if ($check_data->jml > 0) {
                    $this->Mglobals->update("tb_geolancer_version", "status = '" . $this->status->VersionInactive() . "'", "id = '" . $check_data->id . "'");
                }

                //save to database
                $data = array(
                    'version' => $version,
                    'sub_version' => $sub,
                    'descrip' => $descrip,
                    'status' => $statusnya
                );
                $simpan = $this->Mglobals->add("tb_geolancer_version", $data);
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
                $version = $this->input->post('version');
                $sub = $this->input->post('sub');
                $descrip = $this->input->post('descrip');

                //save to database
                $data = array(
                    'version' => $version,
                    'sub_version' => $sub,
                    'descrip' => $descrip
                );
                $condition['id'] = $id;
                $update = $this->Mglobals->update("tb_geolancer_version", $data, $condition);
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
                $delete = $this->Mglobals->delete("tb_geolancer_version", $condition);
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

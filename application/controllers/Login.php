<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of newPHPClass
 *
 * @author dickyhikam
 */
class Login extends CI_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Status');
        $this->load->library('Modul');

        $this->load->model('Mglobals');
    }

    public function index()
    {
        $this->session->sess_destroy();

        //get version
        $get_version = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_version WHERE status = '" . $this->status->VersionActive() . "';");

        //versi browser
        $data['name_browser'] = $this->modul->DetectBrowser();
        $data['menu_name'] = 'Login';
        $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

        $this->load->view('template/1_head', $data);
        $this->load->view('login/index');
        $this->load->view('template/5_script');
    }

    public function index_version()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                //get version
                $get_version = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_version WHERE status = '" . $this->status->VersionActive() . "';");

                $data['menu_name'] = 'Version';
                $data['idnya'] = $this->modul->EnkripId($id_user);
                $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

                $this->load->view('template/1_head', $data);
                $this->load->view('login/version');
                $this->load->view('template/5_script');
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function index_user()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                //get version
                $get_version = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_version WHERE status = '" . $this->status->VersionActive() . "';");

                $data['menu_name'] = 'Choose User';
                $data['idnya'] = $id_user;
                $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;
                $data['query_user'] = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_users WHERE user_id <> '" . $id_user . "' AND status = '1' ORDER BY email");

                $this->load->view('template/1_head', $data);
                $this->load->view('login/user');
                $this->load->view('template/5_script');
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function loginv2()
    {
        $id_user = $this->modul->DekripId($this->uri->segment(3));

        //get data user
        $get_user = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

        //save data to session
        $ses = array(
            'login_status' => "succeed login",
            'id' => $get_user->user_id,
            'username' => $get_user->username,
            'email' => $get_user->email,
            'status_user' => $get_user->status,
            'internal' => $get_user->internal,
            'project' => $get_user->project,
            'country' => $get_user->country,
            'QC' => $get_user->QC
        );
        $this->session->set_userdata("login", $ses);

        $this->modul->Halaman("Dashboard");
    }

    public function proses_login()
    {
        $username = $this->input->post('username');
        $pass = $this->input->post('password');

        $password = hash("sha1", "mapbyyou66" . $pass);

        //check user login
        $check_login = $this->Mglobals->getAllQR("SELECT user_id, COUNT(*) AS jml FROM tb_geolancer_users WHERE username = '" . $username . "' AND password = '" . $password . "' AND status <> '" . $this->status->UserDelete() . "';");
        if ($check_login->jml > 0) {
            //get data user login
            $get_user = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $check_login->user_id . "';");
            $versi = $get_user->version;
            $idnya = $this->modul->EnkripId($get_user->user_id);

            //check status user
            if ($get_user->status == $this->status->UserActive()) {
                //check level QA
                if ($get_user->level == '3') {
                    //page select user
                    $status = "user";
                } else {
                    //user active
                    $status = "active";
                }

                //save user login activity
                $data = array(
                    'ip_address' => $this->modul->IP(),
                    'user_id' => $get_user->user_id,
                    'access_date' => $this->modul->DateNowDB(),
                    'access_time' => $this->modul->TimeNowDB()
                );
                $this->Mglobals->add("tb_ip_akses", $data);

                //save data to session
                $ses = array(
                    'login_status' => "succeed login",
                    'id' => $get_user->user_id,
                    'username' => $get_user->username,
                    'level' => $get_user->level,
                    'email' => $get_user->email,
                    'status_user' => $get_user->status,
                    'internal' => $get_user->internal,
                    'project' => $get_user->project,
                    'country' => $get_user->country,
                    'QC' => $get_user->QC
                );
                $this->session->set_userdata("login", $ses);
            } else if ($get_user->status == $this->status->UserInactive()) {
                //user  inactive
                $status = "inactive";
            } else {
                //save data to session
                $ses = array(
                    'login_status' => "succeed pending",
                    'id' => $get_user->user_id
                );
                $this->session->set_userdata("pending", $ses);

                //user pending
                $status = "pending";
            }
        } else {
            //user login not found
            $status = "not found";

            $versi = '';
            $idnya = '';
        }

        echo json_encode(array("status" => $status, "id" => $idnya));
    }

    public function bypass_login()
    {
        $id_user = $this->uri->segment(3);

        //get data user
        $get_user = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

        //save data to session
        $ses = array(
            'login_status' => "succeed login",
            'id' => $get_user->user_id,
            'username' => $get_user->username,
            'level' => $get_user->level,
            'email' => $get_user->email,
            'status_user' => $get_user->status,
            'internal' => $get_user->internal,
            'project' => $get_user->project,
            'country' => $get_user->country,
            'QC' => $get_user->QC
        );
        $this->session->set_userdata("login", $ses);

        $this->modul->Halaman("Dashboard");
    }

    public function proses_logout()
    {
        $this->session->sess_destroy();
        $this->modul->Halaman("Login");
    }

    public function test()
    {
        $password = $this->modul->IP();
        echo $password;
    }
}

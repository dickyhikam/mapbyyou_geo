<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Pending
 *
 * @author dickyhikam
 */
class Pending extends CI_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Modul');
        $this->load->library('Status');

        $this->load->model('Mglobals');
    }

    public function index()
    {
        //get and check session
        $session_data = $this->session->userdata('pending');
        if (isset($session_data)) {
            $id_user = $session_data['id'];

            //get version
            $get_version = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_version WHERE status = '" . $this->status->VersionActive() . "';");

            $data['id_user'] = $id_user;
            $data['menu_name'] = $this->uri->segment(1);
            $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

            $this->load->view('template/1_head', $data);
            $this->load->view('pending/index');
            $this->load->view('template/5_script');
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function deactivated()
    {
        $data['menu_name'] = $this->uri->segment(1);

        $this->load->view('template/1_head', $data);
        $this->load->view('Pending/reject');
        $this->load->view('template/5_script');
    }

    public function proses_pending()
    {
        //get and check session
        $session_data = $this->session->userdata('pending');
        if (isset($session_data)) {
            $id_user = $session_data['id'];

            //get data user login
            $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

            //check status
            if ($get_user_login->status == $this->status->UserActive()) {
                //user active
                $status = "active";

                //save data to session
                $ses = array(
                    'login_status' => "succeed login",
                    'id' => $get_user_login->user_id,
                    'username' => $get_user_login->username,
                    'email' => $get_user_login->email,
                    'status_user' => $get_user_login->status,
                    'internal' => $get_user_login->internal,
                    'project' => $get_user_login->project,
                    'country' => $get_user_login->country,
                    'QC' => $get_user_login->QC
                );
                $this->session->set_userdata("login", $ses);
            } else if ($get_user_login->status == $this->status->UserInactive()) {
                //user  inactive
                $status = "inactive";
                $this->session->sess_destroy();
            } else {
                //user pending
                $status = "pending";
            }

            echo json_encode(array("status" => $status));
        } else {
            $this->modul->Halaman("Login");
        }
    }
}

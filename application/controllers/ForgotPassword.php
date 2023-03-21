<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ForgotPassword
 *
 * @author dickyhikam
 */
class ForgotPassword extends CI_Controller
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
        //get version
        $get_version = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_version WHERE status = '" . $this->status->VersionActive() . "';");

        $data['menu_name'] = $this->uri->segment(1);
        $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

        $this->load->view('template/1_head', $data);
        $this->load->view('forgot_password/index');
        $this->load->view('template/5_script');
    }

    public function check_data()
    {
        $username = $this->input->post('username');
        $email = $this->input->post('email');

        //check data user
        $check_data = $this->Mglobals->getAllQR("SELECT *, COUNT(*) AS jml FROM tb_geolancer_users WHERE username = '" . $username . "' AND email = '" . $email . "'");
        if ($check_data->jml > 0) {
            //save data to session
            $ses = array(
                'id' => $check_data->user_id
            );
            $this->session->set_userdata("forpass", $ses);
            $status = 'success';
        } else {
            $status = 'failed';
        }

        echo json_encode(array("status" => $status));
    }

    public function change_pass()
    {
        //get and check session
        $session_data = $this->session->userdata('forpass');
        $id_user = $session_data['id'];

        $pass = $this->input->post('password');

        $password = hash("sha1", "mapbyyou66" . $pass);

        //save to database
        $data = array(
            'password' => $password
        );
        $condition = "user_id = '" . $id_user . "'";
        $simpan = $this->Mglobals->update("tb_geolancer_users", $data, $condition);
        if ($simpan) {
            $status = "success";
        } else {
            $status = "failed";
        }

        echo json_encode(array("status" => $status));
    }
}

<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Register
 *
 * @author dickyhikam
 */
class Register extends CI_Controller
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
        $this->load->view('register/index');
        $this->load->view('template/5_script');
    }

    public function proses_register()
    {
        $nama = $this->input->post('nama');
        $email = $this->input->post('email');
        $username = $this->input->post('username');
        $pass = $this->input->post('password');
        $tanggal = $this->modul->DateTimeNowDB();

        $password = hash("sha1", "mapbyyou66" . $pass);

        //check email
        $check_email = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE email = '" . $email . "';")->jml;
        if ($check_email > 0) {
            $status = "email";
        } else {
            //check username
            $check_username = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE username = '" . $username . "';")->jml;
            if ($check_username > 0) {
                $status = "username";
            } else {
                //save to database
                $data = array(
                    'full_name' => $nama,
                    'email' => $email,
                    'username' => $username,
                    'password' => $password,
                    'status' => '0',
                    'project' => 'none',
                    'date' => $tanggal
                );
                $simpan = $this->Mglobals->addwidthid("tb_geolancer_users", $data);
                if ($simpan > 0) {
                    //save session
                    $ses = array(
                        'login_status' => "succeed pending",
                        'id' => $simpan
                    );
                    $this->session->set_userdata("pending", $ses);

                    $status = "success";
                } else {
                    $status = "failed";
                }
            }
        }

        echo json_encode(array("status" => $status));
    }
}

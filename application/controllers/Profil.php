<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Profil
 *
 * @author dickyhikam
 */
class Profil extends CI_Controller
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
                //get data user login
                $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                //mandatory data
                $data['tahun'] = $this->modul->Tahun();
                $data['login_name'] = $get_user_login->full_name;
                $data['menu_name'] = $link_menu;
                $data['page_menu'] = $this->menu->ShowMenu($level_user, $link_menu);
                $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;
                //check photo
                if (isset($get_user_login->foto)) {
                    $data['profil_photo'] = base_url() . $get_user_login->foto;
                } else {
                    $data['profil_photo'] = base_url() . 'assets/images/profil/SampleUser.jpg';
                }

                $data['project'] = $get_user_login->project . ' project';

                $this->load->view('template/1_head', $data);
                $this->load->view('template/2_topbar');
                $this->load->view('template/3_sidebar');
                $this->load->view('profil/index');
                $this->load->view('template/4_footer');
                $this->load->view('template/5_script');
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function data_diri()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {

                //get data user
                $get_users = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                $tampilan = "<tr>
                                <td>Full Name</td>
                                <td>:</td>
                                <td>" . $get_users->full_name . "</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>" . $get_users->email . "</td>
                            </tr>";

                echo json_encode(array("tampilan" => $tampilan, "name" => $get_users->full_name, "email" => $get_users->email));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function save_data_diri()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                $nama = $this->input->post('full_name_dd');
                $email = $this->input->post('email_dd');

                //save to database
                $data = array(
                    'full_name' => $nama,
                    'email' => $email
                );
                $condition = "user_id = '" . $id_user . "'";
                $simpan = $this->Mglobals->update("tb_geolancer_users", $data, $condition);
                if ($simpan) {
                    //get data user
                    $get_users = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                    //save data to session
                    $ses = array(
                        'login_status' => "succeed login",
                        'id' => $get_users->user_id,
                        'username' => $get_users->username,
                        'level' => $get_users->level,
                        'email' => $get_users->email,
                        'status_user' => $get_users->status,
                        'internal' => $get_users->internal,
                        'project' => $get_users->project,
                        'country' => $get_users->country,
                        'QC' => $get_users->QC
                    );
                    $this->session->set_userdata("login", $ses);

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

    public function user_login()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {

                //get data user
                $get_users = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                //check username
                $check_username = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE username = '" . $get_users->username . "';");
                if ($check_username->jml > 1) {
                    $connya = "<br> <small class='text-danger'>Change your username now, because your username is already in use.</small>";
                } else {
                    $connya = '';
                }

                $tampilan = "<tr>
                                <td style='vertical-align: text-top;'>Username</td>
                                <td style='vertical-align: text-top;'>:</td>
                                <td>" . $get_users->username . " " . $connya . "</td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td>:</td>
                                <td>***********</td>
                            </tr>";

                echo json_encode(array("tampilan" => $tampilan, "username" => $get_users->username));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function save_user_login()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                $username = $this->input->post('username_ul');

                //check username
                $check_username = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE username = '" . $username . "';");
                if ($check_username->jml == 0) {
                    //save to database
                    $data = array(
                        'username' => $username
                    );
                    $condition = "user_id = '" . $id_user . "'";
                    $simpan = $this->Mglobals->update("tb_geolancer_users", $data, $condition);
                    if ($simpan) {
                        //get data user
                        $get_users = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                        //save data to session
                        $ses = array(
                            'login_status' => "succeed login",
                            'id' => $get_users->user_id,
                            'username' => $get_users->username,
                            'level' => $get_users->level,
                            'email' => $get_users->email,
                            'status_user' => $get_users->status,
                            'internal' => $get_users->internal,
                            'project' => $get_users->project,
                            'country' => $get_users->country,
                            'QC' => $get_users->QC
                        );
                        $this->session->set_userdata("login", $ses);

                        $status = "success";
                    } else {
                        $status = "failed";
                    }
                } else {
                    $status = "already";
                }

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function check_pass()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                $pass = $this->input->post('opass_ul');

                $password = hash("sha1", "mapbyyou66" . $pass);

                //check username
                $check_username = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE password = '" . $password . "' AND user_id = '" . $id_user . "';");
                if ($check_username->jml > 0) {
                    $status = "already";
                } else {
                    $status = 'not found';
                }

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function save_user_login2()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
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
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }
}

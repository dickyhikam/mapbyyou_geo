<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of PageError
 *
 * @author dickyhikam
 */
class PageError extends CI_Controller
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

    public function error_access()
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

            //check session status
            if ($login_status == 'succeed login') {
                $link_menu = $this->uri->segment(1);

                //get data user login
                $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                //mandatory data
                $data['tahun'] = $this->modul->Tahun();
                $data['login_name'] = $get_user_login->full_name;
                $data['menu_name'] = $this->uri->segment(1);
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
                $this->load->view('errors/html/error_access_login');
                $this->load->view('template/4_footer');
                $this->load->view('template/5_script');
            } else {
                //versi browser
                $data['name_browser'] = $this->modul->DetectBrowser();
                $data['menu_name'] = $this->uri->segment(1);
                $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

                $this->load->view('template/1_head', $data);
                $this->load->view('errors/html/error_access');
                $this->load->view('template/5_script');
            }
        } else {
            //versi browser
            $data['name_browser'] = $this->modul->DetectBrowser();
            $data['menu_name'] = $this->uri->segment(1);
            $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

            $this->load->view('template/1_head', $data);
            $this->load->view('errors/html/error_access');
            $this->load->view('template/5_script');
        }
    }

    public function error_page()
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

            //check session status
            if ($login_status == 'succeed login') {
                $link_menu = 'PageNotFound';

                //get data user login
                $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                //mandatory data
                $data['tahun'] = $this->modul->Tahun();
                $data['login_name'] = $get_user_login->full_name;
                $data['menu_name'] = 'PageNotFound';
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
                $this->load->view('errors/html/error_404_login');
                $this->load->view('template/4_footer');
                $this->load->view('template/5_script');
            } else {
                //versi browser
                $data['name_browser'] = $this->modul->DetectBrowser();
                $data['menu_name'] = 'PageNotFound';
                $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

                $this->load->view('template/1_head', $data);
                $this->load->view('errors/html/error_404');
                $this->load->view('template/5_script');
            }
        } else {
            //versi browser
            $data['name_browser'] = $this->modul->DetectBrowser();
            $data['menu_name'] = 'PageNotFound';
            $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

            $this->load->view('template/1_head', $data);
            $this->load->view('errors/html/error_404');
            $this->load->view('template/5_script');
        }
    }
}

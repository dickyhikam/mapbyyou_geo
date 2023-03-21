<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ProductivityTeam
 *
 * @author dickyhikam
 */
class ProductivityTeam extends CI_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Modul');
        $this->load->library('Status');

        $this->load->model('DbUsers');
        $this->load->model('DbRegular');
        $this->load->model('DbRegularV2');
        $this->load->model('Mglobals');
    }

    public function index()
    {
        //        //get and check session
        //        $session_data = $this->session->userdata('login');
        //
        //        //check session
        //        if (isset($session_data)) {
        //            $login_status = $session_data['login_status'];
        //            $id_user = $session_data['id'];
        //
        //            //check session status
        //            if ($login_status == 'succeed login') {
        //                //get data user login
        //                $data_ul = '*';
        //                $condition_ul = "user_id = '" . $id_user . "'";
        //                $get_user_login = $this->DbUsers->RowUsers($data_ul, $condition_ul);
        //
        //                //check team 
        //                if ($get_user_login->team == 'MO') {
        //                    //mandatory data
        //                    $data['tahun'] = $this->modul->Tahun();
        //                    $data['login_name'] = $get_user_login->full_name;
        //                    $data['menu_name'] = $this->uri->segment(1);
        //                    //check photo
        //                    if (isset($get_user_login->foto)) {
        //                        $data['profil_photo'] = base_url() . $get_user_login->foto;
        //                    } else {
        //                        $data['profil_photo'] = base_url() . 'assets/images/profil/SampleUser.jpg';
        //                    }
        //                    
        //                    //get first day and last day
        //                    $first_day = $this->modul->FirstDay($this->modul->DateNowDB());
        //                    $last_day = $this->modul->LastDay($this->modul->DateNowDB());
        //                    $day = '';
        //                    for ($x = $first_day; $x <= $last_day; $x++) {
        //                        $day .= '<th>'.$x.'</th>';
        //                    }
        //
        //                    //get month and year
        //                    $data['month_year'] = $this->modul->Month() . ' - ' . $this->modul->Tahun();
        //                    $data['datenya'] = $this->modul->Tahun() . '-' . $this->modul->MonthNumber() . '-01';
        //                    $data['list_day'] = $day;
        //
        //                    $this->load->view('template/1_head', $data);
        //                    $this->load->view('template/2_topbar');
        //                    $this->load->view('template/3_sidebar');
        //                    $this->load->view('productivity/index');
        //                    $this->load->view('template/4_footer');
        //                    $this->load->view('template/5_script');
        //                } else {
        //                    $this->modul->Halaman("Dashboard");
        //                }
        //            } else {
        //                $this->modul->Halaman("Login");
        //            }
        //        } else {
        //            $this->modul->Halaman("Login");
        //        }
        $this->load->view('template/1_head');
        $this->load->view('test');
        $this->load->view('template/5_script');
    }
}

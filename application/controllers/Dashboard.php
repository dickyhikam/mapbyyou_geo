<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Home
 *
 * @author dickyhikam
 */
class Dashboard extends CI_Controller
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

            //check session status
            if ($login_status == 'succeed login') {
                $link_menu = $this->uri->segment(1);

                //get data user login
                $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                //mandatory data
                $data['tahun'] = $this->modul->Tahun();
                $data['login_name'] = $get_user_login->full_name;
                $data['menu_name'] = 'Dashboard';
                $data['page_menu'] = $this->menu->ShowMenu($level_user, $link_menu);
                $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;
                //check photo
                if (isset($get_user_login->foto)) {
                    $data['profil_photo'] = base_url() . $get_user_login->foto;
                } else {
                    $data['profil_photo'] = base_url() . 'assets/images/profil/SampleUser.jpg';
                }

                //get all works
                $jml_poi = $this->Mglobals->getAllQR("SELECT MAX(CASE WHEN total IS NOT NULL THEN 0 ELSE total END) 'test', IF(total IS NOT NULL, total, '0') total, IF(done IS NOT NULL, done, '0') done, IF(inprogress IS NOT NULL, inprogress, '0') inprogress FROM view_dashboard_widget WHERE update_by = '" . $id_user . "';");
                $data['all_work'] = $jml_poi->total;
                $data['done'] = $jml_poi->done;
                $data['pending'] = $jml_poi->inprogress;

                //get first day and last day
                $first_day = $this->modul->FirstDay($this->modul->DateNowDB());
                $last_day = $this->modul->LastDay($this->modul->DateNowDB());
                $day = '';
                $con = '';
                $jml_prod = '';
                $procon = array();
                for ($x = $first_day; $x <= $last_day; $x++) {
                    //check tanggal
                    if (strlen($x) == 1) {
                        $angka = '0' . $x;
                        $string = 'date_0' . $x;

                        $day .= '"' . $angka . '",';
                        $con .= ", MAX(CASE WHEN date_day='" . $angka . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    } else {
                        $string = 'date_' . $x;
                        $day .= '"' . $x . '",';
                        $con .= ", MAX(CASE WHEN date_day='" . $x . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    }
                }

                $list = $this->Mglobals->getAllQ("SELECT email" . $con . " FROM view_geo_prod WHERE update_by = '" . $id_user . "' AND date_month = '" . $this->modul->MonthNumber() . "' AND date_year = '" . $this->modul->Tahun() . "' GROUP BY email;");
                foreach ($list->result() as $row) {
                    for ($i = 0; $i < count($procon); $i++) {
                        $tampung = $procon[$i];
                        $jml_prod .= $row->$tampung . ',';
                    }
                }

                //data claim POI expired
                $expired = $this->modul->ExpiredPOI();
                $con_ex = "status_poi IN ('" . $this->status->POIPending() . "', '" . $this->status->POIClaim() . "') AND "
                    . "DATE(claim_date) <= '" . $expired . "'";
                $data_ex = array(
                    'update_by' => '',
                    'status_poi' => $this->status->POIUnclaim()
                );
                $this->Mglobals->update("tb_geolancer_regular_v2", $data_ex, $con_ex);

                //get month and year
                $data['day'] = $day;
                $data['jml_prod'] = $jml_prod;

                $this->load->view('template/1_head', $data);
                $this->load->view('template/2_topbar');
                $this->load->view('template/3_sidebar');
                $this->load->view('dashboard/index');
                $this->load->view('template/4_footer');
                $this->load->view('template/5_script');
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function test()
    {
        echo $this->modul->ExpiredPOI();
    }
}

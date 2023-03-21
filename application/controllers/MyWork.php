
<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of MyWork
 *
 * @author dickyhikam
 */
class MyWork extends CI_Controller
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

                    //get month and year
                    $data['month_year'] = $this->modul->Month() . ' - ' . $this->modul->Tahun();
                    $data['datenya'] = $this->modul->Tahun() . '-' . $this->modul->MonthNumber() . '-01';
                    $data['day'] = $day;
                    $data['jml_prod'] = $jml_prod;

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('mywork/index');
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

    public function show_table()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {

                //===================
                //Code here
                //===================
                $month_date = date('m', strtotime($this->uri->segment(3)));

                $data = array();
                $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_regular_v2 WHERE update_by = '" . $id_user . "' AND (status_poi = '3' OR status_poi = '5' OR status_poi = '6') AND DATE_FORMAT(update_day,'%m') = '" . $month_date . "'");
                foreach ($list->result() as $row) {
                    $val = array();
                    //check status
                    if ($row->status_poi == $this->status->POIApprove()) {
                        $statusnya = '<span class="right badge badge-success">Approved</span>';
                    } elseif ($row->status_poi == $this->status->POIReject()) {
                        $statusnya = '<span class="right badge badge-danger">Rejected</span>';
                    } elseif ($row->status_poi == $this->status->POIRejectGeolancer()) {
                        $statusnya = '<span class="right badge badge-danger">Rejected by Geolancer</span>';
                    } else {
                        $statusnya = '<span class="right badge badge-info">N/A</span>';
                    }

                    $val[] = date('d-m-Y', strtotime($row->update_date));
                    $val[] = $row->location_name;
                    $val[] = $row->location_name_new;
                    $val[] = $this->modul->ConvertMonth($row->create_month) . ' - ' . $row->create_year;
                    $val[] = $row->country;
                    $val[] = $statusnya;
                    $data[] = $val;
                }
                $output = array("data" => $data);
                echo json_encode($output);
                //===================
                //Code here
                //===================
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }
}

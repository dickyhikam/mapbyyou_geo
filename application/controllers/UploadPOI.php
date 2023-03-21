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
class UploadPOI extends CI_Controller
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
                $this->load->view('upload_poi/index');
                $this->load->view('template/4_footer');
                $this->load->view('template/5_script');
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function upload()
    {
        error_reporting(0);
        set_time_limit(3600); //1.800 seconds = 30 minutes

        $config['upload_path'] = './assets/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_filename'] = '255';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = '2048'; //2 MB

        if (isset($_FILES['file']['name'])) {
            if (0 < $_FILES['file']['error']) {
                $status = "Error during file upload " . $_FILES['file']['error'];
            } else {
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('file')) {
                    $datafile = $this->upload->data();

                    $target_path = $config['upload_path'] . $datafile['file_name'];
                    require('./assets/excel/php-excel-reader/excel_reader2.php');
                    require('./assets/excel/SpreadsheetReader.php');

                    $Reader = new SpreadsheetReader($target_path);
                    $numrow = 1;
                    foreach ($Reader as $row) {
                        if ($numrow > 1) {
                            //Deklar data excel
                            $kolom_excel1 = trim($row[0]); //POI ID
                            $kolom_excel2 = trim($row[1]); //Location Name
                            $kolom_excel3 = trim($row[2]); //Recidential Name
                            $kolom_excel4 = trim($row[3]); //Street Name
                            $kolom_excel5 = trim($row[4]); //House No
                            $kolom_excel6 = trim($row[5]); //RT
                            $kolom_excel7 = trim($row[6]); //RW
                            $kolom_excel8 = trim($row[7]); //latitude
                            $kolom_excel9 = trim($row[8]); //longitude
                            $kolom_excel10 = trim($row[9]); //Year
                            $kolom_excel11 = trim($row[10]); //Month
                            $kolom_excel12 = trim($row[11]); //Day
                            $kolom_excel15 = trim($row[12]); //Email
                            $kolom_excel13 = '1'; //Status
                            $kolom_excel14 = 'https://behindthescenes.geolancer.app/poi/campaign?id=' . trim($row[0]) . '&campaign_id=8042f02d-5bba-48e5-b17f-c824b268115d'; //URL

                            //check poi id
                            $check = $this->Mglobals->getAllQR("SELECT COUNT(poi_id) AS jml FROM tb_project WHERE poi_id = '" . $kolom_excel1 . "';")->jml;
                            if ($check == '0') {
                                //save poi project
                                $data = array(
                                    'poi_id' => $kolom_excel1,
                                    'location_name' => $kolom_excel2,
                                    'residential_area' => $kolom_excel3,
                                    'street_name' => $kolom_excel4,
                                    'house_no' => $kolom_excel5,
                                    'rt' => $kolom_excel6,
                                    'rw' => $kolom_excel7,
                                    'latitude' => $kolom_excel8,
                                    'longitude' => $kolom_excel9,
                                    'status_poi' => $kolom_excel13,
                                    'create_date' => $kolom_excel12,
                                    'create_month' => $kolom_excel11,
                                    'create_year' => $kolom_excel10,
                                    'url' => $kolom_excel14,
                                    'email' => $kolom_excel15
                                );

                                $this->Mglobals->add("tb_project", $data);
                            }
                        }
                        $numrow++;
                    }

                    unlink($target_path);
                    //unlink file excel
                    $status = "success";
                } else {
                    $status = $this->upload->display_errors();
                }
            }
        } else {
            $status = "File not exits";
        }
        echo json_encode(array("status" => $status));
    }

    public function test_upload()
    {
        error_reporting(0);
        $target_path = 'assets/13.xlsx';
        //        $target_path = 'C:/Users/HP/Documents/File Projek/TI.xlsx';
        //        echo $target_path;
        require('./assets/excel/php-excel-reader/excel_reader2.php');
        require('./assets/excel/SpreadsheetReader.php');

        set_time_limit(1800); //1.800 seconds = 30 minutes
        $numrow = 1;
        $test = '';
        $no = 1;
        $Reader = new SpreadsheetReader($target_path);
        foreach ($Reader as $row) {
            if ($numrow > 1) {
                //Deklar data excel
                $kolom_excel1 = trim($row[0]); //POI ID
                $kolom_excel2 = trim($row[1]); //Location Name
                $kolom_excel3 = trim($row[2]); //Recidential Name
                $kolom_excel4 = trim($row[3]); //Street Name
                $kolom_excel5 = trim($row[4]); //House No
                $kolom_excel6 = trim($row[5]); //RT
                $kolom_excel7 = trim($row[6]); //RW
                $kolom_excel8 = trim($row[7]); //latitude
                $kolom_excel9 = trim($row[8]); //longitude
                $kolom_excel10 = trim($row[9]); //Year
                $kolom_excel11 = trim($row[10]); //Month
                $kolom_excel12 = trim($row[11]); //Day
                $kolom_excel13 = '1'; //Status
                $kolom_excel14 = 'https://behindthescenes.geolancer.app/poi/campaign?id=' . trim($row[0]) . '&campaign_id=8042f02d-5bba-48e5-b17f-c824b268115d'; //URL

                //check poi id
                $check = $this->Mglobals->getAllQR("SELECT COUNT(poi_id) AS jml FROM tb_project WHERE poi_id = '" . $kolom_excel1 . "';")->jml;
                if ($check == '0') {
                    //save poi project
                    // $data = array(
                    //     'poi_id' => $kolom_excel1,
                    //     'location_name' => $kolom_excel2,
                    //     'residential_area' => $kolom_excel3,
                    //     'street_name' => $kolom_excel4,
                    //     'house_no' => $kolom_excel5,
                    //     'rt' => $kolom_excel6,
                    //     'rw' => $kolom_excel7,
                    //     'latitude' => $kolom_excel8,
                    //     'longitude' => $kolom_excel9,
                    //     'status_poi' => $kolom_excel13,
                    //     'create_date' => $kolom_excel12,
                    //     'create_month' => $kolom_excel11,
                    //     'create_year' => $kolom_excel10,
                    //     'url' => $kolom_excel14
                    // );

                    // $this->Mglobals->add("tb_project_test", $data);
                    $test .= $no++ . '. ' . $kolom_excel1 . ' = kosong <br>';
                }
            }
            $numrow++;
        }

        echo $test;
    }
}

<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of QC_Download
 *
 * @author dickyhikam
 */
class QC_Download extends CI_Controller
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

                    //get team
                    $list_team = '';
                    $query_team = $this->Mglobals->getAllQ("SELECT team FROM tb_geolancer_users WHERE team <> '' GROUP BY team ORDER BY team;");
                    foreach ($query_team->result() as $row) {
                        $list_team .= '<div class="col-2">
                                        <div class="card" id="card_' . $row->team . '" style="cursor: pointer;" onclick="show_team(' . "'" . $row->team . "'" . ');">
                                            <div class="card-body">
                                                <center>
                                                    <h3>' . $row->team . '</h3>
                                                </center>
                                            </div>
                                        </div>
                                    </div>';
                    }

                    //get user
                    $list_user = '';
                    $query_user = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_users WHERE team = 'MO' AND status = '1' AND (level = '4' OR user_id = '" . $id_user . "') ORDER BY email;");
                    foreach ($query_user->result() as $row) {
                        $list_user .= '<option value="' . $row->user_id . '">' . $row->email . '</option>';
                    }

                    $data['month_year'] = $this->modul->Tahun();
                    $data['datenya'] = $this->modul->Tahun() . '-' . $this->modul->MonthNumber() . '-01';
                    $data['list_team'] = $list_team;
                    $data['list_user'] = $list_user;

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('management_qc/download/index');
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

    public function show_table_poi()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {

                //===================
                //Code here
                //===================
                $year_date = date('Y', strtotime($this->uri->segment(3)));
                $year_date2 = date('y', strtotime($this->uri->segment(3)));
                $team = $this->uri->segment(4);

                //get count week in year
                $cw_year = $this->modul->PeriodeYear($year_date);
                $head_year = '';
                $sum = '';
                for ($i = 1; $i <= $cw_year; $i++) {
                    if (strlen($i) == 1) {
                        $angka = '0' . $i;
                    } else {
                        $angka = $i;
                    }
                    $periode = 'W' . $angka . $year_date2;
                    $head_year .= '<th>' . $i . '</th>';
                    $sum .= ", SUM(CASE WHEN qc_periode = '" . $periode . "' THEN jml ELSE 0 END) 'text_" . $i . "'";
                }

                $head = '<tr>
                            <th>Email</th>
                            ' . $head_year . '
                        </tr>';

                //get project
                $body = '';
                $query_project = $this->Mglobals->getAllQ("SELECT update_by, email" . $sum . " FROM view_geo_prod_status WHERE SUBSTRING(qc_periode, 4, 2) = '" . $year_date2 . "' AND status_poi IN ('3', '5') AND team = '" . $team . "' GROUP BY email;");
                foreach ($query_project->result() as $row) {

                    $body .= '<tr>'
                        . '<td>' . $row->email . '</td>';

                    for ($i = 1; $i <= $cw_year; $i++) {
                        if (strlen($i) == 1) {
                            $angka1 = '0' . $i;
                        } else {
                            $angka1 = $i;
                        }
                        $periode = 'W' . $angka1 . $year_date2;
                        $tampung = 'text_' . $i;
                        //check 0
                        if ($row->$tampung == '0') {
                            $body .= '<td style="text-align: center; color: #dee2e6;">' . $row->$tampung . '</td>';
                        } elseif ($row->$tampung >= '200') {
                            $btn = '<button class="btn waves-effect waves-light btn-primary btn-sm" id="btn_download" onclick="modal_assign(' . "'" . $row->update_by . "'" . ', ' . "'" . $row->$tampung . "'" . ', ' . "'" . $periode . "'" . ')"> <i class="fas fa-tasks"></i> </button>';
                            //check qc
                            $check_data = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_qc WHERE periode = '" . $periode . "' AND user_id = '" . $row->update_by . "';")->jml;
                            if ($check_data > 0) {
                                $body .= '<td style="text-align: center; color: #1cb99a;">' . $row->$tampung . '</td>';
                            } else {
                                $body .= '<td style="text-align: center;">' . $row->$tampung . ' ' . $btn . '</td>';
                            }
                        } else {
                            $body .= '<td style="text-align: center; center; color: red;">' . $row->$tampung . '</td>';
                        }
                    }

                    $body .= '</tr>';
                }

                $table = '<thead>
                    ' . $head . '
                </thead>
                <tbody>
                    ' . $body . '
                </tbody>';

                echo json_encode(array("table" => $table));
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

    public function save_qc()
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
                $user_qa = $this->input->post('user');
                $periode = $this->input->post('periode');
                $total = $this->input->post('total');
                $datetimenow = $this->modul->DateTimeNowDB();
                $statusnya = $this->status->QCProgress();
                $assign1 = $this->input->post('assign');
                //check assign
                if ($id_user == $assign1) {
                    $assign = '';
                } else {
                    $assign = $this->input->post('assign');
                }

                //check total POIs
                if ($total > '1000') {
                    $nilai = (10 / 100) * $total;
                    $limit = 'LIMIT ' . round($nilai);
                } else {
                    $limit = 'LIMIT 100';
                }

                //check quality control
                $check_qc = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_qc WHERE periode = '" . $periode . "' AND user_id = '" . $user_qa . "';")->jml;
                if ($check_qc > 0) {
                    $status = "found";
                } else {
                    //save to database
                    $data = array(
                        'user_id' => $user_qa,
                        'periode' => $periode,
                        'create_date' => $datetimenow,
                        'create_by' => $id_user,
                        'assign_by' => $assign,
                        'status' => $statusnya
                    );
                    $simpan = $this->Mglobals->add("tb_geolancer_qc", $data);
                    if ($simpan) {
                        if ($id_user == $assign1) {
                            $status = "success";
                        } else {
                            $status = "assign";
                        }

                        //checklist sample download
                        $query_project = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_regular_v2 a, tb_geolancer_users b WHERE a.update_by = b.user_id AND a.update_by = '" . $user_qa . "' AND a.qc_periode = '" . $periode . "' ORDER BY RAND() " . $limit . ";");
                        foreach ($query_project->result() as $row) {
                            //update qc status in table tb_geolancer_regular_v2
                            $con = array(
                                'poi_id' => $row->poi_id
                            );
                            $data2 = array(
                                'qc_status' => $this->status->QCProgress1(),
                                'qc_sel' => $this->status->QCChoose()
                            );
                            $this->Mglobals->update("tb_geolancer_regular_v2", $data2, $con);
                        }
                    } else {
                        $status = "failed";
                    }
                }

                echo json_encode(array("status" => $status));
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

    public function download_poi()
    {
        $user_qa = $this->uri->segment(3);
        $periode = $this->uri->segment(4);
        $datetimenow = $this->modul->DateTimeNowDB();

        // Load plugin PHPExcel nya
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        //update file download
        $con = array(
            'periode' => $periode,
            'user_id' => $user_qa
        );
        $data2 = array(
            'download_date' => $datetimenow
        );
        $this->Mglobals->update("tb_geolancer_qc", $data2, $con);

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            )
        );

        // Buat header tabel nya pada baris yang diinginkan
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "Email");
        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Status POI");
        $excel->setActiveSheetIndex(0)->setCellValue('C1', "Review Date");
        $excel->setActiveSheetIndex(0)->setCellValue('D1', "POI ID");
        $excel->setActiveSheetIndex(0)->setCellValue('E1', "Location Name New");
        $excel->setActiveSheetIndex(0)->setCellValue('F1', "Building Name");
        $excel->setActiveSheetIndex(0)->setCellValue('G1', "Address New");
        $excel->setActiveSheetIndex(0)->setCellValue('H1', "Unit No New");
        $excel->setActiveSheetIndex(0)->setCellValue('I1', "Level No New");
        $excel->setActiveSheetIndex(0)->setCellValue('J1', "Latlong Entrance");
        $excel->setActiveSheetIndex(0)->setCellValue('K1', "Distance Entrance");
        $excel->setActiveSheetIndex(0)->setCellValue('L1', "Latlong Middle of Building");
        $excel->setActiveSheetIndex(0)->setCellValue('M1', "Distance Middle of Building");
        $excel->setActiveSheetIndex(0)->setCellValue('N1', "Category New");
        $excel->setActiveSheetIndex(0)->setCellValue('O1', "Country");
        $excel->setActiveSheetIndex(0)->setCellValue('P1', "City");
        $excel->setActiveSheetIndex(0)->setCellValue('Q1', "PII Status");
        $excel->setActiveSheetIndex(0)->setCellValue('R1', "Remark");
        //Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('P1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('Q1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('R1')->applyFromArray($style_col);

        // Memanggil keseluruhan data dari database
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 2 (setelah header)
        $query_project = $this->Mglobals->getAllQ("SELECT *, a.country AS countrynya FROM tb_geolancer_regular_v2 a, tb_geolancer_users b WHERE a.update_by = b.user_id AND a.update_by = '" . $user_qa . "' AND a.qc_periode = '" . $periode . "' AND qc_sel = '" . $this->status->QCChoose() . "';");
        foreach ($query_project->result() as $row) {
            //check status
            if ($row->status_poi == $this->status->POIApprove()) {
                $statusnya = 'Approve';
            } elseif ($row->status_poi == $this->status->POIReject()) {
                $statusnya = 'Reject';
            }

            //check data location
            if ($row->latlong_ent == '') {
                $lat_input = '0';
                $lng_input = '0';
            } else {
                $loc2 = (explode(",", $row->latlong_ent));
                $lat_input = $loc2[0];
                $lng_input = $loc2[1];
            }
            //check data location
            if ($row->latlong_building == '') {
                $lat_input_bld = '0';
                $lng_input_bld = '0';
            } else {
                $loc3 = (explode(",", $row->latlong_building));
                $lat_input_bld = $loc3[0];
                $lng_input_bld = $loc3[1];
            }

            //result entrance
            $result_ent = $row->distance * 1000;

            if ($row->latlong_building == '') {
                $result_bld = 0;
            } else {
                //result building
                $kilo_bld = $this->modul->Distance($lat_input, $lng_input, $lat_input_bld, $lng_input_bld, 'K');
                $result_bld = $kilo_bld * 1000;
            }

            $email = $row->email;

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $email);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $statusnya);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, date('d-m-Y', strtotime($row->update_date)));
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row->poi_id);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row->location_name_new);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $row->building_name);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $row->address_new);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $row->unit_no_new);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $row->level_no_new);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $row->latlong_ent);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $result_ent);
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, $row->latlong_building);
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, $result_bld);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $numrow, $row->category_new);
            $excel->setActiveSheetIndex(0)->setCellValue('O' . $numrow, $row->countrynya);
            $excel->setActiveSheetIndex(0)->setCellValue('P' . $numrow, $row->city);
            $excel->setActiveSheetIndex(0)->setCellValue('Q' . $numrow, $row->pii_status);
            $excel->setActiveSheetIndex(0)->setCellValue('R' . $numrow, $row->remark);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('N' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('O' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('P' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('Q' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('R' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Result QC");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Result QC ' . $email . ' (' . $periode . ').xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}

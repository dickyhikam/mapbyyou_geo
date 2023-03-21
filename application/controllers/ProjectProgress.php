<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ProjectProgress
 *
 * @author dickyhikam
 */
class ProjectProgress extends CI_Controller
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

                    $data['table_v3'] = $this->data_table_v3();
                    $data['table2_v3'] = $this->data_table2_v3();
                    $data['card_v3'] = $this->data_card_v3();
                    $data['table3_v3'] = $this->data_table3_v3();

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('project_progress/index');
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

    public function table_v3()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {

                $table1 = $this->data_table_v3();
                $table2 = $this->data_table2_v3();
                $table3 = $this->data_table3_v3();

                echo json_encode(array("table1" => $table1, "table2" => $table2, "table3" => $table3));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    private function data_table_v3()
    {
        $head = '<tr>
                            <th>Project Type</th>';
        $jenis = array("Free", "Claimed");

        //get country
        $query_country = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_country ORDER BY `order` ASC;");
        foreach ($query_country->result() as $row) {
            for ($i = 0; $i < count($jenis); $i++) {
                $head .= '<th style="text-align: center;">' . $row->code . ' ' . $jenis[$i] . '</th>';
            }
        }
        $head .= '</tr>';

        //get project
        $body = '';
        $query_project = $this->Mglobals->getAllQ("SELECT qa_work, "
            . "MAX(CASE WHEN country='ID' THEN jml ELSE 0 END) 'ID', "
            . "MAX(CASE WHEN country='SG' THEN jml ELSE 0 END) 'SG', "
            . "MAX(CASE WHEN country='PH' THEN jml ELSE 0 END) 'PH', "
            . "MAX(CASE WHEN country='IN' THEN jml ELSE 0 END) 'IN', "
            . "MAX(CASE WHEN country='US' THEN jml ELSE 0 END) 'US' "
            . "FROM view_count_free WHERE qa_work <> 'NOT TO REVIEW' GROUP BY qa_work "
            . "ORDER BY FIELD(qa_work,'URGENT', 'PRIORITY', 'REGULAR', 'BACKLOG');");
        foreach ($query_project->result() as $row) {

            $jml_claim = $this->Mglobals->getAllQR("SELECT qa_work, "
                . "MAX(CASE WHEN country='ID' THEN jml ELSE 0 END) 'ID', "
                . "MAX(CASE WHEN country='SG' THEN jml ELSE 0 END) 'SG', "
                . "MAX(CASE WHEN country='PH' THEN jml ELSE 0 END) 'PH', "
                . "MAX(CASE WHEN country='IN' THEN jml ELSE 0 END) 'IN', "
                . "MAX(CASE WHEN country='US' THEN jml ELSE 0 END) 'US' "
                . "FROM view_count_claim WHERE qa_work <> 'NOT TO REVIEW' AND "
                . "qa_work = '" . $row->qa_work . "';");

            $body .= '<tr>'
                . '<th scope="row">' . $row->qa_work . '</th>'
                . '<td style="text-align: center;">' . $row->ID . '</td>'
                . '<td style="text-align: center;">' . $jml_claim->ID . '</td>'
                . '<td style="text-align: center;">' . $row->SG . '</td>'
                . '<td style="text-align: center;">' . $jml_claim->SG . '</td>'
                . '<td style="text-align: center;">' . $row->PH . '</td>'
                . '<td style="text-align: center;">' . $jml_claim->PH . '</td>'
                . '<td style="text-align: center;">' . $row->IN . '</td>'
                . '<td style="text-align: center;">' . $jml_claim->IN . '</td>'
                . '<td style="text-align: center;">' . $row->US . '</td>'
                . '<td style="text-align: center;">' . $jml_claim->US . '</td>'
                . '</tr>';
        }

        $table = '<thead>
                    ' . $head . '
                </thead>
                <tbody>
                    ' . $body . '
                </tbody>';

        return $table;
    }

    private function data_table2_v3()
    {
        $head = '<tr>
                            <th style="min-width: 150px;">Date</th>';

        $procon = array();

        //get project
        $query_project = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_project WHERE `name` IN ('URGENT', 'PRIORITY', 'REGULAR', 'BACKLOG') ORDER BY `order` ASC;");
        foreach ($query_project->result() as $row2) {
            //get country
            $query_country = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_country ORDER BY `order` ASC;");
            foreach ($query_country->result() as $row) {
                $head .= '<th style="text-align: center; min-width: 100px;">' . $row2->name . ' ' . $row->code . '</th>';
                $procon[] = $row2->name . "_" . $row->code;
            }
        }


        $head .= '<th style="text-align: center;">Total</th></tr>';

        //get project
        $body = '';
        $query_poi = $this->Mglobals->getAllQ("SELECT * FROM view_pp_backlog_regular;");
        foreach ($query_poi->result() as $row) {
            $body .= '<tr>'
                . '<th scope="row">' . $row->create_day . '-' . $row->create_month . '-' . $row->create_year . '</th>';
            $total = 0;
            for ($i = 0; $i < count($procon); $i++) {
                $tampung = $procon[$i];
                //check 0
                if ($row->$tampung == '0') {
                    $body .= '<td style="text-align: center; color: #dee2e6;">' . $row->$tampung . '</td>';
                } else {
                    $body .= '<td style="text-align: center;">' . $row->$tampung . '</td>';
                }
                $total += $row->$tampung;
            }
            $body .= '<td style="text-align: center;">' . $total . '</td></tr>';
        }

        $table = '<thead>
                    ' . $head . '
                </thead>
                <tbody>
                    ' . $body . '
                </tbody>';

        return $table;
    }

    private function data_table3_v3()
    {
        $head = '<tr>
                            <th style="min-width: 150px;">Project</th>';

        $con = '';
        $procon = array();

        //get country
        $query_country = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_country ORDER BY `order` ASC;");
        foreach ($query_country->result() as $row) {
            $head .= '<th style="text-align: center; min-width: 100px;">' . $row->code . '</th>';
            $con .= ", COUNT(CASE WHEN country='" . $row->code . "' THEN email END) '" . $row->code . "'";
            $procon[] = $row->code;
        }


        $head .= '<th style="text-align: center;">Total</th></tr>';

        //get project
        $body = '';
        $query_poi = $this->Mglobals->getAllQ("SELECT qa_work"
            . $con
            . "FROM view_geo_user_claim GROUP BY qa_work "
            . "ORDER BY FIELD(qa_work, 'URGENT', 'PRIORITY', 'REGULAR', 'BACKLOG')");
        foreach ($query_poi->result() as $row) {
            $body .= '<tr>'
                . '<th scope="row">' . $row->qa_work . '</th>';
            $total = 0;
            for ($i = 0; $i < count($procon); $i++) {
                $tampung = $procon[$i];
                //check 0
                if ($row->$tampung == '0') {
                    $body .= '<td style="text-align: center; color: #dee2e6;">' . $row->$tampung . '</td>';
                } else {
                    $body .= '<td style="text-align: center;">' . $row->$tampung . '</td>';
                }
                $total += $row->$tampung;
            }

            $body .= '<td style="text-align: center;">' . $total . '</td></tr>';
        }

        $table = '<thead>
                    ' . $head . '
                </thead>
                <tbody>
                    ' . $body . '
                </tbody>';

        return $table;
    }

    private function data_card_v3()
    {
        //get jumlah all data
        $data_all = $this->Mglobals->getAllQR("SELECT SUM(jml) AS total FROM view_count_free WHERE qa_work <> 'NOT TO REVIEW';")->total;

        $card = '<div class="col-3">
                    <div class="card-box tilebox-two">
                        <i class="fas fa-layer-group float-right text-muted"></i>
                        <h6 class="text-primary text-uppercase m-b-15 m-t-10">All POI</h6>
                        <h2 class="m-b-10"><span data-plugin="counterup">' . number_format($data_all, 0, "", ".") . '</span> POI</h2>
                    </div>
                </div>';
        $query_project = $this->Mglobals->getAllQ("SELECT qa_work, SUM(jml) AS total FROM view_count_free WHERE qa_work <> 'NOT TO REVIEW' GROUP BY qa_work ORDER BY FIELD(qa_work,'URGENT', 'PRIORITY', 'REGULAR', 'BACKLOG');");
        foreach ($query_project->result() as $row) {
            $card .= '<div class="col-3">
                        <div class="card-box tilebox-two">
                            <i class="fas fa-layer-group float-right text-muted"></i>
                            <h6 class="text-primary text-uppercase m-b-15 m-t-10">' . $row->qa_work . ' POI</h6>
                            <h2 class="m-b-10"><span data-plugin="counterup">' . number_format($row->total, 0, "", ".") . '</span> POI</h2>
                        </div>
                    </div>';
        }
        return $card;
    }
}

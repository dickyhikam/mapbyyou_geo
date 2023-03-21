<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Productivity
 *
 * @author dickyhikam
 */
class Productivity extends CI_Controller
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

                    //get month and year
                    $data['month_year'] = $this->modul->Month() . ' - ' . $this->modul->Tahun();
                    $data['datenya'] = $this->modul->Tahun() . '-' . $this->modul->MonthNumber() . '-01';
                    $data['day'] = '';
                    $data['list_team'] = $list_team;
                    $data['list_jml'] = '';

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('productivity/index');
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

            //check session status
            if ($login_status == 'succeed login') {

                //===================
                //Code here
                //===================
                $month_date = date('m', strtotime($this->uri->segment(3)));
                $year_date = date('Y', strtotime($this->uri->segment(3)));
                $date = date('Y-m-d', strtotime($this->uri->segment(3)));
                $team = $this->uri->segment(4);

                //get first day and last day
                $first_day = $this->modul->FirstDay($date);
                $last_day = $this->modul->LastDay($date);
                $day = '';
                $con = '';
                $procon = array();
                for ($x = $first_day; $x <= $last_day; $x++) {
                    //check tanggal
                    if (strlen($x) == 1) {
                        $angka = '0' . $x;
                        $string = 'date_0' . $x;

                        $day .= '<th style="text-align: center;">' . $angka . '</th>';
                        $con .= ", MAX(CASE WHEN date_day='" . $angka . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    } else {
                        $string = 'date_' . $x;
                        $day .= '<th>' . $x . '</th>';
                        $con .= ", MAX(CASE WHEN date_day='" . $x . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    }
                }

                $name = '';
                $list = $this->Mglobals->getAllQ("SELECT email" . $con . " FROM view_geo_prod WHERE team = '" . $team . "' AND date_month = '" . $month_date . "' AND date_year = '" . $year_date . "' GROUP BY email;");
                foreach ($list->result() as $row) {
                    $name .= '<tr>
                                <td>' . $row->email . '</td>';
                    $total = 0;
                    for ($i = 0; $i < count($procon); $i++) {
                        $tampung = $procon[$i];
                        $total += $row->$tampung;
                        //check 0
                        if ($row->$tampung == '0') {
                            $name .= '<td style="text-align: center; color: #dee2e6;">' . $row->$tampung . '</td>';
                        } else {
                            $name .= '<td style="text-align: center;">' . $row->$tampung . '</td>';
                        }
                    }
                    $name .= '<td style="text-align: center;">' . $total . '</td>';
                    $name .= '</tr>';
                }

                $table = '<thead>
                            <tr>
                                <th>Email/Date</th>
                                ' . $day . '
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $name . '
                        </tbody>';

                $table2 = $this->show_table2($month_date, $team, $year_date);

                echo json_encode(array("table" => $table, "table2" => $table2));
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

    private function show_table2($month_date, $team, $year_date)
    {
        $name = '';
        $list = $this->Mglobals->getAllQ("SELECT email, team, "
            . "SUM(CASE WHEN status_poi='" . $this->status->POIClaim() . "' THEN jml ELSE 0 END) 'total_" . $this->status->POIClaim() . "', "
            . "SUM(CASE WHEN status_poi='" . $this->status->POIPending() . "' THEN jml ELSE 0 END) 'total_" . $this->status->POIPending() . "', "
            . "SUM(CASE WHEN status_poi='" . $this->status->POIApprove() . "' THEN jml ELSE 0 END) 'total_" . $this->status->POIApprove() . "', "
            . "SUM(CASE WHEN status_poi='" . $this->status->POIReject() . "' THEN jml ELSE 0 END) 'total_" . $this->status->POIReject() . "', "
            . "SUM(CASE WHEN status_poi='" . $this->status->POIRejectGeolancer() . "' THEN jml ELSE 0 END) 'total_" . $this->status->POIRejectGeolancer() . "', "
            . "SUM(jml) AS total "
            . "FROM view_geo_prod_status WHERE DATE_FORMAT(claim_date,'%m') = '" . $month_date . "' AND DATE_FORMAT(claim_date,'%Y') = '" . $year_date . "' AND team = '" . $team . "' GROUP BY email;");
        foreach ($list->result() as $row) {
            $str_claim = 'total_' . $this->status->POIClaim();
            $str_pending = 'total_' . $this->status->POIPending();
            $str_approve = 'total_' . $this->status->POIApprove();
            $str_reject = 'total_' . $this->status->POIReject();
            $str_rejectgeo = 'total_' . $this->status->POIRejectGeolancer();

            $name .= '<tr>
                        <td>' . $row->email . '</td>';

            if ($row->$str_claim == '0') {
                $name .= '<td style="text-align: center; color: #dee2e6;">' . $row->$str_claim . '</td>';
            } else {
                $name .= '<td style="text-align: center;">' . $row->$str_claim . '</td>';
            }
            if ($row->$str_pending == '0') {
                $name .= '<td style="text-align: center; color: #dee2e6;">' . $row->$str_pending . '</td>';
            } else {
                $name .= '<td style="text-align: center;">' . $row->$str_pending . '</td>';
            }
            if ($row->$str_approve == '0') {
                $name .= '<td style="text-align: center; color: #dee2e6;">' . $row->$str_approve . '</td>';
            } else {
                $name .= '<td style="text-align: center;">' . $row->$str_approve . '</td>';
            }
            if ($row->$str_reject == '0') {
                $name .= '<td style="text-align: center; color: #dee2e6;">' . $row->$str_reject . '</td>';
            } else {
                $name .= '<td style="text-align: center;">' . $row->$str_reject . '</td>';
            }
            if ($row->$str_rejectgeo == '0') {
                $name .= '<td style="text-align: center; color: #dee2e6;">' . $row->$str_rejectgeo . '</td>';
            } else {
                $name .= '<td style="text-align: center;">' . $row->$str_rejectgeo . '</td>';
            }
            $name .= '<td style="text-align: center;">' . $row->total . '</td>
                        </tr>';
        }

        $table = '<thead>
                    <tr>
                        <th>Email</th>
                        <th style="text-align: center;">Claim</th>
                        <th style="text-align: center;">Pending</th>
                        <th style="text-align: center;">Approve</th>
                        <th style="text-align: center;">Reject</th>
                        <th style="text-align: center;">Reject Geolancer</th>
                        <th style="text-align: center;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $name . '
                </tbody>';

        return $table;
    }
}

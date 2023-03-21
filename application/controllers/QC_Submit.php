<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of QC_Submit
 *
 * @author dickyhikam
 */
class QC_Submit extends CI_Controller
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

                    //get weeknum
                    $list_weeknum = '';
                    $cw_year = $this->modul->PeriodeYear($this->modul->Tahun());
                    for ($i = 1; $i <= $cw_year; $i++) {
                        if (strlen($i) == 1) {
                            $angka = '0' . $i;
                        } else {
                            $angka = $i;
                        }
                        $periode = 'W' . $angka . date('y', strtotime($this->modul->Tahun()));

                        $list_weeknum .= '<div class="col-2">
                                        <div class="card" id="card_' . $periode . '" style="cursor: pointer;" onclick="show_periode(' . "'" . $periode . "'" . ');">
                                            <div class="card-body">
                                                <center>
                                                    <h3>' . $periode . '</h3>
                                                </center>
                                            </div>
                                        </div>
                                    </div>';
                    }

                    $data['list_weeknum'] = $list_weeknum;
                    $data['month_year'] = $this->modul->Tahun();
                    $data['datenya'] = $this->modul->Tahun() . '-' . $this->modul->MonthNumber() . '-01';

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('management_qc/submit/index');
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

    public function table()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $level_user = $session_data['level'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                $tahun = date('Y', strtotime($this->uri->segment(3)));
                $periode = $this->uri->segment(4);

                //check level
                if ($level_user == '2') {
                    $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_qc WHERE DATE_FORMAT(create_date,'%Y') = '" . $tahun . "' AND create_by = '" . $id_user . "' AND periode = '" . $periode . "';");
                } else {
                    $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_qc WHERE DATE_FORMAT(create_date,'%Y') = '" . $tahun . "' AND periode = '" . $periode . "';");
                }

                $data = array();
                foreach ($list->result() as $row) {
                    $val = array();

                    //get user qc
                    $get_user_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->create_by . "';");
                    $name_qc = $get_user_qc->email;

                    //get user qa
                    $get_user_qa = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->user_id . "';");
                    $name_qa = $get_user_qa->email;
                    $team_qa = $get_user_qa->team;

                    //get user assign qc
                    $get_assign_qc = $this->Mglobals->getAllQR("SELECT *, COUNT(*) AS jml FROM tb_geolancer_users WHERE user_id = '" . $row->assign_by . "';");
                    if ($get_assign_qc->jml > 0) {
                        $assign_qc = $get_assign_qc->email;
                    } else {
                        $assign_qc = '-';
                    }

                    //check status
                    if ($row->status == $this->status->QCProgress()) {
                        $statusnya = '<span class="badge badge-warning">Inprogress</span>';
                        $btn = '<button class="btn btn-sm btn-warning" onclick="modal_add(' . "'" . $row->id . "'" . ', ' . "'" . $get_user_qa->email . "'" . ', ' . "'" . $row->periode . "'" . ');" style="margin-right: 10px;"><i class="fas fa-edit"></i></button>'
                            . '<button class="btn btn-sm btn-primary" onclick="download(' . "'" . $row->user_id . "'" . ', ' . "'" . $row->periode . "'" . ');"><i class="fas fa-file-excel"></i></button>';
                    } elseif ($row->status == $this->status->QCDone()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-danger">Not Been QA Edit</span>';
                        $btn = '<button class="btn btn-sm btn-primary" onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';
                    } elseif ($row->status == $this->status->QCProgressQA()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-warning">Inprogress QA Edit</span>';
                        $btn = '<button class="btn btn-sm btn-info" onclick="modal_status(' . "'" . $row->id . "'" . ', ' . "'" . $name_qa . "'" . ');" style="margin-right: 10px;"><i class="fas fa-check"></i></button>'
                            . '<button class="btn btn-sm btn-primary" onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';

                        //update status
                        if ($row->edit == '100') {
                            //save to database
                            $data1 = array(
                                'status' => $this->status->QCDoneQA()
                            );
                            $condition1['id'] = $row->id;
                            $this->Mglobals->update("tb_geolancer_qc", $data1, $condition1);
                        }
                    } else {
                        $statusnya = '<span class="badge badge-success">Done QC and QA Edit</span>';
                        $btn = '<button class="btn btn-sm btn-primary" onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';
                    }

                    //check score
                    if ($row->score == '0') {
                        $score = '-';
                        $update = '-';
                        $hasil = '-';
                    } else {
                        //get progress edit
                        if ($row->edit >= '100') {
                            $hasil = '<b style="color: green;">' . $row->edit . '%</b>';
                        } else {
                            $hasil = '<b style="color: red;">' . $row->edit . '%</b>';
                        }

                        if ($get_user_qa->team == 'MO' || $get_user_qa->team == 'CA') {
                            if ($row->score >= '98') {
                                $score = '<b style="color: green;">' . $row->score . '%</b>';
                            } else {
                                $score = '<b style="color: red;">' . $row->score . '%</b>';
                            }
                        } else {
                            if ($row->score >= '95') {
                                $score = '<b style="color: green;">' . $row->score . '%</b>';
                            } else {
                                $score = '<b style="color: red;">' . $row->score . '%</b>';
                            }
                        }
                        $update = date("d-m-Y", strtotime($row->update_date));
                    }

                    $val[] = date("d-m-Y", strtotime($row->create_date));
                    $val[] = $name_qc;
                    $val[] = $assign_qc;
                    $val[] = $row->periode;
                    $val[] = $name_qa;
                    $val[] = $team_qa;
                    $val[] = $update;
                    $val[] = $score;
                    $val[] = $hasil;
                    $val[] = $statusnya;
                    $val[] = $btn;
                    $data[] = $val;
                }
                $output = array("data" => $data);
                echo json_encode($output);
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function add()
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
                $id = $this->input->post('id');
                $score = $this->input->post('score');
                $remark = $this->input->post('remark');
                $datetimenow = $this->modul->DateTimeNowDB();

                //get data qc
                $data_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_qc WHERE id = '" . $id . "';");

                //get data user login
                $get_score = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users a, tb_geolancer_team b WHERE a.team = b.code AND a.user_id = '" . $data_qc->user_id . "';");

                //check score
                if ($score >= $get_score->score_qc) {
                    $edit_status = $this->status->QADone();
                    $statusnya = $this->status->QCDoneQA();
                } else {
                    $edit_status = $this->status->QAProgress();
                    $statusnya = $this->status->QCDone();
                }

                //save to database
                $data = array(
                    'score' => $score,
                    'remark' => $remark,
                    'status' => $statusnya,
                    'update_date' => $datetimenow
                );
                $condition['id'] = $id;
                $simpan = $this->Mglobals->update("tb_geolancer_qc", $data, $condition);
                if ($simpan) {
                    $number = 1;

                    //get data tb_geolancer_regular_v2
                    $query_project = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_regular_v2 WHERE "
                        . "update_by = '" . $data_qc->user_id . "' AND "
                        . "update_date BETWEEN ("
                        . "SELECT update_date FROM tb_geolancer_regular_v2 WHERE update_by = '" . $data_qc->user_id . "' AND qc_periode = '" . $data_qc->periode . "' ORDER BY update_date LIMIT 1) AND ("
                        . "SELECT update_date FROM tb_geolancer_regular_v2 WHERE update_by = '" . $data_qc->user_id . "' ORDER BY update_date DESC LIMIT 1) ORDER BY update_date;");
                    foreach ($query_project->result() as $row) {
                        //update qc status in table tb_geolancer_regular_v2
                        $con = array(
                            'poi_id' => $row->poi_id
                        );
                        $data2 = array(
                            'edit_status' => $edit_status,
                            'qc_sample' => $data_qc->periode,
                            'qc_num' => $number++
                        );
                        $this->Mglobals->update("tb_geolancer_regular_v2", $data2, $con);
                    }
                    $status = "success";
                } else {
                    $status = "failed";
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

    public function index_detil()
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

                    $id_qc = $this->modul->dekrip_url($this->uri->segment(3));

                    //get data QC
                    $data_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_qc WHERE id = '" . $id_qc . "';");

                    //get user qc
                    $get_user_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $data_qc->create_by . "';");
                    //get user qa
                    $get_user_qa = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $data_qc->user_id . "';");

                    //check status
                    if ($data_qc->status == $this->status->QCProgress()) {
                        $statusnya = '<span class="badge badge-warning">Inprogress</span>';
                    } elseif ($data_qc->status == $this->status->QCDone()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-danger">Not Been QA Edit</span>';
                    } elseif ($data_qc->status == $this->status->QCProgressQA()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-warning">Inprogress QA Edit</span>';
                    } else {
                        $statusnya = '<span class="badge badge-success">Done QC and QA Edit</span>';
                    }

                    //get score
                    if ($get_user_qa->team == 'MO' || $get_user_qa->team == 'CA') {
                        if ($data_qc->score >= '98') {
                            $score = '<b style="color: green;">' . $data_qc->score . '%</b>';
                        } else {
                            $score = '<b style="color: red;">' . $data_qc->score . '%</b>';
                        }
                    } else {
                        if ($data_qc->score >= '95') {
                            $score = '<b style="color: green;">' . $data_qc->score . '%</b>';
                        } else {
                            $score = '<b style="color: red;">' . $data_qc->score . '%</b>';
                        }
                    }

                    if ($data_qc->edit <> '100' || $data_qc->edit == '0') {
                        //get progress
                        $jml_all = $this->Mglobals->getAllQR("SELECT COUNT(*) AS total, COUNT(CASE edit_status WHEN '" . $this->status->QADone() . "' then 1 else null end) AS edit FROM tb_geolancer_regular_v2 WHERE update_by = '" . $data_qc->user_id . "' AND qc_sample = '" . $data_qc->periode . "';");
                        $hasil = $jml_all->edit / $jml_all->total * 100 . '%';

                        //update progress edit
                        // $condition_edt = array(
                        //     'id' => $id_qc
                        // );
                        // $data_edt['edit'] = round($hasil, 1);
                        // $this->Mglobals->update("tb_geolancer_qc", $data_edt, $condition_edt);
                    }

                    $data['qc_create_date'] = date("d-m-Y H:i:s", strtotime($data_qc->create_date));
                    $data['qc_update_date'] = date("d-m-Y H:i:s", strtotime($data_qc->update_date));
                    $data['qc_name_qc'] = $get_user_qc->email;
                    $data['qc_name_qa'] = $get_user_qa->email;
                    $data['qc_score'] = $score;
                    $data['qc_progress_edit'] = '<div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: ' . $data_qc->edit . '%;" aria-valuenow="' . $data_qc->edit . '" aria-valuemin="0" aria-valuemax="100">' . $data_qc->edit . '%</div>
                                                </div>';
                    $data['qc_status'] = $statusnya;
                    $data['qc_remark'] = $data_qc->remark;
                    $data['qc_periode'] = $data_qc->periode;
                    $data['qc_id_qa'] = $data_qc->user_id;

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('management_qc/submit/detail');
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
                $data = array();
                $no = 1;
                $periode = $this->uri->segment(3);
                $id_user = $this->uri->segment(4);

                $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_regular_v2 WHERE qc_sample = '" . $periode . "' AND update_by = '" . $id_user . "' ORDER BY update_date;");
                foreach ($list->result() as $row) {
                    $val = array();
                    //check status
                    if ($row->edit_status == $this->status->QAProgress()) {
                        $statusnya = '<span class="right badge badge-warning">' . $this->status->QAProgress() . '</span>';
                    } else {
                        $statusnya = '<span class="right badge badge-success">' . $this->status->QADone() . '</span>';
                    }

                    //check status poi
                    if ($row->status_poi == $this->status->POIApprove()) {
                        $status = '<span class="right badge badge-success">Approve</span>';
                    } else {
                        $status = '<span class="right badge badge-danger">Reject</span>';
                    }

                    $val[] = $no++;
                    $val[] = date('d-m-Y H:i:s', strtotime($row->update_date));
                    $val[] = $row->location_name_new;
                    $val[] = $row->country;
                    $val[] = $status;
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

    public function change_status()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->input->post('id_confirm');

                //save to database
                $data = array(
                    'status' => $this->status->QCDoneQA()
                );
                $condition['id'] = $id;
                $update = $this->Mglobals->update("tb_geolancer_qc", $data, $condition);
                if ($update) {
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

    public function test()
    {
        $data = '';
        $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_qc;");
        foreach ($list->result() as $row) {
            //get user qc
            $get_user_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->create_by . "';");
            $name_qc = $get_user_qc->email;

            if ($row->edit <> '100' || $row->edit == '0') {

                //get progress
                $jml_all = $this->Mglobals->getAllQR("SELECT COUNT(*) AS total, COUNT(CASE edit_status WHEN '" . $this->status->QADone() . "' then 1 else null end) AS edit FROM tb_geolancer_regular_v2 WHERE update_by = '" . $row->user_id . "' AND qc_sample = '" . $row->periode . "';");
                $hasil = $jml_all->edit / $jml_all->total * 100 . '%';

                //update progress edit
                $condition2 = array(
                    'id' => $row->id
                );
                $data2['edit'] = round($hasil, 1);
                $this->Mglobals->update("tb_geolancer_qc", $data2, $condition2);
            }

            $data .= date("d-m-Y", strtotime($row->create_date)) . '|';
            $data .= $name_qc . '|';
            $data .= $row->periode . '<br>';
        }
        echo $data;
    }
}

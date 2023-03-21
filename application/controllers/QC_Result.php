<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of QC_Result
 *
 * @author dickyhikam
 */
class QC_Result extends CI_Controller
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

                    //check team
                    if ($get_user_login->team == "MO") {
                        $data['teamnya'] = '';
                    } else {
                        $data['teamnya'] = 'display: none;';
                    }

                    $data['month_year'] = $this->modul->Tahun();
                    $data['datenya'] = $this->modul->Tahun() . '-' . $this->modul->MonthNumber() . '-01';

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('management_qc/result/index');
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

                $data = array();
                $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_qc WHERE DATE_FORMAT(create_date,'%Y') = '" . $tahun . "' AND user_id = '" . $id_user . "' AND status <> '" . $this->status->QCProgress() . "';");
                foreach ($list->result() as $row) {
                    $val = array();

                    if ($row->assign_by > 0) {
                        //get user assign qc
                        $get_user_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->assign_by . "';");
                        $name_qc = $get_user_qc->email;
                    } else {
                        //get user qc
                        $get_user_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->create_by . "';");
                        $name_qc = $get_user_qc->email;
                    }

                    //get user qa
                    $get_user_qa = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->user_id . "';");

                    //check status
                    if ($row->status == $this->status->QCDone()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-danger">Not Been QA Edit</span>';
                        $btn = '<button class="btn btn-sm btn-primary" onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';
                    } elseif ($row->status == $this->status->QCProgressQA()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-warning">Inprogress QA Edit</span>';
                        $btn = '<button class="btn btn-sm btn-primary" onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';

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
                    //get progress edit
                    if ($row->edit >= '100') {
                        $hasil = '<b style="color: green;">' . $row->edit . '%</b>';
                    } else {
                        $hasil = '<b style="color: red;">' . $row->edit . '%</b>';
                    }
                    $update = date("d-m-Y", strtotime($row->update_date));

                    $val[] = $update;
                    $val[] = $name_qc;
                    $val[] = $row->periode;
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

    public function table_assign()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                $tahun = date('Y', strtotime($this->uri->segment(3)));

                $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_qc WHERE DATE_FORMAT(create_date,'%Y') = '" . $tahun . "' AND assign_by = '" . $id_user . "';");
                $data = array();
                foreach ($list->result() as $row) {
                    $val = array();

                    //get user qc
                    $get_user_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->create_by . "';");
                    $name_qc = $get_user_qc->email;

                    //get user qa
                    $get_user_qa = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $row->user_id . "';");
                    $name_qa = $get_user_qa->email;

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
                        $btn = '<button class="btn btn-sm btn-primary" disabled onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';
                    } elseif ($row->status == $this->status->QCProgressQA()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-warning">Inprogress QA Edit</span>';
                        $btn = '<button class="btn btn-sm btn-primary" disabled onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';
                    } else {
                        $statusnya = '<span class="badge badge-success">Done QC and QA Edit</span>';
                        $btn = '<button class="btn btn-sm btn-primary" disabled onclick="modal_detail(' . "'" . $this->modul->enkrip_url($row->id) . "'" . ');"><i class="fas fa-file-alt"></i></button>';
                    }

                    //check score
                    if ($row->score == '0') {
                        $score = '-';
                        $update = '-';
                    } else {
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
                    $val[] = $update;
                    $val[] = $score;
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
                    $data_qc = $this->Mglobals->getAllQR("SELECT a.user_id, a.status, a.score, a.edit, a.id, a.create_date, a.update_date, a.periode, a.remark, IF(a.assign_by = 0, (SELECT email FROM tb_geolancer_users WHERE user_id = a.create_by), (SELECT email FROM tb_geolancer_users WHERE user_id = a.assign_by)) AS qc_analyst FROM tb_geolancer_qc a, tb_geolancer_users b WHERE a.user_id = b.user_id AND a.id = '" . $id_qc . "';");

                    //get user qa
                    $get_user_qa = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $data_qc->user_id . "';");

                    //check status
                    if ($data_qc->status == $this->status->QCProgress()) {
                        $statusnya = '<span class="badge badge-warning">Inprogress</span>';
                        $data['qc_btn'] = '';
                    } elseif ($data_qc->status == $this->status->QCDone()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-danger">Not Been QA Edit</span>';
                        $data['qc_btn'] = '';
                    } elseif ($data_qc->status == $this->status->QCProgressQA()) {
                        $statusnya = '<span class="badge badge-success">Done QC</span> <br>'
                            . '<span class="badge badge-warning">Inprogress QA Edit</span>';
                        $data['qc_btn'] = '';
                    } else {
                        $statusnya = '<span class="badge badge-success">Done QC and QA Edit</span>';
                        $data['qc_btn'] = 'disabled';
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

                    //update status
                    if ($data_qc->edit == '100' || $data_qc->edit == $this->status->QCProgressQA()) {
                        //save to database
                        $data1 = array(
                            'status' => $this->status->QCDoneQA()
                        );
                        $condition1['id'] = $data_qc->id;
                        $this->Mglobals->update("tb_geolancer_qc", $data1, $condition1);
                    }

                    //check and get data POI
                    $get_poi = $this->Mglobals->getAllQR("SELECT poi_id FROM tb_geolancer_regular_v2 WHERE qc_sample = '" . $data_qc->periode . "' AND update_by = '" . $data_qc->user_id . "' AND edit_status <> '" . $this->status->QADone() . "' ORDER BY qc_num LIMIT 1;");
                    if (isset($get_poi->poi_id)) {
                        $data_poi_id = $get_poi->poi_id;
                    } else {
                        $data_poi_id = '';
                    }

                    $data['qc_create_date'] = date("d-m-Y H:i:s", strtotime($data_qc->create_date));
                    $data['qc_update_date'] = date("d-m-Y H:i:s", strtotime($data_qc->update_date));
                    $data['qc_name_qc'] = $data_qc->qc_analyst;
                    $data['qc_name_qa'] = $get_user_qa->email;
                    $data['qc_score'] = $score;
                    $data['qc_progress_edit'] = '<div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: ' . $data_qc->edit . '%;" aria-valuenow="' . $data_qc->edit . '" aria-valuemin="0" aria-valuemax="100">' . $data_qc->edit . '%</div>
                                                </div>';
                    $data['qc_status'] = $statusnya;
                    $data['qc_remark'] = $data_qc->remark;
                    $data['qc_periode'] = $data_qc->periode;
                    $data['qc_id_qa'] = $data_qc->user_id;
                    $data['qc_id_qc'] = $this->uri->segment(3);
                    $data['id_poi_edit'] = $data_poi_id;

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('management_qc/result/detail');
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

    public function index_edit()
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
                    //check browser
                    $browser = $this->modul->DetectBrowser();
                    if ($browser == 'Google Chrome') {

                        //get data user login
                        $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                        //mandatory data
                        $data['tahun'] = $this->modul->Tahun();
                        $data['login_name'] = $get_user_login->full_name;
                        $data['menu_name'] = 'Edit POI';
                        $data['page_menu'] = $this->menu->ShowMenu($level_user, $link_menu);
                        $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;
                        //check photo
                        if (isset($get_user_login->foto)) {
                            $data['profil_photo'] = base_url() . $get_user_login->foto;
                        } else {
                            $data['profil_photo'] = base_url() . 'assets/images/profil/SampleUser.jpg';
                        }

                        $poi_id = $this->uri->segment(3);

                        //get data POI
                        $data_poi = $this->Mglobals->getAllQR("SELECT qc_sample, update_by, qc_num FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $poi_id . "';");
                        $last_poi = $this->Mglobals->getAllQR("SELECT qc_num FROM tb_geolancer_regular_v2 WHERE qc_sample = '" . $data_poi->qc_sample . "' AND update_by = '" . $data_poi->update_by . "' AND edit_status <> '" . $this->status->QADone() . "' ORDER BY qc_num DESC LIMIT 1;");

                        //get data QC
                        $data_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_qc WHERE periode = '" . $data_poi->qc_sample . "' AND user_id = '" . $data_poi->update_by . "';");

                        $data['qc_remark'] = $data_qc->remark;
                        $data['qc_id_qc'] = $this->uri->segment(3);
                        $data['last_number_poi'] = number_format($last_poi->qc_num);
                        $data['select_number_poi'] = $data_poi->qc_num;
                        $data['show_page'] = $this->page_edit_live($poi_id, $last_poi->qc_num);

                        $this->load->view('template/1_head', $data);
                        $this->load->view('template/2_topbar');
                        $this->load->view('template/3_sidebar');
                        $this->load->view('management_qc/result/edit');
                        $this->load->view('template/4_footer');
                        $this->load->view('template/5_script');
                    } else {
                        $this->modul->Halaman("ErrorBrwoser");
                    }
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

    private function page_edit_live($poi_id, $last_number)
    {
        //get data POI
        $data_poi = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $poi_id . "';");
        $show2 = $data_poi->qc_num . ' / ' . number_format($last_number);

        //get poi id
        $get_poi = $this->Mglobals->getAllQR("SELECT poi_id, country FROM tb_geolancer_regular_v2 WHERE 
                    qc_sample = '" . $data_poi->qc_sample . "' AND 
                    update_by = '" . $data_poi->update_by . "' AND 
                    poi_id <> '" . $poi_id . "' AND 
                    edit_status <> '" . $this->status->QADone() . "' 
                    ORDER BY qc_num LIMIT 1;");

        //get data QC
        $data_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_qc WHERE periode = '" . $data_poi->qc_sample . "' AND user_id = '" . $data_poi->update_by . "';");

        //check pii
        if ($data_poi->pii_status == 'correct') {
            $data_pii = '<option value="">Choose PII Correct</option>
                                    <option value="correct" selected>Correct</option>
                                    <option value="incorrect">Incorrect</option>';
        } elseif ($data_poi->pii_status == 'incorrect') {
            $data_pii = '<option value="">Choose PII Correct</option>
                                    <option value="correct">Correct</option>
                                    <option value="incorrect" selected>Incorrect</option>';
        } else {
            $data_pii = '<option value="" selected>Choose PII Correct</option>
                                    <option value="correct">Correct</option>
                                    <option value="incorrect">Incorrect</option>';
        }

        //check building
        if ($data_poi->inside_building == 'yes') {
            $data_building = '<option value="">Choose inside building</option>
                                            <option value="yes" selected>Yes</option>
                                            <option value="no">No</option>';
        } elseif ($data_poi->inside_building == 'no') {
            $data_building = '<option value="">Choose inside building</option>
                                            <option value="yes">Yes</option>
                                            <option value="no" selected>No</option>';
        } else {
            $data_building = '<option value="" selected>Choose inside building</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>';
        }

        //check status
        if ($data_poi->status_poi == '3') {
            $data_status = '<option value="">Choose status</option>
                                        <option value="3" selected>Approve</option>
                                        <option value="5">Reject</option>';
        } elseif ($data_poi->status_poi == '5') {
            $data_status = '<option value="">Choose status</option>
                                        <option value="3">Approve</option>
                                        <option value="5" selected>Reject</option>';
        } else {
            $data_status = '<option value="" selected>Choose status</option>
                                        <option value="3">Approve</option>
                                        <option value="5">Reject</option>';
        }

        //get data category
        $data_category = '<option value="">Choose category</option>';
        $query_nc = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_category WHERE country = '" . $data_poi->country . "' ORDER BY category;");
        foreach ($query_nc->result() as $row) {
            if ($row->category == $data_poi->category_new) {
                $data_category .= '<option value="' . $row->category . '" selected>' . $row->category . '</option>';
            } else {
                $data_category .= '<option value="' . $row->category . '">' . $row->category . '</option>';
            }
        }

        //check poi location
        if ($data_poi->latlong_ent == '') {
            //get lat long
            $lat_input = '';
            $lng_input = '';
        } else {
            //get lat long
            $loc2 = (explode(",", $data_poi->latlong_ent));
            $lat_input = $loc2[0];
            $lng_input = $loc2[1];
        }

        //check num poi
        if ($data_poi->qc_num == $last_number) {
            $btn = '<button class="btn btn-info btn-block" onclick="btn_back(' . "'" . $this->modul->enkrip_url($data_qc->id) . "'" . ');">Finish <i class="fas fa-angle-right"></i></button>';
        } else {
            //check data
            if (isset($get_poi->poi_id)) {
                $btn = '<button class="btn btn-info btn-block" onclick="btn_next(' . "'" . $get_poi->poi_id . "'" . ');" id="btn_next">Next <i class="fas fa-angle-right"></i></button>';
            } else {
                $btn = '<button class="btn btn-info btn-block" onclick="btn_back(' . "'" . $this->modul->enkrip_url($data_qc->id) . "'" . ');">Finish <i class="fas fa-angle-right"></i></button>';
            }
        }

        $page = '<div class="row">
                    <div class="col-sm-4">
                        <div class="card-box">
                            <center>
                                <h3>Edit The POI</h3>
                                <a href="' . $data_poi->url . '" target="_blank">Admin Page</a>
                                <br>
                                <input readonly hidden class="form-control" id="link_ap" name="link_ap" readonly value="' . $data_poi->url . '">
                            </center>

                            <form id="form_work">
                                <fieldset class="form-group" hidden>
                                    <label for="idpoi">POI ID</label>
                                    <input class="form-control" id="idpoi" name="idpoi" readonly value="' . $data_poi->poi_id . '">
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="namenew">Location Name</label>
                                    <input class="form-control" id="namenew" name="namenew" placeholder="Enter Location name" value="' . $data_poi->location_name_new . '">
                                    <div class="invalid-feedback">
                                        Location name cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="address">Address</label>
                                    <input class="form-control" id="address" name="address" placeholder="Enter address" value="' . $data_poi->address_new . '">
                                    <div class="invalid-feedback">
                                        Address cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="house_number">House Number</label>
                                    <input class="form-control" id="house_number" name="house_number" placeholder="Enter house number" value="' . $data_poi->unit_no_new . '">
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="main_location">Main Entrance Location</label>
                                    <input class="form-control" id="main_location" name="main_location" placeholder="Enter main entrance location" value="' . $data_poi->latlong_ent . '">
                                    <div class="invalid-feedback">
                                        Main entrance location cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="building_location">Middle of Building Location</label>
                                    <input class="form-control" id="building_location" name="building_location" placeholder="Enter middle of building location" value="' . $data_poi->latlong_building . '">
                                    <small>note. middle of building location distance must be more than 3 meters and less than 1 kilometer from the main entrance location.</small>
                                    <div class="invalid-feedback">
                                        Middle of building location cannot be empty.
                                    </div>
                                </fieldset>

                                <a href="javascript:void(0);" class="btn btn-info btn-block" onclick="google_map(' . "'" . $lat_input . "'" . ', ' . "'" . $lng_input . "'" . ');">Location Google MAP</a>
                                <br>

                                <fieldset class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" name="category" id="category" data-placeholder="Choose ...">
                                        ' . $data_category . '
                                    </select>
                                    <div class="invalid-feedback">
                                        Category cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="pii">PII Correct</label>
                                    <select class="form-control" id="pii" name="pii" data-placeholder="Choose ...">
                                        ' . $data_pii . '
                                    </select>
                                    <div class="invalid-feedback">
                                        PII correct cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="building">Inside Building</label>
                                    <select class="form-control" id="building" name="building" onchange="show_building();">
                                        ' . $data_building . '
                                    </select>
                                    <div class="invalid-feedback">
                                        Inside building cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group" id="part_buildingname">
                                    <label for="buildingname">Building Name</label>
                                    <input class="form-control" id="buildingname" name="buildingname" placeholder="Enter building name" value="' . $data_poi->building_name . '">
                                    <div class="invalid-feedback">
                                        Building name cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="note">Rejection Reason</label>
                                    <select class="form-control" name="note" id="note" data-placeholder="Choose ...">
                                        <option value="">Choose rejection reason</option>
                                    </select>
                                    <div class="invalid-feedback" id="invalid_reason">
                                        Rejection reason cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="status">Status*</label>
                                    <select class="form-control" id="status" name="status" data-placeholder="Choose ..." onchange="reason()">
                                        ' . $data_status . '
                                    </select>
                                    <div class="invalid-feedback">
                                        Status cannot be empty.
                                    </div>
                                </fieldset>
                            </form>

                            <button class="btn btn-primary btn-block mt-2" onclick="check_status();" id="btn_save">Save</button>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="card-box">
                            <button class="btn btn-info btn-block mt-2" onclick="reset_admin_panel();">Refresh Admin Page</button>
                            <br>
                            <iframe src="' . $data_poi->url . '" width="100%" height="1020px" id="page_admin_panel"></iframe>
                        </div>
                    </div>
                </div>
        
                <div class="card-box">
                    <div class="row">
                        <div class="col-sm-5">
                            <button class="btn btn-light btn-block" onclick="btn_back(' . "'" . $this->modul->enkrip_url($data_qc->id) . "'" . ');" id="btn_prev"><i class="fas fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-light btn-block" disabled>' . $show2 . '</button>
                        </div>
                        <div class="col-sm-5">
                            ' . $btn . '
                        </div>
                    </div>
                </div>';

        return $page;
    }

    public function page_edit()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $poi_id = $this->uri->segment(3);

                //get data POI
                $data_poi = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $poi_id . "';");
                $last_poi = $this->Mglobals->getAllQR("SELECT qc_num FROM tb_geolancer_regular_v2 WHERE qc_sample = '" . $data_poi->qc_sample . "' AND update_by = '" . $data_poi->update_by . "' AND edit_status <> '" . $this->status->QADone() . "' ORDER BY qc_num DESC LIMIT 1;");
                $show2 = $data_poi->qc_num . ' / ' . number_format($last_poi->qc_num);

                //get poi id
                $get_poi = $this->Mglobals->getAllQR("SELECT poi_id, country FROM tb_geolancer_regular_v2 WHERE 
                    qc_sample = '" . $data_poi->qc_sample . "' AND 
                    update_by = '" . $data_poi->update_by . "' AND 
                    poi_id <> '" . $poi_id . "' AND 
                    edit_status <> '" . $this->status->QADone() . "' 
                    ORDER BY qc_num LIMIT 1;");

                //get data QC
                $data_qc = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_qc WHERE periode = '" . $data_poi->qc_sample . "' AND user_id = '" . $data_poi->update_by . "';");

                //check pii
                if ($data_poi->pii_status == 'correct') {
                    $data_pii = '<option value="">Choose PII Correct</option>
                                    <option value="correct" selected>Correct</option>
                                    <option value="incorrect">Incorrect</option>';
                } elseif ($data_poi->pii_status == 'incorrect') {
                    $data_pii = '<option value="">Choose PII Correct</option>
                                    <option value="correct">Correct</option>
                                    <option value="incorrect" selected>Incorrect</option>';
                } else {
                    $data_pii = '<option value="" selected>Choose PII Correct</option>
                                    <option value="correct">Correct</option>
                                    <option value="incorrect">Incorrect</option>';
                }

                //check building
                if ($data_poi->inside_building == 'yes') {
                    $data_building = '<option value="">Choose inside building</option>
                                            <option value="yes" selected>Yes</option>
                                            <option value="no">No</option>';
                } elseif ($data_poi->inside_building == 'no') {
                    $data_building = '<option value="">Choose inside building</option>
                                            <option value="yes">Yes</option>
                                            <option value="no" selected>No</option>';
                } else {
                    $data_building = '<option value="" selected>Choose inside building</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>';
                }

                //check status
                if ($data_poi->status_poi == '3') {
                    $data_status = '<option value="">Choose status</option>
                                        <option value="3" selected>Approve</option>
                                        <option value="5">Reject</option>';
                } elseif ($data_poi->status_poi == '5') {
                    $data_status = '<option value="">Choose status</option>
                                        <option value="3">Approve</option>
                                        <option value="5" selected>Reject</option>';
                } else {
                    $data_status = '<option value="" selected>Choose status</option>
                                        <option value="3">Approve</option>
                                        <option value="5">Reject</option>';
                }

                //get data category
                $data_category = '<option value="">Choose category</option>';
                $query_nc = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_category WHERE country = '" . $data_poi->country . "' ORDER BY category;");
                foreach ($query_nc->result() as $row) {
                    if ($row->category == $data_poi->category_new) {
                        $data_category .= '<option value="' . $row->category . '" selected>' . $row->category . '</option>';
                    } else {
                        $data_category .= '<option value="' . $row->category . '">' . $row->category . '</option>';
                    }
                }

                //check poi location
                if ($data_poi->latlong_ent == '') {
                    //get lat long
                    $lat_input = '';
                    $lng_input = '';
                } else {
                    //get lat long
                    $loc2 = (explode(",", $data_poi->latlong_ent));
                    $lat_input = $loc2[0];
                    $lng_input = $loc2[1];
                }

                //check num poi
                if ($data_poi->qc_num == $last_poi->qc_num) {
                    $btn = '<button class="btn btn-info btn-block" onclick="btn_back(' . "'" . $this->modul->enkrip_url($data_qc->id) . "'" . ');">Finish <i class="fas fa-angle-right"></i></button>';
                } else {
                    //check data
                    if (isset($get_poi->poi_id)) {
                        $btn = '<button class="btn btn-info btn-block" onclick="btn_next(' . "'" . $get_poi->poi_id . "'" . ');" id="btn_next">Next <i class="fas fa-angle-right"></i></button>';
                    } else {
                        $btn = '<button class="btn btn-info btn-block" onclick="btn_back(' . "'" . $this->modul->enkrip_url($data_qc->id) . "'" . ');">Finish <i class="fas fa-angle-right"></i></button>';
                    }
                }

                $page = '<div class="row">
                            <div class="col-sm-4">
                                <div class="card-box">
                                    <center>
                                        <h3>Edit The POI</h3>
                                        <a href="' . $data_poi->url . '" target="_blank">Admin Page</a>
                                        <br>
                                        <input readonly hidden class="form-control" id="link_ap" name="link_ap" readonly value="' . $data_poi->url . '">
                                    </center>

                                    <form id="form_work">
                                        <fieldset class="form-group" hidden>
                                            <label for="idpoi">POI ID</label>
                                            <input class="form-control" id="idpoi" name="idpoi" readonly value="' . $data_poi->poi_id . '">
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="namenew">Location Name</label>
                                            <input class="form-control" id="namenew" name="namenew" placeholder="Enter Location name" value="' . $data_poi->location_name_new . '">
                                            <div class="invalid-feedback">
                                                Location name cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="address">Address</label>
                                            <input class="form-control" id="address" name="address" placeholder="Enter address" value="' . $data_poi->address_new . '">
                                            <div class="invalid-feedback">
                                                Address cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="house_number">House Number</label>
                                            <input class="form-control" id="house_number" name="house_number" placeholder="Enter house number" value="' . $data_poi->unit_no_new . '">
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="main_location">Main Entrance Location</label>
                                            <input class="form-control" id="main_location" name="main_location" placeholder="Enter main entrance location" value="' . $data_poi->latlong_ent . '">
                                            <div class="invalid-feedback">
                                                Main entrance location cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="building_location">Middle of Building Location</label>
                                            <input class="form-control" id="building_location" name="building_location" placeholder="Enter middle of building location" value="' . $data_poi->latlong_building . '">
                                            <small>note. middle of building location distance must be more than 3 meters and less than 1 kilometer from the main entrance location.</small>
                                            <div class="invalid-feedback">
                                                Middle of building location cannot be empty.
                                            </div>
                                        </fieldset>

                                        <a href="javascript:void(0);" class="btn btn-info btn-block" onclick="google_map(' . "'" . $lat_input . "'" . ', ' . "'" . $lng_input . "'" . ');">Location Google MAP</a>
                                        <br>

                                        <fieldset class="form-group">
                                            <label for="category">Category</label>
                                            <select class="form-control" name="category" id="category" data-placeholder="Choose ...">
                                                ' . $data_category . '
                                            </select>
                                            <div class="invalid-feedback">
                                                Category cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="pii">PII Correct</label>
                                            <select class="form-control" id="pii" name="pii" data-placeholder="Choose ...">
                                                ' . $data_pii . '
                                            </select>
                                            <div class="invalid-feedback">
                                                PII correct cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="building">Inside Building</label>
                                            <select class="form-control" id="building" name="building" onchange="show_building();">
                                                ' . $data_building . '
                                            </select>
                                            <div class="invalid-feedback">
                                                Inside building cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group" id="part_buildingname">
                                            <label for="buildingname">Building Name</label>
                                            <input class="form-control" id="buildingname" name="buildingname" placeholder="Enter building name" value="' . $data_poi->building_name . '">
                                            <div class="invalid-feedback">
                                                Building name cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="note">Rejection Reason</label>
                                            <select class="form-control" name="note" id="note" data-placeholder="Choose ...">
                                                <option value="">Choose rejection reason</option>
                                            </select>
                                            <div class="invalid-feedback" id="invalid_reason">
                                                Rejection reason cannot be empty.
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <label for="status">Status*</label>
                                            <select class="form-control" id="status" name="status" data-placeholder="Choose ..." onchange="reason()">
                                                ' . $data_status . '
                                            </select>
                                            <div class="invalid-feedback">
                                                Status cannot be empty.
                                            </div>
                                        </fieldset>
                                    </form>

                                    <button class="btn btn-primary btn-block mt-2" onclick="check_status();" id="btn_save">Save</button>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="card-box">
                                    <button class="btn btn-info btn-block mt-2" onclick="reset_admin_panel();">Refresh Admin Page</button>
                                    <br>
                                    <iframe src="' . $data_poi->url . '" width="100%" height="1020px" id="page_admin_panel"></iframe>
                                </div>
                            </div>
                        </div>
                
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-5">
                                    <button class="btn btn-light btn-block" onclick="btn_back(' . "'" . $this->modul->enkrip_url($data_qc->id) . "'" . ');" id="btn_prev"><i class="fas fa-arrow-left"></i> Back</button>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-light btn-block" disabled>' . $show2 . '</button>
                                </div>
                                <div class="col-sm-5">
                                    ' . $btn . '
                                </div>
                            </div>
                        </div>';

                echo json_encode(array("page" => $page));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function save_review()
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
                //get data input
                $idpoi = $this->input->post('idpoi');
                $namenew = $this->input->post('namenew'); //use quotation marks
                $address = $this->input->post('address'); //use quotation marks
                $note = $this->input->post('note'); //use quotation marks
                $house_number = $this->input->post('house_number');
                $main_location = $this->input->post('main_location');
                $building_location = $this->input->post('building_location');
                $category = $this->input->post('category');
                $pii = $this->input->post('pii');
                $building = $this->input->post('building');
                $buildingname = $this->input->post('buildingname');
                $statusnya = $this->input->post('status');
                $datetimenow = $this->modul->DateTimeNowDB();

                //check data location
                if ($main_location == '') {
                    $lat_input = '0';
                    $lng_input = '0';
                } else {
                    $loc2 = (explode(",", $main_location));
                    $lat_input = $loc2[0];
                    $lng_input = $loc2[1];
                }
                //check data location
                if ($building_location == '') {
                    $lat_input_bld = '0';
                    $lng_input_bld = '0';
                } else {
                    $loc3 = (explode(",", $building_location));
                    $lat_input_bld = $loc3[0];
                    $lng_input_bld = $loc3[1];
                }

                //get data poi
                $get_poi = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $idpoi . "'");

                $kilo = $this->modul->Distance($get_poi->latitude, $get_poi->longitude, $lat_input, $lng_input, 'K');
                $kilo_bld = $this->modul->Distance($lat_input, $lng_input, $lat_input_bld, $lng_input_bld, 'K');

                //check status
                if ($statusnya == '5') { //reject
                    $data = array(
                        'remark' => $note,
                        'edit_status' => $this->status->QADone(),
                        'edit_date' => $datetimenow,
                        'admin_panel' => $this->status->APReject(),
                        'status_poi' => $this->status->POIReject()
                    );
                    $con_distance = 'safe';
                } elseif ($statusnya == '3') { //approve
                    $data = array(
                        'location_name_new' => $namenew,
                        'category_new' => $category,
                        'address_new' => $address,
                        'status_poi' => $this->status->POIApprove(),
                        'pii_status' => $pii,
                        'admin_panel' => $this->status->APApprove(),
                        'building_name' => $buildingname,
                        'inside_building' => $building,
                        'latlong_ent' => $main_location,
                        'latlong_building' => $building_location,
                        'unit_no_new' => $house_number,
                        'distance' => $kilo,
                        'edit_status' => $this->status->QADone(),
                        'edit_date' => $datetimenow
                    );

                    //check distance
                    if ($kilo >= 1.000 || $kilo <= 0.001) {
                        $con_distance = 'not safe entrance';
                    } else {
                        //check distance building
                        if ($kilo_bld >= 1.000 || $kilo_bld <= 0.003) {
                            $con_distance = 'not safe building';
                        } else {
                            //check distance building
                            $con_distance = 'safe';
                        }
                    }
                }

                //check number
                if ($get_poi->qc_num == '1') {
                    $condition1 = array(
                        'user_id' => $get_poi->update_by,
                        'periode' => $get_poi->qc_sample
                    );
                    $data1['status'] = $this->status->QCProgressQA();
                    $this->Mglobals->update("tb_geolancer_qc", $data1, $condition1);
                }

                //check condition distance
                if ($con_distance == 'not safe entrance') {
                    $status = "entrance";
                } elseif ($con_distance == 'not safe building') {
                    $status = "building";
                } else {
                    $con['poi_id'] = $idpoi;
                    $save = $this->Mglobals->update("tb_geolancer_regular_v2", $data, $con);
                    if ($save == 1) {
                        //get progress
                        $jml_all = $this->Mglobals->getAllQR("SELECT COUNT(*) AS total, COUNT(CASE edit_status WHEN '" . $this->status->QADone() . "' then 1 else null end) AS edit FROM tb_geolancer_regular_v2 WHERE update_by = '" . $get_poi->update_by . "' AND qc_sample = '" . $get_poi->qc_sample . "';");
                        $hasil = $jml_all->edit / $jml_all->total * 100;

                        //update progress edit
                        $condition2 = array(
                            'user_id' => $get_poi->update_by,
                            'periode' => $get_poi->qc_sample
                        );
                        $data2['edit'] = round($hasil, 1);
                        $this->Mglobals->update("tb_geolancer_qc", $data2, $condition2);
                        $status = "success";
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
                $periode = $this->uri->segment(3);
                $id_user = $this->uri->segment(4);
                $id_qc = $this->uri->segment(5);

                $list = $this->Mglobals->getAllQ("SELECT poi_id, edit_status, status_poi, qc_num, update_date, location_name_new, country FROM tb_geolancer_regular_v2 WHERE qc_sample = '" . $periode . "' AND update_by = '" . $id_user . "' AND edit_status <> '" . $this->status->QADone() . "' ORDER BY update_date;");
                foreach ($list->result() as $row) {
                    $val = array();
                    //check status
                    if ($row->edit_status == $this->status->QAProgress()) {
                        $btn = '<button class="btn btn-sm btn-warning" onclick="edit(' . "'" . $row->poi_id . "'" . ');"><i class="fas fa-edit"></i></button>';
                        $statusnya = '<span class="right badge badge-warning">' . $this->status->QAProgress() . '</span>';
                    } else {
                        $btn = '<button class="btn btn-sm btn-warning" disabled><i class="fas fa-edit"></i></button>';
                        $statusnya = '<span class="right badge badge-success">' . $this->status->QADone() . '</span>';
                    }

                    //check status poi
                    if ($row->status_poi == $this->status->POIApprove()) {
                        $status = '<span class="right badge badge-success">Approve</span>';
                    } else {
                        $status = '<span class="right badge badge-danger">Reject</span>';
                    }

                    $val[] = $row->qc_num;
                    $val[] = date('d-m-Y H:i:s', strtotime($row->update_date));
                    $val[] = $row->location_name_new;
                    $val[] = $row->country;
                    $val[] = $status;
                    $val[] = $statusnya;
                    $val[] = $btn;
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

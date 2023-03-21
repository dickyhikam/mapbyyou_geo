<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Claim
 *
 * @author dickyhikam
 */
class Claim extends CI_Controller
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
                    //check browser
                    $browser = $this->modul->DetectBrowser();
                    if ($browser == 'Google Chrome') {
                        //get data user login
                        $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                        //check status
                        if ($get_user_login->status == '2') {
                            $this->modul->Halaman("Login");
                        } else {
                            //get data menu
                            $get_menu = $this->Mglobals->getAllQR("SELECT * FROM tb_geo_menu WHERE link = '" . $link_menu . "';");

                            //check team
                            if ($get_user_login->team == 'MO') {
                                $form_poi = '<div class="col-12">
                                                <div class="card-box">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="form-group">
                                                                <input class="form-control" placeholder="Enter poi id" id="poi_idnya" name="poi_idnya">
                                                                <div class="invalid-feedback">
                                                                    POI ID cannot be empty.
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <button class="btn btn-block btn-primary" id="btn_review" onclick="review_poi();">Review POI</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                            } else {
                                $form_poi = '';
                            }

                            //mandatory data
                            $data['tahun'] = $this->modul->Tahun();
                            $data['login_name'] = $get_user_login->full_name;
                            $data['menu_name'] = $get_menu->name;
                            $data['page_menu'] = $this->menu->ShowMenu($level_user, $link_menu);
                            $data['team_user'] = $form_poi;
                            $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;
                            //check photo
                            if (isset($get_user_login->foto)) {
                                $data['profil_photo'] = base_url() . $get_user_login->foto;
                            } else {
                                $data['profil_photo'] = base_url() . 'assets/images/profil/SampleUser.jpg';
                            }

                            $check_qc = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_qc WHERE user_id = '" . $id_user . "' AND status IN ('" . $this->status->QCDone() . "', '" . $this->status->QCProgressQA() . "');")->jml;
                            $data['data_qc'] = $check_qc;
                            if ($check_qc > 0) {
                                $data['btnnya'] = 'btn-secondary';
                                $data['con_btnnya'] = 'disabled';
                                $data['alertnya'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="color: black;">
                                                        <strong>Warning!</strong> Your QC results have been published and still need to be corrected, please correct the QC results first before claiming again.
                                                    </div>';
                            } else {
                                $data['btnnya'] = 'btn-primary';
                                $data['con_btnnya'] = '';
                                $data['alertnya'] = '';
                            }

                            $this->load->view('template/1_head', $data);
                            $this->load->view('template/2_topbar');
                            $this->load->view('template/3_sidebar');
                            $this->load->view('claim/index');
                            $this->load->view('template/4_footer');
                            $this->load->view('template/5_script');
                        }
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
                $data = array();
                $no = 1;

                $list = $this->Mglobals->getAllQ("SELECT * FROM view_data_qa WHERE update_by = '" . $id_user . "';");
                foreach ($list->result() as $row) {
                    $val = array();
                    //check status
                    if ($row->status_poi == $this->status->POIClaim()) {
                        $statusnya = '<span class="right badge badge-info">New Claim</span>';
                    } else {
                        $statusnya = '<span class="right badge badge-warning">Pending</span>';
                    }

                    $val[] = $no++;
                    $val[] = date('d-m-Y', strtotime($row->claim_date));
                    $val[] = $row->location_name;
                    $val[] = $this->modul->ConvertMonth($row->create_month) . ' - ' . $row->create_year;
                    $val[] = $row->country;
                    $val[] = $statusnya;
                    $val[] = '<button class="btn btn-primary btn-sm" onclick="modal_work(' . "'" . $row->poi_id . "'" . ')" data-toggle="tooltip" data-placement="top" title="Start Work"><i class="fas fa-file-alt"></i></button>';
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

    private function auto_assign($project, $country)
    {
        //check country
        if ($country == 'ID') {
            $con_country = "`order` >= (SELECT `order` FROM tb_geolancer_country WHERE code = '" . $country . "')";
        } else {
            $con_country = "code = '" . $country . "'";
        }

        //get country
        $data_country = '';
        $query_country = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_country WHERE " . $con_country . ";");
        foreach ($query_country->result() as $row) {
            $data_country .= "'" . $row->code . "', ";
        }
        $data_country = substr($data_country, 0, strlen($data_country) - 2);

        //get project
        $data_project = '';
        $list_project = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_project WHERE `order` >= (SELECT `order` FROM tb_geolancer_project WHERE `name` = '" . $project . "');");
        foreach ($list_project->result() as $row) {
            $data_project .= "'" . $row->name . "', ";
        }
        $data_project = substr($data_project, 0, strlen($data_project) - 2);

        //get project and country
        $query_protry = $this->Mglobals->getAllQR("SELECT * FROM view_cf_date WHERE qa_work IN (" . $data_project . ") AND "
            . "country IN (" . $data_country . ") "
            . "ORDER BY create_year, create_month, create_day, "
            . "FIELD(qa_work, " . $data_project . "), "
            . "FIELD(country, " . $data_country . ");");
        if (isset($query_protry->qa_work)) {
            $data_mentah = $query_protry->qa_work . ' ' . $query_protry->country;
        } else {
            $data_mentah = 'empty';
        }

        return $data_mentah;
    }

    public function claim_proses()
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
                //get data user login
                $get_user_login = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $id_user . "';");

                $datenow = $this->modul->DateTimeNowDB();
                $date = $this->modul->DateNowDB();

                //check project user
                if ($get_user_login->qa_project == 'URGENT') { //Urgent
                    //check amount urgent all country
                    $check_urgent = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_regular_v2 WHERE qa_work = 'URGENT' AND (status_poi = '0' OR status_poi = '" . $this->status->POIUnclaim() . "');");
                    if ($check_urgent->jml > 0) {
                        $project = "qa_work = 'URGENT' AND ";
                        $conCountry = "";
                        $client = "";
                    } else {
                        //auto assign
                        $data_mentah = $this->auto_assign($get_user_login->qa_project, $get_user_login->country);
                        //check data poi kosong atau habis
                        if ($data_mentah == 'empty') {
                            //set data
                            $project = "";
                            $conCountry = "";
                            $client = "";
                        } else {
                            $data_hasil = explode(" ", $data_mentah);
                            //set data
                            $project = "qa_work = '" . $data_hasil[0] . "' AND ";
                            $conCountry = "country = '" . $data_hasil[1] . "' AND ";
                            $client = "";
                        }
                    }
                } elseif ($get_user_login->qa_project == 'PROJECT') {
                    //check amount urgent all country
                    $check_client = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_regular_v2 WHERE client IS NOT NULL AND (status_poi = '0' OR status_poi = '" . $this->status->POIUnclaim() . "');");
                    if ($check_client->jml > 0) {
                        $project = "";
                        $conCountry = "";
                        $client = "client IS NOT NULL AND qa_work <> 'NOT TO REVIEW' AND ";
                    } else {
                        //auto assign
                        $data_mentah = $this->auto_assign("PRIORITY", $get_user_login->country);
                        //check data poi kosong atau habis
                        if ($data_mentah == 'empty') {
                            //set data
                            $project = "";
                            $conCountry = "";
                            $client = "";
                        } else {
                            $data_hasil = explode(" ", $data_mentah);
                            //set data
                            $project = "qa_work = '" . $data_hasil[0] . "' AND ";
                            $conCountry = "country = '" . $data_hasil[1] . "' AND ";
                            $client = "";
                        }
                    }
                } else {
                    //auto assign
                    $data_mentah = $this->auto_assign($get_user_login->qa_project, $get_user_login->country);
                    //check data poi kosong atau habis
                    if ($data_mentah == 'empty') {
                        //set data
                        $project = "";
                        $conCountry = "";
                        $client = "";
                    } else {
                        $data_hasil = explode(" ", $data_mentah);
                        //set data
                        $project = "qa_work = '" . $data_hasil[0] . "' AND ";
                        $conCountry = "country = '" . $data_hasil[1] . "' AND ";
                        $client = "";
                    }
                }

                //check project user
                if ($get_user_login->qa_project == 'none' || $get_user_login->qa_project == '') {
                    $status = 'not found';
                } else {
                    //check status claim POI
                    $check_status = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_regular_v2 WHERE status_poi = '" . $this->status->POIClaim() . "' AND update_by = '" . $id_user . "';");
                    if ($check_status->jml > 0) {
                        $status = 'already';
                    } else {
                        //check count poi
                        $check_poi = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_regular_v2 WHERE update_by = '" . $id_user . "' AND update_date = '" . $date . "';")->jml;
                        if ($check_poi > 400) {
                            $status = 'max';
                        } else {
                            //get data poi
                            $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_regular_v2 WHERE " . $conCountry . $project . $client . " (status_poi = '0' OR status_poi = '" . $this->status->POIUnclaim() . "') ORDER BY create_year, create_month, create_day ASC LIMIT 10;");
                            foreach ($list->result() as $row) {
                                //update data poi (claim data POI)
                                $data = array(
                                    'status_poi' => $this->status->POIClaim(),
                                    'update_by' => $id_user,
                                    'claim_date' => $datenow
                                );
                                $con['poi_id'] = $row->poi_id;
                                $this->Mglobals->update("tb_geolancer_regular_v2", $data, $con);
                            }
                            $status = 'success';
                        }
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

    public function check_poi()
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

                $poi_id = $this->uri->segment(3);

                //get data poi
                $get_poi = $this->Mglobals->getAllQR("SELECT *, COUNT(*) AS jml FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $poi_id . "';");
                if ($get_poi->jml > 0) {
                    if ($get_poi->qa_work == 'NOT TO REVIEW') {
                        $status = 'not review';
                    } else {
                        if ($get_poi->update_by == 0) {
                            $status = 'success';
                        } else {
                            $status = 'already';
                        }
                    }
                } else {
                    $status = 'not found';
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

    //====================
    //Detil POI
    //====================
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
                    //check browser
                    $browser = $this->modul->DetectBrowser();
                    if ($browser == 'Google Chrome') {
                        $id_poi = $this->uri->segment(3);

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

                        //get data poi
                        $get_poi = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $id_poi . "';");

                        // ubah string JSON menjadi array
                        $show_drop_down = '';
                        //get data from database
                        $query_nc = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_category WHERE country = '" . $get_poi->country . "' ORDER BY category;");
                        foreach ($query_nc->result() as $row) {
                            $show_drop_down .= '<option value="' . $row->category . '">' . $row->category . '</option>';
                        }

                        $datetimenow = $this->modul->DateTimeNowDB();

                        //check update_by
                        if ($get_poi->update_by == 0) {
                            //check qa work (project)
                            if ($get_poi->qa_work == 'NOT TO REVIEW') {
                                $con_update_by = 'not_to_review';
                                $readonly = 'readonly';
                                $disabled = 'disabled';
                            } else {
                                $con_update_by = 'safe';
                                $readonly = '';
                                $disabled = '';

                                //update for claim
                                $data1 = array(
                                    'status_poi' => $this->status->POIClaim(),
                                    'update_by' => $id_user,
                                    'claim_date' => $datetimenow
                                );
                                $con['poi_id'] = $id_poi;
                                $this->Mglobals->update("tb_geolancer_regular_v2", $data1, $con);

                                //save time QA
                                $this->save_qa_time($id_poi, $id_user);
                            }
                        } elseif ($get_poi->update_by == $id_user) {
                            $con_update_by = 'safe';
                            $readonly = '';
                            $disabled = '';

                            //save time QA
                            $this->save_qa_time($id_poi, $id_user);
                        } else {
                            $con_update_by = 'not_safe';
                            $readonly = 'readonly';
                            $disabled = 'disabled';
                        }

                        //check category approve
                        if ($get_poi->category_autoapproval == '1') {
                            $disabled_cat = 'disabled';
                            $hidden_cat = 'hidden';
                        } else {
                            $disabled_cat = '';
                            $hidden_cat = '';
                        }

                        //send data to view
                        $data['id_poi'] = $id_poi;
                        $data['location_name'] = $get_poi->location_name;
                        $data['location_name_new'] = $get_poi->location_name_std_feedback;
                        $data['new_category'] = $show_drop_down;
                        $data['address'] = $get_poi->address_feedback;
                        $data['unit_no'] = $get_poi->unit_no;
                        $data['latlong_ent'] = $get_poi->latlong_ent;
                        $data['latitude'] = $get_poi->latitude;
                        $data['longitude'] = $get_poi->longitude;
                        $data['category'] = $get_poi->category;
                        $data['pii_status'] = $get_poi->pii_status;
                        $data['inside_building'] = $get_poi->inside_building;
                        $data['building_name'] = $get_poi->building_name;
                        $data['remark'] = $get_poi->remark;
                        $data['predict'] = '';
                        $data['urlnya'] = $get_poi->url;
                        $data['update_bynya'] = $con_update_by;
                        $data['con_readonly'] = $readonly;
                        $data['con_disabled'] = $disabled;
                        $data['con_disabled_cat'] = $disabled_cat;
                        $data['con_hidden_cat'] = $hidden_cat;

                        $this->load->view('template/1_head', $data);
                        $this->load->view('template/2_topbar');
                        $this->load->view('template/3_sidebar');
                        $this->load->view('claim/detil');
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

    private function save_qa_time($id_poi, $id_user)
    {
        //check time QA
        $check_time_qa = $this->Mglobals->getAllQR("SELECT dur_id, COUNT(dur_id) AS jml FROM tb_poi_id_durations WHERE poi_id = '" . $id_poi . "' AND user_id = '" . $id_user . "';");
        if ($check_time_qa->jml > 0) {
            //update data time QA
            $data_tq['work_at'] = $this->modul->DateTimeNowDB();
            $con_tq['dur_id'] = $check_time_qa->dur_id;
            $this->Mglobals->update("tb_poi_id_durations", $data_tq, $con_tq);
        } else {
            //insert data time QA
            $data_add_tq = array(
                'poi_id' => $id_poi,
                'user_id' => $id_user,
                'work_at' => $this->modul->DateTimeNowDB()
            );
            $this->Mglobals->add("tb_poi_id_durations", $data_add_tq);
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
                $category_ori = $this->input->post('category_ori');
                $pii = $this->input->post('pii');
                $building = $this->input->post('building');
                $buildingname = $this->input->post('buildingname');
                $statusnya = $this->input->post('status');
                $predict = $this->input->post('predict');
                $datetimenow = $this->modul->DateTimeNowDB();
                $datenow = $this->modul->DateNowDB();
                $periode = $this->modul->PeriodeNew($datenow);
                $time = $this->input->post('time');

                //get data qa time
                $get_time_qa = $this->Mglobals->getAllQR("SELECT work_at, dur_id FROM tb_poi_id_durations WHERE poi_id = '" . $idpoi . "' AND user_id = '" . $id_user . "';");
                $duration = $this->modul->DurationTime($get_time_qa->work_at, $datetimenow);

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

                //check category
                if ($category == '') {
                    $new_cat = $category_ori;
                } else {
                    $new_cat = $category;
                }

                //get data poi
                $get_poi = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $idpoi . "';");

                $kilo = $this->modul->Distance($get_poi->latitude, $get_poi->longitude, $lat_input, $lng_input, 'K');
                $kilo_bld = $this->modul->Distance($lat_input, $lng_input, $lat_input_bld, $lng_input_bld, 'K');

                //check status
                if ($statusnya == '4') { //pending
                    $data = array(
                        'remark' => $note,
                        'status_poi' => $this->status->POIPending()
                    );
                    $con_distance = 'safe';
                } elseif ($statusnya == '6') { //reject by geolancer
                    //check update_by
                    if ($get_poi->update_by == 0) {
                        $data = array(
                            'edit_status' => $note,
                            'edit_date' => $datetimenow,
                            'update_day' => $datenow,
                            'status_poi' => $this->status->POIRejectGeolancer(),
                            'qc_periode' => $periode,
                            'update_by' => $id_user,
                            'claim_date' => $datetimenow
                        );
                    } else {
                        $data = array(
                            'edit_status' => $note,
                            'edit_date' => $datetimenow,
                            'update_day' => $datenow,
                            'status_poi' => $this->status->POIRejectGeolancer(),
                            'qc_periode' => $periode
                        );
                    }
                    $con_distance = 'safe';
                } elseif ($statusnya == '5') { //reject
                    //check update_by
                    if ($get_poi->update_by == 0) {
                        $data = array(
                            'remark' => $note,
                            'update_date' => $datetimenow,
                            'update_day' => $datenow,
                            'admin_panel' => $this->status->APReject(),
                            'status_poi' => $this->status->POIReject(),
                            'qc_periode' => $periode,
                            'update_by' => $id_user,
                            'claim_date' => $datetimenow
                        );
                    } else {
                        $data = array(
                            'remark' => $note,
                            'update_date' => $datetimenow,
                            'update_day' => $datenow,
                            'admin_panel' => $this->status->APReject(),
                            'status_poi' => $this->status->POIReject(),
                            'qc_periode' => $periode
                        );
                    }
                    $con_distance = 'safe';
                } elseif ($statusnya == '3') { //approve
                    //check update_by
                    if ($get_poi->update_by == 0) {
                        $data = array(
                            'location_name_new' => $namenew,
                            'category_new' => $new_cat,
                            'address_new' => $address,
                            'status_poi' => $this->status->POIApprove(),
                            'pii_status' => $pii,
                            'admin_panel' => $this->status->APApprove(),
                            'building_name' => $buildingname,
                            'inside_building' => $building,
                            'update_date' => $datetimenow,
                            'latlong_ent' => $main_location,
                            'latlong_building' => $building_location,
                            'unit_no_new' => $house_number,
                            'predict' => $predict,
                            'distance' => $kilo,
                            'update_day' => $datenow,
                            'qc_periode' => $periode,
                            'update_by' => $id_user,
                            'claim_date' => $datetimenow
                        );
                    } else {
                        $data = array(
                            'location_name_new' => $namenew,
                            'category_new' => $new_cat,
                            'address_new' => $address,
                            'status_poi' => $this->status->POIApprove(),
                            'pii_status' => $pii,
                            'admin_panel' => $this->status->APApprove(),
                            'building_name' => $buildingname,
                            'inside_building' => $building,
                            'update_date' => $datetimenow,
                            'latlong_ent' => $main_location,
                            'latlong_building' => $building_location,
                            'unit_no_new' => $house_number,
                            'predict' => $predict,
                            'distance' => $kilo,
                            'update_day' => $datenow,
                            'qc_periode' => $periode
                        );
                    }

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
                } else { //unclaim
                    $data = array(
                        'update_by' => '',
                        'status_poi' => $this->status->POIUnclaim()
                    );
                    $con_distance = 'safe';
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
                        //update data time QA
                        $con_qa = array(
                            'dur_id' => $get_time_qa->dur_id
                        );
                        $data_qa = array(
                            'status_poi' => $statusnya,
                            'save_at' => $datetimenow,
                            'duration' => $duration,
                            'iddle_dur' => $time
                        );
                        $this->Mglobals->update("tb_poi_id_durations", $data_qa, $con_qa);

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

    //====================
    //Detil POI
    //====================

    public function index_attribution()
    {
        // get and check session
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

                        //check status
                        if ($get_user_login->status == '2') {
                            $this->modul->Halaman("Login");
                        } else {
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

                            $this->load->view('template_full/1_head', $data);
                            $this->load->view('template_full/2_topbar');
                            $this->load->view('claim/attribution');
                            $this->load->view('template_full/3_footer');
                            $this->load->view('template_full/4_script');
                        }
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

    public function index_regular()
    {
        // get and check session
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

                        //check status
                        if ($get_user_login->status == '2') {
                            $this->modul->Halaman("Login");
                        } else {
                            $poi_id = $this->uri->segment(3);
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

                            $id_poi = $this->uri->segment(3);
                            //get data poi
                            $get_poi = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_regular_v2 WHERE poi_id = '" . $id_poi . "';");

                            $data['project'] = $get_user_login->project . ' project';

                            $this->load->view('template_full/1_head', $data);
                            $this->load->view('template_full/2_topbar');
                            $this->load->view('claim/regular');
                            $this->load->view('template_full/3_footer');
                            $this->load->view('template_full/4_script');
                        }
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
}

<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UM_PersonalData
 *
 * @author dickyhikam
 */
class UM_PersonalData extends CI_Controller
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

                    //get list team
                    $list_team1 = '';
                    $query_team1 = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_team;");
                    foreach ($query_team1->result() as $row) {
                        $list_team1 .= '<option value="' . $row->code . '">' . $row->code . ' (' . $row->name . ')</option>';
                    }

                    //get list level
                    $list_level = '';
                    $query_level = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_level ORDER BY `name`;");
                    foreach ($query_level->result() as $row) {
                        $list_level .= '<option value="' . $row->id . '">' . $row->name . '</option>';
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

                    $data['list_team'] = $list_team;
                    $data['list_team1'] = $list_team1;
                    $data['list_level'] = $list_level;

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('user_management/personal_data/index');
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
                    $user = $this->modul->dekrip_url($this->uri->segment(3));

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

                    //get data user
                    $data_user = $this->Mglobals->getAllQR("SELECT * FROM tb_geolancer_users WHERE user_id = '" . $user . "';");

                    //check status
                    if ($data_user->status == $this->status->UserActive()) {
                        $statusnya = '<span class="badge badge-success">Active</span>';
                        $btn_status = '<button class="btn btn-block btn-sm btn-danger" onclick="modal_inactive(' . "'" . $data_user->user_id . "'" . ', ' . "'" . $data_user->username . "'" . ');">Inactive</button>';
                        $btn_delete = '<button class="btn btn-block btn-sm btn-danger" disabled>Delete</button>';
                    } elseif ($data_user->status == $this->status->UserInactive()) {
                        $statusnya = '<span class="badge badge-danger">Inactive</span>';
                        $btn_status = '<button class="btn btn-block btn-sm btn-success" onclick="modal_active(' . "'" . $data_user->user_id . "'" . ', ' . "'" . $data_user->username . "'" . ');">Active</button>';
                        $btn_delete = '<button class="btn btn-block btn-sm btn-danger" onclick="modal_delete(' . "'" . $data_user->user_id . "'" . ', ' . "'" . $data_user->username . "'" . ');">Delete</button>';
                    } elseif ($data_user->status == $this->status->UserDelete()) {
                        $statusnya = '<span class="badge badge-dark">Delete</span>';
                        $btn_status = '<button class="btn btn-block btn-sm btn-success" disabled>Active</button>';
                        $btn_delete = '<button class="btn btn-block btn-sm btn-danger" disabled>Delete</button>';
                    } else {
                        $statusnya = '<span class="badge badge-warning">Pending</span>';
                        $btn_status = '<button class="btn btn-block btn-sm btn-success" onclick="modal_active(' . "'" . $data_user->user_id . "'" . ', ' . "'" . $data_user->username . "'" . ');">Active</button>';
                        $btn_delete = '<button class="btn btn-block btn-sm btn-danger" onclick="modal_delete(' . "'" . $data_user->user_id . "'" . ', ' . "'" . $data_user->username . "'" . ');">Delete</button>';
                    }

                    //check user
                    if ($data_user->internal == '1') {
                        $internal = 'Internal';
                    } else {
                        $internal = 'External';
                    }

                    //get level
                    $data_level = $this->Mglobals->getAllQR("SELECT *, COUNT(*) AS jml FROM tb_geolancer_level WHERE id = '" . $data_user->level . "';");
                    if ($data_level->jml > 0) {
                        $levelnya = $data_level->name;
                    } else {
                        $levelnya = '-';
                    }

                    //get list team
                    $list_team1 = '';
                    $query_team1 = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_team;");
                    foreach ($query_team1->result() as $row) {
                        //check team
                        if ($row->code == $data_user->team) {
                            $list_team1 .= '<option value="' . $row->code . '" selected>' . $row->code . ' (' . $row->name . ')</option>';
                        } else {
                            $list_team1 .= '<option value="' . $row->code . '">' . $row->code . ' (' . $row->name . ')</option>';
                        }
                    }
                    //get list level
                    $list_level = '';
                    $query_level = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_level ORDER BY `name`;");
                    foreach ($query_level->result() as $row) {
                        //check team
                        if ($row->id == $data_user->level) {
                            $list_level .= '<option value="' . $row->id . '" selected>' . $row->name . '</option>';
                        } else {
                            $list_level .= '<option value="' . $row->id . '">' . $row->name . '</option>';
                        }
                    }
                    //get list country
                    $list_country = '';
                    $query_country = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_country ORDER BY `order`;");
                    foreach ($query_country->result() as $row) {
                        //check country
                        if ($row->code == $data_user->country) {
                            $list_country .= '<option value="' . $row->code . '" selected>' . $row->code . ' (' . $row->name . ')</option>';
                        } else {
                            $list_country .= '<option value="' . $row->code . '">' . $row->code . ' (' . $row->name . ')</option>';
                        }
                    }
                    //get list project
                    $list_project = '';
                    $query_project = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_project ORDER BY FIELD(`type`,'Regular','Attribution','Champions'), `order`;");
                    foreach ($query_project->result() as $row) {
                        //check project
                        if ($row->name == $data_user->qa_project) {
                            $list_project .= '<option value="' . $row->name . '" selected>' . $row->name . ' (' . $row->type . ')</option>';
                        } else {
                            $list_project .= '<option value="' . $row->name . '">' . $row->name . ' (' . $row->type . ')</option>';
                        }
                    }

                    $data['id_user'] = $this->uri->segment(3);
                    $data['name_user'] = $data_user->full_name;
                    $data['username_user'] = $data_user->username;
                    $data['email_user'] = $data_user->email;
                    $data['level_user'] = $levelnya;
                    $data['team_user'] = $data_user->team;
                    $data['user_user'] = $internal;
                    $data['country_user'] = $data_user->country;
                    $data['project_user'] = $data_user->qa_project;
                    $data['version_user'] = 'V.' . $data_user->version;
                    $data['status_user'] = $statusnya;
                    $data['btn_status'] = $btn_status;
                    $data['btn_delete'] = $btn_delete;
                    $data['list_team1'] = $list_team1;
                    $data['list_level'] = $list_level;
                    $data['list_country'] = $list_country;
                    $data['list_project'] = $list_project;

                    $this->load->view('template/1_head', $data);
                    $this->load->view('template/2_topbar');
                    $this->load->view('template/3_sidebar');
                    $this->load->view('user_management/personal_data/detil');
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

            //check session status
            if ($login_status == 'succeed login') {
                $team = $this->uri->segment(3);

                //check team
                if ($team == 'all') {
                    $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_users WHERE status <> '" . $this->status->UserDelete() . "';");
                } else {
                    $list = $this->Mglobals->getAllQ("SELECT * FROM tb_geolancer_users WHERE status <> '" . $this->status->UserDelete() . "' AND team = '" . $team . "';");
                }

                $data = array();
                foreach ($list->result() as $row) {
                    $val = array();
                    //check status
                    if ($row->status == $this->status->UserActive()) {
                        $statusnya = '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == $this->status->UserInactive()) {
                        $statusnya = '<span class="badge badge-danger">Inactive</span>';
                    } elseif ($row->status == $this->status->UserDelete()) {
                        $statusnya = '<span class="badge badge-dark">Delete</span>';
                    } else {
                        $statusnya = '<span class="badge badge-warning">Pending</span>';
                    }

                    //cek version
                    if ($row->version == '3') {
                        $version = 'V.3';
                    } else {
                        $version = 'V.2';
                    }

                    //get level
                    $data_level = $this->Mglobals->getAllQR("SELECT *, COUNT(*) AS jml FROM tb_geolancer_level WHERE id = '" . $row->level . "';");
                    if ($data_level->jml > 0) {
                        $levelnya = $data_level->name;
                    } else {
                        $levelnya = '-';
                    }

                    $val[] = $row->date;
                    $val[] = $row->full_name;
                    $val[] = $row->email;
                    $val[] = $levelnya;
                    $val[] = $row->team;
                    $val[] = $row->username;
                    $val[] = $row->qa_project . '(' . $row->country . ')';
                    $val[] = $version;
                    $val[] = $statusnya;
                    $val[] = '<button class="btn waves-effect waves-light btn-info btn-sm" onclick="modal_detil(' . "'" . $this->modul->enkrip_url($row->user_id) . "'" . ')"> <i class="fas fa-file-alt"></i> </button>';
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
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $username = $this->input->post('username');
                $level = $this->input->post('level');
                $team = $this->input->post('team');
                $user = $this->input->post('user');
                $version = '3';

                //check username
                $check_user = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE username = '" . $username . "';");
                if ($check_user->jml > 0) {
                    $status = "username";
                } else {
                    //check email
                    $check_user1 = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE email = '" . $email . "';");
                    if ($check_user1->jml > 0) {
                        $status = "email";
                    } else {
                        //save to database
                        $data = array(
                            'full_name' => $name,
                            'email' => $email,
                            'username' => $username,
                            'internal' => $user,
                            'team' => $team,
                            'version' => $version,
                            'level' => $level
                        );
                        $simpan = $this->Mglobals->add("tb_geolancer_users", $data);
                        if ($simpan) {
                            $status = "success";
                        } else {
                            $status = "failed";
                        }
                    }
                }

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function update()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->modul->dekrip_url($this->input->post('id'));
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $username = $this->input->post('username');
                $level = $this->input->post('level');
                $team = $this->input->post('team');
                $user = $this->input->post('user');
                $version = '3';
                $project = $this->input->post('project');
                $country = $this->input->post('country');

                //check username
                $check_user = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE username = '" . $username . "' AND user_id != '" . $id . "' AND status != '" . $this->status->UserDelete() . "';");
                if ($check_user->jml > 0) {
                    $status = "username";
                } else {
                    //check email
                    $check_user1 = $this->Mglobals->getAllQR("SELECT COUNT(*) AS jml FROM tb_geolancer_users WHERE email = '" . $email . "' AND user_id != '" . $id . "' AND status != '" . $this->status->UserDelete() . "';");
                    if ($check_user1->jml > 0) {
                        $status = "email";
                    } else {
                        //save to database
                        $data = array(
                            'full_name' => $name,
                            'email' => $email,
                            'username' => $username,
                            'internal' => $user,
                            'team' => $team,
                            'version' => $version,
                            'level' => $level,
                            'qa_project' => $project,
                            'country' => $country
                        );
                        $condition['user_id'] = $id;
                        $simpan = $this->Mglobals->update("tb_geolancer_users", $data, $condition);
                        if ($simpan) {
                            $status = "success";
                        } else {
                            $status = "failed";
                        }
                    }
                }

                echo json_encode(array("status" => $status));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function active()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->input->post('id_confirm');

                //delete to database
                $condition['user_id'] = $id;
                $data['status'] = $this->status->UserInactive();
                $delete = $this->Mglobals->update("tb_geolancer_users", $data, $condition);
                if ($delete) {
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

    public function inactive()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->input->post('id_confirm');

                //delete to database
                $condition['user_id'] = $id;
                $data['status'] = $this->status->UserActive();
                $delete = $this->Mglobals->update("tb_geolancer_users", $data, $condition);
                if ($delete) {
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

    public function delete()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];

            //check session status
            if ($login_status == 'succeed login') {
                $id = $this->input->post('id_confirm');

                //delete to database
                $condition['user_id'] = $id;
                $data['status'] = $this->status->UserDelete();
                $delete = $this->Mglobals->update("tb_geolancer_users", $data, $condition);
                if ($delete) {
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
}

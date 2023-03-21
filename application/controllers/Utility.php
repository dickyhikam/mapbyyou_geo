<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Utility
 *
 * @author dickyhikam
 */
class Utility extends CI_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Modul');
        $this->load->library('Status');

        $this->load->model('Mglobals');
    }

    public function prev()
    {
        $date = $this->uri->segment(3); //get data from url

        $date_results = date('Y-m-d', strtotime($date . " -1 month"));
        $month_year = date('F - Y', strtotime($date . " -1 month"));

        echo json_encode(array("month_year" => $month_year, "date_results" => $date_results));
    }

    public function next()
    {
        $date = $this->uri->segment(3); //get data from url

        $date_results = date('Y-m-d', strtotime($date . " +1 month"));
        $month_year = date('F - Y', strtotime($date . " +1 month"));

        echo json_encode(array("month_year" => $month_year, "date_results" => $date_results));
    }

    public function prev_year()
    {
        $date = $this->uri->segment(3); //get data from url

        $date_results = date('Y-m-d', strtotime($date . " -1 year"));
        $month_year = date('Y', strtotime($date . " -1 year"));

        echo json_encode(array("month_year" => $month_year, "date_results" => $date_results));
    }

    public function next_year()
    {
        $date = $this->uri->segment(3); //get data from url

        $date_results = date('Y-m-d', strtotime($date . " +1 year"));
        $month_year = date('Y', strtotime($date . " +1 year"));

        echo json_encode(array("month_year" => $month_year, "date_results" => $date_results));
    }

    public function prevChart()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                $date = $this->uri->segment(3); //get data from url

                $date_results = date('Y-m-d', strtotime($date . " -1 month"));
                $month_year = date('F - Y', strtotime($date . " -1 month"));

                //get month and year
                $year = date('Y', strtotime($date_results));
                $month = date('m', strtotime($date_results));

                //get first day and last day
                $first_day = $this->modul->FirstDay($date_results);
                $last_day = $this->modul->LastDay($date_results);
                $day = array();
                $jml_prod = array();
                $con = '';
                $procon = array();
                for ($x = $first_day; $x <= $last_day; $x++) {
                    //check tanggal
                    if (strlen($x) == 1) {
                        $angka = '0' . $x;
                        $string = 'date_0' . $x;

                        $day[] = $angka;
                        $con .= ", MAX(CASE WHEN date_day='" . $angka . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    } else {
                        $string = 'date_' . $x;
                        $day[] = $x;
                        $con .= ", MAX(CASE WHEN date_day='" . $x . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    }
                }

                $list = $this->Mglobals->getAllQ("SELECT email" . $con . " FROM view_geo_prod WHERE update_by = '" . $id_user . "' AND date_month = '" . $month . "' AND date_year = '" . $year . "' GROUP BY email;");
                foreach ($list->result() as $row) {
                    for ($i = 0; $i < count($procon); $i++) {
                        $tampung = $procon[$i];
                        $jml_prod[] = $row->$tampung . ',';
                    }
                }

                echo json_encode(array(
                    "month_year" => $month_year,
                    "date_results" => $date_results,
                    "day" => $day,
                    "jml_prod" => $jml_prod
                ));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }

    public function nextChart()
    {
        //get and check session
        $session_data = $this->session->userdata('login');

        //check session
        if (isset($session_data)) {
            $login_status = $session_data['login_status'];
            $id_user = $session_data['id'];

            //check session status
            if ($login_status == 'succeed login') {
                $date = $this->uri->segment(3); //get data from url

                $date_results = date('Y-m-d', strtotime($date . " +1 month"));
                $month_year = date('F - Y', strtotime($date . " +1 month"));

                //get month and year
                $year = date('Y', strtotime($date_results));
                $month = date('m', strtotime($date_results));

                //get first day and last day
                $first_day = $this->modul->FirstDay($date_results);
                $last_day = $this->modul->LastDay($date_results);
                $day = array();
                $jml_prod = array();
                $con = '';
                $procon = array();
                for ($x = $first_day; $x <= $last_day; $x++) {
                    //check tanggal
                    if (strlen($x) == 1) {
                        $angka = '0' . $x;
                        $string = 'date_0' . $x;

                        $day[] = $angka;
                        $con .= ", MAX(CASE WHEN date_day='" . $angka . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    } else {
                        $string = 'date_' . $x;
                        $day[] = $x;
                        $con .= ", MAX(CASE WHEN date_day='" . $x . "' THEN jml ELSE 0 END) '" . $string . "'";
                        $procon[] = $string;
                    }
                }

                $list = $this->Mglobals->getAllQ("SELECT email" . $con . " FROM view_geo_prod WHERE update_by = '" . $id_user . "' AND date_month = '" . $month . "' AND date_year = '" . $year . "' GROUP BY email;");
                foreach ($list->result() as $row) {
                    for ($i = 0; $i < count($procon); $i++) {
                        $tampung = $procon[$i];
                        $jml_prod[] = $row->$tampung . ',';
                    }
                }

                echo json_encode(array(
                    "month_year" => $month_year,
                    "date_results" => $date_results,
                    "day" => $day,
                    "jml_prod" => $jml_prod
                ));
            } else {
                $this->modul->Halaman("Login");
            }
        } else {
            $this->modul->Halaman("Login");
        }
    }
}

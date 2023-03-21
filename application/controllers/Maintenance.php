<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Maintenance
 *
 * @author dickyhikam
 */
class Maintenance extends CI_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Status');
        $this->load->library('Modul');

        $this->load->model('Mglobals');
        $this->load->model('DbUsers');
        $this->load->model('DbVersion');
    }

    public function index()
    {
        $this->session->sess_destroy();

        //get version
        $get_version = $this->DbVersion->RowVersion('*', "status = '" . $this->status->VersionActive() . "'");

        //versi browser
        $data['name_browser'] = $this->modul->DetectBrowser();
        $data['menu_name'] = 'Maintenance';
        $data['show_version'] = $get_version->version . '.' . $get_version->sub_version;

        $this->load->view('template/1_head', $data);
        $this->load->view('maintenance/index');
        $this->load->view('template/5_script');
    }
}

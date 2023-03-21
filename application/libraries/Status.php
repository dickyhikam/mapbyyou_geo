<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of status
 *
 * @author Dicky
 */
class Status {

    //put your code here
    public function QCProgress() {
        $name_status = 'Inprogress';
        return $name_status;
    }
    public function QCDone() {
        $name_status = 'Done QC';
        return $name_status;
    }
    public function QCProgressQA() {
        $name_status = 'Inprogress QA Edit';
        return $name_status;
    }
    public function QCDoneQA() {
        $name_status = 'Done QA Edit';
        return $name_status;
    }
    public function QCChoose() {
        $name_status = 'Yes';
        return $name_status;
    }
    
    public function QCProgress1() {
        $name_status = '1';
        return $name_status;
    }
    public function QCDone1() {
        $name_status = '2';
        return $name_status;
    }
    
    public function QAProgress() {
        $name_status = 'Inprogress';
        return $name_status;
    }
    public function QADone() {
        $name_status = 'Done';
        return $name_status;
    }
    
    public function VersionActive() {
        $name_status = 'Active';
        return $name_status;
    }
    
    public function VersionInactive() {
        $name_status = 'Inactive';
        return $name_status;
    }
    
    public function UserActive() {
        $name_status = '1'; //user login
        return $name_status;
    }
    
    public function UserInactive() {
        $name_status = '2'; //user login
        return $name_status;
    }
    
    public function UserDelete() {
        $name_status = '3';
        return $name_status;
    }
    
    public function UserPending() {
        $name_status = '0'; //user login
        return $name_status;
    }
    
    public function POIUnclaim() {
        $name_status = '1';
        return $name_status;
    }
    
    public function POIClaim() {
        $name_status = '2';
        return $name_status;
    }
    
    public function POIApprove() {
        $name_status = '3';
        return $name_status;
    }
    
    public function POIPending() {
        $name_status = '4';
        return $name_status;
    }
    
    public function POIReject() {
        $name_status = '5';
        return $name_status;
    }
    
    public function POIRejectGeolancer() {
        $name_status = '6';
        return $name_status;
    }
    
    public function APReject() {
        $name_status = 'pending reject';
        return $name_status;
    }
    
    public function APApprove() {
        $name_status = 'pending';
        return $name_status;
    }
    
}

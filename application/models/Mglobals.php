<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mglobals
 *
 * @author Dicky Hikam
 */
class Mglobals extends CI_Model
{
    //put your code here
    //pakai query custom menampilkan banyak data
    public function getAllQ($q)
    {
        $this->load->database();
        $this->db->reconnect();

        $list = $this->db->query($q);
        return $list;
    }

    //query custom menampilkan satu baris data
    public function getAllQR($q)
    {
        $this->load->database();
        $this->db->reconnect();

        $list = $this->db->query($q);
        return $list->row();
    }

    //aktive record memanggil nama tabel saja
    public function getAll($table)
    {
        $this->load->database();
        $this->db->reconnect();

        $this->db->from($table);
        return $this->db->get();
    }

    public function getAllW($table, $kondisi)
    {
        $this->load->database();
        $this->db->reconnect();

        $this->db->from($table);
        $this->db->where($kondisi);
        return $this->db->get();
    }

    public function add($table, $data)
    {
        $this->load->database();
        $this->db->reconnect();

        $simpan = $this->db->insert($table, $data);
        return $simpan;
    }

    public function addwidthid($table, $data)
    {
        $this->load->database();
        $this->db->reconnect();

        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function delete($table, $kondisi)
    {
        $this->load->database();
        $this->db->reconnect();

        $this->db->where($kondisi);
        $delete = $this->db->delete($table);
        return $delete;
    }

    public function deleteAll($table)
    {
        $this->load->database();
        $this->db->reconnect();

        $delete = $this->db->delete($table);
        return $delete;
    }

    public function get_by_id($table, $kondisi)
    {
        $this->load->database();
        $this->db->reconnect();

        $this->db->from($table);
        $this->db->where($kondisi);
        $query = $this->db->get();
        return $query->row();
    }

    public function update($table, $data, $condition)
    {
        $this->load->database();
        $this->db->reconnect();

        $this->db->where($condition);
        $update = $this->db->update($table, $data);
        return $update;
    }

    public function updateNK($table, $data)
    {
        $this->load->database();
        $this->db->reconnect();

        $update = $this->db->update($table, $data);
        return $update;
    }
}

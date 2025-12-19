<?php

namespace App\Models;

use CodeIgniter\Model;

class Dashboard_model extends MY_Model {

    function __construct() {
        parent::__construct();
        
    }
    
    function sumInterview(){
        $sql = "SELECT count(id) AS total FROM m_pekerja WHERE verifikasi_admin=0";
        $query = $this->db->query($sql);
        $result =$query->getRow();
        return $result->total;
    }
    function sumCompany(){
        $sql = "SELECT count(id_company) AS total FROM company WHERE status<9";
        $query = $this->db->query($sql);
        $result =$query->getRow();
        return $result->total;
    }
    function sumPartner(){
        $sql = "SELECT count(id) AS total FROM m_pekerja WHERE status=1 AND verifikasi_admin=1";
        $query = $this->db->query($sql);
        $result =$query->getRow();
        return $result->total;
    }
    function sumTask(){
        $sql = "SELECT count(id) AS total FROM t_tugas WHERE tanggal_selesai<NOW() AND status < 9";
        $query = $this->db->query($sql);
        $result =$query->getRow();
        return $result->total;
    }
    
    
    
    
    function pendingDeposit(){
        $sql = "SELECT sld.*, com.name AS nama_company
               FROM t_saldo AS sld
                LEFT JOIN company AS com ON com.id_company=sld.id_company
               WHERE sld.flag=0 AND sld.status=0";
        $query = $this->db->query($sql);
        return $query->getResult();
    }
    function pendingWithDraw(){
        $sql = "SELECT sld.*, pek.nama AS nama_pekerja
               FROM t_saldo_pekerja AS sld
                LEFT JOIN m_pekerja AS pek ON pek.id=sld.id_pekerja
               WHERE sld.flag=1 AND sld.status=0";
        $query = $this->db->query($sql);
        return $query->getResult();
    }
    function pending_userold(){
        $sql = "SELECT * FROM m_pekerja WHERE id<5000 AND foto_formal=''";
        $query = $this->db->query($sql);
        return $query->getResult();
    }
    
    
    function sumDeposit(){
        $sql = "SELECT sum(total) AS total FROM t_saldo WHERE flag=0 AND status=1";
        $query = $this->db->query($sql);
        $result =$query->getRow();
        return $result->total;
    }
    function sumWithDraw(){
        $sql = "SELECT sum(total) AS total FROM t_saldo_pekerja WHERE flag=1 AND status=1";
        $query = $this->db->query($sql);
        $result =$query->getRow();
        return $result->total;
    }





    public function corporate_countPerbulan_deposit($tgl, $com){   
        if($this->session->userdata('sess_level')==1){
            $cabang = " AND saldo.id_cabang='".$this->session->userdata('sess_cabang_id')."' ";
            $flag = " AND saldo.flag=1";
        }else{
            $cabang = "";
            $flag = " AND saldo.flag=0";
        }
        $sql = "
                SELECT
                    SUM(saldo.total) AS total
                FROM t_saldo AS saldo
                WHERE saldo.status=1 AND saldo.id_company IN ('".implode("','", $com)."') ".$cabang.$flag." AND SUBSTR(created_date, 1, 7)='".$tgl."'
                ";
        $query = $this->db->query($sql);
        return $query->getRow(); 
    }
    
    public function corporate_countPerbulan_task($tgl, $com){   
        if($this->session->userdata('sess_level')==1){
            $cabang = " AND tugas.id_cabang='".$this->session->userdata('sess_cabang_id')."' ";
        }else{
            $cabang = "";
        }
        $sql = "
                SELECT
                    SUM(pekerja.aktual_fee) AS total
                FROM t_tugas_pekerja AS pekerja
                    LEFT JOIN t_tugas AS tugas ON tugas.id = pekerja.id_tugas
                WHERE 
                    pekerja.status=1 AND pekerja.id_company IN ('".implode("','", $com)."') ".$cabang." AND SUBSTR(pekerja.modified_date, 1, 7)='".$tgl."'
                ";
        $query = $this->db->query($sql);
        return $query->getRow(); 
    }

}

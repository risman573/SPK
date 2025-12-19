<?php

namespace App\Models;

use App\Models\MY_Model;
use CodeIgniter\Model;

class Company_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "company";
        $this->primaryKey = "company.id_company";
        $this->fields = array(            
            "company.id_company",
            "company.id_corporate",
            "company.name",
            "company.telp",
            "company.address",
            "company.email",
            "company.pic",
            "company.kode_pekerja",
            "company.persen",
            "company.foto",
            "company.startdate",
            "company.enddate",
            "company.id_user",
            
            "status.nama AS status_dok",
            "company.status",
            "company.created_date",
            "company.created_user",
            "company.modified_date",
            "company.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
            
            "user.name AS u_name",
            "user.username AS u_username",
            );
        $this->orderBy = array("company.name" => "ASC");
        $this->relations = array(
            "user AS user"=>"user.id_user=company.id_user AND user.status<9",
            "combo AS status"=>"status.kode=company.status and status.flag='status' and status.status<9",
            "user AS user1"=>"user1.id_user=company.created_user",
            "user AS user2"=>"user2.id_user=company.modified_user",
        );
        $this->joins = array(
            "left",
            "left",
            "left",
            "left",
        );
    }
    
    function get_company(){
        $sql = "select * from company";
        $query = $this->db->query($sql);
        return $query->getResult('array');
    }
    
    function getProvider($id=null){
        $where = ($id==null) ? '' : ' AND cp.id_company = "'.$id.'"';
        $sql = "
                SELECT
                    p.name, p.url, p.username, p.password, cp.maskingid, cp.id, cp.quota, cp.id
                FROM company_provider AS cp
                    RIGHT JOIN provider AS p ON p.id = cp.id_provider
                WHERE p.status = 1
                ";
        $query = $this->db->query($sql);
        return $query->getResult('array');
    }

    function countCompAktif(){
        $sql = "  SELECT COUNT(modified_date) AS aktif 
                    FROM company
                    WHERE enddate > CURDATE();
                ";
        $query = $this->db->query($sql);
        return $query->getRow()->aktif;
    }

    function countExpired(){
        $sql = "  SELECT COUNT(modified_date) AS expired 
                    FROM company
                    WHERE enddate < CURDATE()
                ";
        $query = $this->db->query($sql);
        return $query->getRow()->expired;
    }

    function getKategoryCompany($val){
    
        $sql = "SELECT 
                    COUNT(comp.id_company) AS jumlah, comp.name AS perusahaan, cab.nama AS cabang, us.name AS user
                FROM 
                    company AS comp 
                    RIGHT JOIN m_cabang AS cab ON cab.id_company = comp.id_company
                    RIGHT JOIN user AS us ON us.id_user = comp.id_user
                WHERE comp.status < 9 AND enddate>=CURDATE()
                GROUP BY ".$val."
                ORDER BY ".$val." ASC
               ";
        $query = $this->db->query($sql);
        return $query->getResult('array');
    }
    
}

<?php

namespace App\Controllers;

use App\Controllers\MY_Controller;
use App\Models\Hasil_model;

class Hasil extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->Hasil_model = new Hasil_model();

        $this->model = $this->Hasil_model;
        $this->table = "hasil";
        $this->pkField = "hasil.id_hasil";
        $this->kode = "id_hasil";

        $this->fields = array(
            "alternatif.nama_alternatif" => array("TIPE" => "STRING", "LABEL" => "Alternative"),
            "hasil.nilai_preferensi" => array("TIPE" => "FLOAT", "LABEL" => "Preference Value"),
            "hasil.ranking" => array("TIPE" => "INT", "LABEL" => "Ranking"),
            "status.nama" => array("TIPE" => "STRING", "LABEL" => "Status"),
            "user1.name" => array("TIPE" => "STRING", "LABEL" => "Created User"),
            "hasil.created_date" => array("TIPE" => "DATETIME", "LABEL" => "Created Date"),
            "user2.name" => array("TIPE" => "STRING", "LABEL" => "Modified User"),
            "hasil.modified_date" => array("TIPE" => "DATETIME", "LABEL" => "Modified Date"),
            "action" => array("TIPE" => "TRANSACTION", "LABEL" => "Action"),
        );
    }

    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
        if($this->lihat!=1){
            return view('noaccess_view', $this->data);
        }else{
            return view('spk/hasil_view', $this->data);
        }
    }

    public function ajax_list() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }

        $draw = $this->request->getPost('draw');
        $row = $this->request->getPost('start');
        $rowperpage = $this->request->getPost('length');
        $columnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $columnName = $this->request->getPost('columns')[$columnIndex]['data'];
        $columnSortOrder = $this->request->getPost('order')[0]['dir'] ?? 'asc';
        $searchValue = $this->request->getPost('search')['value'];

        $totalrows = $this->model->countAll();

        $data = $this->model->get_datatables($draw, $row, $rowperpage, $searchValue, $columnName, $columnSortOrder);

        $result = array(
            "draw" => $draw,
            "iTotalRecords" => $totalrows,
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        return $this->response->setJSON($result);
    }

    public function dataInput() {
        $this->form_validation->setRule('id_alternatif', 'Alternative', 'required');
        $this->form_validation->setRule('nilai_preferensi', 'Preference Value', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[1]');
        $this->form_validation->setRule('ranking', 'Ranking', 'required|numeric|greater_than[0]');
        $this->form_validation->setRule('status', 'Status', 'required');

        if ($this->form_validation->withRequest($this->request)->run() == FALSE) {
            $result['status'] = 'error';
            $result['msg'] = $this->form_validation->getErrors();
        } else {
            $id_hasil = $this->request->getPost('id_hasil');

            $data = array(
                'id_alternatif' => $this->request->getPost('id_alternatif'),
                'nilai_preferensi' => $this->request->getPost('nilai_preferensi'),
                'ranking' => $this->request->getPost('ranking'),
                'status' => $this->request->getPost('status'),
                'modified_user' => $this->session->get('website_admin_id'),
                'modified_date' => date('Y-m-d H:i:s.u')
            );

            if (empty($id_hasil)) {
                $data['id_hasil'] = \Ramsey\Uuid\Uuid::uuid4()->toString();
                $data['created_user'] = $this->session->get('website_admin_id');
                $data['created_date'] = date('Y-m-d H:i:s');

                if ($this->model->save($data)) {
                    $result['status'] = 'success';
                    $result['msg'] = 'Data saved successfully';
                } else {
                    $result['status'] = 'error';
                    $result['msg'] = 'Failed to save data';
                }
            } else {
                if ($this->model->update($id_hasil, $data)) {
                    $result['status'] = 'success';
                    $result['msg'] = 'Data updated successfully';
                } else {
                    $result['status'] = 'error';
                    $result['msg'] = 'Failed to update data';
                }
            }
        }

        return $this->response->setJSON($result);
    }

    public function add() {
        if ($this->request->getMethod() == 'post') {
            return $this->dataInput();
        }
    }

    public function update() {
        if ($this->request->getMethod() == 'post') {
            return $this->dataInput();
        }
    }

    public function edit($id = null) {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }

        $id_hasil = $id ?? $this->request->getPost('id_hasil');
        $data = $this->model->find($id_hasil);

        return $this->response->setJSON($data);
    }

    public function delete($id = null) {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }

        $id_hasil = $id ?? $this->request->getPost('id_hasil');

        if ($this->model->delete($id_hasil)) {
            $result['status'] = 'success';
            $result['msg'] = 'Data deleted successfully';
        } else {
            $result['status'] = 'error';
            $result['msg'] = 'Failed to delete data';
        }

        return $this->response->setJSON($result);
    }
}

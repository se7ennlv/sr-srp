<?php

class EmpModel extends CI_Model
{
    public function FindOneEmp($empId)
    {
        $this->db->where('EmpCode', $empId);
        $query = $this->db->get('Employees');

        return $query->row();
    }

    public function FindAllEmps()
    {
        if ($this->session->userdata('userOrgId') == 3) {
            $suvisor = '200001';
        } else {
            $suvisor = $this->session->userdata('userEmpId');
        }

        $this->db->group_start();
        $this->db->where('Manager1', $suvisor);
        $this->db->or_where('Manager2', $suvisor);
        $this->db->or_where('Suvisor1', $suvisor);
        $this->db->or_where('Suvisor2', $suvisor);
        $this->db->group_end();
        $this->db->where('EmpID NOT LIKE', $suvisor);
        $this->db->order_by('OrgID, EmpCode', 'ASC');
        $query = $this->db->get('Employees');

        return $query->result();
    }
}

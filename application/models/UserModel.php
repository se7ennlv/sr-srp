<?php

class UserModel extends CI_Model
{
    public function LoginValid($usr, $pwd)
    {
        $this->db->where('UserEmpCode', $usr);
        $this->db->where('UserPassword', $pwd);
        $this->db->where('UserIsActive', 1);
        $query = $this->db->get('Users');

        return $query->row();
    }

    public function FindMGroupBySession()
    {
        $this->db->where('MGroupIsActive', 1);
        $this->db->where_in('MGroupID', $this->session->userdata('userMgroupId'), false);
        $this->db->order_by('MGroupOrderBy', 'ASC');
        $qurey = $this->db->get('MenuGroups');

        return $qurey->result();
    }

    public function FindMenuBySession()
    {
        $this->db->where('MenuIsActive', 1);
        $this->db->where_in('MenuID', $this->session->userdata('userMenuId'), false);
        $this->db->order_by('MenuOrderBy', 'ASC');
        $qurey = $this->db->get('Menus');

        return $qurey->result();
    }

    public function ExecuteChangePass()
	{
		$data = array(
			'UserPassword' => sha1($this->input->post('NewPass')),
			'UserUpdatedAt' => date('Y-m-d H:i:s')
		);

		$this->db->where('UserID', $this->session->userdata('userId'));
		$this->db->update('Users', $data);

		$this->output->set_content_type('application/json')->set_output(json_encode(array("status" => "success", "message" => 'Password Changed.')));
	}
}

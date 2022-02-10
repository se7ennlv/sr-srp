<?php

class ServiceModel extends CI_Model
{
    public function FindMailApprovers($orgId)
    {
        $this->db->where('ApprOrgID', $orgId);
        $this->db->where('ApprIsActive', 1);
        $query = $this->db->get('ApproverGroups');

        return $query->result();
    }

    public function SendMail($to, $subj, $msg)
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'webmail.savanresorts.com',
            'smtp_user' => 'sageadmin@savanresorts.com',
            'smtp_pass' => '777Login@@@',
            'smtp_port' => 666
        );

        $this->load->library('email', $config);

        $this->email->set_newline("\r\n");
        $this->email->from('srpadmin@savanresorts.com', 'SRP Administrator');
        $this->email->to($to);
        $this->email->subject($subj);
        $this->email->message($msg);
        $this->email->send();
    }
}

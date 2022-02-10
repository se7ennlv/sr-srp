<?php

class PrizeModel extends CI_Model
{
    public function FindAllPrizes()
    {
        $this->db->where('PrizeIsActive', 1);
        $query = $this->db->get('Prizes');
        return $query->result();
    }

    
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Task_model extends CI_Model
{
    /**
     * This function is used to get the task listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */

    function listTagihan($tanggal_awal, $tanggal_akhir)
    {
        $this->db->select('SUM(b.total_tagihan) AS total_tagihan, a.nama_vendor');
        $this->db->from('tbl_tagihan as b');
        $this->db->join('tbl_vendors as a', 'a.vendor_id = b.vendor_id', 'left');
        $this->db->where('b.status', 'konfirmasi');
        $this->db->where('b.tanggal_tagihan >=', $tanggal_awal);
        $this->db->where('b.tanggal_tagihan <=', $tanggal_akhir);
        $this->db->group_by('b.vendor_id');
        $query = $this->db->get();


        return $query->result();
    }
}

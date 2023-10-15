<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Vendor_model extends CI_Model
{
    /**
     * This function is used to get the task listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function vendorListingCount($searchText)
    {
        $this->db->select('BaseTbl.vendor_id, BaseTbl.nama_vendor, BaseTbl.alamat_vendor, BaseTbl.kode_vendor, BaseTbl.tanggal_bergabung, u.email');
        $this->db->from('tbl_vendors as BaseTbl');
        $this->db->join('tbl_users u', 'BaseTbl.vendor_id = u.vendorId', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.taskTitle LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        // $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function getVendorById($vendor_id)
    {
        $this->db->select('BaseTbl.vendor_id, BaseTbl.nama_vendor, BaseTbl.alamat_vendor, BaseTbl.kode_vendor, BaseTbl.tanggal_bergabung, u.email');
        $this->db->from('tbl_vendors as BaseTbl');
        $this->db->join('tbl_users u', 'BaseTbl.vendor_id = u.vendorId', 'left');
        $this->db->where('BaseTbl.vendor_id', $vendor_id);

        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    public function updateVendor($vendor_id, $data)
    {
        $this->db->where('vendor_id', $vendor_id);
        return $this->db->update('tbl_vendors', $data);
    }

    public function updateEmailUser($vendor_id, $email)
    {
        $this->db->set('email', $email);
        $this->db->where('vendorId', $vendor_id);
        $this->db->update('tbl_users');
    }

    function vendorListing($searchText, $page, $segment)
    {
        $this->db->select('BaseTbl.vendor_id, BaseTbl.nama_vendor, BaseTbl.alamat_vendor, BaseTbl.kode_vendor, BaseTbl.tanggal_bergabung, u.email');
        $this->db->from('tbl_vendors as BaseTbl');
        $this->db->join('tbl_users u', 'BaseTbl.vendor_id = u.vendorId', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.nama_vendor LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        // $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.vendor_id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to add new task to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewVendor($vendorInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_vendors', $vendorInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }
}

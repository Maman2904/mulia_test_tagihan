<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Tagihan_model extends CI_Model
{

    public function getDataToExport()
    {
        $this->db->select('u.name AS vendor_name, t.tagihan_id, t.nomor_tagihan, t.tanggal_tagihan, t.total_tagihan, t.file_lampiran, t.status, i.item_id, i.item, i.besar_item');
        $this->db->from('tbl_tagihan t');
        $this->db->join('item_tagihan i', 't.tagihan_id = i.tagihan_id', 'left');
        $this->db->join('tbl_users u', 't.vendor_id = u.vendorId', 'left');
        $this->db->where('t.status', 'konfirmasi');

        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    public function getTagihanById($tagihan_id)
    {
        $this->db->where('tagihan_id', $tagihan_id);
        return $this->db->get('tbl_tagihan')->row();
    }

    public function getItemsByTagihanId($tagihan_id)
    {
        $this->db->where('tagihan_id', $tagihan_id);
        return $this->db->get('item_tagihan')->result();
    }

    public function getVendorIdByUserId($user_id)
    {
        $this->db->select('vendorId');
        $this->db->where('userId', $user_id);
        $query = $this->db->get('tbl_users');

        if ($query->num_rows() > 0) {
            // Ambil hasil query pertama (karena userId harus unik)
            $result = $query->row();
            return $result->vendorId;
        } else {
            // Jika tidak ada hasil, kembalikan null atau nilai default lainnya
            return null;
        }
    }

    public function getNameVendorByVendorId($vendor_id)
    {
        $this->db->select('nama_vendor');
        $this->db->where('vendor_id', $vendor_id);
        $query = $this->db->get('tbl_vendors');

        if ($query->num_rows() > 0) {
            // Ambil hasil query pertama (karena userId harus unik)
            $result = $query->row();
            return $result->nama_vendor;
        } else {
            // Jika tidak ada hasil, kembalikan null atau nilai default lainnya
            return null;
        }
    }

    function tagihanListingCount($searchText, $vendor_id, $isAdmin)
    {
        if (!$isAdmin) {
            $this->db->select('BaseTbl.tagihan_id, BaseTbl.nomor_tagihan, BaseTbl.tanggal_tagihan, BaseTbl.total_tagihan, BaseTbl.file_lampiran, BaseTbl.status');
            $this->db->from('tbl_tagihan as BaseTbl');
            if (!empty($searchText)) {
                $likeCriteria = "(BaseTbl.nomor_tagihan LIKE '%" . $searchText . "%')";
                $this->db->where($likeCriteria);
            }
            // $this->db->where('BaseTbl.isDeleted', 0);
            $this->db->where('BaseTbl.vendor_id', $vendor_id);
            $query = $this->db->get();

            return $query->num_rows();
        } else {

            $this->db->select('BaseTbl.tagihan_id, BaseTbl.nomor_tagihan, BaseTbl.tanggal_tagihan, BaseTbl.total_tagihan, BaseTbl.file_lampiran, BaseTbl.status, u.name');
            $this->db->from('tbl_tagihan as BaseTbl');

            // JOIN dengan tabel 'tbl_user' berdasarkan kolom 'vendor_id'
            $this->db->join('tbl_users as u', 'BaseTbl.vendor_id = u.vendorId', 'left');

            if (!empty($searchText)) {
                $likeCriteria = "(u.name LIKE '%" . $searchText . "%')";
                $this->db->where($likeCriteria);
            }
            $this->db->where('BaseTbl.status', "konfirmasi");
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

    /**
     * This function is used to get the task listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function tagihanListing($searchText, $page, $segment, $vendor_id, $isAdmin)
    {
        if (!$isAdmin) {
            $this->db->select('BaseTbl.tagihan_id, BaseTbl.nomor_tagihan, BaseTbl.tanggal_tagihan, BaseTbl.total_tagihan, BaseTbl.file_lampiran, BaseTbl.status');
            $this->db->from('tbl_tagihan as BaseTbl');
            if (!empty($searchText)) {
                $likeCriteria = "(BaseTbl.nomor_tagihan LIKE '%" . $searchText . "%')";
                $this->db->where($likeCriteria);
            }
            // $this->db->where('BaseTbl.isDeleted', 0);
            // Filter berdasarkan vendor_id jika bukan isAdmin
            $this->db->where('BaseTbl.vendor_id', $vendor_id);
            $this->db->order_by('BaseTbl.tagihan_id', 'DESC');
            $this->db->limit($page, $segment);
            $query = $this->db->get();

            $result = $query->result();
            return $result;
        } else {
            $this->db->select('BaseTbl.tagihan_id, BaseTbl.nomor_tagihan, BaseTbl.tanggal_tagihan, BaseTbl.total_tagihan, BaseTbl.file_lampiran, BaseTbl.status, u.name');
            $this->db->from('tbl_tagihan as BaseTbl');

            $this->db->join('tbl_users as u', 'BaseTbl.vendor_id = u.vendorId', 'left');
            if (!empty($searchText)) {
                $likeCriteria = "(u.name  LIKE '%" . $searchText . "%')";
                $this->db->where($likeCriteria);
            }
            $this->db->where('BaseTbl.status', "konfirmasi");
            $this->db->order_by('BaseTbl.tagihan_id', 'DESC');
            $this->db->limit($page, $segment);
            $query = $this->db->get();

            $result = $query->result();
            return $result;
        }
    }

    /**
     * This function is used to get the task listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    // Metode untuk menyimpan data tagihan ke dalam tabel 'tagihan'
    public function saveTagihan($data_tagihan)
    {
        $this->db->insert('tbl_tagihan', $data_tagihan);
        return $this->db->insert_id(); // Mengembalikan ID tagihan yang baru saja disimpan
    }

    // Metode untuk menyimpan data item yang ditagih ke dalam tabel 'item_tagihan'
    public function saveItems($tagihan_id, $items, $besar_items)
    {
        for ($i = 0; $i < count($items); $i++) {
            $data_item = array(
                'tagihan_id' => $tagihan_id,
                'item' => $items[$i],
                'besar_item' => $besar_items[$i],
            );

            $this->db->insert('item_tagihan', $data_item);
        }
    }

    public function updateItem($item_id, $data_item)
    {
        // Lakukan pembaruan data item berdasarkan item_id
        $this->db->where('item_id', $item_id);
        $this->db->update('item_tagihan', $data_item);
    }

    public function addItem($data_item)
    {
        // Lakukan penambahan item
        $this->db->insert('item_tagihan', $data_item);
    }

    public function updateTagihan($tagihan_id, $data)
    {
        $this->db->where('tagihan_id', $tagihan_id);
        return $this->db->update('tbl_tagihan', $data);
    }

    public function getDraftTagihanByVendor($vendor_id)
    {
        $this->db->where('vendor_id', $vendor_id);
        $this->db->where('status', 'Draft');
        return $this->db->get('tbl_tagihan')->result();
    }

    public function getKonfirmasiTagihanByVendor($vendor_id)
    {
        $this->db->where('vendor_id', $vendor_id);
        $this->db->where('status', 'Konfirmasi');
        return $this->db->get('tbl_tagihan')->result();
    }

    public function konfirmasiTagihan($tagihan_id)
    {
        $this->db->where('tagihan_id', $tagihan_id);
        $this->db->update('tbl_tagihan', array('status' => 'Konfirmasi'));
    }

    function getTagihanInfo($tagihan_id)
    {
        $this->db->select('tagihan_id, nomor_tagihan, tanggal_tagihan, total_tagihan, file_lampiran, status');
        $this->db->from('tbl_tagihan');
        $this->db->where('tagihan_id', $tagihan_id);
        // $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->row();
    }

    function getTagihanItemInfo($tagihan_id)
    {
        $this->db->select('item_id, tagihan_id, item, besar_item');
        $this->db->from('item_tagihan');
        $this->db->where('tagihan_id', $tagihan_id);
        // $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->row();
    }
}

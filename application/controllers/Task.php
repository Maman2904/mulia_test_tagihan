<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Task extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Task_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Task';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        // redirect('task/taskListing');
        $this->global['pageTitle'] = 'Chart Filter';
        $this->loadViews("task/list", $this->global);
    }


    function chart()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Ambil data dari form (tanggal_awal dan tanggal_akhir)
            $tanggal_awal = $this->input->post('tanggal_awal');
            $tanggal_akhir = $this->input->post('tanggal_akhir');

            // var_dump($tanggal_awal);
            // var_dump($$tanggal_akhir); // Ini akan menampilkan data ke dalam klien
            // echo "Debug message";

            $res = $this->tm->listTagihan($tanggal_awal, $tanggal_akhir);


            $total_tagihan_array = array();
            $nama_vendor_array = array();

            foreach ($res as $row) {
                $total_tagihan_array[] = $row->total_tagihan;
                $nama_vendor_array[] = $row->nama_vendor;
            }

            // print_r($total_tagihan_array);
            // print_r($nama_vendor_array);

            $data['nama_vendor'] = json_encode($nama_vendor_array);
            $data['total_tagihan'] = json_encode($total_tagihan_array);

            $this->global['pageTitle'] = 'Chart';

            $this->loadViews("task/chart", $this->global, $data, NULL);
        }
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';


class Vendor_ extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Vendor_model', 'vm');
        $this->isLoggedIn();
        $this->module = 'Vendor_';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('vendor_/vendorListing');
    }

    /**
     * This function is used to load the vendor list
     */
    function vendorListing()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $searchText = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->vm->vendorListingCount($searchText);

            $returns = $this->paginationCompress("vendorListing/", $count, 10);

            $data['records'] = $this->vm->vendorListing($searchText, $returns["page"], $returns["segment"]);

            // var_dump($data);

            $this->global['pageTitle'] = 'Vendor';

            $this->loadViews("vendor/list", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Add New Vendor';

            $this->loadViews("vendor/add", $this->global, NULL, NULL);
        }
    }

    function edit($vendor_id = NULL)
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            if ($vendor_id == null) {
                redirect('vendor_/vendorListing');
            }

            $this->load->helper('form');
            $this->load->library('form_validation');

            $data['record'] = $this->vm->getVendorById($vendor_id);

            $data['record'] = (object) $data['record'][0];

            $this->global['pageTitle'] = 'Mulia : Edit Tagihan';

            $this->loadViews("vendor/edit", $this->global, $data, NULL);
        }
    }

    public function updateVendor($vendor_id)
    {
        // Validasi form edit jika diperlukan
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama_vendor', 'Nama Vendor', 'required');
        $this->form_validation->set_rules('alamat_vendor', 'Alamat Vendor', 'required');
        $this->form_validation->set_rules('kode_vendor', 'Kode Vendor', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            // Jika validasi gagal, tampilkan kembali halaman edit dengan pesan error
            $this->load->view('vendor/edit');
        } else {
            $email = $this->input->post('email');
            $data = array(
                'nama_vendor' => $this->input->post('nama_vendor'),
                'alamat_vendor' => $this->input->post('alamat_vendor'),
                'kode_vendor' => $this->input->post('kode_vendor'),
                'tanggal_bergabung' => $this->input->post('tanggal_bergabung'),
                // ...
            );

            $up = $this->vm->updateVendor($vendor_id, $data);

            $this->vm->updateEmailUser($vendor_id, $email);

            if ($up > 0) {
                $this->session->set_flashdata('success', 'Edit Vendor User created successfully');
            } else {
                $this->session->set_flashdata('error', ' Edit Vendor User creation failed');
            }

            // Redirect ke halaman sukses atau halaman lain yang sesuai
            redirect('vendor_');
        }
    }


    function addNewVendor()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('nama_vendor', 'Nama', 'trim|required|max_length[256]');
            $this->form_validation->set_rules('alamat_vendor', 'Alamat', 'trim|required|max_length[1024]');

            if ($this->form_validation->run() == FALSE) {
                $this->add();
            } else {
                $nama_vendor = $this->security->xss_clean($this->input->post('nama_vendor'));
                $alamat_vendor = $this->security->xss_clean($this->input->post('alamat_vendor'));
                $kode_vendor = $this->security->xss_clean($this->input->post('kode_vendor'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $tanggal_bergabung = $this->security->xss_clean($this->input->post('tanggal_bergabung'));

                $vendorInfo = array('nama_vendor' => $nama_vendor, 'alamat_vendor' => $alamat_vendor, 'kode_vendor' => $kode_vendor, 'tanggal_bergabung' => $tanggal_bergabung);

                $vendor_id = $this->vm->addNewVendor($vendorInfo);

                $userInfo = array(
                    'email' => $email, 'password' => getHashedPassword("123456"), 'roleId' => 2,
                    'name' => $nama_vendor, 'mobile' => "0000000000", 'isAdmin' => 2,
                    'createdBy' => $this->vendorId, 'vendorId' => $vendor_id, 'createdDtm' => date('Y-m-d H:i:s')
                );

                $this->load->model('user_model');
                $this->user_model->addNewUser($userInfo);

                if ($vendor_id > 0) {
                    $this->session->set_flashdata('success', 'New Vendor User created successfully');
                } else {
                    $this->session->set_flashdata('error', ' Vendor User creation failed');
                }

                redirect('vendor_/vendorListing');
            }
        }
    }
}

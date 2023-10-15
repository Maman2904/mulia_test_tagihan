<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Tagihan extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Task_model', 'tm');
        $this->load->model('Tagihan_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Tagihan';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('tagihan/tagihanListing');
    }

    function encrypt($file)
    {
        // Kunci enkripsi (disimpan dengan aman)
        $encryptionKey = "SecretKey123";

        // Menghasilkan IV yang acak dengan panjang 16 byte
        $iv = openssl_random_pseudo_bytes(16);

        // Enkripsi file
        $fileContent = file_get_contents($file);
        $encryptedFileContent = openssl_encrypt($fileContent, 'aes-256-cbc', $encryptionKey, 0, $iv);

        // Simpan IV dan file terenkripsi di direktori "uploads"
        $ivFilePath = 'assets/enc/iv.bin';
        $encryptedFilePath = 'assets/enc/encrypted_file.enc';

        file_put_contents($ivFilePath, $iv);
        file_put_contents($encryptedFilePath, $encryptedFileContent);

        return [$ivFilePath, $encryptedFilePath];
    }

    function decrypt($ivFilePath, $encryptedFilePath)
    {

        $encryptionKey = "SecretKey123";

        // Baca IV dari file
        $iv = file_get_contents($ivFilePath);

        // Baca isi file terenkripsi
        $encryptedFileContent = file_get_contents($encryptedFilePath);

        // Dekripsi file dengan IV yang sesuai
        $decryptedFileContent = openssl_decrypt($encryptedFileContent, 'aes-256-cbc', $encryptionKey, 0, $iv);

        return $decryptedFileContent;
    }

    public function exportToExcel()
    {
        // Hanya izinkan pengguna dengan hak akses admin
        // Load library atau alat yang diperlukan, misalnya PHPExcel atau CSVWriter
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        $sheet->setCellValue('A1', "DataTagihan Vendor"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', "No");
        $sheet->setCellValue('B3', "Nama Vendor"); // Set kolom A3 dengan tulisan "NO"
        $sheet->setCellValue('C3', "Nomor Tagihan"); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('D3', "Tanggal Tagihan"); // Set kolom C3 dengan tulisan "NAMA"
        $sheet->setCellValue('E3', "Total Tagihan"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $sheet->setCellValue('F3', "File Lapiran");
        $sheet->setCellValue('G3', "Item");
        $sheet->setCellValue('H3', "Besar Item");

        $sheet->getStyle('A3')->applyFromArray($style_col);
        $sheet->getStyle('B3')->applyFromArray($style_col);
        $sheet->getStyle('C3')->applyFromArray($style_col);
        $sheet->getStyle('D3')->applyFromArray($style_col);
        $sheet->getStyle('E3')->applyFromArray($style_col);
        $sheet->getStyle('F3')->applyFromArray($style_col);
        $sheet->getStyle('G3')->applyFromArray($style_col);
        $sheet->getStyle('H3')->applyFromArray($style_col);
        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $tagihan = $this->tm->getDataToExport();
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($tagihan as $data) { // Lakukan looping pada variabel siswa
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data->vendor_name);
            $sheet->setCellValue('C' . $numrow, $data->nomor_tagihan);
            $sheet->setCellValue('D' . $numrow, $data->tanggal_tagihan);
            $sheet->setCellValue('E' . $numrow, $data->total_tagihan);
            $sheet->setCellValue('F' . $numrow, $data->file_lampiran);
            $sheet->setCellValue('G' . $numrow, $data->item);
            $sheet->setCellValue('H' . $numrow, $data->besar_item);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('H' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }
        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(30); // Set width kolom C
        $sheet->getColumnDimension('G')->setWidth(35); // Set width kolom D
        $sheet->getColumnDimension('H')->setWidth(40); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Laporan Data Tagihan");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Tagihan.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }



    function tagihanListing()
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

            $isAdmin = $this->isAdmin();

            $vendor_id = $this->tm->getVendorIdByUserId($this->vendorId);
            $name = $this->tm->getNameVendorByVendorId($vendor_id);

            $count = $this->tm->tagihanListingCount($searchText, $vendor_id, $isAdmin);

            $returns = $this->paginationCompress("tagihanListing/", $count, 10);


            $data['records'] = $this->tm->tagihanListing($searchText, $returns["page"], $returns["segment"], $vendor_id, $isAdmin);
            $data["isAdmin"] = $isAdmin;
            $data["name"] = $name;

            $this->global['pageTitle'] = 'Mulia : Tagihan';

            // $vendor_id = $this->session->userdata('vendor_id'); // Gantilah ini sesuai dengan cara Anda menyimpan ID vendor saat login
            // $data['draft_tagihan'] = $this->tm->getDraftTagihanByVendor($vendor_id);
            // $data['konfirmasi_tagihan'] = $this->tm->getKonfirmasiTagihanByVendor($vendor_id);

            // Tampilkan daftar tagihan ke view
            $this->loadViews("tagihan/list", $this->global, $data, NULL);
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

            $this->global['pageTitle'] = 'Mulia : Add New Tagihan';

            $this->loadViews("tagihan/add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to add new user to the system
     */
    // public function addNewTagihan()
    // {
    //     if (!$this->hasCreateAccess()) {
    //         $this->loadThis();
    //     } else {
    //         // Validasi form
    //         $this->load->library('form_validation');
    //         $this->form_validation->set_rules('nomor_tagihan', 'Nomor Tagihan', 'required');
    //         $this->form_validation->set_rules('tanggal_tagihan', 'Tanggal Tagihan', 'required');
    //         $this->form_validation->set_rules('total_tagihan', 'Total Tagihan', 'required|numeric');

    //         // Cek apakah validasi sukses
    //         if ($this->form_validation->run() === FALSE) {
    //             // Jika validasi gagal, tampilkan kembali halaman form dengan pesan error
    //             $this->load->view('tagihan/add');
    //         } else {

    //             $file_info = $_FILES['file_lampiran'];
    //             $file_name = $file_info['name'];
    //             $temp_file = $file_info['tmp_name'];

    //             $enc = $this->encrypt($temp_file);

    //             $decrypt = $this->decrypt($enc[0], $enc[1]);

    //             // var_dump($enc);

    //             var_dump($decrypt);
    //             exit;

    //             // Tentukan folder penyimpanan untuk file PDF
    //             $upload_directory = 'assets/uploads/';
    //             $destination = $upload_directory . $file_name;


    //             // Jika validasi sukses, simpan data ke tabel 'tagihan'
    //             $vendor_id = $this->tm->getVendorIdByUserId($this->vendorId);
    //             // var_dump($vendor_id);
    //             $data_tagihan = array(
    //                 'nomor_tagihan' => $this->input->post('nomor_tagihan'),
    //                 'tanggal_tagihan' => $this->input->post('tanggal_tagihan'),
    //                 'total_tagihan' => $this->input->post('total_tagihan'),
    //                 // 'file_lampiran' => $file_name,
    //                 'vendor_id' => $vendor_id
    //             );
    //             if (move_uploaded_file($temp_file, $destination)) {
    //                 // File berhasil diunggah, simpan nama file ke dalam database
    //                 $data_tagihan['file_lampiran'] = $file_name;
    //             } else {
    //                 echo "gagal upload";
    //             }

    //             $items = $this->input->post('item');
    //             $besar_items = $this->input->post('besar_item');

    //             $tagihan_id = $this->tm->saveTagihan($data_tagihan);

    //             // Simpan data item yang ditagih ke dalam tabel 'item_tagihan' menggunakan model
    //             $this->tm->saveItems($tagihan_id, $items, $besar_items);

    //             if ($tagihan_id > 0) {
    //                 $this->session->set_flashdata('success', 'New Tagihan created successfully');
    //             } else {
    //                 $this->session->set_flashdata('error', ' Tagihan creation failed');
    //             }
    //             redirect('tagihan');
    //         }
    //     }
    // }

    public function addNewTagihan()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            // Validasi form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('nomor_tagihan', 'Nomor Tagihan', 'required');
            $this->form_validation->set_rules('tanggal_tagihan', 'Tanggal Tagihan', 'required');
            $this->form_validation->set_rules('total_tagihan', 'Total Tagihan', 'required|numeric');

            // Cek apakah validasi sukses
            if ($this->form_validation->run() === FALSE) {
                // Jika validasi gagal, tampilkan kembali halaman form dengan pesan error
                $this->load->view('tagihan/add');
            } else {
                // Lakukan enkripsi file
                $file_info = $_FILES['file_lampiran'];
                $file_name = $file_info['name'];
                $temp_file = $file_info['tmp_name'];

                list($ivFilePath, $encryptedFilePat, $file_name_encrypt) = $this->encryptAndSave($temp_file);


                // Jika validasi sukses, simpan data ke tabel 'tagihan'
                $vendor_id = $this->tm->getVendorIdByUserId($this->vendorId);

                $data_tagihan = array(
                    'nomor_tagihan' => $this->input->post('nomor_tagihan'),
                    'tanggal_tagihan' => $this->input->post('tanggal_tagihan'),
                    'total_tagihan' => $this->input->post('total_tagihan'),
                    'file_lampiran' => $file_name, // Simpan path file terenkripsi
                    'vendor_id' => $vendor_id,
                    'file_name_encrypt' => $file_name_encrypt
                );

                $items = $this->input->post('item');
                $besar_items = $this->input->post('besar_item');

                $tagihan_id = $this->tm->saveTagihan($data_tagihan);

                // Simpan data item yang ditagih ke dalam tabel 'item_tagihan' menggunakan model
                $this->tm->saveItems($tagihan_id, $items, $besar_items);

                if ($tagihan_id > 0) {
                    $this->session->set_flashdata('success', 'New Tagihan created successfully');
                } else {
                    $this->session->set_flashdata('error', ' Tagihan creation failed');
                }
                redirect('tagihan');
            }
        }
    }

    // Fungsi untuk enkripsi dan menyimpan file
    function encryptAndSave($file)
    {
        // Kunci enkripsi (disimpan dengan aman)
        $encryptionKey = "SecretKey123";

        // Menghasilkan IV yang acak dengan panjang 16 byte
        $iv = openssl_random_pseudo_bytes(16);

        // Enkripsi file
        $fileContent = file_get_contents($file);
        $encryptedFileContent = openssl_encrypt($fileContent, 'aes-256-cbc', $encryptionKey, 0, $iv);

        $file_name = uniqid() . ".enc";

        // Simpan IV dan file terenkripsi di direktori "uploads"
        $ivFilePath = 'assets/enc/iv.bin';
        $encryptedFilePath = 'assets/enc/' . $file_name;

        file_put_contents($ivFilePath, $iv);
        file_put_contents($encryptedFilePath, $encryptedFileContent);

        return [$ivFilePath, $encryptedFilePath, $file_name];
    }


    public function konfirmasiTagihan($tagihan_id)
    {
        // Cek apakah tagihan dengan ID tertentu milik vendor yang sesuai
        $vendor_id = $this->tm->getVendorIdByUserId($this->vendorId); // Gantilah ini sesuai dengan cara Anda menyimpan ID vendor saat login
        $tagihan = $this->tm->getTagihanById($tagihan_id);

        if ($tagihan && $tagihan->vendor_id == $vendor_id && $tagihan->status == 'Draft') {
            $this->tm->konfirmasiTagihan($tagihan_id);

            redirect('tagihan');
        } else {
            $this->session->set_flashdata('error', 'Tidak dapat mengkonfirmasi tagihan.');
            redirect('tagihan');
        }
    }


    // function edit($tagihan_id = NULL)
    // {
    //     if (!$this->hasUpdateAccess()) {
    //         $this->loadThis();
    //     } else {
    //         if ($tagihan_id == null) {
    //             redirect('tagihan/tagihanListing');
    //         }

    //         $this->load->helper('form');
    //         $this->load->library('form_validation');

    //         $data['tagihan'] = $this->tm->getTagihanById($tagihan_id);
    //         // var_dump($data);
    //         $data['items'] = $this->tm->getItemsByTagihanId($tagihan_id);


    //         $this->global['pageTitle'] = 'Mulia : Edit Tagihan';

    //         $this->loadViews("tagihan/edit", $this->global, $data, NULL);
    //     }
    // }

    function edit($tagihan_id = NULL)
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            if ($tagihan_id == null) {
                redirect('tagihan/tagihanListing');
            }

            $this->load->helper('form');
            $this->load->library('form_validation');

            $data['tagihan'] = $this->tm->getTagihanById($tagihan_id);
            $data['items'] = $this->tm->getItemsByTagihanId($tagihan_id);

            // Dekripsi file dan simpan ke direktori sementara
            $decryptedFilePath = $this->decrypt_($data['tagihan']->file_name_encrypt);

            // Mengganti file_lampiran dengan path file yang didekripsi
            $data['tagihan']->file_lampiran = $decryptedFilePath;
            $data['decrypt'] = $decryptedFilePath;



            $this->global['pageTitle'] = 'Mulia : Edit Tagihan';

            $this->loadViews("tagihan/edit", $this->global, $data, NULL);
        }
    }

    // Fungsi untuk mendekripsi dan menyimpan file ke direktori sementara
    function decrypt_($encryptedFilePath)
    {
        $encryptedFilePath = 'assets/enc/' . $encryptedFilePath;
        // Baca file terenkripsi
        $encryptedFileContent = file_get_contents($encryptedFilePath);

        // Kunci enkripsi (disimpan dengan aman)
        $encryptionKey = "SecretKey123";

        // Membaca IV dari file
        $iv = file_get_contents('assets/enc/iv.bin');

        // Dekripsi file
        $decryptedFileContent = openssl_decrypt($encryptedFileContent, 'aes-256-cbc', $encryptionKey, 0, $iv);

        return $decryptedFileContent;
    }


    public function updateTagihan($tagihan_id)
    {
        // Validasi form edit jika diperlukan
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nomor_tagihan', 'Nomor Tagihan', 'required');
        $this->form_validation->set_rules('tanggal_tagihan', 'Tanggal Tagihan', 'required');
        $this->form_validation->set_rules('total_tagihan', 'Total Tagihan', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            // Jika validasi gagal, tampilkan kembali halaman edit dengan pesan error
            $this->load->view('tagihan/edit');
        } else {
            // Jika validasi sukses, simpan perubahan data tagihan di database

            // var_dump($_FILES);
            $file_info = $_FILES['file_lampiran'];
            $file_name = $file_info['name'];
            $temp_file = $file_info['tmp_name'];

            $existing_tagihan = $this->tm->getTagihanById($tagihan_id);

            $data = array(
                'nomor_tagihan' => $this->input->post('nomor_tagihan'),
                'tanggal_tagihan' => $this->input->post('tanggal_tagihan'),
                'total_tagihan' => $this->input->post('total_tagihan'),
                'status' => "konfirmasi",
                // 'file_lampiran' => $file_name,
                // ...
            );

            if (!empty($file_name)) {
                //     // Hapus file lampiran lama jika ada
                if (!empty($existing_tagihan->file_name_encrypt)) {
                    $file_to_delete = 'assets/enc/' . $existing_tagihan->file_name_encrypt;
                    if (file_exists($file_to_delete)) {
                        unlink($file_to_delete);
                    }
                }
                list($ivFilePath, $encryptedFilePat, $file_name_encrypt) = $this->encryptAndSave($temp_file);
                $data['file_lampiran'] = $file_name;
                $data['file_name_encrypt'] =  $file_name_encrypt;
                // if (move_uploaded_file($temp_file, $destination)) {
                //     // File berhasil diunggah, simpan nama file ke dalam database
                //     $data['file_lampiran'] = $file_name;
                // } else {
                //     echo "gagal upload";
                // }
            }



            $this->tm->updateTagihan($tagihan_id, $data);

            $items = $this->input->post('item');
            $besar_items = $this->input->post('besar_item');
            $item_ids = $this->input->post('item_id'); // Menyertakan item_id dalam formulir

            foreach ($items as $key => $item) {
                $data_item = array(
                    'item' => $item,
                    'besar_item' => $besar_items[$key],
                    'tagihan_id' => $tagihan_id
                );

                if (isset($item_ids[$key])) {
                    $item_id = $item_ids[$key];
                    $this->tm->updateItem($item_id, $data_item);
                } else {
                    $data_item['tagihan_id'] = $tagihan_id;
                    $this->tm->addItem($data_item);
                }
            }

            // Redirect ke halaman sukses atau halaman lain yang sesuai
            redirect('tagihan');
        }
    }
}

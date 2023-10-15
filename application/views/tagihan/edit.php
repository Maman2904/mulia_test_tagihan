<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle-o" aria-hidden="true"></i> Tagihan
            <small>Add / Edit</small>
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <?php echo form_open('tagihan/updateTagihan/' . $tagihan->tagihan_id, array('method' => 'post', 'enctype' => 'multipart/form-data')); ?>

                    <?php if ($tagihan->status == "Konfirmasi") : ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_tagihan">Nomor Tagihan</label>
                                        <input type="text" class="form-control" id="nomor_tagihan" name="nomor_tagihan" value="<?php echo $tagihan->nomor_tagihan; ?>" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_tagihan">Tanggal Tagihan</label>
                                        <input type="date" class="form-control" name="tanggal_tagihan" id="tanggal_tagihan" value="<?php echo $tagihan->tanggal_tagihan; ?>" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_tagihan">Total Tagihan</label>
                                        <input type="text" class="form-control" name="total_tagihan" id="total_tagihan" value="<?php echo $tagihan->total_tagihan; ?>" readonly />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <input type="file" class="form-control" name="file_lampiran" accept=".pdf" id="file_lampiran" readonly /> -->
                                        <!-- <span id="file-name-placeholder"></span> -->

                                        <label for="total_tagihan">Lampiran</label>

                                        <a href="data:application/pdf;base64,<?php echo base64_encode($decrypt); ?>" target="_blank">Lihat PDF</a>

                                    </div>
                                </div>

                                <div class=" col-md-6" id="items">
                                    <h3>Item yang Ditagih</h3>
                                    <?php foreach ($items as $item) : ?>
                                        <div class="form-group">
                                            <input type="hidden" value="<?php echo $item->item_id; ?>" name="item_id" id="item_id" />
                                            <label for="item">Item</label>
                                            <input type="text" class="form-control" name="item[]" value="<?php echo $item->item; ?>" readonly>
                                            <label for="besar_item">Besar Item</label>
                                            <input type="text" class="form-control" name="besar_item[]" value="<?php echo $item->besar_item; ?>" readonly>
                                        </div>
                                    <?php endforeach; ?>
                                </div>


                            </div>
                        </div>
                    <?php else : ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_tagihan">Nomor Tagihan</label>
                                        <input type="text" class="form-control" id="nomor_tagihan" name="nomor_tagihan" value="<?php echo $tagihan->nomor_tagihan; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_tagihan">Tanggal Tagihan</label>
                                        <input type="date" class="form-control" name="tanggal_tagihan" id="tanggal_tagihan" value="<?php echo $tagihan->tanggal_tagihan; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_tagihan">Total Tagihan</label>
                                        <input type="text" class="form-control" name="total_tagihan" id="total_tagihan" value="<?php echo $tagihan->total_tagihan; ?>" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="file_lampiran" accept=".pdf" id="file_lampiran" />
                                        <span id="file-name-placeholder"></span>
                                        <a href="data:application/pdf;base64,<?php echo base64_encode($decrypt); ?>" target="framename">Lihat PDF</a>
                                        <!-- <?php
                                                if (!empty($tagihan->file_lampiran)) {
                                                    echo '<iframe src="' . base_url('assets/uploads/' . $decrypt . '.pdf') . '" width="30%" height="100"></iframe>';
                                                }
                                                ?> -->
                                    </div>
                                </div>

                                <div class=" col-md-6" id="items">
                                    <h3>Item yang Ditagih</h3>
                                    <button type="button" id="tambah_item">Tambah Item</button>
                                    <?php foreach ($items as $item) : ?>
                                        <div class="form-group">
                                            <input type="hidden" value="<?php echo $item->item_id; ?>" name="item_id" id="item_id" />
                                            <label for="item">Item</label>
                                            <input type="text" class="form-control" name="item[]" value="<?php echo $item->item; ?>">
                                            <label for="besar_item">Besar Item</label>
                                            <input type="text" class="form-control" name="besar_item[]" value="<?php echo $item->besar_item; ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>


                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Konfirmasi" />
                        </div>
                    <?php endif; ?>

                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) {
                ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Script JavaScript untuk menambahkan field item dan besar item dinamis
            document.getElementById('tambah_item').addEventListener('click', function() {
                const itemsDiv = document.getElementById('items');
                const newItemDiv = document.createElement('div');
                newItemDiv.innerHTML = `
                <label for="item">Item:</label>
                <input type="text" class="form-control" name="item[]" class="item"><br>

                <label for="besar_item">Besar Item:</label>
                <input type="text" class="form-control" name="besar_item[]" class="besar_item"><br>
            `;
                itemsDiv.appendChild(newItemDiv);
            });

            // // Dapatkan elemen input berkas dan elemen untuk menampilkan nama file
            // const fileInput = document.getElementById('file_lampiran');
            // const fileNamePlaceholder = document.getElementById('file-name-placeholder');

            // // Saat nilai input berkas berubah, perbarui tampilan nama file
            // fileInput.addEventListener('change', function() {
            //     if (fileInput.files.length > 0) {
            //         fileNamePlaceholder.textContent = 'File terpilih: ' + fileInput.files[0].name;
            //     } else {
            //         fileNamePlaceholder.textContent = ''; // Kosongkan teks jika tidak ada file terpilih
            //     }
            // });
        </script>
    </section>
</div>
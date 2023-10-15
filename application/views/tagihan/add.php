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
                        <h3 class="box-title">Enter Task Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addTagihan" action="<?php echo base_url() ?>tagihan/addNewTagihan" method="post" enctype="multipart/form-data" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_tagihan">Nomor Tagihan</label>
                                        <input type="text" class="form-control required" id="nomor_tagihan" name="nomor_tagihan" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_tagihan">Tanggal Tagihan</label>
                                        <input type="date" class="form-control required" name="tanggal_tagihan" id="tanggal_tagihan" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_tagihan">Total Tagihan</label>
                                        <input type="text" class="form-control required" name="total_tagihan" id="total_tagihan" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_lampiran">Lampiran</label>
                                        <input type="file" class="form-control" name="file_lampiran" accept=".pdf">
                                    </div>
                                </div>

                                <div class="col-md-6" id="items">
                                    <h3>Item yang Ditagih</h3>
                                    <button type="button" id="tambah_item">Tambah Item</button>
                                    <div class="form-group">
                                        <label for="item">Item</label>
                                        <input type="text" class="form-control" name="item[]" class="item">

                                        <label for="besar_item">Besar Item</label>
                                        <input type="text" class="form-control" name="besar_item[]" class="besar_item">
                                    </div>
                                </div>


                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                        </div>
                    </form>
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
        </script>
    </section>

</div>
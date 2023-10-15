<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle-o" aria-hidden="true"></i> Tagihan
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <?php if (!$isAdmin) : ?>
                    <div class="form-group">
                        <a class="btn btn-primary" href="<?php echo base_url(); ?>tagihan/add"><i class="fa fa-plus"></i> Add New Tagihan</a>
                    </div>
                <?php else : ?>
                    <div class="form-group">
                        <a class="btn btn-primary" href="<?php echo base_url(); ?>tagihan/exportToExcel"><i class="fa fa-excel"></i> Export To Excel</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Tagihan List</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>tagihan/tagihanListing" method="POST" id="searchList">
                                <div class="input-group">
                                    <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>nama Vendor</th>
                                <th>Nomor Tagihan</th>
                                <th>Tanggal Tagihan</th>
                                <th>Total Tagihan</th>
                                <th>File Lampiran</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            <?php
                            if (!empty($records)) {
                                foreach ($records as $record) {
                            ?>
                                    <tr>
                                        <?php if (!$isAdmin) : ?>
                                            <td><?php echo $name ?></td>
                                        <?php else : ?>
                                            <td><?php echo $record->name ?></td>
                                        <?php endif; ?>
                                        <td><?php echo $record->nomor_tagihan ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->tanggal_tagihan)) ?></td>
                                        <td><?php echo $record->total_tagihan ?></td>
                                        <td><?php echo $record->file_lampiran ?></td>
                                        <td><?php echo $record->status ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url() . 'tagihan/edit/' . $record->tagihan_id; ?>" title="Preview"><i class="fa fa-search"></i></a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </table>

                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('ul.pagination li a').click(function(e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "tagihan/tagihanListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
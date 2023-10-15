<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- General form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Date Report</h3>
                    </div><!-- /.box-header -->
                    <!-- Form start -->
                    <?php $this->load->helper("form"); ?>
                    <!-- <form role="form" id="filterForm" action="" method="post"> -->
                    <form role="form" id="filterForm" action="<?php echo base_url() ?>task/chart" method="post" role="form">
                        <div class=" box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_awal">Tanggal Awal</label>
                                        <input type="date" class="form-control required" name="tanggal_awal" id="tanggal_awal" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_akhir">Tanggal Akhir</label>
                                        <input type="date" class="form-control required" name="tanggal_akhir" id="tanggal_akhir" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
</div>

<!-- <script>
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const tanggal_awal = document.getElementById('tanggal_awal').value;
        const tanggal_akhir = document.getElementById('tanggal_akhir').value;

        console.log("===>", tanggal_awal)
        console.log(tanggal_akhir)

        const data = {
            tanggal_awal: tanggal_awal,
            tanggal_akhir: tanggal_akhir
        };

        fetch('api/data', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Lakukan sesuatu dengan respons dari server
                console.log(data);
                updateChart(data)
            })
            .catch(error => {
                console.error('Gagal mengirimkan permintaan:', error);
            });
    });


    function updateChart(data) {
        const ctx = document.getElementById('myChart').getContext("2d");
        if (myChart) {
            myChart.destroy();
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script> -->
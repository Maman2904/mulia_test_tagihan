<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
    const ctx = document.getElementById('myChart');
    const nama_vendor = <?= $nama_vendor ?>;
    const total_tagihan = <?= $total_tagihan ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: nama_vendor,
            datasets: [{
                label: 'Vendor',
                data: total_tagihan,
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
</script>
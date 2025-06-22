<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahun transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <?php include("navbar.php"); ?>
    <div id="wrapper">
        <!-- <h1>Data Transaksi</h1>
        <div id="chart"></div> -->
        <h1>Pilih tahun transaksi</h1>
        <div id="btn-tahun" class="btn-daftar">

        <?php foreach($rekap2 as $rekap): ?>

            <button class="btn btn-outline-primary w-100 mt-1" onclick="window.location.href='index.php?c=DonasiController&m=getRekapByYear&tahun=<?= $rekap['tahun'] ?>'">
                <?= $rekap['tahun'] ?>
            </button>
        <!-- </a> -->
        <?php endforeach; ?>

            <!-- <a href="hal-bulantransaksi.php"><button type="button" class="btn btn-outline-secondary w-100">2025</button></a>
            <a href="hal-bulantransaksi.php"><button type="button" class="btn btn-outline-secondary w-100">2024</button></a> -->
        </div>
            <!-- <script>
            var options = {
            series: [{
                name: 'Total Donasi',
                data: [35000000, 12500000]
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            colors: ['#4A90E2'],
            plotOptions: {
                bar: {
                borderRadius: 10,
                dataLabels: {
                    position: 'top',
                },
                columnWidth: '50em'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                return "Rp " + val;
                },
                offsetY: -20,
                style: {
                fontSize: '12px',
                colors: ["#304758"]
                }
            },
            xaxis: {
                categories: ["2024", "2025"],
                position: 'bottom',
                axisBorder: {
                show: true
                },
                axisTicks: {
                show: true
                },
                crosshairs: {
                fill: {
                    type: 'gradient',
                    gradient: {
                    colorFrom: '#D8E3F0',
                    colorTo: '#BED1E6',
                    stops: [0, 100],
                    opacityFrom: 0.4,
                    opacityTo: 0.5,
                    }
                }
                },
                tooltip: {
                enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                show: false
                },
                axisTicks: {
                show: false,
                },
                labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        </script> -->
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>
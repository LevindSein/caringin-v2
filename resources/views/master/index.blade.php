@extends('layout.master')

@section('title')
<title>Dashboard | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
@endsection

@section('button')
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4">
        <!-- Members list group card -->
        <div class="card shadow mb-4">
            <!-- Card header -->
            <div class="card-header">
                <!-- Title -->
                <h5 class="h3 mb-0">Realisasi Tahun {{$thn}}</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="pieRealisasi"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i>
                        Listrik
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i>
                        Air Bersih
                    </span>
                </div>
                
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i>
                        Keamanan & IPK
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i>
                        Kebersihan
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <!-- Members list group card -->
        <div class="card shadow mb-4">
            <!-- Card header -->
            <div class="card-header">
                <!-- Title -->
                <h5 class="h3 mb-0">Selisih Tahun {{$thn}}</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="pieSelisih"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i>
                        Listrik
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i>
                        Air Bersih
                    </span>
                </div>
                
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i>
                        Keamanan & IPK
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i>
                        Kebersihan
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <!-- Members list group card -->
        <div class="card shadow mb-4">
            <!-- Card header -->
            <div class="card-header">
                <!-- Title -->
                <h5 class="h3 mb-0">Pengguna Fasilitas {{$thn}}</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="piePengguna"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i>
                        Listrik
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i>
                        Air Bersih
                    </span>
                </div>
                
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i>
                        Keamanan & IPK
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i>
                        Kebersihan
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row d-none d-lg-block">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="h3 mb-0">Rincian Tagihan Tahun {{$thn}}</h5>
            </div>
            <div class="card-body">
                <div class="chart-area-new">
                    <canvas id="rincianChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row d-none d-lg-block">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="h3 mb-0">Tagihan Tahun {{$thn}}</h5>
            </div>
            <div class="card-body">
                <div class="chart-area-new">
                    <canvas id="pendapatanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row d-none d-lg-block">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="h3 mb-0">Akumulasi Tagihan Tahun {{$thn}}</h5>
            </div>
            <div class="card-body">
                <div class="chart-area-new">
                    <canvas id="akumulasiChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="h3 mb-0">Status Tempat Usaha {{number_format($pengguna)}} Unit</h5>
            </div>
            <div class="card-body">
                <?php if($pengguna == 0){$pengguna = 1;} ?>
                <h8 class="font-weight-bold">Aktif
                    <span class="float-right">
                    {{number_format($penggunaAktif)}} Unit - {{round(($penggunaAktif / $pengguna) * 100)}}%
                    </span>
                </h8>
                <div class="progress mb-4">
                    <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style="width: {{($penggunaAktif / $pengguna) * 100}}%"
                        aria-valuenow="20"
                        aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <h8 class="font-weight-bold">Non-Aktif
                    <span class="float-right">
                    {{number_format($penggunaNonAktif)}} Unit - {{round(($penggunaNonAktif / $pengguna) * 100)}}%</span>
                </h8>
                <div class="progress mb-4">
                    <div
                        class="progress-bar bg-danger"
                        role="progressbar"
                        style="width: {{($penggunaNonAktif / $pengguna) * 100}}%"
                        aria-valuenow="20"
                        aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="h3 mb-0">Tentang Kami</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img
                        class="img-fluid px-3 px-sm-4 mt-3 mb-4"
                        style="width: 21rem;"
                        src="{{asset('img/undraw_posting_photo.svg')}}"
                        alt="Tentang Kami BP3C"></div>
                    <p>Aplikasi dikelola oleh PT. Pengelola Pusat Perdagangan Caringin.</p>
                </div>
            </div>
        </div>
    </div>
    
    @include('home.footer')
</div>
@endsection

@section('modal')
@endsection

@section('js')
<script>
    var rincianCanvas = document.getElementById("rincianChart");
    Chart.defaults.global.defaultFontFamily = 'Nunito',
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",' +
            'Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';
    Chart.defaults.global.defaultFontSize = 10;

    var data = {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Agt",
            "Sep",
            "Okt",
            "Nov",
            "Des"
        ],
        datasets: [
            {
                label: "Listrik",
                backgroundColor: "#fb6340",
                data: [
                    {{$listrikJan}},
                    {{$listrikFeb}},
                    {{$listrikMar}},
                    {{$listrikApr}},
                    {{$listrikMei}},
                    {{$listrikJun}},
                    {{$listrikJul}},
                    {{$listrikAgu}},
                    {{$listrikSep}},
                    {{$listrikOkt}},
                    {{$listrikNov}},
                    {{$listrikDes}}
                ]
            }, {
                label: "Air Bersih",
                backgroundColor: "#4e73df",
                data: [
                    {{$airJan}},
                    {{$airFeb}},
                    {{$airMar}},
                    {{$airApr}},
                    {{$airMei}},
                    {{$airJun}},
                    {{$airJul}},
                    {{$airAgu}},
                    {{$airSep}},
                    {{$airOkt}},
                    {{$airNov}},
                    {{$airDes}}
                ]
            }, {
                label: "Keamanan & IPK",
                backgroundColor: "#36b9cc",
                data: [
                    {{$keamananipkJan}},
                    {{$keamananipkFeb}},
                    {{$keamananipkMar}},
                    {{$keamananipkApr}},
                    {{$keamananipkMei}},
                    {{$keamananipkJun}},
                    {{$keamananipkJul}},
                    {{$keamananipkAgu}},
                    {{$keamananipkSep}},
                    {{$keamananipkOkt}},
                    {{$keamananipkNov}},
                    {{$keamananipkDes}}
                ]
            }, {
                label: "Kebersihan",
                backgroundColor: "#1cc88a",
                data: [
                    {{$kebersihanJan}},
                    {{$kebersihanFeb}},
                    {{$kebersihanMar}},
                    {{$kebersihanApr}},
                    {{$kebersihanMei}},
                    {{$kebersihanJun}},
                    {{$kebersihanJul}},
                    {{$kebersihanAgu}},
                    {{$kebersihanSep}},
                    {{$kebersihanOkt}},
                    {{$kebersihanNov}},
                    {{$kebersihanDes}}
                ]
            }
        ]
    };
    var rincianChart = new Chart(rincianCanvas, {
        type: 'bar',
        data: data,
        options: {
            tooltips: {
                enabled: true,
                callbacks: {
                    // this callback is used to create the tooltip label
                    label: function (tooltipItem, data) {
                        // get the data label and data value to display convert the data value to local
                        // string so it uses a comma seperated number
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ],
                        // [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label need to clone because we are
                            // changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                }
            },
            barValueSpacing: 20,
            scales: {
                xAxes: [
                    {
                        barPercentage: 1,
                        categoryPercentage: 0.6
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            min: 0
                        }
                    }
                ]
            }
        }
    });

    var pendapatanCanvas = document.getElementById("pendapatanChart");
    Chart.defaults.global.defaultFontFamily = 'Nunito',
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",' +
            'Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';
    Chart.defaults.global.defaultFontSize = 10;

    var data = {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Agt",
            "Sep",
            "Okt",
            "Nov",
            "Des"
        ],
        datasets: [
            {
                label: "Tagihan",
                backgroundColor: "#4e73df",
                data: [
                    {{$tagihanJan}},
                    {{$tagihanFeb}},
                    {{$tagihanMar}},
                    {{$tagihanApr}},
                    {{$tagihanMei}},
                    {{$tagihanJun}},
                    {{$tagihanJul}},
                    {{$tagihanAgu}},
                    {{$tagihanSep}},
                    {{$tagihanOkt}},
                    {{$tagihanNov}},
                    {{$tagihanDes}}
                ]
            }, {
                label: "Realisasi",
                backgroundColor: "#1cc88a",
                data: [
                    {{$realisasiJan}},
                    {{$realisasiFeb}},
                    {{$realisasiMar}},
                    {{$realisasiApr}},
                    {{$realisasiMei}},
                    {{$realisasiJun}},
                    {{$realisasiJul}},
                    {{$realisasiAgu}},
                    {{$realisasiSep}},
                    {{$realisasiOkt}},
                    {{$realisasiNov}},
                    {{$realisasiDes}}
                ]
            }, {
                label: "Selisih",
                backgroundColor: "#e74a3b",
                data: [
                    {{$selisihJan}},
                    {{$selisihFeb}},
                    {{$selisihMar}},
                    {{$selisihApr}},
                    {{$selisihMei}},
                    {{$selisihJun}},
                    {{$selisihJul}},
                    {{$selisihAgu}},
                    {{$selisihSep}},
                    {{$selisihOkt}},
                    {{$selisihNov}},
                    {{$selisihDes}}
                ]
            }
        ]
    };
    var pendapatanChart = new Chart(pendapatanCanvas, {
        type: 'bar',
        data: data,
        options: {
            tooltips: {
                enabled: true,
                callbacks: {
                    // this callback is used to create the tooltip label
                    label: function (tooltipItem, data) {
                        // get the data label and data value to display convert the data value to local
                        // string so it uses a comma seperated number
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ],
                        // [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label need to clone because we are
                            // changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                }
            },
            barValueSpacing: 20,
            scales: {
                xAxes: [
                    {
                        barPercentage: 1,
                        categoryPercentage: 0.6
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            min: 0
                        }
                    }
                ]
            }
        }
    });

    var akumulasiCanvas = document.getElementById("akumulasiChart");
    Chart.defaults.global.defaultFontFamily = 'Nunito',
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",' +
            'Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';
    Chart.defaults.global.defaultFontSize = 10;

    var data = {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Agt",
            "Sep",
            "Okt",
            "Nov",
            "Des"
        ],
        datasets: [
            {
                label: "Tagihan",
                backgroundColor: "#4e73df",
                data: [
                    {{$tagihanJanAku}},
                    {{$tagihanFebAku}},
                    {{$tagihanMarAku}},
                    {{$tagihanAprAku}},
                    {{$tagihanMeiAku}},
                    {{$tagihanJunAku}},
                    {{$tagihanJulAku}},
                    {{$tagihanAguAku}},
                    {{$tagihanSepAku}},
                    {{$tagihanOktAku}},
                    {{$tagihanNovAku}},
                    {{$tagihanDesAku}}
                ]
            }, {
                label: "Realisasi",
                backgroundColor: "#1cc88a",
                data: [
                    {{$realisasiJanAku}},
                    {{$realisasiFebAku}},
                    {{$realisasiMarAku}},
                    {{$realisasiAprAku}},
                    {{$realisasiMeiAku}},
                    {{$realisasiJunAku}},
                    {{$realisasiJulAku}},
                    {{$realisasiAguAku}},
                    {{$realisasiSepAku}},
                    {{$realisasiOktAku}},
                    {{$realisasiNovAku}},
                    {{$realisasiDesAku}}
                ]
            }, {
                label: "Selisih",
                backgroundColor: "#e74a3b",
                data: [
                    {{$selisihJanAku}},
                    {{$selisihFebAku}},
                    {{$selisihMarAku}},
                    {{$selisihAprAku}},
                    {{$selisihMeiAku}},
                    {{$selisihJunAku}},
                    {{$selisihJulAku}},
                    {{$selisihAguAku}},
                    {{$selisihSepAku}},
                    {{$selisihOktAku}},
                    {{$selisihNovAku}},
                    {{$selisihDesAku}}
                ]
            }
        ]
    };
    var akumulasiChart = new Chart(akumulasiCanvas, {
        type: 'bar',
        data: data,
        options: {
            tooltips: {
                enabled: true,
                callbacks: {
                    // this callback is used to create the tooltip label
                    label: function (tooltipItem, data) {
                        // get the data label and data value to display convert the data value to local
                        // string so it uses a comma seperated number
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ],
                        // [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label need to clone because we are
                            // changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                }
            },
            barValueSpacing: 20,
            scales: {
                xAxes: [
                    {
                        barPercentage: 1,
                        categoryPercentage: 0.6
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            min: 0
                        }
                    }
                ]
            }
        }
    });
</script>

<script>
    // Set new default font family and font color to mimic Bootstrap's default
    // styling
    Chart.defaults.global.defaultFontFamily = 'Nunito',
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",' +
        'Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart Example
    var ctx = document.getElementById("pieRealisasi");
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                "Air Bersih", "Kebersihan", "Keamanan & IPK", "Listrik"
            ],
            datasets: [
                {
                    data: [
                        {{$reaAirBersih}}, {{$reaKebersihan}}, {{$reaKeamananIpk}}, {{$reaListrik}}
                    ],
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#fb6340'
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9', '#17a673', '#2c9faf', '#ea3005'
                    ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                enabled: true,
                callbacks: {
                    // this callback is used to create the tooltip label
                    label: function (tooltipItem, data) {
                        // get the data label and data value to display convert the data value to local
                        // string so it uses a comma seperated number
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ' : Rp ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ],
                        // [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label need to clone because we are
                            // changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                },
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            cutoutPercentage: 50,
            rotation: 10
        }
    });

    // Set new default font family and font color to mimic Bootstrap's default
    // styling
    Chart.defaults.global.defaultFontFamily = 'Nunito',
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",' +
        'Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart Example
    var ctx = document.getElementById("pieSelisih");
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                "Air Bersih", "Kebersihan", "Keamanan & IPK", "Listrik"
            ],
            datasets: [
                {
                    data: [
                        {{$selAirBersih}}, {{$selKebersihan}}, {{$selKeamananIpk}}, {{$selListrik}}
                    ],
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#fb6340'
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9', '#17a673', '#2c9faf', '#ea3005'
                    ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                enabled: true,
                callbacks: {
                    // this callback is used to create the tooltip label
                    label: function (tooltipItem, data) {
                        // get the data label and data value to display convert the data value to local
                        // string so it uses a comma seperated number
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ' : Rp ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ],
                        // [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label need to clone because we are
                            // changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                },
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            cutoutPercentage: 50,
            rotation: 10
        }
    });

    // Set new default font family and font color to mimic Bootstrap's default
    // styling
    Chart.defaults.global.defaultFontFamily = 'Nunito',
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",' +
        'Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart Example
    var ctx = document.getElementById("piePengguna");
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                "Air Bersih", "Kebersihan", "Keamanan & IPK", "Listrik"
            ],
            datasets: [
                {
                    data: [
                        {{$penggunaAirBersih}}, {{$penggunaKebersihan}}, {{$penggunaKeamananIpk}}, {{$penggunaListrik}}
                    ],
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#fb6340'
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9', '#17a673', '#2c9faf', '#ea3005'
                    ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                enabled: true,
                callbacks: {
                    // this callback is used to create the tooltip label
                    label: function (tooltipItem, data) {
                        // get the data label and data value to display convert the data value to local
                        // string so it uses a comma seperated number
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString() + ' Pengguna';

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ],
                        // [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label need to clone because we are
                            // changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                },
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            cutoutPercentage: 50,
            rotation: 10
        }
    });
</script>
@endsection
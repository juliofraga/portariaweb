  <?php //var_dump($dados["pedidosPorDia"]["domingo"][0]->qtd)?>
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="<?= URL ?>/public/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />

<!-- Campos para dashboards -->
<!-- Valores para semana -->
<input type="hidden" id="pedidosSegunda" value="<?= $dados["pedidosPorDia"]["segunda"][0]->qtd ?>">
<input type="hidden" id="pedidosTerca" value="<?= $dados["pedidosPorDia"]["terca"][0]->qtd ?>"> 
<input type="hidden" id="pedidosQuarta" value="<?= $dados["pedidosPorDia"]["quarta"][0]->qtd ?>"> 
<input type="hidden" id="pedidosQuinta" value="<?= $dados["pedidosPorDia"]["quinta"][0]->qtd ?>"> 
<input type="hidden" id="pedidosSexta" value="<?= $dados["pedidosPorDia"]["sexta"][0]->qtd ?>"> 
<input type="hidden" id="pedidosSabado" value="<?= $dados["pedidosPorDia"]["sabado"][0]->qtd ?>"> 
<input type="hidden" id="pedidosDomingo" value="<?= $dados["pedidosPorDia"]["domingo"][0]->qtd ?>">
<!-- Fim de valores para semana -->
<!-- Valores para mês -->
<input type="hidden" id="pedidosJaneiro" value="<?= $dados["pedidosPorMes"]["janeiro"][0]->qtd ?>">
<input type="hidden" id="pedidosFevereiro" value="<?= $dados["pedidosPorMes"]["fevereiro"][0]->qtd ?>">
<input type="hidden" id="pedidosMarco" value="<?= $dados["pedidosPorMes"]["marco"][0]->qtd ?>">
<input type="hidden" id="pedidosAbril" value="<?= $dados["pedidosPorMes"]["abril"][0]->qtd ?>">
<input type="hidden" id="pedidosMaio" value="<?= $dados["pedidosPorMes"]["maio"][0]->qtd ?>">
<input type="hidden" id="pedidosJunho" value="<?= $dados["pedidosPorMes"]["junho"][0]->qtd ?>">
<input type="hidden" id="pedidosJulho" value="<?= $dados["pedidosPorMes"]["julho"][0]->qtd ?>">
<input type="hidden" id="pedidosAgosto" value="<?= $dados["pedidosPorMes"]["agosto"][0]->qtd ?>">
<input type="hidden" id="pedidosSetembro" value="<?= $dados["pedidosPorMes"]["setembro"][0]->qtd ?>">
<input type="hidden" id="pedidosOutubro" value="<?= $dados["pedidosPorMes"]["outubro"][0]->qtd ?>">
<input type="hidden" id="pedidosNovembro" value="<?= $dados["pedidosPorMes"]["novembro"][0]->qtd ?>">
<input type="hidden" id="pedidosDezembro" value="<?= $dados["pedidosPorMes"]["dezembro"][0]->qtd ?>">
<!-- Fim de valores para mês-->
<!-- Acessos por mês -->
<input type="hidden" id="acessosJaneiro" value="<?= $dados["acessosPorMes"]["janeiro"][0]->qtd ?>">
<input type="hidden" id="acessosFevereiro" value="<?= $dados["acessosPorMes"]["fevereiro"][0]->qtd ?>">
<input type="hidden" id="acessosMarco" value="<?= $dados["acessosPorMes"]["marco"][0]->qtd ?>">
<input type="hidden" id="acessosAbril" value="<?= $dados["acessosPorMes"]["abril"][0]->qtd ?>">
<input type="hidden" id="acessosMaio" value="<?= $dados["acessosPorMes"]["maio"][0]->qtd ?>">
<input type="hidden" id="acessosJunho" value="<?= $dados["acessosPorMes"]["junho"][0]->qtd ?>">
<input type="hidden" id="acessosJulho" value="<?= $dados["acessosPorMes"]["julho"][0]->qtd ?>">
<input type="hidden" id="acessosAgosto" value="<?= $dados["acessosPorMes"]["agosto"][0]->qtd ?>">
<input type="hidden" id="acessosSetembro" value="<?= $dados["acessosPorMes"]["setembro"][0]->qtd ?>">
<input type="hidden" id="acessosOutubro" value="<?= $dados["acessosPorMes"]["outubro"][0]->qtd ?>">
<input type="hidden" id="acessosNovembro" value="<?= $dados["acessosPorMes"]["novembro"][0]->qtd ?>">
<input type="hidden" id="acessosDezembro" value="<?= $dados["acessosPorMes"]["dezembro"][0]->qtd ?>">
<!-- Fim de acessos para mês-->
<!-- Fim (campos p/ dashboards>-->
<div class="container-fluid py-4">
    <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">add</i>
            </div>
            <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Pedidos Realizados Hoje</p>
            <h4 class="mb-0"><?= $dados["numeroPedidosHoje"][0]->qtd ?></h4>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <?php if($dados["DiferencaPedidosHoje"][2] == "positivo"){ ?>
            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+<?= $dados["DiferencaPedidosHoje"][0] ?>% </span><?= $dados["DiferencaPedidosHoje"][1] ?></p>
          <?php }else if($dados["DiferencaPedidosHoje"][2] == "negativo"){?>
            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-<?= $dados["DiferencaPedidosHoje"][0] ?>% </span><?= $dados["DiferencaPedidosHoje"][1] ?></p>
          <?php }else{?>
            <p class="mb-0"><span class="text-sm font-weight-bolder"><?= $dados["DiferencaPedidosHoje"][0] ?>% </span><?= $dados["DiferencaPedidosHoje"][1] ?></p>
          <?php } ?>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Nº Acessos ao App Hoje</p>
            <h4 class="mb-0"><?= $dados["numeroAcessosHoje"][0]->qtd ?></h4>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <?php if($dados["DiferencaAcessosHoje"][2] == "positivo"){ ?>
            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+<?= $dados["DiferencaAcessosHoje"][0] ?>% </span><?= $dados["DiferencaAcessosHoje"][1] ?></p>
          <?php }else if($dados["DiferencaAcessosHoje"][2] == "negativo"){?>
            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-<?= $dados["DiferencaAcessosHoje"][0] ?>% </span><?= $dados["DiferencaAcessosHoje"][1] ?></p>
          <?php }else{?>
            <p class="mb-0"><span class="text-sm font-weight-bolder"><?= $dados["DiferencaAcessosHoje"][0] ?>% </span><?= $dados["DiferencaAcessosHoje"][1] ?></p>
          <?php } ?>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Clientes Cadastrados</p>
            <h4 class="mb-0"><?= $dados["numeroClientesCadastrados"][0]->qtd ?></h4>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder"><br></p>
        </div>
        </div>
    </div>
    </div>
    <div class="row mt-4">
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
        <div class="card z-index-2 ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
            <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
            <div class="chart">
                <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
            </div>
            </div>
        </div>
        <div class="card-body">
            <h6 class="mb-0 ">Nº de Pedidos por Dia</h6>
            <p class="text-sm "><br></p>
            <hr class="dark horizontal">
            <div class="d-flex ">
            <i class="material-icons text-sm my-auto me-1"></i>
            <p class="mb-0 text-sm"><br></p>
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
        <div class="card z-index-2  ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
            <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
            <div class="chart">
                <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
            </div>
            </div>
        </div>
        <div class="card-body">
            <h6 class="mb-0 "> Nº de Pedidos por Mês </h6>
            <p class="text-sm "><br></p>
            <hr class="dark horizontal">
            <div class="d-flex ">
            <i class="material-icons text-sm my-auto me-1"></i>
            <p class="mb-0 text-sm"><br></p>
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-4 mt-4 mb-3">
        <div class="card z-index-2 ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
            <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
            <div class="chart">
                <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
            </div>
            </div>
        </div>
        <div class="card-body">
            <h6 class="mb-0 ">Nº de Acessos ao APP por Mês</h6>
            <p class="text-sm "><br></p>
            <hr class="dark horizontal">
            <div class="d-flex ">
            <i class="material-icons text-sm my-auto me-1"></i>
            <p class="mb-0 text-sm"><br></p>
            </div>
        </div>
        </div>
    </div>
</div>
<!--   Core JS Files   -->
<script src="<?= URL ?>/public/js/core/popper.min.js"></script>
  <script src="<?= URL ?>/public/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?= URL ?>/public/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="<?= URL ?>/public/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");
    var seg = document.getElementById("pedidosSegunda").value;
    var ter = document.getElementById("pedidosTerca").value;
    var qua = document.getElementById("pedidosQuarta").value;
    var qui = document.getElementById("pedidosQuinta").value;
    var sex = document.getElementById("pedidosSexta").value;
    var sab = document.getElementById("pedidosSabado").value;
    var dom = document.getElementById("pedidosDomingo").value;
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["S", "T", "Q", "Q", "S", "S", "D"],
        datasets: [{
          label: "Pedidos",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "rgba(255, 255, 255, .8)",
          data: [seg, ter, qua, qui, sex, sab, dom],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var jan = document.getElementById("pedidosJaneiro").value;
    var fev = document.getElementById("pedidosFevereiro").value;
    var mar = document.getElementById("pedidosMarco").value;
    var abr = document.getElementById("pedidosAbril").value;
    var mai = document.getElementById("pedidosMaio").value;
    var jun = document.getElementById("pedidosJunho").value;
    var jul = document.getElementById("pedidosJulho").value;
    var ago = document.getElementById("pedidosAgosto").value;
    var set = document.getElementById("pedidosSetembro").value;
    var out = document.getElementById("pedidosOutubro").value;
    var nov = document.getElementById("pedidosNovembro").value;
    var dez = document.getElementById("pedidosDezembro").value;
    
    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Ago", "Set", "Out", "Nov", "Dez"],
        datasets: [{
          label: "Pedidos",
          tension: 0,
          borderWidth: 0,
          pointRadius: 5,
          pointBackgroundColor: "rgba(255, 255, 255, .8)",
          pointBorderColor: "transparent",
          borderColor: "rgba(255, 255, 255, .8)",
          borderColor: "rgba(255, 255, 255, .8)",
          borderWidth: 4,
          backgroundColor: "transparent",
          fill: true,
          data: [jan, fev, mar, abr, mai, jun, jul, ago, set, out, nov, dez],
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });

    var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

    var janAce = document.getElementById("acessosJaneiro").value;
    var fevAce = document.getElementById("acessosFevereiro").value;
    var marAce = document.getElementById("acessosMarco").value;
    var abrAce = document.getElementById("acessosAbril").value;
    var maiAce = document.getElementById("acessosMaio").value;
    var junAce = document.getElementById("acessosJunho").value;
    var julAce = document.getElementById("acessosJulho").value;
    var agoAce = document.getElementById("acessosAgosto").value;
    var setAce = document.getElementById("acessosSetembro").value;
    var outAce = document.getElementById("acessosOutubro").value;
    var novAce = document.getElementById("acessosNovembro").value;
    var dezAce = document.getElementById("acessosDezembro").value;

    new Chart(ctx3, {
      type: "line",
      data: {
        labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Ago", "Set", "Out", "Nov", "Dez"],
        datasets: [{
          label: "Nº Acessos",
          tension: 0,
          borderWidth: 0,
          pointRadius: 5,
          pointBackgroundColor: "rgba(255, 255, 255, .8)",
          pointBorderColor: "transparent",
          borderColor: "rgba(255, 255, 255, .8)",
          borderWidth: 4,
          backgroundColor: "transparent",
          fill: true,
          data: [janAce, fevAce, marAce, abrAce, maiAce, junAce, julAce, agoAce, setAce, outAce, novAce, dezAce],
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#f8f9fa',
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.0.4"></script>
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
  header('location:/working/login/login_es.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <style>
    .toast {
      background-color: #084D6E !important;
      padding: 16px !important;
    }
    .toast .toast-content {
      display: flex !important;
      align-items: center !important;
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INDUSTRO - COLABORADORES</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="/working/css/pgEmp.css">
  <link rel="icon" href="/working/imagenes/logo_industro_.png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<nav class="nav-custom">
  <div class="nav-wrapper">
    <a href="#" data-target="mobile-sidebar" class="sidenav-trigger show-on-large">
      <i class="material-icons">menu</i>
    </a>
    <a href="#" class="brand-logo">INDUSTRO</a>
    <ul class="right hide-on-med-and-down">
      <li>
        <a class="dropdown-trigger white-text" href="#!" data-target="dropdown-empleado">
          <i class="material-icons left">account_circle</i>rocky
          <i class="material-icons right">arrow_drop_down</i>
        </a>
      </li>
    </ul>
  </div>
</nav>

<ul id="dropdown-empleado" class="dropdown-content">
  <li><a href="#!" id="btn-ajustes"><i class="material-icons">settings</i>Ajustes</a></li>
  <li class="divider"></li>
  <li><a href="/working/login/logout.php"><i class="material-icons">exit_to_app</i>Cerrar sesión</a></li>
</ul>

<ul id="mobile-sidebar" class="sidenav sidenav-custom">
  <li>
    <div class="user-view">
      <a href="#"><span class="white-text name">rocky</span></a>
      <a href="#"><span class="white-text email">rocky@gmail.com</span></a>
    </div>
  </li>
  <li><a href="#!" class="mostrar-formulario"><i class="material-icons">insert_chart</i>Registro de Producción</a></li>
  <li><a href="#!" class="mostrar-analisis"><i class="material-icons">science</i>Mi Análisis</a></li>
</ul>

<main class="main-content">
  <div id="formularioProduccion" class="formulario-produccion">
    <h5>Registro de Producción</h5>
    <div class="row">
      <div class="input-field col s12">
        <select id="producto" required>
          <option value="" disabled selected>Seleccione un producto</option>
          <option value="6">cinturones</option>
          <option value="7">bolsos</option>
          <option value="8">tacones</option>
          <option value="9">camisa</option>
          <option value="10">gaban</option>
          <option value="11">corbata</option>
          <option value="12">3</option>
          <option value="13">Protector Lunar</option>
        </select>
        <label>Producto</label>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s12">
        <input id="cantidad" type="number" class="validate" required min="1">
        <label for="cantidad">Cantidad</label>
        <span class="helper-text">Ingrese la cantidad producida</span>
      </div>
    </div>
    <div class="botones-formulario">
      <button class="btn waves-effect waves-light" type="button" id="btnRegistrar">Listo
        <i class="material-icons right">check</i>
      </button>
    </div>
  </div>

<div id="seccion-analisis" class="seccion-analisis">
  <h5 class="center-align">Mi Análisis de Producción</h5>
  <p class="grey-text center-align">Desliza para ver diferentes métricas</p>

  <div class="carousel carousel-slider center carousel-analisis">

    <div class="carousel-item white">
      <h6 class="carousel-title">Producción Diaria</h6>
      <div class="grafica-container" style="position: relative; height: 400px;">
        <canvas id="graficaDiaria"></canvas>
      </div>
      <div class="center-align" style="margin-top: 15px;">
        <a class="btn-floating btn-small blue darken-4 download-btn waves-effect waves-light" data-canvas="graficaDiaria" style="cursor: pointer;">
          <i class="material-icons">file_download</i>
        </a>
      </div>
    </div>

    <div class="carousel-item white">
      <h6 class="carousel-title">Producción Mensual</h6>
      <div class="grafica-container" style="position: relative; height: 400px;">
        <canvas id="graficaMensual"></canvas>
      </div>
      <div class="center-align" style="margin-top: 15px;">
        <a class="btn-floating btn-small amber darken-3 download-btn waves-effect waves-light" data-canvas="graficaMensual" style="cursor: pointer;">
          <i class="material-icons">file_download</i>
        </a>
      </div>
    </div>

    <div class="carousel-item white">
      <h6 class="carousel-title">Distribución por Producto</h6>
      <div class="grafica-container" style="position: relative; height: 400px;">
        <canvas id="graficaProductos"></canvas>
      </div>
      <div class="center-align" style="margin-top: 15px;">
        <a class="btn-floating btn-small green darken-2 download-btn waves-effect waves-light" data-canvas="graficaProductos" style="cursor: pointer;">
          <i class="material-icons">file_download</i>
        </a>
      </div>
    </div>

  </div>

  <div class="carousel-fixed-item center">
    <a class="btn btn-carousel waves-effect white grey-text darken-text-2 btn-prev">
      <i class="material-icons">chevron_left</i>
    </a>
    <a class="btn btn-carousel waves-effect white grey-text darken-text-2 btn-next">
      <i class="material-icons">chevron_right</i>
    </a>
  </div>
</div>


<div class="carousel-fixed-item center">
  <a class="btn btn-carousel waves-effect white grey-text darken-text-2 btn-prev">
    <i class="material-icons">chevron_left</i>
  </a>
  <a class="btn btn-carousel waves-effect white grey-text darken-text-2 btn-next">
    <i class="material-icons">chevron_right</i>
  </a>
</div>

<div style="display: flex; justify-content: center; margin-top: 20px;">
  <button class="btn waves-effect waves-light" id="volver-formulario" style="display: none;">
    <i class="material-icons left">arrow_back</i>Volver al registro
  </button>
</div>


</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  console.log('✅ Código cargado correctamente');

window.onload = function() {
  M.Sidenav.init(document.querySelectorAll('.sidenav'));
  M.FormSelect.init(document.querySelectorAll('select'));
  M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'), { coverTrigger: false });

  const formulario = document.getElementById('formularioProduccion');
  const analisis = document.getElementById('seccion-analisis');
  const btnVolver = document.getElementById('volver-formulario');
  const btnAnalisis = document.querySelectorAll('.mostrar-analisis');
  const btnFormulario = document.querySelectorAll('.mostrar-formulario');

  let graficas = { diaria: null, mensual: null, productos: null };
  let intervaloRefresco = null;
  let carrusel = null;

btnFormulario.forEach(btn => {
  btn.addEventListener('click', () => {
    formulario.style.display = 'block';
    analisis.style.display = 'none';
    document.getElementById('volver-formulario').style.display = 'none';  // 👈 Ocultar botón
    clearInterval(intervaloRefresco);
  });
});

btnAnalisis.forEach(btn => {
  btn.addEventListener('click', () => {
    formulario.style.display = 'none';
    analisis.style.display = 'block';
    document.getElementById('volver-formulario').style.display = 'block';  // 👈 Mostrar botón
    analisis.scrollIntoView({ behavior: 'smooth' });

    const carrusel = M.Carousel.init(document.querySelector('.carousel'), {
      fullWidth: true,
      indicators: true
    });

    document.querySelector('.btn-next').onclick = () => carrusel.next();
    document.querySelector('.btn-prev').onclick = () => carrusel.prev();

    cargarGraficas().then(() => {
      inicializarBotonesDescarga();
    });

    if (intervaloRefresco) clearInterval(intervaloRefresco);
    intervaloRefresco = setInterval(() => {
      cargarGraficas().then(() => {
        inicializarBotonesDescarga();
      });
    }, 30000);
  });
});

btnVolver.addEventListener('click', () => {
  formulario.style.display = 'block';
  analisis.style.display = 'none';
  document.getElementById('volver-formulario').style.display = 'none';  // 👈 Ocultar botón
  clearInterval(intervaloRefresco);
});


  function inicializarBotonesDescarga() {
    document.querySelectorAll('.download-btn').forEach(btn => {
      btn.onclick = () => {
        const canvasId = btn.getAttribute('data-canvas');
        const canvas = document.getElementById(canvasId);
        if (canvas) {
          const link = document.createElement('a');
          link.href = canvas.toDataURL('image/png');
          link.download = `${canvasId}.png`;
          link.click();
        } else {
          M.toast({ html: '❌ Gráfica no encontrada' });
        }
      };
    });
  }

  async function cargarGraficas() {
    try {
      const res = await fetch('/working/procedimientos/obtener_datos_graficas.php');
      const data = await res.json();

      if (graficas.diaria) graficas.diaria.destroy();
      if (graficas.mensual) graficas.mensual.destroy();
      if (graficas.productos) graficas.productos.destroy();

      graficas.diaria = new Chart(document.getElementById('graficaDiaria').getContext('2d'), {
        type: 'line',
        data: {
          labels: data.diaria.map(item => item.fecha),
          datasets: [{
            label: 'Producción diaria',
            data: data.diaria.map(item => parseInt(item.total)),
            borderColor: '#084D6E',
            backgroundColor: 'rgba(8, 77, 110, 0.1)',
            tension: 0.3
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });

      graficas.mensual = new Chart(document.getElementById('graficaMensual').getContext('2d'), {
        type: 'bar',
        data: {
          labels: data.mensual.map(item => `Sem ${item.semana}`),
          datasets: [{
            label: 'Producción semanal',
            data: data.mensual.map(item => parseInt(item.total)),
            backgroundColor: '#FFCC00'
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });

      graficas.productos = new Chart(document.getElementById('graficaProductos').getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: data.productos.map(item => item.producto),
          datasets: [{
            data: data.productos.map(item => parseInt(item.total)),
            backgroundColor: ['#084D6E', '#FFCC00', '#0A6E9C', '#4CAF50', '#E91E63', '#9C27B0']
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });

      inicializarBotonesDescarga();

    } catch (error) {
      console.error('Error cargando gráficas:', error);
      M.toast({ html: '⚠️ Error cargando análisis' });
    }
  }

  function inicializarBotonesDescarga() {
  document.querySelectorAll('.download-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const canvasId = btn.getAttribute('data-canvas');
      const canvas = document.getElementById(canvasId);
      if (!canvas) {
        M.toast({ html: '❌ Gráfica no encontrada' });
        return;
      }
      const link = document.createElement('a');
      link.href = canvas.toDataURL('image/png');
      link.download = `${canvasId}.png`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });
  });
}


  document.getElementById('btnRegistrar').addEventListener('click', async () => {
    const producto = document.getElementById('producto').value;
    const cantidad = document.getElementById('cantidad').value;

    if (!producto || !cantidad || cantidad <= 0) {
      M.toast({ html: '⚠️ Complete todos los campos correctamente' });
      return;
    }

    try {
      const res = await fetch('/working/procedimientos/registrar_produccion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `producto=${producto}&cantidad=${cantidad}`
      });
      const data = await res.json();

      if (data.error) throw new Error(data.error);

M.toast({
  html: `
    <span style="display: flex; align-items: center;">
      <i class="material-icons" style="color: #FFCC00; margin-right: 10px;">check_circle</i>
      <span style="color: white; font-weight: bold;">Producción registrada con éxito</span>
    </span>
  `,
  classes: 'rounded',
  displayLength: 3000
});

    } catch (error) {
      M.toast({ html: `⚠️ ${error.message}` });
    }
  });

};
</script>
</body>
</html>

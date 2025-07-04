<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:/working/login/login_es.php');
    exit();
}
$nomUsuario = $_SESSION['usuario'];
$email = $_SESSION['email'];
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
<i class="material-icons left">account_circle</i><?php echo htmlspecialchars($nomUsuario); ?>

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
<a href="#"><span class="white-text name"><?php echo htmlspecialchars($nomUsuario); ?></span></a>
<a href="#"><span class="white-text email"><?php echo htmlspecialchars($email); ?></span></a>

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
document.addEventListener('DOMContentLoaded', function() {
  M.Sidenav.init(document.querySelectorAll('.sidenav'));
  M.FormSelect.init(document.querySelectorAll('select'));
  M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'), { coverTrigger: false });


async function cargarProductos() {
  try {
    const res = await fetch('/working/procedimientos/obtener_productos.php');
    const productos = await res.json();

    const select = document.getElementById('producto');
    select.innerHTML = '<option value="" disabled selected>Seleccione un producto</option>';

    productos.forEach(prod => {
      const option = document.createElement('option');
      option.value = prod.idProd;
      option.textContent = prod.nomProd;
      select.appendChild(option);
    });

    M.FormSelect.init(select);
  } catch (error) {
    console.error('Error cargando productos:', error);
    M.toast({ html: '⚠️ No se pudo cargar la lista de productos' });
  }
}

cargarProductos();



  const formulario = document.getElementById('formularioProduccion');
  const analisis = document.getElementById('seccion-analisis');
  const btnVolver = document.getElementById('volver-formulario');
  const btnAnalisis = document.querySelectorAll('.mostrar-analisis');
  const btnFormulario = document.querySelectorAll('.mostrar-formulario');

  let graficas = { diaria: null, mensual: null, productos: null };
  let intervaloRefresco = null;
  let carrusel = null;
  let carruselInicializado = false;

  btnFormulario.forEach(btn => {
    btn.addEventListener('click', () => {
      formulario.style.display = 'block';
      analisis.style.display = 'none';
      btnVolver.style.display = 'none';
      clearInterval(intervaloRefresco);
    });
  });

  btnAnalisis.forEach(btn => {
    btn.addEventListener('click', () => {
      formulario.style.display = 'none';
      analisis.style.display = 'block';
      btnVolver.style.display = 'inline-block';
      analisis.scrollIntoView({ behavior: 'smooth' });

      if (!carruselInicializado) {
        carrusel = M.Carousel.init(document.querySelector('.carousel'), {
          fullWidth: true,
          indicators: true
        });
        document.querySelector('.btn-next').onclick = () => carrusel.next();
        document.querySelector('.btn-prev').onclick = () => carrusel.prev();
        carruselInicializado = true;
      }

      cargarGraficas();
      if (intervaloRefresco) clearInterval(intervaloRefresco);
      intervaloRefresco = setInterval(() => {
        cargarGraficas();
      }, 30000);
    });
  });

  btnVolver.addEventListener('click', () => {
    formulario.style.display = 'block';
    analisis.style.display = 'none';
    btnVolver.style.display = 'none';
    clearInterval(intervaloRefresco);
  });

  async function cargarGraficas() {
    try {
      const res = await fetch('/working/procedimientos/obtener_datos_graficas.php');
      const data = await res.json();

      if (graficas.diaria) graficas.diaria.destroy();
      if (graficas.mensual) graficas.mensual.destroy();
      if (graficas.productos) graficas.productos.destroy();

      const ctxDiaria = document.getElementById('graficaDiaria').getContext('2d');
      const ctxMensual = document.getElementById('graficaMensual').getContext('2d');
      const ctxProductos = document.getElementById('graficaProductos').getContext('2d');

      graficas.diaria = new Chart(ctxDiaria, {
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

      graficas.mensual = new Chart(ctxMensual, {
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

      graficas.productos = new Chart(ctxProductos, {
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
      console.error('Error al cargar gráficas:', error);
      M.toast({ html: '⚠️ No se pudieron cargar las gráficas' });
    }
  }

  function inicializarBotonesDescarga() {
    document.querySelectorAll('.download-btn').forEach(btn => {
      btn.onclick = () => {
        const canvasId = btn.getAttribute('data-canvas');
        const canvas = document.getElementById(canvasId);
        if (canvas) {
          const link = document.createElement('a');
          link.href = canvas.toDataURL('image/png');
          link.download = `${canvasId}.png`;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        } else {
          M.toast({ html: '❌ Gráfica no encontrada' });
        }
      };
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

      // Limpiar formulario después de registrar
document.getElementById('producto').selectedIndex = 0;
document.getElementById('cantidad').value = '';
M.FormSelect.init(document.querySelectorAll('select')); // Refrescar selector Materialize

      
    } catch (error) {
      M.toast({ html: `⚠️ ${error.message}` });
    }
  });

});
</script>

</body>
</html>
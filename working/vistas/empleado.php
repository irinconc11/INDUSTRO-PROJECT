<?php
session_start();

if(!isset($_SESSION['usuario'])){
  header('location:/working/login/login_es.php');
  exit(); 
}



?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INDUSTRO - COLABORADORES</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="/working/css/pgEmp.css">
  <link rel="icon" href="/working/imagenes/logo_industro_.png">
  <!-- Añadir Chart.js para las gráficas -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <!-- Barra de navegación superior -->
  <nav class="nav-custom">
    <div class="nav-wrapper">
      <a href="#" data-target="mobile-sidebar" class="sidenav-trigger show-on-large">
        <i class="material-icons">menu</i>
      </a>
      <a href="#" class="brand-logo">INDUSTRO</a>
      <ul class="right hide-on-med-and-down">
        <li>
          <a class="dropdown-trigger white-text" href="#!" data-target="dropdown-empleado">
            <i class="material-icons left">account_circle</i><?php echo htmlspecialchars($_SESSION['usuario']); ?>
            <i class="material-icons right">arrow_drop_down</i>
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Menú desplegable -->
  <ul id="dropdown-empleado" class="dropdown-content">
    <li><a href="#!" id="btn-ajustes"><i class="material-icons">settings</i>Ajustes</a></li>
    <li class="divider" tabindex="-1"></li>
    <li><a href="/working/login/logout.php" id="btn-cerrar-sesion"><i class="material-icons">exit_to_app</i>Cerrar sesión</a></li>
  </ul>

  <!-- Sidebar -->
  <ul id="mobile-sidebar" class="sidenav sidenav-custom">
    <li>
      <div class="user-view">
        <a href="#"><span class="white-text name"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span></a>
        <a href="#"><span class="white-text email"><?php echo htmlspecialchars($_SESSION['email']); ?></span></a>
      </div>
    </li>
    <li><a href="#!" class="mostrar-formulario"><i class="material-icons">insert_chart</i>Registro de Producción</a></li>
    <li><a href="#!" class="mostrar-analisis"><i class="material-icons">science</i>Mi Análisis</a></li>
    <!-- <li><a href="#!"><i class="material-icons">logout</i>Cerrar sesión</a></li> -->
  </ul>

  <!-- Contenido principal -->
  <main class="main-content">
    <div id="formularioProduccion" class="formulario-produccion">
      <h5>Registro de Producción</h5>
      <div class="row">
        <div class="input-field col s6">
          <input type="date" id="fecha" class="validate" required>
          <label for="fecha">Fecha</label>
        </div>
        <div class="input-field col s6">
          <input type="time" id="hora" class="validate" required>
          <label for="hora">Hora</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <select id="producto" required>
            <option value="" disabled selected>Seleccione un producto</option>
            <option value="camisetas">Camisetas</option>
            <option value="pantalones">Pantalones</option>
            <option value="vestidos">Vestidos</option>
            <option value="chaquetas">Chaquetas</option>
            <option value="sudaderas">Sudaderas</option>
            <option value="ropa_interior">Ropa interior</option>
            <option value="uniformes">Uniformes</option>
            <option value="jeans">Jeans</option>
            <option value="blusas">Blusas</option>
            <option value="trajes">Trajes</option>
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
        <button class="btn waves-effect waves-light blue" type="button" id="otroProducto">Tengo un producto más
          <i class="material-icons right">add</i>
        </button>
      </div>
    </div>

    <!-- Sección de Análisis (nueva) -->
    <div id="seccion-analisis" class="seccion-analisis">
      <h5 class="center-align">Mi Análisis de Producción</h5>
      <p class="grey-text center-align">Desliza para ver diferentes métricas</p>
      
      <!-- Carrusel estilo Materialize -->
      <div class="carousel carousel-slider center carousel-analisis">
        <!-- Panel 1: Producción Diaria -->
        <div class="carousel-item white" href="#one!">
          <h6 class="carousel-title">Producción Diaria</h6>
          <div class="grafica-container">
            <canvas id="graficaDiaria"></canvas>
          </div>
        </div>
        
        <!-- Panel 2: Producción Mensual -->
        <div class="carousel-item white" href="#two!">
          <h6 class="carousel-title">Producción Mensual</h6>
          <div class="grafica-container">
            <canvas id="graficaMensual"></canvas>
          </div>
        </div>
        
        <!-- Panel 3: Distribución por Producto -->
        <div class="carousel-item white" href="#three!">
          <h6 class="carousel-title">Distribución por Producto</h6>
          <div class="grafica-container">
            <canvas id="graficaProductos"></canvas>
          </div>
        </div>
        
        <!-- Controles de navegación -->
        <div class="carousel-fixed-item center">
          <a class="btn btn-carousel waves-effect white grey-text darken-text-2 btn-prev">
            <i class="material-icons">chevron_left</i>
          </a>
          <a class="btn btn-carousel waves-effect white grey-text darken-text-2 btn-next">
            <i class="material-icons">chevron_right</i>
          </a>
        </div>
      </div>
      
      <!-- Botón para volver al formulario -->
      <div class="center">
        <button class="btn waves-effect waves-light" id="volver-formulario">
          <i class="material-icons left">arrow_back</i>Volver al registro
        </button>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Inicializaciones de Materialize
      M.Sidenav.init(document.querySelectorAll('.sidenav'), { edge: 'left', draggable: true });
      M.FormSelect.init(document.querySelectorAll('select'));
      M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'), { coverTrigger: false });

      // Mostrar formulario
      document.querySelectorAll('.mostrar-formulario').forEach(el => {
        el.addEventListener('click', e => {
          e.preventDefault();
          const f = document.getElementById('formularioProduccion');
          const a = document.getElementById('seccion-analisis');
          f.style.display = 'block';
          a.style.display = 'none';
          f.scrollIntoView({ behavior: 'smooth' });
        });
      }); 

      // Mostrar análisis
      document.querySelectorAll('.mostrar-analisis').forEach(el => {
        el.addEventListener('click', e => {
          e.preventDefault();
          const f = document.getElementById('formularioProduccion');
          const a = document.getElementById('seccion-analisis');
          f.style.display = 'none';
          a.style.display = 'block';
          a.scrollIntoView({ behavior: 'smooth' });
          
          // Inicializar carrusel solo cuando se muestra la sección
          var carrusel = M.Carousel.init(document.querySelector('.carousel'), {
            fullWidth: true,
            indicators: true,
            duration: 200
          });
          
          // Configurar botones de navegación
          document.querySelector('.btn-next').addEventListener('click', () => carrusel.next());
          document.querySelector('.btn-prev').addEventListener('click', () => carrusel.prev());
          
          // Inicializar gráficas
          inicializarGraficas();
        });
      });

      // Botón volver al formulario (CORREGIDO)
      document.getElementById('volver-formulario').addEventListener('click', function(e) {
        e.preventDefault();
        const f = document.getElementById('formularioProduccion');
        const a = document.getElementById('seccion-analisis');
        f.style.display = 'block';
        a.style.display = 'none';
        f.scrollIntoView({ behavior: 'smooth' });
      });
      
      // Botón para agregar otro producto
      document.getElementById('otroProducto').addEventListener('click', function () {
        alert('Producto registrado. Puede agregar otro.');
        document.getElementById('producto').value = '';
        document.getElementById('cantidad').value = '';
        M.updateTextFields();
      });
      
      // Función para inicializar gráficas
      function inicializarGraficas() {
        // Datos de ejemplo (en una aplicación real, estos vendrían de una API)
        const datos = {
          diaria: {
            labels: ['8:00', '10:00', '12:00', '14:00', '16:00', '18:00'],
            datasets: [{
              label: 'Unidades producidas hoy',
              data: [12, 19, 8, 15, 22, 10],
              backgroundColor: 'rgba(52, 152, 219, 0.2)',
              borderColor: 'rgba(52, 152, 219, 1)',
              borderWidth: 2,
              tension: 0.4
            }]
          },
          mensual: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
            datasets: [{
              label: 'Producción semanal',
              data: [120, 190, 150, 210],
              backgroundColor: 'rgba(46, 204, 113, 0.2)',
              borderColor: 'rgba(46, 204, 113, 1)',
              borderWidth: 2
            }]
          },
          productos: {
            labels: ['Camisetas', 'Pantalones', 'Vestidos', 'Chaquetas', 'Sudaderas'],
            datasets: [{
              label: 'Distribución por producto',
              data: [35, 25, 20, 10, 10],
              backgroundColor: [
                'rgba(231, 76, 60, 0.7)',
                'rgba(52, 152, 219, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(241, 196, 15, 0.7)',
                'rgba(46, 204, 113, 0.7)'
              ],
              borderWidth: 1
            }]
          }
        };
        
        // Crear gráficas
        new Chart(document.getElementById('graficaDiaria').getContext('2d'), {
          type: 'line',
          data: datos.diaria,
          options: { responsive: true, maintainAspectRatio: false }
        });
        
        new Chart(document.getElementById('graficaMensual').getContext('2d'), {
          type: 'bar',
          data: datos.mensual,
          options: { responsive: true, maintainAspectRatio: false }
        });
        
        new Chart(document.getElementById('graficaProductos').getContext('2d'), {
          type: 'doughnut',
          data: datos.productos,
          options: { responsive: true, maintainAspectRatio: false }
        });
      }
    });
  </script>
  <script>
    document.getElementById('btnRegistrar')addEventListener('click', function(){
      const datos={
        fecha: getElementById('fecha');
        hora: getElementById('hora');
        producto: getElementById('producto');
        cantidad: getElementById('cantidad');
      }
    })
  </script>
</body>
</html>
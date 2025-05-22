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
  <title>INDUSTRO - Panel Administrador</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="/working/css/PgAdmon.css">
  <link rel="icon" href="/working/imagenes/logo_industro_.png">
</head>
<body>
  <!-- Barra de navegación superior -->
  <nav class="nav-custom">
    <div class="nav-wrapper">
      <a href="#" data-target="mobile-demo" class="sidenav-trigger show-on-large">
        <i class="material-icons">menu</i>
      </a>
      <a href="#" class="brand-logo" id="logo-industro">INDUSTRO</a>
      <ul class="right hide-on-med-and-down">
        <!-- Botón desplegable de Administrador -->
        <li>
          <a class="dropdown-trigger white-text" href="#!" data-target="dropdown-admin">
            <i class="material-icons left">account_circle</i><?php echo htmlspecialchars($_SESSION['usuario']); ?>
            <i class="material-icons right">arrow_drop_down</i>
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Menú desplegable -->
  <ul id="dropdown-admin" class="dropdown-content">
    <li><a href="#!" id="btn-ajustes"><i class="material-icons">settings</i>Ajustes</a></li>
    <li class="divider" tabindex="-1"></li>
    <li><a href="/working/login/logout.php/" id="btn-cerrar-sesion"><i class="material-icons">exit_to_app</i>Cerrar sesión</a></li>
  </ul>

  <!-- Sidebar -->
  <ul id="mobile-demo" class="sidenav sidenav-custom">
    <li>
      <div class="user-view">
        <div class="background"></div>
        <a href="#"><span class="white-text name"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span></a>
        <a href="#"><span class="white-text email"><?php echo htmlspecialchars($_SESSION['email']); ?></span></a>
      </div>
    </li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">Personal de Área</a></li>
    <li><a href="#" class="waves-effect" id="btn-personal"><i class="material-icons">group</i>Gestión de Personal</a></li>
    <li><div class="divider"></div></li>
    <li><a href="#" class="waves-effect" id="btn-produccion"><i class="material-icons">assignment</i>Producción</a></li>
    <li><a href="#" class="waves-effect" id="btn-productos"><i class="material-icons">shopping_cart</i>Gestión de Productos</a></li>
    <li><a href="#" class="waves-effect" id="btn-inventario"><i class="material-icons">inventory</i>Inventario de Materiales</a></li>
  </ul>

  <!-- Contenido principal -->
  <div class="container">
    <!-- Sección de Bienvenida (agregado ID) -->
    <div id="seccion-bienvenida">
      <h4>Bienvenido, Administrador</h4>
      <div class="card">
        <div class="card-content">
          <p>Busca la estadística que necesites.</p>
        </div>
      </div>
    </div>

    <!-- Sección: Gestión de Personal -->
    <div id="seccion-personal" class="grafico-container" style="display: none;">
      <div class="card">
        <div class="card-content">
          <span class="card-title">Gestión de Personal</span>
          <div class="input-field">
            <input type="text" id="buscar-personal" placeholder="Buscar personal existente">
            <label for="buscar-personal">Buscar Personal</label>
          </div>
          <div class="card blue lighten-5 z-depth-1" style="padding: 20px; margin-top: 20px;">
            <h6 class="blue-text text-darken-4"><i class="material-icons left">person_add</i>Registrar nuevo personal</h6>
            <p>Desde esta sección podrás ingresar nuevos empleados a la base de datos de la empresa.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección: Producción (ACTUALIZADA) -->
    <div id="seccion-produccion" style="display: none;">
      <div class="row">
        <div class="col s12 m4">
          <div class="card-panel card-grafico hoverable" data-target="grafico-produccion">
            <div class="card-content center-align">
              <i class="material-icons large-icon">show_chart</i>
              <h5 class="card-title">PRODUCCIÓN MENSUAL</h5>
              <p class="card-desc">Análisis detallado de la producción mensual</p>
            </div>
          </div>
        </div>
        <div class="col s12 m4">
          <div class="card-panel card-grafico hoverable" data-target="grafico-diario">
            <div class="card-content center-align">
              <i class="material-icons large-icon">timeline</i>
              <h5 class="card-title">PRODUCCIÓN DIARIA</h5>
              <p class="card-desc">Seguimiento diario de la producción</p>
            </div>
          </div>
        </div>
        <div class="col s12 m4">
          <div class="card-panel card-grafico hoverable" data-target="grafico-reconstruccion">
            <div class="card-content center-align">
              <i class="material-icons large-icon">build</i>
              <h5 class="card-title">RECONSTRUCCIÓN</h5>
              <p class="card-desc">Procesos de reconstrucción y mantenimiento</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenedores de gráficos -->
      <div id="grafico-produccion" class="grafico-container" style="display:none;">
        <div class="card">
          <div class="card-content">
            <span class="card-title">Producción Mensual</span>
            <div class="grafico-placeholder">Gráfico de producción</div>
          </div>
        </div>
      </div>

      <div id="grafico-diario" class="grafico-container" style="display:none;">
        <div class="card">
          <div class="card-content">
            <span class="card-title">Producción Diaria</span>
            <div class="grafico-placeholder">Gráficas de producción global</div>
          </div>
        </div>
      </div>

      <div id="grafico-reconstruccion" class="grafico-container" style="display:none;">
        <div class="card">
          <div class="card-content">
            <span class="card-title">Reconstrucción</span>
            <div class="grafico-placeholder">Gráficas de reconstrucción</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección: Gestión de Productos (ORIGINAL - SIN MODIFICAR) -->
    <div id="seccion-productos" class="grafico-container" style="display: none;">
      <div class="card">
        <div class="card-content">
          <span class="card-title">Gestión de Productos</span>
          
          <div class="row">
            <div class="col s12 m6 l3">
              <a class="waves-effect waves-light btn-large blue" id="btn-crear-producto" style="width: 100%; margin-bottom: 10px;">
                <i class="material-icons left">add</i>Crear Producto
              </a>
            </div>
            <div class="col s12 m6 l3">
              <a class="waves-effect waves-light btn-large green" id="btn-ver-producto" style="width: 100%; margin-bottom: 10px;">
                <i class="material-icons left">visibility</i>Ver Producto
              </a>
            </div>
            <div class="col s12 m6 l3">
              <a class="waves-effect waves-light btn-large orange" id="btn-actualizar-producto" style="width: 100%; margin-bottom: 10px;">
                <i class="material-icons left">edit</i>Actualizar Producto
              </a>
            </div>
            <div class="col s12 m6 l3">
              <a class="waves-effect waves-light btn-large red" id="btn-eliminar-producto" style="width: 100%; margin-bottom: 10px;">
                <i class="material-icons left">delete</i>Eliminar Producto
              </a>
            </div>
          </div>
          
          <!-- Subsección: Crear Producto -->
          <div id="subseccion-crear" style="display: none; margin-top: 20px;">
            <h5>Crear Nuevo Producto</h5>
            <form id="form-crear-producto">
              <div class="row">
                <div class="input-field col s12">
                  <input id="nombre-producto" type="text" class="validate" required>
                  <label for="nombre-producto">Nombre del Producto</label>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s6">
                  <input id="cantidad" type="number" class="validate" min="1" required>
                  <label for="cantidad">Cantidad</label>
                </div>
                <div class="input-field col s6">
                  <input id="precio" type="number" step="0.01" class="validate" min="0" required>
                  <label for="precio">Precio</label>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12">
                  <input id="fecha-ingreso" type="date" class="validate" required>
                  <label for="fecha-ingreso">Fecha de Ingreso</label>
                </div>
              </div>
              
              <div class="row">
                <div class="file-field input-field col s12">
                  <div class="btn">
                    <span>Foto del Producto</span>
                    <input type="file" id="foto-producto" accept="image/*">
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col s12">
                  <button class="btn waves-effect waves-light" type="submit" name="action">
                    Guardar Producto
                    <i class="material-icons right">send</i>
                  </button>
                </div>
              </div>
            </form>
          </div>
          
          <!-- Subsección: Ver Producto -->
          <div id="subseccion-ver" style="display: none; margin-top: 20px;">
            <h5>Ver Productos</h5>
            <div class="input-field">
              <input type="text" id="buscar-producto" placeholder="Buscar producto...">
              <label for="buscar-producto">Buscar Producto</label>
            </div>
            
            <table class="highlight">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Fecha Ingreso</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Producto Ejemplo 1</td>
                  <td>50</td>
                  <td>$25.99</td>
                  <td>2023-05-15</td>
                  <td>
                    <a href="#!" class="btn-small blue"><i class="material-icons">visibility</i></a>
                  </td>
                </tr>
                <tr>
                  <td>Producto Ejemplo 2</td>
                  <td>120</td>
                  <td>$15.50</td>
                  <td>2023-06-20</td>
                  <td>
                    <a href="#!" class="btn-small blue"><i class="material-icons">visibility</i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Subsección: Actualizar Producto -->
          <div id="subseccion-actualizar" style="display: none; margin-top: 20px;">
            <h5>Actualizar Producto</h5>
            <div class="input-field">
              <input type="text" id="buscar-actualizar" placeholder="Buscar producto para actualizar...">
              <label for="buscar-actualizar">Buscar Producto</label>
            </div>
            
            <div class="card blue lighten-5" style="padding: 20px; margin-top: 20px;">
              <p>Seleccione un producto de la lista para actualizar sus datos.</p>
            </div>
          </div>
          
          <!-- Subsección: Eliminar Producto -->
          <div id="subseccion-eliminar" style="display: none; margin-top: 20px;">
            <h5>Eliminar Producto</h5>
            <div class="input-field">
              <input type="text" id="buscar-eliminar" placeholder="Buscar producto para eliminar...">
              <label for="buscar-eliminar">Buscar Producto</label>
            </div>
            
            <div class="card red lighten-5" style="padding: 20px; margin-top: 20px;">
              <p>Seleccione un producto de la lista para eliminarlo del sistema.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección: Inventario de Materiales (NUEVA) -->
    <div id="seccion-inventario" class="grafico-container" style="display: none;">
      <div class="card">
        <div class="card-content">
          <span class="card-title">Inventario de Materiales</span>
          
          <!-- Filtros y búsqueda -->
          <div class="row">
            <div class="col s12 m6">
              <div class="input-field">
                <input type="text" id="buscar-material" placeholder="Buscar material...">
                <label for="buscar-material">Buscar Material</label>
              </div>
            </div>
            <div class="col s12 m6">
              <div class="input-field">
                <select id="filtro-categoria">
                  <option value="" selected>Todas las categorías</option>
                  <option value="tela">Telas</option>
                  <option value="boton">Botones</option>
                  <option value="cremallera">Cremalleras</option>
                  <option value="hilo">Hilos</option>
                  <option value="otros">Otros materiales</option>
                </select>
                <label>Filtrar por categoría</label>
              </div>
            </div>
          </div>
          
          <!-- Botones de acción -->
          <div class="row">
            <div class="col s12 m4">
              <a class="waves-effect waves-light btn blue" id="btn-agregar-material" style="width: 100%; margin-bottom: 10px;">
                <i class="material-icons left">add</i>Agregar Material
              </a>
            </div>
            <div class="col s12 m4">
              <a class="waves-effect waves-light btn orange" id="btn-reporte-inventario" style="width: 100%; margin-bottom: 10px;">
                <i class="material-icons left">description</i>Generar Reporte
              </a>
            </div>
            <div class="col s12 m4">
              <a class="waves-effect waves-light btn green" id="btn-materiales-faltantes" style="width: 100%; margin-bottom: 10px;">
                <i class="material-icons left">warning</i>Ver Faltantes
              </a>
            </div>
          </div>
          
          <!-- Tabla de inventario -->
          <table class="highlight responsive-table">
            <thead>
              <tr>
                <th>Material</th>
                <th>Categoría</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Unidad</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Tela de algodón - Blanco</td>
                <td>Tela</td>
                <td>150</td>
                <td>50</td>
                <td>Metros</td>
                <td><span class="new badge green" data-badge-caption="Disponible"></span></td>
                <td>
                  <a href="#!" class="btn-small blue"><i class="material-icons">edit</i></a>
                  <a href="#!" class="btn-small red"><i class="material-icons">delete</i></a>
                </td>
              </tr>
              <tr>
                <td>Botones pequeños - Negro</td>
                <td>Botón</td>
                <td>320</td>
                <td>500</td>
                <td>Unidades</td>
                <td><span class="new badge orange" data-badge-caption="Bajo stock"></span></td>
                <td>
                  <a href="#!" class="btn-small blue"><i class="material-icons">edit</i></a>
                  <a href="#!" class="btn-small red"><i class="material-icons">delete</i></a>
                </td>
              </tr>
              <tr>
                <td>Cremallera invisible - 40cm</td>
                <td>Cremallera</td>
                <td>12</td>
                <td>30</td>
                <td>Unidades</td>
                <td><span class="new badge red" data-badge-caption="Faltante"></span></td>
                <td>
                  <a href="#!" class="btn-small blue"><i class="material-icons">edit</i></a>
                  <a href="#!" class="btn-small red"><i class="material-icons">delete</i></a>
                </td>
              </tr>
            </tbody>
          </table>
          
          <!-- Sección de materiales faltantes -->
          <div id="seccion-faltantes" style="display: none; margin-top: 20px;">
            <h5>Materiales con Stock Bajo o Faltante</h5>
            <div class="card red lighten-5">
              <div class="card-content">
                <div class="row">
                  <div class="col s12">
                    <ul class="collection">
                      <li class="collection-item">
                        <span class="badge">5/50</span>
                        Botones pequeños - Negro
                      </li>
                      <li class="collection-item">
                        <span class="badge">2/30</span>
                        Cremallera invisible - 40cm
                      </li>
                      <li class="collection-item">
                        <span class="badge">8/30</span>
                        Hilo de coser - Blanco
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="center-align">
                  <a class="waves-effect waves-light btn blue" id="btn-solicitar-materiales">
                    <i class="material-icons left">local_shipping</i>Solicitar Materiales
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    // Inicialización de componentes de Materialize
    document.addEventListener('DOMContentLoaded', function() {
      // Sidebar
      var sidenav = document.querySelectorAll('.sidenav');
      M.Sidenav.init(sidenav);
      
      // Dropdown
      var dropdown = document.querySelectorAll('.dropdown-trigger');
      M.Dropdown.init(dropdown, {coverTrigger: false});
      
      // Datepicker
      var datepicker = document.querySelectorAll('.datepicker');
      M.Datepicker.init(datepicker);
      
      // Selectores
      var elemsSelect = document.querySelectorAll('select');
      M.FormSelect.init(elemsSelect);
      
      // Mostrar/ocultar secciones principales
      document.getElementById('btn-personal').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSections();
        document.getElementById('seccion-personal').style.display = 'block';
        document.getElementById('seccion-bienvenida').style.display = 'none';
      });
      
      document.getElementById('btn-produccion').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSections();
        document.getElementById('seccion-produccion').style.display = 'block';
        document.getElementById('seccion-bienvenida').style.display = 'none';
      });
      
      document.getElementById('btn-productos').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSections();
        document.getElementById('seccion-productos').style.display = 'block';
        document.getElementById('seccion-bienvenida').style.display = 'none';
        hideAllSubsections();
      });
      
      document.getElementById('btn-inventario').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSections();
        document.getElementById('seccion-inventario').style.display = 'block';
        document.getElementById('seccion-bienvenida').style.display = 'none';
      });
      
      // Evento para el logo de INDUSTRO
      document.getElementById('logo-industro').addEventListener('click', function(e) {
        e.preventDefault();
        showWelcomeSection();
      });
      
      // Botones de gráficos
      var cardsGraficos = document.querySelectorAll('.card-grafico');
      cardsGraficos.forEach(function(card) {
        card.addEventListener('click', function() {
          var target = this.getAttribute('data-target');
          var graficos = document.querySelectorAll('.grafico-container');
          graficos.forEach(function(grafico) {
            grafico.style.display = 'none';
          });
          document.getElementById(target).style.display = 'block';
        });
      });
      
      // Botones de gestión de productos
      document.getElementById('btn-crear-producto').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSubsections();
        document.getElementById('subseccion-crear').style.display = 'block';
      });
      
      document.getElementById('btn-ver-producto').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSubsections();
        document.getElementById('subseccion-ver').style.display = 'block';
      });
      
      document.getElementById('btn-actualizar-producto').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSubsections();
        document.getElementById('subseccion-actualizar').style.display = 'block';
      });
      
      document.getElementById('btn-eliminar-producto').addEventListener('click', function(e) {
        e.preventDefault();
        hideAllSubsections();
        document.getElementById('subseccion-eliminar').style.display = 'block';
      });
      
      // Botones de gestión de inventario
      document.getElementById('btn-materiales-faltantes').addEventListener('click', function(e) {
        e.preventDefault();
        var seccionFaltantes = document.getElementById('seccion-faltantes');
        if (seccionFaltantes.style.display === 'none') {
          seccionFaltantes.style.display = 'block';
        } else {
          seccionFaltantes.style.display = 'none';
        }
      });
    });
    
    function showWelcomeSection() {
      hideAllSections();
      document.getElementById('seccion-bienvenida').style.display = 'block';
    }
    
    function hideAllSections() {
      document.getElementById('seccion-personal').style.display = 'none';
      document.getElementById('seccion-produccion').style.display = 'none';
      document.getElementById('seccion-productos').style.display = 'none';
      document.getElementById('seccion-inventario').style.display = 'none';
      
      document.getElementById('grafico-produccion').style.display = 'none';
      document.getElementById('grafico-diario').style.display = 'none';
      document.getElementById('grafico-reconstruccion').style.display = 'none';
      
      document.getElementById('seccion-faltantes').style.display = 'none';
    }
    
    function hideAllSubsections() {
      var subsections = [
        'subseccion-crear',
        'subseccion-ver',
        'subseccion-actualizar',
        'subseccion-eliminar'
      ]; 
      
      subsections.forEach(function(id) {
        document.getElementById(id).style.display = 'none';
      });
    }
  </script>
</body>
</html>
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "industro_uno";

    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

    if (!$enlace) {
        die("Error de conexión: " . mysqli_connect_error());
    }       

    $enlace -> set_charset("utf8");
    
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
  <div class="bienvenida-admin">
</div>

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
    <li><a href="#" class="waves-effect" id="btn-produccion"><i class="material-icons">bar_chart</i>Estadisticas</a></li>
    <li><a href="#" class="waves-effect" id="btn-productos"><i class="material-icons">shopping_cart</i>Gestión de Productos</a></li>
    <li><a href="#" class="waves-effect" id="btn-inventario"><i class="material-icons">inventory</i>Inventario de Materiales</a></li>
  </ul>

  <!-- Contenido principal -->
  <div class="container">
    <!-- Sección de Bienvenida (agregado ID) -->
    <div id="seccion-bienvenida">
        <img src="/working/imagenes/admin-avatar.png" alt="Admin Avatar">
    </div>

    <!-- Sección: Gestión de Personal -->
    <div id="seccion-personal" class="grafico-container" style="display: none;">
      <div class="card">
        <div class="card-content">
          <span class="card-title">Gestión de Personal</span>
          <!-- <div class="input-field">
            <input type="text" id="buscar-personal" placeholder="Buscar personal existente">
            <label for="buscar-personal">Buscar Personal</label>
          </div> -->
          <a href="../authentication/registro.php" style="text-decoration: none;">
            <div class="card blue lighten-5 z-depth-1" style="padding: 20px; margin-top: 20px;">
              <h6 class="blue-text text-darken-4"><i class="material-icons left">person_add</i>Registrar nuevo personal</h6>
              <p>Desde esta sección podrás ingresar nuevos empleados a la base de datos de la empresa.</p>
            </div>
          </a>
          <div class="card blue lighten-5 z-depth-1 hoverable" style="padding: 20px; margin-top: 20px; cursor: pointer;"onclick="toggleTablaPersonal()">
            <h6 class="blue-text text-darken-4">
              <i class="material-icons left">group</i>Personal Registrado
            </h6>
            <p>Visualiza todos los empleados registrados en el sistema.</p>
            <!-- Texto "MOSTRAR/OCULTAR" como indicador (opcional) -->
            <div class="blue-text text-darken-2" style="margin-top: 10px;">
              <!-- <i class="material-icons tiny">touch_app</i> HAZ CLICK PARA MOSTRAR/OCULTAR -->
            </div>
          </div>
          <div class="tabla_personal" id="tabla_personal" style="display: none;">
            <table class="table table-hover">
              <div class="input-field" style="margin-top: 20px;">
                <input type="text" id="buscar-personal-tabla" placeholder="Buscar por nombre, apellido, usuario...">
                <label for="buscar-personal-tabla">Buscar en personal registrado</label>
              </div>
              <thead>
                <tr>
                  <th>id</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Usuario</th>
                  <th>Correo</th>
                  <th>Tipo de documento</th>
                  <th>Documento</th>
                  <th>Cargo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                 $sql = "SELECT id, nombre, apellido, nomUsuario, email, tipoDocumento, numeroDocumento, id_rol FROM registro";
                 $result = mysqli_query($enlace, $sql);

                 if($result && mysqli_num_rows($result) > 0){
                  while  ($row = mysqli_fetch_assoc($result)){
                    echo "<tr>
                      <td>{$row['id']}</td>

                      <td class='editable' data-field='nombre' data-id='{$row['id']}'>{$row['nombre']}</td>

                      <td class='editable' data-field='apellido' data-id='{$row['id']}'>{$row['apellido']}</td>

                      <td class='editable' data-field='nomUsuario' data-id='{$row['id']}'>{$row['nomUsuario']}</td>

                      <td class='editable' data-field='email' data-id='{$row['id']}'>{$row['email']}</td>




                      <td>
                        <select class='editable-select' data-field='tipoDocumento' data-id='{$row['id']}'>

                          <option value='Cédula de Ciudadanía'".($row['tipoDocumento']=='Cédula de Ciudadanía'?' selected':'').">Cédula de Ciudadanía</option>
                          
                          <option value='Tarjeta de Identidad'".($row['tipoDocumento']=='Tarjeta de Identidad'?' selected':'').">Tarjeta de identidad</option>

                          <option value='Pasaporte'".($row['tipoDocumento']=='Pasaporte'?' selected':'').">Pasaporte</option>
                        </select>

                      </td>
                      <td class='editable' data-field='numeroDocumento' data-id='{$row['id']}'>{$row['numeroDocumento']}</td>
                      
                      <td>
                        <select class='editable-select' data-field='id_rol' data-id='{$row['id']}'>
                          <option value='1'".($row['id_rol']==1?' selected':'').">Administrador</option>

                          <option value='2'".($row['id_rol']==2?' selected':'').">Colaborador</option>
                        </select>
                      </td>
                      <td>
                        <button class='btn-small blue darken-1 save-btn' data-id='{$row['id']}' style='display:none;'>
                          <i class='material-icons left'>save</i> Guardar
                        </button>
                        <button class='btn-small yellow darken-1 delete-btn' data-id='{$row['id']}'>
                          <i class='material-icons left'>delete</i> Borrar
                        </button>
                       </td>
                    </tr>";
                  }
                 }
                ?>
              </tbody>
            </table>
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
          
        
          <div id="subseccion-ver" style="display: none; margin-top: 20px;">
            <h5>Ver Productos</h5>
            <div class="input-field">
              <input type="text" id="buscar-producto" placeholder="Buscar producto...">
              <label for="buscar-producto">Buscar Producto</label>
            </div>
            
            <table class="highlight" id="tablaBuscador">
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
                  <?php
                    $consulta = "CALL sp_obtener_productos()";
                    $resultado = mysqli_query($enlace, $consulta);

                    while ($producto = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($producto['nomProd']) . "</td>";
                        echo "<td>" . htmlspecialchars($producto['cantProd']) . "</td>";

                        echo "<td>$" . number_format($producto['precio'], 2) . "</td>";

                        
                        echo "<td>" . htmlspecialchars($producto['fechaActu']) . "</td>";
                        
                        echo '<td><button class="btn blue"><i class="material-icons">visibility</i></button></td>';
                        echo "</tr>";
                    }
                    mysqli_next_result($enlace);
                    ?>
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
              <table class="product-update-table" id="tablaActualizar">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Cantidad</th>
                  <th>precio</th>
                  <th>Fecha Actualización</th>
                  <th>Foto</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $consulta = "CALL sp_obtener_productos()";
              $resultado = mysqli_query($enlace, $consulta);

              while ($producto = mysqli_fetch_assoc($resultado)) {
                  echo '<tr>';
                  echo '<form class="form-actualizar-producto">';
                  echo '<td>'.$producto['idProd'].'<input type="hidden" name="idProd" value="'.$producto['idProd'].'"></td>';
                  echo '<td><input type="text" name="nomProd" value="'.htmlspecialchars($producto['nomProd']).'" required></td>';
                  echo '<td><input type="number" name="cantProd" value="'.htmlspecialchars($producto['cantProd']).'" required></td>';
                  echo '<td><input type="number" step="0.01" name="precio" value="'.htmlspecialchars($producto['precio'] ?? '').'" required></td>';
                  echo '<td>'.$producto['fechaActu'].'</td>';
                  echo '<td>
                          <div class="file-field input-field">
                            <div class="btn waves-effect waves-light btn-file-update">
                              <span>Subir foto</span>
                              <input type="file" name="foto" accept="image/*" style="display: none;">
                            </div>
                            <input type="hidden" name="foto_antigua" value="'.htmlspecialchars($producto['foto'] ?? '').'">
                          </div>
                        </td>';
                  echo '<td>
                          <button type="submit" class="btn waves-effect waves-light btn-save-update">
                            <i class="material-icons right">save</i>Guardar
                          </button>
                        </td>';
                  echo '</form>';
                  echo '</tr>';
              }
              mysqli_next_result($enlace);
              ?>
              </tbody>

            </table>

            <div class="card blue lighten-5" style="padding: 20px; margin-top: 20px;">
              <p>Seleccione un producto de la lista para actualizar sus datos.</p>
            </div>
          </div>
          
              <!-- Subsección: Eliminar Producto -->

<!-- Subsección: Eliminar Producto -->
<!-- Subsección: Eliminar Producto -->
<div id="subseccion-eliminar" style="display: none; margin-top: 20px;">
    <h5>Eliminar Producto</h5>
    
    <div id="mensaje-eliminar"></div>
    
    <table class="striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-productos">
            <?php
            $query = "SELECT idProd, nomProd, cantProd, precio FROM inventario";
            $result = mysqli_query($enlace, $query);
            
            while($row = mysqli_fetch_assoc($result)){
                echo '<tr id="fila-'.$row['idProd'].'">
                    <td>'.htmlspecialchars($row['nomProd']).'</td>
                    <td>'.$row['cantProd'].'</td>
                    <td>$'.number_format($row['precio'], 2).'</td>
                    <td>
                        <button onclick="eliminarProducto('.$row['idProd'].')" 
                                class="btn red">
                            <i class="material-icons">delete</i>
                        </button>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>


<script>
// function eliminarProducto(id) {
//     if(!confirm('¿Seguro que deseas eliminar este producto?')) {
//         return false;
//     }

//     var formData = new FormData();
//     formData.append('id', id);

//     fetch('/working/procedimientos/eliminar_producto.php', {
//         method: 'POST',
//         body: formData
//     })
//     .then(response => response.json())
//     .then(data => {
//         if(data.estado == 'exito') {
//             var fila = document.getElementById('fila-'+id);
//             fila.style.transition = 'opacity 0.5s';
//             fila.style.opacity = '0';
//             setTimeout(function() {
//                 fila.remove();
//                 document.getElementById('mensaje-eliminar').innerHTML = 
//                     '<div class="card-panel green lighten-4">'+data.mensaje+'</div>';
//             }, 500);
//         } else {
//             document.getElementById('mensaje-eliminar').innerHTML = 
//                 '<div class="card-panel red lighten-4">Error: '+data.mensaje+'</div>';
//         }
//     })
//     .catch(error => {
//         console.error("Error en el fetch eliminar_producto:", error);
//         document.getElementById('mensaje-eliminar').innerHTML = 
//             '<div class="card-panel red lighten-4">Error de conexión: ' + error.message + '</div>';
//     });


//     return false;
// }

</script>
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
          <!-- Sección de materiales faltantes  -->
        <div id="seccion-faltantes" style="display: none; margin-top: 20px;">
          <div class="card" style="background-color: #fff8e1 !important;">
            <div class="card-content">
              <span class="card-title">Materiales con Stock Bajo o Faltante</span>
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

       <!-- Sección de materiales faltantes -->
<div id="seccion-faltantes" style="display: none; margin-top: 20px;">
  <div class="card" style="background-color: #fff8e1 !important;">
    <div class="card-content">
      <span class="card-title">Materiales con Stock Bajo o Faltante</span>
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
      <div class="center-align">
        <a class="waves-effect waves-light btn blue" id="btn-solicitar-materiales">
          <i class="material-icons left">local_shipping</i>SOLICITAR MATERIALES
        </a>
      </div>
    </div>
  </div>
</div>
<!-- Sección de agregar materiales -->
<div id="agregar-material" style="display: none; margin-top: 20px;">
  <div class="card" style="background-color: #fff8e1 !important;">
    <div class="card-content">
      <span class="card-title">Agregue un material nuevo a inventario</span>
      
      <!-- Formulario para agregar material -->
      <form id="form-agregar-material">
        <div class="row">
          <!-- Campo Material -->
          <div class="input-field col s12">
            <input id="nombre-material" type="text" class="validate" required>
            <label for="nombre-material">Material*</label>
          </div>
          
          <!-- Campo Categoría -->
          <div class="input-field col s12">
            <input id="categoria-material" type="text" class="validate" required>
            <label for="categoria-material">Categoría*</label>
          </div>
          
          <!-- Campo Stock Actual -->
          <div class="input-field col s12 m6">
            <input id="stock-actual" type="number" min="0" class="validate" required>
            <label for="stock-actual">Stock Actual*</label>
          </div>
          
          <!-- Campo Stock Mínimo -->
          <div class="input-field col s12 m6">
            <input id="stock-minimo" type="number" min="0" class="validate" required>
            <label for="stock-minimo">Stock Mínimo*</label>
          </div>
          
          <!-- Campo Unidad -->
          <div class="input-field col s12 m6">
            <input id="unidad-material" type="text" class="validate" required>
            <label for="unidad-material">Unidad*</label>
          </div>
          
          <!-- Campo Estado -->
          <div class="input-field col s12 m6">
            <input id="estado-material" type="text" class="validate" value="Disponible">
            <label for="estado-material">Estado</label>
          </div>
        </div>
        
        <div class="center-align">
          <button class="waves-effect waves-light btn blue" type="submit">
            <i class="material-icons left">add</i>AGREGAR MATERIAL
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>

  // Función para recargar la tabla de productos (añade esto junto con showWelcomeSection, hideAllSections, etc.)
function cargarTablaProductos() {
    fetch('/working/procedimientos/obtener_productos.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tablaBuscador tbody');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            data.forEach(producto => {
                tbody.innerHTML += `
                    <tr>
                        <td>${producto.nomProd}</td>
                        <td>${producto.cantProd}</td>
                        <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                        <td>${producto.fechaActu}</td>
                        <td><button class="btn blue"><i class="material-icons">visibility</i></button></td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Error al cargar productos:', error);
        });
}
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
    
    // Botones agregar nuevo materiales
    document.getElementById('btn-agregar-material').addEventListener('click', function(e) {
      e.preventDefault();
      var seccionFaltantes = document.getElementById('agregar-material');
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

  function toggleTablaPersonal() {
    var tabla = document.getElementById("tabla_personal");
    if (tabla.style.display === "none" || tabla.style.display === "") {
      tabla.style.display = "block";
    } else {
      tabla.style.display = "none";
    }
  }

  // BUSCADOR DE VER PERSONAL
  document.addEventListener('DOMContentLoaded', function() {
    const buscarInput = document.getElementById('buscar-personal-tabla');
    
    if(buscarInput) {
      buscarInput.addEventListener('keyup', function() {
        const valorBusqueda = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tabla-personal-completa tbody tr');
        
        filas.forEach(function(fila) {
          const textoFila = fila.textContent.toLowerCase();
          fila.style.display = textoFila.includes(valorBusqueda) ? '' : 'none';
        });
      });
    }
  });

  // BUSCADOR DE VER PRODUCTO
  document.addEventListener('DOMContentLoaded', function() {
    const filtrarProducto = document.getElementById('buscar-producto');
    
    if(filtrarProducto) {
      filtrarProducto.addEventListener('keyup', function() {
        const resultadoProducto = this.value.toLowerCase();
        const filitas = document.querySelectorAll('#tablaBuscador tbody tr');
        
        filitas.forEach(function(fila) {
          const textoFilita = fila.textContent.toLowerCase();
          fila.style.display = textoFilita.includes(resultadoProducto) ? '' : 'none';
        });
      });
    }
  });

  // BUSCADOR DE ACTUALIZAR PRODUCTO
  document.addEventListener('DOMContentLoaded', function() {
    const filtrarActualizar = document.getElementById('buscar-actualizar');
    let timeout;
    
    if(filtrarActualizar) {
      filtrarActualizar.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const terminoBusqueda = this.value.toLowerCase();
          const filas = document.querySelectorAll('#tablaActualizar tbody tr');
          
          if(terminoBusqueda === '') {
            filas.forEach(fila => fila.style.display = '');
            return;
          }
          
          filas.forEach(function(fila) {
            const nombre = fila.querySelector('input[name="nomProd"]')?.value.toLowerCase() || '';
            const cantidad = fila.querySelector('input[name="cantProd"]')?.value.toLowerCase() || '';
            const precio = fila.querySelector('input[name="precio"]')?.value.toLowerCase() || '';
            const id = fila.querySelector('td:first-child')?.textContent.toLowerCase() || '';
            
            const celdas = fila.querySelectorAll('td');
            const fechaActualizacion = celdas[4]?.textContent.toLowerCase() || '';
            
            const coincide = (
              nombre.includes(terminoBusqueda) || 
              cantidad.includes(terminoBusqueda) || 
              precio.includes(terminoBusqueda) || 
              id.includes(terminoBusqueda) ||
              fechaActualizacion.includes(terminoBusqueda)
            );
            
            fila.style.display = coincide ? '' : 'none';
          });
        }, 300);
      });
    }
  });

  // BUSCADOR DE PERSONAL (segunda implementación)
  document.addEventListener('DOMContentLoaded', function() {
    const buscarInput = document.getElementById('buscar-personal-tabla');
    
    if(buscarInput) {
      buscarInput.addEventListener('keyup', function() {
        const valorBusqueda = this.value.toLowerCase();
        const filas = document.querySelectorAll('.tabla_personal tbody tr');
        
        filas.forEach(function(fila) {
          const celdas = fila.querySelectorAll('td');
          let textoFila = '';
          
          celdas.forEach(function(celda) {
            textoFila += celda.textContent.toLowerCase() + ' ';
          });
          
          fila.style.display = textoFila.includes(valorBusqueda) ? '' : 'none';
        });
      });
    }
  });

  // Función para manejar la edición de campos
  document.addEventListener('DOMContentLoaded', function() {
    // Hacer campos editables
    document.querySelectorAll('.editable').forEach(cell => {
      cell.addEventListener('click', function(e) {
        if (document.querySelector('.edit-input-active')) {
          return;
        }
        
        const currentValue = this.textContent.trim();
        const field = this.getAttribute('data-field');
        const id = this.getAttribute('data-id');
        const originalCell = this;
        
        const tempInput = document.createElement('input');
        tempInput.type = 'text';
        tempInput.className = 'edit-input edit-input-active';
        tempInput.value = currentValue;
        
        this.innerHTML = '';
        this.appendChild(tempInput);
        tempInput.focus();
        
        const finishEditing = () => {
          const newValue = tempInput.value.trim();
          originalCell.textContent = newValue;
          
          if (newValue !== currentValue) {
            document.querySelector(`.save-btn[data-id="${id}"]`).style.display = 'inline-block';
          }
          
          originalCell.addEventListener('click', arguments.callee);
        };
        
        tempInput.addEventListener('blur', finishEditing);
        tempInput.addEventListener('keypress', function(e) {
          if(e.key === 'Enter') {
            finishEditing();
          }
        });
        
        originalCell.removeEventListener('click', arguments.callee);
      });
    });
    
    // Manejar cambios en selects
    document.querySelectorAll('.editable-select').forEach(select => {
      select.addEventListener('change', function() {
        const id = this.getAttribute('data-id');
        document.querySelector(`.save-btn[data-id="${id}"]`).style.display = 'inline-block';
      });
    });
    
    // Guardar cambios
    document.querySelectorAll('.save-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const data = {
          id: id,
          nombre: '',
          apellido: '',
          nomUsuario: '',
          email: '',
          tipoDocumento: '',
          numeroDocumento: '',
          id_rol: ''
        };
        
        document.querySelectorAll(`[data-id="${id}"]`).forEach(element => {
          const field = element.getAttribute('data-field');
          if(element.classList.contains('editable')) {
            data[field] = element.textContent.trim();
          } else if(element.classList.contains('editable-select')) {
            data[field] = element.value;
          }
        });
        
        this.innerHTML = '<i class="material-icons">hourglass_empty</i>';
        
        fetch('/working/procedimientos/actualizar_usuario.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(data)
        })
        .then(response => {
          if (!response.ok) throw new Error('Error en la respuesta del servidor');
          return response.json();
        })
        .then(data => {
          if(data.success) {
            M.toast({
              html: 'Usuario actualizado correctamente',
              classes: 'green',
              displayLength: 2000
            });
            this.innerHTML = '<i class="material-icons">check</i>';
            setTimeout(() => {
              this.style.display = 'none';
              this.innerHTML = '<i class="material-icons">save</i>';
            }, 1000);
          } else {
            throw new Error(data.error || 'Error desconocido');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          this.innerHTML = '<i class="material-icons">save</i>';
          M.toast({
            html: `Error al actualizar: ${error.message}`,
            classes: 'red',
            displayLength: 4000
          });
        });
      });
    });
    
    // Eliminar usuario
    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        
        if(confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
          fetch('/working/procedimientos/eliminar_usuario.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({id: id})
          })
          .then(response => response.json())
          .then(data => {
            if(data.success) {
              M.toast({html: 'Usuario eliminado correctamente', classes: 'green'});
              this.closest('tr').remove();
            } else {
              M.toast({html: 'Error al eliminar: ' + data.error, classes: 'red'});
            }
          })
          .catch(error => {
            M.toast({html: 'Error: ' + error, classes: 'red'});
          });
        }
      });
    });
  });

  // Función para confirmar eliminación con estilo
  function confirmarEliminacion(btn) {
    const id = btn.getAttribute('data-id');
    const nombre = btn.closest('tr').querySelector('td:nth-child(2)').textContent;
    
    if(confirm(`¿Estás seguro de eliminar a ${nombre}? Esta acción no se puede deshacer.`)) {
      btn.innerHTML = '<i class="material-icons">hourglass_empty</i> Eliminando...';
      btn.disabled = true;
      
      fetch('/working/procedimientos/eliminar_usuario.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({id: id})
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          const fila = btn.closest('tr');
          fila.style.transition = 'all 0.3s';
          fila.style.opacity = '0';
          setTimeout(() => fila.remove(), 300);
          
          M.toast({
            html: `Usuario eliminado correctamente`,
            classes: 'green',
            displayLength: 2000
          });
        } else {
          throw new Error(data.error || 'Error al eliminar');
        }
      })
      .catch(error => {
        btn.innerHTML = '<i class="material-icons">delete</i> Borrar';
        btn.disabled = false;
        M.toast({
          html: `Error: ${error.message}`,
          classes: 'red',
          displayLength: 4000
        });
      });
    }
  }

  // Función para editar fila
  function editarFila(btn) {
    const fila = btn.closest('tr');
    fila.querySelector('.btn-save').style.display = 'inline-block';
    btn.style.display = 'none';
  }

  // Formulario crear producto
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-crear-producto");

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      console.log("✅ ¡Se hizo clic en el botón y se capturó el submit!");

      const nombre = document.getElementById("nombre-producto").value;
      const cantidad = document.getElementById("cantidad").value;
      const precio = document.getElementById("precio").value;
      const foto = document.getElementById("foto") ? document.getElementById("foto").value : '';

      fetch("../procedimientos/insertar_producto.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `nombre=${encodeURIComponent(nombre)}&cantidad=${cantidad}&precio=${precio}&foto=${encodeURIComponent(foto)}`
      })
      .then(response => response.text())
      .then(data => {
        console.log("📥 Respuesta del servidor:", data);
        alert(data);
      })
      .catch(error => {
        console.error("❌ Error en fetch:", error);
      });
    });
  });

  // Eliminar producto
// Función unificada para eliminar productos
function eliminarProducto(id) {
    if(!confirm('¿Seguro que deseas eliminar este producto?')) {
        return false;
    }

    // Mostrar estado de carga
    const boton = event.target; // Capturamos el botón clickeado
    const originalHTML = boton.innerHTML;
    boton.innerHTML = '<i class="material-icons">hourglass_empty</i>';
    boton.disabled = true;

    // Enviar solicitud
    fetch('/working/procedimientos/eliminar_producto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Cambiado a formato form
        },
        body: `id=${id}` // Envía como parámetro POST tradicional
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        return response.json();
    })
    .then(data => {
        if(data.estado == 'exito') {
            // Animación para eliminar la fila
            const fila = document.getElementById('fila-'+id);
            if(fila) {
                fila.style.transition = 'opacity 0.5s';
                fila.style.opacity = '0';
                setTimeout(() => fila.remove(), 500);
            }
            
            // Mostrar notificación
            M.toast({
                html: data.mensaje,
                classes: 'green',
                displayLength: 3000
            });
        } else {
            throw new Error(data.mensaje || 'Error al eliminar');
        }
    })
    .catch(error => {
        console.error("Error:", error);
        M.toast({
            html: 'Error: ' + error.message,
            classes: 'red',
            displayLength: 4000
        });
    })
    .finally(() => {
        boton.innerHTML = originalHTML;
        boton.disabled = false;
    });

    return false;
}

  // Actualizar producto
  document.querySelectorAll('.form-actualizar-producto').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(form);

      fetch('/working/procedimientos/actualizar_producto.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        alert(data);
      })
      .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al actualizar producto.');
      });
    });
  });



  

  // Buscador actualizar producto
  document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.getElementById('buscar-actualizar');

    if (!inputBuscar) return;

    inputBuscar.addEventListener('input', function() {
      const texto = this.value.toLowerCase();
      const filas = document.querySelectorAll('#tabla-actualizar-producto tbody tr');

      filas.forEach(fila => {
        const contenido = fila.textContent.toLowerCase();
        fila.style.display = contenido.includes(texto) ? '' : 'none';
      });
    });
  });
</script>



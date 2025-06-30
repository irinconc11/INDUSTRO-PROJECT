-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-06-2025 a las 06:31:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `industro_uno`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_produccion` (IN `p_id_produccion` INT, IN `p_nueva_cantidad` INT)   BEGIN
    DECLARE v_vieja_cantidad INT;
    DECLARE v_id_prod INT;
    
    -- Obtener datos actuales
    SELECT cantidadProd, idProd INTO v_vieja_cantidad, v_id_prod
    FROM produccion
    WHERE idRegistro = p_id_produccion;
    
    IF v_id_prod IS NULL THEN
        SELECT '❌ Error: Registro no encontrado' AS mensaje;
    ELSE
        -- Actualizar producción
        UPDATE produccion
        SET cantidadProd = p_nueva_cantidad
        WHERE idRegistro = p_id_produccion;
        
        -- Ajustar inventario (diferencia entre nueva y vieja cantidad)
        UPDATE inventario
        SET cantProd = cantProd + (p_nueva_cantidad - v_vieja_cantidad),
            fechaActu = CURRENT_DATE
        WHERE idProd = v_id_prod;
        
        SELECT '🔄 Producción actualizada correctamente' AS mensaje;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_producto` (IN `p_idProd` INT, IN `p_nomProd` VARCHAR(50), IN `p_cantProd` INT, IN `p_precio` INT, IN `p_foto` VARCHAR(50))   BEGIN
    DECLARE v_existe INT;
    
    -- 1. Verificar si el producto existe
    SELECT COUNT(*) INTO v_existe FROM inventario WHERE idProd = p_idProd;
    
    IF v_existe = 1 THEN
        -- 2. Actualizar el producto
        UPDATE inventario 
        SET 
            nomProd = p_nomProd,
            cantProd = p_cantProd,
            fechaActu = CURDATE(),  
            precio= p_precio,
            foto = IFNULL(p_foto, foto)
        WHERE idProd = p_idProd;
        
        SELECT '✅ Producto actualizado correctamente.' AS mensaje;
    ELSE
        SELECT '❌ Error: El producto no existe.' AS mensaje;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_stock` (IN `p_id_mat` INT, IN `p_nom_mat` VARCHAR(500), IN `p_cant_mat` INT)   BEGIN
    UPDATE stock 
    SET 
        nom_mat = p_nom_mat,
        cant_mat = p_cant_mat,
        DateActu = CURDATE()
    WHERE id_mat = p_id_mat;
    
    SELECT CONCAT('✅ Material ID ', p_id_mat, ' actualizado.') AS mensaje;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_usuario1` (IN `p_id` INT, IN `p_nombre` VARCHAR(100), IN `p_apellido` VARCHAR(100), IN `p_nomUsuario` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_tipoDocumento` VARCHAR(50), IN `p_numeroDocumento` VARCHAR(20), IN `p_id_rol` INT)   BEGIN
    UPDATE registro SET
        nombre = p_nombre,
        apellido = p_apellido,
        nomUsuario = p_nomUsuario,
        email = p_email,
        tipoDocumento = p_tipoDocumento,
        numeroDocumento = p_numeroDocumento,
        id_rol = p_id_rol
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_produccion` (IN `p_id_produccion` INT)   BEGIN
    DECLARE v_cantidad INT;
    DECLARE v_id_prod INT;
    
    -- Obtener datos antes de eliminar
    SELECT cantidadProd, idProd INTO v_cantidad, v_id_prod
    FROM produccion
    WHERE idRegistro = p_id_produccion;
    
    IF v_id_prod IS NOT NULL THEN
        -- Eliminar registro
        DELETE FROM produccion WHERE idRegistro = p_id_produccion;
        
        -- Revertir inventario (restar la cantidad)
        UPDATE inventario
        SET cantProd = cantProd - v_cantidad,
            fechaActu = CURRENT_DATE
        WHERE idProd = v_id_prod;
        
        SELECT ' Producción eliminada e inventario ajustado' AS mensaje;
    ELSE
        SELECT ' Error: Registro no encontrado' AS mensaje;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_producto` (IN `p_id` INT)   BEGIN
    DECLARE existe INT DEFAULT 0;
    
    -- Verificar si el producto existe
    SELECT COUNT(*) INTO existe FROM inventario WHERE idProd = p_id;
    
    -- Eliminar solo si existe
    IF existe > 0 THEN
        DELETE FROM inventario WHERE idProd = p_id;
        SELECT 'Producto eliminado correctamente' AS mensaje;
    ELSE
        SELECT 'Error: El producto no existe' AS mensaje;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_stock` (IN `p_id_mat` INT)   BEGIN
    DECLARE v_nombre VARCHAR(500);
    SELECT nom_mat INTO v_nombre FROM stock WHERE id_mat = p_id_mat;
    
    DELETE FROM stock WHERE id_mat = p_id_mat;
    
    SELECT CONCAT('✅ Material "', IFNULL(v_nombre, p_id_mat), '" eliminado.') AS mensaje;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_usuario` (IN `p_id` INT)   BEGIN
    DECLARE v_existe INT;
    SELECT COUNT(*) INTO v_existe FROM registro WHERE id = p_id;
    
    IF v_existe = 1 THEN
        DELETE FROM registro WHERE id = p_id;
        SELECT 'Usuario eliminado correctamente' AS mensaje;
    ELSE
        SELECT 'Error: El usuario no existe' AS mensaje;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_produccion3` (IN `p_id` INT, IN `p_id_prod` INT, IN `p_cantidad` INT)   BEGIN
    DECLARE v_existe_producto INT;
    DECLARE v_existe_empleado INT;
    
    -- 1. Validar existencia del producto y empleado
    SELECT COUNT(*) INTO v_existe_producto FROM inventario WHERE idProd = p_id_prod;
    SELECT COUNT(*) INTO v_existe_empleado FROM registro WHERE id = p_id;
    
    -- 2. Manejo de errores
    IF v_existe_producto = 0 THEN
        SELECT '❌ Error: El producto no existe' AS mensaje;
    ELSEIF v_existe_empleado = 0 THEN
        SELECT '❌ Error: El empleado no está registrado' AS mensaje;
    ELSE
        -- 3. Insertar producción (idRegistro es AUTO_INCREMENT)
        INSERT INTO produccion (id, idProd, cantidadProd, fechaRegistro)
        VALUES (p_id, p_id_prod, p_cantidad, NOW());
        
        -- 4. Actualizar inventario
        UPDATE inventario 
        SET cantProd = cantProd + p_cantidad,
            fechaActu = CURDATE()
        WHERE idProd = p_id_prod;
        
        -- 5. Retornar éxito
        SELECT '✅ Producción registrada por empleado #' || p_id AS mensaje, 
               LAST_INSERT_ID() AS idRegistro;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_producto` (IN `p_nombre` VARCHAR(50), IN `p_cantidad` INT, IN `p_precio` INT, IN `p_foto` VARCHAR(50))   BEGIN
    INSERT INTO inventario (nomProd, cantProd, precio,fechaActu, foto)
    VALUES (p_nombre, p_cantidad,p_precio, CURDATE(), p_foto);
    
    SELECT 'Producto insertado correctamente' AS mensaje, LAST_INSERT_ID() AS id_producto;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_stock` (IN `p_nom_mat` VARCHAR(500), IN `p_cant_mat` INT)   BEGIN
    INSERT INTO stock (nom_mat, cant_mat, DateActu)
    VALUES (p_nom_mat, p_cant_mat, CURDATE());
    
    SELECT CONCAT('✅ Material "', p_nom_mat, '" agregado. ID: ', LAST_INSERT_ID()) AS mensaje;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_produccion` ()   BEGIN
    SELECT 
        p.idRegistro,
        r.nombre AS empleado,
        i.nomProd AS producto,
        p.cantidadProd,
        p.fechaRegistro,
        i.cantProd AS stock_actual
    FROM produccion p
    JOIN registro r ON p.id = r.id
    JOIN inventario i ON p.idProd = i.idProd
    ORDER BY p.fechaRegistro DESC, p.idRegistro DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_produccion_por_empleado` (IN `p_id_empleado` INT)   BEGIN
    -- Verificar si el empleado existe
    IF NOT EXISTS (SELECT 1 FROM registro WHERE id = p_id_empleado) THEN
        SELECT 'Error: El empleado no existe' AS mensaje;
    ELSE
        -- Obtener todos los registros de producción del empleado
        SELECT 
            p.idRegistro AS id_produccion,
            i.nomProd AS producto,
            p.cantidadProd AS cantidad,
            p.fechaRegistro AS fecha,
            CONCAT(r.nombre, ' ', r.apellido) AS empleado,
            i.cantProd AS stock_actual
        FROM produccion p
        JOIN inventario i ON p.idProd = i.idProd
        JOIN registro r ON p.id = r.id
        WHERE p.id = p_id_empleado
        ORDER BY p.fechaRegistro DESC;
        
        -- También devolver el total producido por el empleado
        SELECT 
            SUM(p.cantidadProd) AS total_producido,
            COUNT(p.idRegistro) AS registros_totales
        FROM produccion p
        WHERE p.id = p_id_empleado;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_productos` ()   BEGIN
    SELECT idProd, nomProd, cantProd, fechaActu,precio, foto
    FROM inventario;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_producto_por_id` (IN `p_id` INT)   BEGIN
    SELECT idProd, nomProd, cantProd, fechaActu,precio, foto
    FROM inventario
    WHERE idProd = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_stock` ()   BEGIN
    SELECT 
        id_mat AS 'ID',
        nom_mat AS 'Material', 
        cant_mat AS 'Cantidad', 
        DateActu AS 'Última Actualización'
    FROM stock;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_usuario_por_id` (IN `p_id` INT)   BEGIN
    SELECT 
        r.id,
        r.nombre,
        r.apellido,
        r.nomUsuario,
        r.email,
        r.tipoDocumento,
        r.numeroDocumento,
        r.id_rol,
        ro.rol AS nombre_rol
    FROM registro r
    JOIN rol ro ON r.id_rol = ro.id_rol
    WHERE r.id = p_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `idProd` int(11) NOT NULL,
  `nomProd` varchar(50) NOT NULL,
  `cantProd` int(11) NOT NULL,
  `fechaActu` date NOT NULL,
  `precio` int(11) NOT NULL,
  `foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`idProd`, `nomProd`, `cantProd`, `fechaActu`, `precio`, `foto`) VALUES
(2, 'faldasssssssssssssss', 6666666, '2025-06-17', 666666, ''),
(4, 'boinas', 700, '2025-06-17', 35, ''),
(5, 'bufandas', 200, '2025-06-17', 188, ''),
(6, 'cinturones', 500, '2025-06-17', 1000, ''),
(7, 'bolsos', 100, '2025-06-17', 500, ''),
(8, 'tacones', 20, '2025-06-17', 50, ''),
(9, 'camisa', 30, '2025-06-17', 33, ''),
(10, 'gaban', 100000, '2025-06-17', 100, ''),
(11, 'corbata', 400, '2025-06-17', 38, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion`
--

CREATE TABLE `produccion` (
  `idRegistro` int(11) NOT NULL,
  `nomUsuario` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `cantidadProd` int(11) NOT NULL,
  `fechaRegistro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `produccion`
--

INSERT INTO `produccion` (`idRegistro`, `nomUsuario`, `id`, `idProd`, `cantidadProd`, `fechaRegistro`) VALUES
(1, 0, 1, 2, 4000, '0000-00-00'),
(2, 0, 4, 2, 62, '0000-00-00'),
(3, 0, 1, 2, 4000, '0000-00-00'),
(5, 0, 1, 4, 3000, '2025-06-03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nomUsuario` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tipoDocumento` varchar(50) NOT NULL,
  `numeroDocumento` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id`, `nombre`, `apellido`, `nomUsuario`, `email`, `tipoDocumento`, `numeroDocumento`, `password`, `id_rol`) VALUES
(1, 'Ismael', 'Rincón', 'ismaelRincon', 'maelrc11@gmail.com', 'Tarjeta de Identidad', '1001331889', '$2y$10$IWmVg1xbVcpZLrs3NntUzua6yJgI3XbiAXJbOJ51ZdKHf4tiuR1oy', 1),
(2, 'Edgar ', 'Cardona', 'edgarCardona', 'edgar@gmail.com', 'Cédula de Ciudadanía', '123456', '$2y$10$/yEIX4lpRUYaDyfal8JqEOX7NbGTu4U2Qa0yVm/4R0BjsA2Oj5aBO', 2),
(3, 'alejandra', 'castañeda', 'MALEJAROD12', 'malejarod12@gmail.com', 'Cédula de Ciudadanía', '1034577200', '$2y$10$iX5DVXHdqS70kgn0ez0m2uZIS1JlTRmHemKUYIRYrOrNFo39lOcna', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id_mat` int(11) NOT NULL,
  `nom_mat` varchar(500) NOT NULL,
  `cant_mat` int(11) NOT NULL,
  `DateActu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`id_mat`, `nom_mat`, `cant_mat`, `DateActu`) VALUES
(2, 'Saten', 10, '2025-05-20'),
(3, 'Cuellos', 4, '2025-05-20'),
(5, 'Cuellos', 4, '2025-05-20'),
(6, 'Pretina', 3, '2025-05-21'),
(7, 'Lana', 2, '2025-05-20'),
(9, 'Lija', 900, '2025-05-21');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`idProd`);

--
-- Indices de la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD PRIMARY KEY (`idRegistro`),
  ADD KEY `id` (`id`),
  ADD KEY `idProd` (`idProd`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_registros_roles` (`id_rol`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_mat`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `idProd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `idRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id_mat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD CONSTRAINT `idProd` FOREIGN KEY (`idProd`) REFERENCES `inventario` (`idProd`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro`
--
ALTER TABLE `registro`
  ADD CONSTRAINT `fk_registros_roles` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

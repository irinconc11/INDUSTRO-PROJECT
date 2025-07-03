-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-07-2025 a las 18:02:19
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_producto` (IN `p_idProd` INT)   BEGIN
    DECLARE existe INT DEFAULT 0;
    
    SELECT COUNT(*) INTO existe FROM inventario WHERE idProd = p_idProd;
    
    IF existe > 0 THEN
        DELETE FROM inventario WHERE idProd = p_idProd;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_distribucion_productos` (IN `p_id_usuario` INT)   BEGIN
    SELECT 
        i.nomProd as producto, 
        SUM(p.cantidadProd) as total 
    FROM Produccion p
    JOIN Inventario i ON p.idProd = i.idProd
    WHERE p.id = p_id_usuario
    GROUP BY i.nomProd;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_produccion_diaria` (IN `p_id_usuario` INT)   BEGIN
    SELECT 
        DATE_FORMAT(fechaRegistro, '%Y-%m-%d') as fecha, 
        SUM(cantidadProd) as total 
    FROM Produccion 
    WHERE fechaRegistro BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
    AND id = p_id_usuario
    GROUP BY fecha;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_produccion_mensual` (IN `p_id_usuario` INT)   BEGIN
    SELECT 
        WEEK(fechaRegistro, 1) as semana, 
        SUM(cantidadProd) as total 
    FROM Produccion 
    WHERE fechaRegistro BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()
    AND id = p_id_usuario
    GROUP BY semana;
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
(6, 'cinturones', 2326080, '2025-07-03', 1000, ''),
(7, 'bolsos', 2147483647, '2025-07-03', 500, ''),
(8, 'tacones', 374, '2025-07-02', 50, ''),
(9, 'camisa', 288919, '2025-07-03', 33, ''),
(10, 'gaban', 44544888, '2025-07-02', 100, ''),
(11, 'corbata', 14000500, '2025-07-03', 38, ''),
(12, '3', 2147483647, '2025-07-03', 3, ''),
(13, 'Protector Lunar', 10, '2025-06-18', 110, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion`
--

CREATE TABLE `produccion` (
  `idRegistro` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `cantidadProd` int(255) NOT NULL,
  `fechaRegistro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `produccion`
--

INSERT INTO `produccion` (`idRegistro`, `id`, `idProd`, `cantidadProd`, `fechaRegistro`) VALUES
(1, 7, 7, 2147483647, '2025-06-29'),
(2, 7, 7, 2147483647, '2025-06-29'),
(3, 7, 7, 2147483647, '2025-06-29'),
(4, 7, 7, 2147483647, '2025-06-29'),
(5, 7, 7, 2147483647, '2025-06-29'),
(6, 7, 7, 2147483647, '2025-06-29'),
(7, 7, 7, 2147483647, '2025-06-29'),
(8, 7, 7, 2147483647, '2025-06-29'),
(9, 7, 7, 2147483647, '2025-06-29'),
(10, 7, 6, 222, '2025-06-30'),
(11, 7, 7, 333, '2025-07-01'),
(12, 7, 6, 4, '2025-07-01'),
(13, 7, 7, 1111111, '2025-07-02'),
(14, 7, 9, 144444, '2025-07-02'),
(15, 7, 9, 144444, '2025-07-02'),
(16, 7, 10, 222, '2025-07-02'),
(17, 7, 10, 222, '2025-07-02'),
(18, 7, 8, 66, '2025-07-02'),
(19, 7, 8, 66, '2025-07-02'),
(20, 7, 10, 22222222, '2025-07-02'),
(21, 7, 10, 22222222, '2025-07-02'),
(22, 7, 6, 2, '2025-07-02'),
(23, 7, 6, 2, '2025-07-02'),
(24, 7, 6, 2, '2025-07-02'),
(25, 7, 6, 1, '2025-07-02'),
(26, 7, 6, 3, '2025-07-02'),
(27, 7, 6, 3, '2025-07-02'),
(28, 7, 6, 3, '2025-07-02'),
(29, 7, 6, 1, '2025-07-02'),
(30, 7, 7, 1, '2025-07-03'),
(31, 7, 11, 14000000, '2025-07-03'),
(32, 7, 12, 2147483647, '2025-07-03'),
(33, 7, 6, 2, '2025-07-03'),
(34, 7, 7, 1, '2025-07-03'),
(35, 7, 9, 1, '2025-07-03'),
(36, 7, 6, 1, '2025-07-03'),
(37, 7, 7, 5, '2025-07-03'),
(38, 7, 7, 5, '2025-07-03');

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
(2, 'Edgar ', 'Cardona', 'edgarCardona', 'edgar@gmail.com', 'Cédula de Ciudadanía', '123456', '$2y$10$/yEIX4lpRUYaDyfal8JqEOX7NbGTu4U2Qa0yVm/4R0BjsA2Oj5aBO', 2),
(3, 'alejandra', 'castañeda', 'MALEJAROD12', 'malejarod12@gmail.com', 'Cédula de Ciudadanía', '1034577200', '$2y$10$iX5DVXHdqS70kgn0ez0m2uZIS1JlTRmHemKUYIRYrOrNFo39lOcna', 1),
(4, 'Ñengo', 'Flow', 'Ñengoso', 'nengoso@jajaja.com', 'Cédula de Ciudadanía', '1029140579', '$2y$10$dKZZj0Xn1pBjkiGOcvh16eTofM4JiERgDDZDhSZex3HJhyGBEnPyy', 1),
(5, 'NovaY', 'Jory', 'Nova', 'novayjory@gmail.com', 'Cédula de Ciudadanía', '1029140579', '$2y$10$Q4nysvUWYfFKK5wH1m07f.ytArST/EViuIo9ORJGnbuRrmrVyEqeS', 2),
(6, 'Carito', 'Caicerdo', 'Carito', 'carito@gmail.com', 'Cédula de Ciudadanía', '53166464', '$2y$10$HPLUtQ8uGNfgg/YrWEJaauWzP7rtrOCWQ7PjnQMHLXjwke0wrqjMy', 1),
(7, 'Rocky', 'Godzilla', 'Rocky', 'rocky@gmail.com', 'Cédula de Ciudadanía', '1029140579', '$2y$10$T4W7hKVt0IAvv7dilvwna.h3R8wS8fGkp/UoxxE9HxjBZSN7jXpLe', 2);

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
(3, 'Cuellos', 4, '2025-05-20'),
(5, 'Cuellos', 4, '2025-05-20'),
(9, 'Lija', 900, '2025-05-21');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_materiales_bajo_stock_detallado`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_materiales_bajo_stock_detallado` (
`id_mat` int(11)
,`nom_mat` varchar(500)
,`cant_mat` int(11)
,`DateActu` date
,`estado` varchar(10)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_materiales_bajo_stock_detallado`
--
DROP TABLE IF EXISTS `vw_materiales_bajo_stock_detallado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_materiales_bajo_stock_detallado`  AS SELECT `stock`.`id_mat` AS `id_mat`, `stock`.`nom_mat` AS `nom_mat`, `stock`.`cant_mat` AS `cant_mat`, `stock`.`DateActu` AS `DateActu`, CASE WHEN `stock`.`cant_mat` <= 0 THEN 'AGOTADO' WHEN `stock`.`cant_mat` < 100 THEN 'BAJO STOCK' END AS `estado` FROM `stock` WHERE `stock`.`cant_mat` <= 0 OR `stock`.`cant_mat` < 100 ORDER BY `stock`.`cant_mat` ASC ;

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
  MODIFY `idProd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `idRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id_mat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD CONSTRAINT `idProd` FOREIGN KEY (`idProd`) REFERENCES `inventario` (`idProd`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro`
--
ALTER TABLE `registro`
  ADD CONSTRAINT `fk_registros_roles` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

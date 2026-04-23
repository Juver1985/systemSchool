-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: bdcalificaciones
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acudiente_estudiante`
--

DROP TABLE IF EXISTS `acudiente_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acudiente_estudiante` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_acudiente` bigint NOT NULL,
  `id_estudiante` bigint NOT NULL,
  `parentesco` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acudiente_estudiante_index_0` (`id_acudiente`,`id_estudiante`),
  KEY `id_estudiante` (`id_estudiante`),
  CONSTRAINT `acudiente_estudiante_ibfk_1` FOREIGN KEY (`id_acudiente`) REFERENCES `acudientes` (`id_acudiente`),
  CONSTRAINT `acudiente_estudiante_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acudiente_estudiante`
--

LOCK TABLES `acudiente_estudiante` WRITE;
/*!40000 ALTER TABLE `acudiente_estudiante` DISABLE KEYS */;
INSERT INTO `acudiente_estudiante` VALUES (1,3,6,'Acudiente'),(2,3,7,'Acudiente'),(3,3,8,'Acudiente'),(4,4,8,'Acudiente');
/*!40000 ALTER TABLE `acudiente_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acudientes`
--

DROP TABLE IF EXISTS `acudientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acudientes` (
  `id_acudiente` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint NOT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_acudiente`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `acudientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acudientes`
--

LOCK TABLES `acudientes` WRITE;
/*!40000 ALTER TABLE `acudientes` DISABLE KEYS */;
INSERT INTO `acudientes` VALUES (1,1,'3107760588'),(2,2,'3053988951'),(3,3,'3107760588'),(4,4,'3122343445'),(5,5,'323454567'),(6,6,'3232345455'),(7,9,'312344555'),(8,10,'3103454356'),(9,12,'312332434'),(10,13,'3107760588'),(11,14,'32345345345'),(12,16,'3243545677'),(13,17,'342354435'),(14,18,'3234455666'),(15,20,'3213232'),(16,26,'321344345'),(17,32,'3123354345');
/*!40000 ALTER TABLE `acudientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `id_curso` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Ej: 9A',
  `grado` int NOT NULL,
  `anio` int NOT NULL COMMENT 'Ej: 2026',
  `jornada` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Mañana',
  PRIMARY KEY (`id_curso`),
  UNIQUE KEY `cursos_index_1` (`nombre`,`anio`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (1,'Sexto',601,2026,'Mañana'),(2,'Septimo',701,2026,'Mañana'),(3,'Octavo',801,2026,'Mañana'),(4,'Noveno',901,2026,'Mañana'),(5,'Decimo',101,2026,'Mañana'),(6,'Once',1101,2026,'Mañana');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docente_materia_curso`
--

DROP TABLE IF EXISTS `docente_materia_curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `docente_materia_curso` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_docente_usuario` bigint NOT NULL COMMENT 'Usuario con rol DOCENTE',
  `id_materia` bigint NOT NULL,
  `id_curso` bigint NOT NULL,
  `anio` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `docente_materia_curso_index_3` (`id_docente_usuario`,`id_materia`,`id_curso`,`anio`),
  KEY `id_materia` (`id_materia`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `docente_materia_curso_ibfk_1` FOREIGN KEY (`id_docente_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `docente_materia_curso_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`),
  CONSTRAINT `docente_materia_curso_ibfk_3` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docente_materia_curso`
--

LOCK TABLES `docente_materia_curso` WRITE;
/*!40000 ALTER TABLE `docente_materia_curso` DISABLE KEYS */;
INSERT INTO `docente_materia_curso` VALUES (1,2,1,1,2026),(6,8,3,3,2026),(5,16,8,1,2026),(4,19,6,2,2026),(3,21,5,1,2026);
/*!40000 ALTER TABLE `docente_materia_curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiantes` (
  `id_estudiante` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint NOT NULL,
  `codigo_estudiantil` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `grado_actual` int DEFAULT NULL,
  PRIMARY KEY (`id_estudiante`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  UNIQUE KEY `codigo_estudiantil` (`codigo_estudiantil`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` VALUES (6,35,'TI107523569','2012-02-07',601),(7,36,'TI107589685','2007-04-08',601),(8,37,'TI1075356486','2011-04-03',601);
/*!40000 ALTER TABLE `estudiantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluaciones`
--

DROP TABLE IF EXISTS `evaluaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluaciones` (
  `id_evaluacion` bigint NOT NULL AUTO_INCREMENT,
  `id_docente_materia_curso` bigint NOT NULL,
  `id_periodo` bigint NOT NULL,
  `titulo` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo` varchar(15) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'OTRO' COMMENT 'TAREA|QUIZ|EXAMEN|PROYECTO|OTRO',
  `fecha` date NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL COMMENT '0..100',
  PRIMARY KEY (`id_evaluacion`),
  KEY `id_docente_materia_curso` (`id_docente_materia_curso`),
  KEY `id_periodo` (`id_periodo`),
  CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`id_docente_materia_curso`) REFERENCES `docente_materia_curso` (`id`),
  CONSTRAINT `evaluaciones_ibfk_2` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluaciones`
--

LOCK TABLES `evaluaciones` WRITE;
/*!40000 ALTER TABLE `evaluaciones` DISABLE KEYS */;
INSERT INTO `evaluaciones` VALUES (3,4,1,'Ensayo','TAREA','2026-03-11',15.00),(4,4,1,'Video','TAREA','2026-02-21',50.00),(5,4,1,'Examén','QUIZ','2026-04-22',35.00),(6,1,1,'Ejercicios Trigometria','TAREA','2026-02-18',10.00);
/*!40000 ALTER TABLE `evaluaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materias` (
  `id_materia` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `intensidad_horaria` int DEFAULT NULL,
  PRIMARY KEY (`id_materia`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
INSERT INTO `materias` VALUES (1,'Matematicas',4),(2,'Biologia',4),(3,'Fisica',4),(4,'Sociales',4),(5,'Informatica',4),(6,'Ingles',4),(7,'Filosofia',4),(8,'Literatura',4),(9,'Religion',4),(10,'Historia',4),(11,'Ciencias Naturalez',4);
/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matriculas`
--

DROP TABLE IF EXISTS `matriculas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matriculas` (
  `id_matricula` bigint NOT NULL AUTO_INCREMENT,
  `id_estudiante` bigint NOT NULL,
  `id_curso` bigint NOT NULL,
  `fecha_matricula` date NOT NULL,
  `estado` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ACTIVA' COMMENT 'ACTIVA|RETIRADA',
  PRIMARY KEY (`id_matricula`),
  UNIQUE KEY `matriculas_index_2` (`id_estudiante`,`id_curso`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matriculas`
--

LOCK TABLES `matriculas` WRITE;
/*!40000 ALTER TABLE `matriculas` DISABLE KEYS */;
INSERT INTO `matriculas` VALUES (2,6,2,'2026-04-21','ACTIVA'),(3,8,2,'2026-04-21','ACTIVA'),(4,7,2,'2026-04-21','ACTIVA'),(5,6,1,'2026-04-21','ACTIVA'),(6,8,1,'2026-04-21','ACTIVA'),(8,7,1,'2026-04-21','ACTIVA');
/*!40000 ALTER TABLE `matriculas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas`
--

DROP TABLE IF EXISTS `notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas` (
  `id_nota` bigint NOT NULL AUTO_INCREMENT,
  `id_evaluacion` bigint NOT NULL,
  `id_matricula` bigint NOT NULL,
  `valor` decimal(5,2) NOT NULL COMMENT 'Escala 0-5 o 0-100',
  `observacion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id_nota`),
  UNIQUE KEY `notas_index_5` (`id_evaluacion`,`id_matricula`),
  KEY `id_matricula` (`id_matricula`),
  CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_evaluacion`) REFERENCES `evaluaciones` (`id_evaluacion`),
  CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`id_matricula`) REFERENCES `matriculas` (`id_matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas`
--

LOCK TABLES `notas` WRITE;
/*!40000 ALTER TABLE `notas` DISABLE KEYS */;
INSERT INTO `notas` VALUES (1,3,2,5.00,'Presento el ensayo con los criterios solicitados','2026-04-22 04:26:09'),(2,3,3,2.50,'Falto mas complemente de acuerdo a los criterios solicitados','2026-04-22 04:26:09'),(3,3,4,4.50,'Presento el ensayo con los criterios solicitados, falto algo de presentación','2026-04-22 04:26:09'),(4,4,2,5.00,'No presento el video ','2026-04-22 04:29:28'),(5,4,3,3.50,'Presento pero falto presentación en el trabajo mas calidad','2026-04-22 04:29:28'),(6,4,4,4.20,'Muy bien presento el video, un poco mas de presentación','2026-04-22 04:29:28'),(7,5,2,5.00,'Aprobo ','2026-04-22 04:32:12'),(8,5,3,3.00,'Aprobo','2026-04-22 04:32:12'),(9,5,4,4.80,'Aprobo','2026-04-22 04:32:12'),(10,6,5,5.00,'Presento a medias los ejercicios','2026-04-22 04:48:16'),(11,6,6,2.00,'Los ejercicios presentado no muestran procesos para dar la solución','2026-04-22 04:48:16'),(12,6,8,1.50,'Presento 2 de 6 ejercicios y erroneos','2026-04-22 04:48:16');
/*!40000 ALTER TABLE `notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `periodos`
--

DROP TABLE IF EXISTS `periodos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periodos` (
  `id_periodo` bigint NOT NULL AUTO_INCREMENT,
  `anio` int NOT NULL,
  `nombre` varchar(20) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'P1, P2, P3, P4',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `cerrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_periodo`),
  UNIQUE KEY `periodos_index_4` (`anio`,`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodos`
--

LOCK TABLES `periodos` WRITE;
/*!40000 ALTER TABLE `periodos` DISABLE KEYS */;
INSERT INTO `periodos` VALUES (1,2026,'I Trimestre','2026-02-09','2026-03-31',0),(2,2026,'II Trimestre','2026-04-01','2026-06-30',0),(3,2026,'III Trimestre','2026-07-01','2026-09-30',0),(4,2026,'IV Trimestre','2026-10-01','2026-11-30',0);
/*!40000 ALTER TABLE `periodos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` bigint NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` varchar(20) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ADMIN|DOCENTE|ESTUDIANTE|ACUDIENTE',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Ruben Dario','Delgado Cruz','rubennet85@hotmail.com','$2y$10$kXevJKmyUVx86LJUNEeTNOCqLFEr1ByyuKJwODldSchZWMXksn4oW','administrador',1,'2026-03-09 01:06:04'),(2,'Yisney','Lopez Sanchez','maylita1997@hotmail.com','$2y$10$im3mYsz7P/MccF.nFpX7q.qZDcmBM2dXAbNcPIY30Vbl6TqOr6426','docente',1,'2026-03-09 01:13:24'),(3,'Jose','Perez Torres','jose@correo.com','$2y$10$/Df8EPaFsL.rG1dUDEbt8e/UWV1Dmy4aTWTSB5DBi8GeCprilBd9.','acudiente',1,'2026-03-09 01:23:04'),(4,'Ana Maria','Beltran Barragan','ana@correo.com','$2y$10$/Xwm38CMwp./gU4G/65S4eM7U.MVmbYMzNXbLILhHWYCKLub4sxbe','acudiente',1,'2026-03-09 01:24:37'),(5,'Juan Carlos','Rodriguez Perez','juan@correo.com','$2y$10$vXZbQ2a7gjlXvecRwDo0fuIBtWAPCSnQ.dl2L0TYYNLkRz.Ag8Hu2','acudiente',1,'2026-03-09 01:28:48'),(6,'Andres Felipe','Martinez Perez','andres@correo.com','$2y$10$VWd3Z1BhuOPzNz7BMEx2p.P1C2cVK6TbCx84N0ocQ4HbYTX3/wykG','acudiente',1,'2026-03-09 01:35:50'),(7,'Carlos Alberto','Ramírez Gómez','admin@edugrade.com','$2y$10$EjemploHashAqui','administrador',1,'2026-03-09 01:55:46'),(8,'Laura Marcela','Pérez Torres','docente@edugrade.com','$2y$10$EjemploHashAqui','docente',1,'2026-03-09 01:55:46'),(9,'Leidy Maria','Cruz Miranda','leidy@correo.com','$2y$10$9sZ0BudIKFyKo//99hZfauccfn/cFWfSjljfHRR4QCSshSzloi7Uu','acudiente',1,'2026-03-09 02:38:38'),(10,'Ivon Marcela','Jaramillo Guzman','ivon@correo.com','$2y$10$w.oKEOkkN0Dv2DRwsOYhWOowbYSo9V4Ib1CJO7AP5WZpmrkn3Qkhq','acudiente',1,'2026-03-09 13:27:21'),(11,'Dylan Joao','Cruz Delgado','dylan@correo.com','dylan2025','administrador',3,'2026-03-09 13:33:06'),(12,'Pepito','Perez','pepito@correo.com','$2y$10$Zwos2/MveZ.7Zo.oTSzmoOif2xa7KPx5wc/XZtFz289nVA4ZQw7LO','acudiente',1,'2026-03-09 13:45:32'),(13,'Jose','Tulio','jose123@correo.com','$2y$10$CJDVYhAju5RcvfNWyygeweM1m/jr4IUn3N25xT3Tm9C7Zrx3Zv6r2','acudiente',1,'2026-03-09 13:50:50'),(14,'Karen','Galiano Ramirez','karen@correo.com','$2y$10$hheFvXiA4vIGEzVgVyaRd.xXY7fLav316NS9akEp.onJHbziPFmVO','acudiente',1,'2026-03-10 13:54:10'),(15,'Pedro','Tulio Gomez','pedro@correo.com','$2y$10$PedroHashAqui','administrador',1,'2026-03-10 14:01:15'),(16,'Keiner','Trejos Caviedes','keiner@correo.com','$2y$10$EHTLaZG9fJMvWGvw4VupPO4b7nC2Vyft9dx/Sq./NEPnJUX2XXd9y','docente',1,'2026-03-11 15:32:09'),(17,'Yuliana','Arbelaez Trujillo','yulianita@correo.com','$2y$10$njcGWSOX4zt4iXGRamDigO3iZcali0UIRGn5.Otb3An8.DB48MsO2','acudiente',1,'2026-03-13 19:25:33'),(18,'Diego','Osorio Saavedra','diego@correo.com','$2y$10$5rommB.G4Xu3zDyptTsAhejipZ1T6X4/vV5753sAC5NxI2sTlIVha','acudiente',1,'2026-03-14 14:46:07'),(19,'William','Mendez Ortiz','william@correo.com','$2y$10$kgaVXCSJk/O0LCbYBx/Cm.UdGrq38eQJTSVxo/05iM13pkgcDk.Zm','docente',1,'2026-03-20 12:59:42'),(20,'Alexander Andres','Garcia Medina','alex@correo.com','$2y$10$sFl1ulggNaPDgs16oinCseEjyxAvlZuO.3zANX4a3RxkMxjIrNDvu','acudiente',1,'2026-03-21 01:28:46'),(21,'Andres Felipe','Pedriza Virgen','andresf@correo.com','$2y$10$cCu2nO0MQ01KxYjrdT9j8exg9p/jidlBRSEHE.wBEXROUhuZB7RAu','docente',1,'2026-03-21 14:09:39'),(24,'Luisa Maria','Perez','lusperez15@gmail.com','$2y$10$06/KHIBgXrkmMmL51DXnS.jMLapvhkC2aESOeOhoWu4N5XQou6g.2','administrador',1,'2026-03-27 16:20:25'),(26,'Martha Maria','Tamayo Torres','lala@correo.com','$2y$10$L5qYFSjYYvwsSMQK.j7vhe8WjR46y/rHDE.4uDp6iVxXbVbA.od7u','administrador',1,'2026-04-06 15:39:34'),(32,'Casilda','Perez Trujillo','casilda@correo.com','$2y$10$bs0.xAnI0SHQTflnIFQJueOiss1VRdHaPTeRaJj2cx9lZ9XVucgUy','acudiente',1,'2026-04-14 21:48:14'),(35,'Juan Andres','Carvajal Jimenez','juancarvajal@correo.co','$2y$10$PlfXDZK7bl.PyeffK7nhreFosa2oqS5.FuBfweuGjtA0oAXSPgZUS','estudiante',1,'2026-04-22 03:54:32'),(36,'Marisol','Triviño Ome','marisol@correo.com','$2y$10$aWlXqrAbMy2VJ.h5jEn/ruSB4G882Yncw0SJ0dmKNinDJ7hufT15q','estudiante',1,'2026-04-22 04:00:58'),(37,'Beto','Hortuega Villa','beto@correo.com','$2y$10$RN.aoL.O9Uf/pPes8hvSN.8Ilcxvr274F.4PYDGBWx3MUFoOYChKS','estudiante',1,'2026-04-22 04:17:00');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-22  9:20:59

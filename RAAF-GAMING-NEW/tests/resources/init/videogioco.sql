CREATE TABLE IF NOT EXISTS videogioco (
  `prodotto` int NOT NULL,
  `dimensione` decimal(4,1) NOT NULL,
  `pegi` int NOT NULL,
  `edizione_limitata` int NOT NULL,
  `ncd` int DEFAULT NULL,
  `vkey` char(14) DEFAULT NULL,
  `software_house` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`prodotto`)
);
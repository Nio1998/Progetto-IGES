
CREATE TABLE IF NOT EXISTS dlc (
  `prodotto` int NOT NULL,
  `dimensione` decimal(3,1) NOT NULL,
  `descrizione` varchar(50) NOT NULL,
  PRIMARY KEY (`prodotto`)
);

CREATE TABLE IF NOT EXISTS recensisce (
  `cliente` varchar(30) NOT NULL,
  `prodotto` int NOT NULL,
  `voto` smallint NOT NULL,
  `commento` varchar(45) NOT NULL,
  PRIMARY KEY (`cliente`,`prodotto`)
);
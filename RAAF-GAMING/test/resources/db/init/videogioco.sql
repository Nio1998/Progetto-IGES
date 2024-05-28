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

CREATE TABLE IF NOT EXISTS prodotto (
  `codice_prodotto` int NOT NULL AUTO_INCREMENT,
  `prezzo` double NOT NULL,
  `copertina` longblob,
  `sconto` decimal(2,0) DEFAULT '0',
  `data_uscita` date NOT NULL,
  `nome` varchar(50) NOT NULL,
  `quantita_fornitura` int NOT NULL,
  `data_fornitura` date NOT NULL,
  `fornitore` varchar(20) DEFAULT NULL,
  `gestore` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`codice_prodotto`)
);


CREATE TABLE IF NOT EXISTS recensisce (
  `cliente` varchar(30) NOT NULL,
  `prodotto` int NOT NULL,
  `voto` smallint NOT NULL,
  `commento` varchar(45) NOT NULL,
  PRIMARY KEY (`cliente`,`prodotto`)
);

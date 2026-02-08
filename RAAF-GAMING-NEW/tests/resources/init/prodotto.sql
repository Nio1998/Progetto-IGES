
/*CREATE TABLE IF NOT EXISTS prodotto(
  `codice_prodotto` int  NOT NULL AUTO_INCREMENT,
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
  );*/
  CREATE TABLE IF NOT EXISTS prodotto(
  `codice_prodotto` INTEGER PRIMARY KEY AUTOINCREMENT,
  `prezzo` REAL NOT NULL,
  `copertina` BLOB,
  `sconto` INTEGER DEFAULT 0,
  `data_uscita` TEXT NOT NULL,
  `nome` TEXT NOT NULL,
  `quantita_fornitura` INTEGER NOT NULL,
  `data_fornitura` TEXT NOT NULL,
  `fornitore` TEXT DEFAULT NULL,
  `gestore` TEXT DEFAULT NULL
);


CREATE TABLE  IF NOT EXISTS cartadicredito (
  `codicecarta` varchar(16) NOT NULL,
  `data_scadenza` date NOT NULL,
  `codice_cvv` int NOT NULL,
  PRIMARY KEY (`codicecarta`)
);

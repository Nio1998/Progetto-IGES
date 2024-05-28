
CREATE TABLE IF NOT EXISTS ordine (
  `codice` char(11) NOT NULL,
  `data_acquisto` date NOT NULL,
  `indirizzo_di_consegna` varchar(200) NOT NULL,
  `cliente` varchar(30) NOT NULL,
  `prezzo_totale` double NOT NULL,
  `gestore` varchar(45) DEFAULT NULL,
  `stato` varchar(45) NOT NULL,
  `metodo_di_pagamento` varchar(16) NOT NULL,
  PRIMARY KEY (`codice`)
);

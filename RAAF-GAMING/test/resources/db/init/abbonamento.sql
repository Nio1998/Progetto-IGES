
CREATE TABLE IF NOT EXISTS abbonamento (
  `codice` char(11) NOT NULL,
  `prodotto` int  NOT NULL,
  `durata_abbonamento` smallint NOT NULL DEFAULT '1',
  PRIMARY KEY (`codice`,`prodotto`)
);
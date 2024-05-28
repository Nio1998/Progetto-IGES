
CREATE TABLE IF NOT EXISTS presente_in (
  `magazzino` varchar(50) NOT NULL,
  `prodotto` int NOT NULL,
  `quantita_disponibile` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`magazzino`,`prodotto`)
);

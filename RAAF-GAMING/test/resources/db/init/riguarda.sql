CREATE TABLE IF NOT EXISTS riguarda (
  `prodotto` int NOT NULL,
  `ordine` varchar(11) NOT NULL,
  `quantita_acquistata` smallint NOT NULL DEFAULT '1',
  PRIMARY KEY (`prodotto`,`ordine`)
);


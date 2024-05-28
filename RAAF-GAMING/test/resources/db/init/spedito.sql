CREATE TABLE IF NOT EXISTS `spedito` (
  `ordine` varchar(11) NOT NULL,
  `corriere_espresso` varchar(10) NOT NULL,
  `data_consegna` date DEFAULT NULL,
  PRIMARY KEY (`ordine`,`corriere_espresso`)
);
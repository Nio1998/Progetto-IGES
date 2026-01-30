


CREATE TABLE  IF NOT EXISTS cliente (
  `email` varchar(30) NOT NULL,
  `nome` varchar(15) NOT NULL,
  `cognome` varchar(15) NOT NULL,
  `data_di_nascita` date NOT NULL,
  `password` varchar(50) NOT NULL,
  `carta_fedelta` char(10) NOT NULL,
  `cartadicredito` varchar(16) NOT NULL,
  PRIMARY KEY (`email`)
);

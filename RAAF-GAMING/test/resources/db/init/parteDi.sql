CREATE TABLE IF NOT EXISTS parte_di (
  `videogioco` int  NOT NULL,
  `categoria` varchar(20) NOT NULL,
  PRIMARY KEY (`videogioco`,`categoria`)
)
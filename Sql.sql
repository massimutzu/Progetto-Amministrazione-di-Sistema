CREATE TABLE `operatore` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `nome` varchar(128) DEFAULT NULL,
  `cognome` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `cellulare` varchar(128) DEFAULT NULL,
  `abilitato` bit(1) DEFAULT b'1',
  `admin` bit(1) DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) 

CREATE TABLE `utente` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `nome` varchar(128) NOT NULL,
  `cognome` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `indirizzo` varchar(128) DEFAULT NULL,
  `cellulare` varchar(128) DEFAULT NULL,
  `abilitato` bit(1) DEFAULT b'1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
)

CREATE TABLE `catalogo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Titolo` varchar(128) NOT NULL,
  `Autore` varchar(128) NOT NULL,
  `Genere` varchar(128) DEFAULT NULL,
  `Anno` varchar(128) DEFAULT NULL,
  `Isbn` varchar(128) NOT NULL,
  `UrlImmagine` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) 

CREATE TABLE `libro` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `codice_inventario` varchar(128) NOT NULL,
  `noleggiabile` bit(1) DEFAULT b'1',
  `note` varchar(128) DEFAULT NULL,
  `catalogo_fk` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `CATALOGO_FK_idx` (`catalogo_fk`),
  CONSTRAINT `CATALOGO_FK` FOREIGN KEY (`catalogo_fk`) REFERENCES `catalogo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) 

CREATE TABLE `noleggio` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `start_rent` datetime NOT NULL,
  `end_rent` datetime DEFAULT NULL,
  `utente_fk` bigint(20) DEFAULT NULL,
  `libro_fk` bigint(20) NOT NULL,
  `operatore_startrent_fk` bigint(20) DEFAULT NULL,
  `operatore_endrent_fk` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `LIBRO_FK_idx` (`libro_fk`),
  KEY `UTENTE_FK_idx` (`utente_fk`),
  KEY `OPERATORE_IN_FK_idx` (`operatore_startrent_fk`),
  KEY `OPERATORE_OUT_FK_idx` (`operatore_endrent_fk`),
  CONSTRAINT `LIBRO_FK` FOREIGN KEY (`libro_fk`) REFERENCES `libro` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `OPERATORE_IN_FK` FOREIGN KEY (`operatore_startrent_fk`) REFERENCES `operatore` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `OPERATORE_OUT_FK` FOREIGN KEY (`operatore_endrent_fk`) REFERENCES `operatore` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `UTENTE_FK` FOREIGN KEY (`utente_fk`) REFERENCES `utente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) 



INSERT INTO `utente` VALUES (1,'Utente1','Utente1','Rosa','Spina','rspina@hotmail.com','via roma 1 00141 Roma','3291111111',''),(2,'Utente2','Utente2','Chiara','Verdi','chiara.verdi@hotmail.com','via Milano 1 7100 Sassari','3471111111','');
INSERT INTO `operatore` VALUES (1,'Operatore1','Operatore1','Mario','Rossi','mario.rossi@gmail.com','3381111111','',''),(3,'Operatore2','Operatore2','Antonio','Bianchi','antonio.bianchi@gmail.com','3371111111','','\0');
INSERT INTO `catalogo` VALUES (1,'Il nome della rosa','Umberto Eco','Romanzo','1980','1245212541254','https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTYwswQVpos3ZTWUAddRTMq_0zsZkBDXI-TcP03nO5raBPjKmsK'),(3,'Vita di Eleonora D\'Arborea','Bianca Pitzorno','Saggio Storico','2005','7237e7sdhh777','http://www.librimondadori.it/var/ezflow_site/storage/images/sili/opere/adulti/vita-di-eleonora-d-arborea/9788804597148/183556-16-ita-IT/9788804597148-vita-di-eleonora-d-arborea_copertina_piatta_fo.jpg'),(5,'Dracula','Bram Stoker','Romanzo','1896','23214324234231','http://media.booksblog.it/d/dra/dracula_02.jpg'),(6,'Le Montagne Della Follia','H.P. Lovecraft','Romanzo','1912','18348342832929','http://image.anobii.com/anobi/image_book.php?item_id=013e0f978a735f6107&time=&type=6');
INSERT INTO `libro` VALUES (1,'00000001','\0','Danneggiato',1),(2,'00000002','','Integro',1),(3,'00000003','','Integro',1),(5,'00000005','','copertina rovinata',1),(6,'lib000006','','',3),(9,'lib000009','','',5),(10,'lib000010','','',6),(25,'lib000025','','',6),(26,'lib000026','','',6),(27,'lib000027','','',6),(28,'lib000028','','',6),(29,'lib000029','','',3),(30,'lib000030','','',3),(31,'lib000031','','',3),(32,'lib000032','','',3);
INSERT INTO `noleggio` VALUES (1,'2016-12-05 17:00:00','2017-01-22 18:01:47',1,1,1,1),(2,'2016-12-28 14:00:00','2017-01-22 18:01:49',2,2,1,1),(4,'2017-01-22 17:29:30',NULL,NULL,10,1,NULL),(5,'2017-01-22 17:31:31','2017-01-22 17:33:24',1,10,1,1),(6,'2017-01-22 17:32:21','2017-01-22 17:32:48',2,10,1,1),(7,'2017-01-22 17:33:59','2017-01-22 17:40:46',2,10,1,1),(8,'2017-01-22 17:37:19','2017-01-22 17:40:42',1,10,1,1),(9,'2017-01-22 17:40:09','2017-01-22 17:40:40',1,10,1,1),(10,'2017-01-22 17:40:14','2017-01-22 17:40:47',2,10,1,1),(11,'2017-01-22 17:40:57','2017-01-22 18:01:52',1,10,1,1),(12,'2017-01-22 17:41:44','2017-01-22 17:42:01',2,10,1,1),(13,'2017-01-22 17:58:30','2017-01-22 18:01:54',2,3,1,1);
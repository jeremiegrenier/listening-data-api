CREATE TABLE `artists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(140) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `artists_data` (
  `artist_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `gender` char(1) NOT NULL,
  `nb_streams` int(11) NOT NULL,
  KEY `artist_id` (`artist_id`),
  CONSTRAINT `artists_data_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOAD DATA INFILE '/docker-entrypoint-initdb.d/init_data/artists.csv'
INTO TABLE `artists`
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n' (`id`,`name`,`status`,`twitter`);

LOAD DATA INFILE '/docker-entrypoint-initdb.d/init_data/artist_stream_demog_gender.csv'
INTO TABLE `artists_data`
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n' (`artist_id`,`date`,`gender`,`nb_streams`);

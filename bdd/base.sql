DROP TABLE IF EXISTS commercant;
DROP TABLE IF EXISTS type_commercant;
DROP TABLE IF EXISTS user_commercant;

CREATE TABLE user_commercant(
  id_user int(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  droits_user ENUM('admin', 'user'),
  nom_user VARCHAR(50),
  mdp_user VARCHAR(60)
);

INSERT INTO user_commercant(droits_user, nom_user, mdp_user) VALUES
  ('user', 'test', '098f6bcd4621d373cade4e832627b4f6');
  INSERT INTO user_commercant(droits_user, nom_user, mdp_user) VALUES
  ('admin', 'admin', '21232f297a57a5a743894a0e4a801fc3');

CREATE TABLE type_commercant(
id_type int(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
noms VARCHAR(20)
);

CREATE TABLE commercant(
  id_commercant int(11) PRIMARY KEY  NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50),
  date_installation VARCHAR(10),
  prix_location FLOAT(7,2),
  id_type_commercant int(10),
  FOREIGN KEY commercant(id_type_commercant) REFERENCES type_commercant(id_type) ON DELETE CASCADE
);

INSERT INTO type_commercant VALUES (1, 'Boucherie');
INSERT INTO type_commercant VALUES (2, 'Boulangerie');
INSERT INTO type_commercant VALUES (3, 'Maraicher');
INSERT INTO type_commercant VALUES (4, 'Fromagerie');

INSERT INTO commercant VALUES (1,'Boucherie Rousselet','2010-01-01','500',1);
INSERT INTO commercant VALUES (2,'Boulanger Peslier','2009-01-01','600',2);
INSERT INTO commercant VALUES (3,'Patisserie Perez','2014-01-01','600',2);
INSERT INTO commercant VALUES (4,'Maraicher Ravier','2014-01-01','600',3);
INSERT INTO commercant VALUES (5,'Bon Pain Woltz','2013-01-01','700',2);
INSERT INTO commercant VALUES (6,'Caly Maraicher','2014-01-01','600',3);
INSERT INTO commercant VALUES (7,'Boucherie Caly','2013-01-01','600',1);
INSERT INTO commercant VALUES (8,'Fromagerie mervant','2013-01-01','600',4);
INSERT INTO commercant VALUES (9,'Boulangerie Laure','2013-01-01','800',2);
INSERT INTO commercant VALUES (10,'Boucherie Laurent','2013-01-01','800',1);
INSERT INTO commercant VALUES (11,'Fromagerie dupuis','2014-01-01','600',4);


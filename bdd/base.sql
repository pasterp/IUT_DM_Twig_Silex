DROP TABLE IF EXISTS commercant;
DROP TABLE IF EXISTS type_commercant;



CREATE TABLE type_commercant(
id_type int(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
noms VARCHAR(20)
);

CREATE TABLE commercant(
  id_commercant int(11) PRIMARY KEY  NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50),
  date_installation DATE,
  prix_location FLOAT(7,2),
  id_type_commercant int(10),
  FOREIGN KEY commercant(id_type_commercant) REFERENCES type_commercant(id_type) ON DELETE CASCADE
);

INSERT INTO type_commercant VALUES (1, 'Boucherie');
INSERT INTO type_commercant VALUES (2, 'Boulangerie');
INSERT INTO type_commercant VALUES (3, 'Maraicher');
INSERT INTO type_commercant VALUES (4, 'Fromagerie');

INSERT INTO commercant VALUES (1,'Boucherie Rousselet','2010-1-1','500',1);
INSERT INTO commercant VALUES (2,'Boulanger Peslier','2009-1-1','600',2);
INSERT INTO commercant VALUES (3,'Patisserie Perez','2014-1-1','600',2);
INSERT INTO commercant VALUES (4,'Maraicher Ravier','2014-1-1','600',3);
INSERT INTO commercant VALUES (5,'Bon Pain Woltz','2013-1-1','700',2);
INSERT INTO commercant VALUES (6,'Caly Maraicher','2014-1-1','600',3);
INSERT INTO commercant VALUES (7,'Boucherie Caly','2013-1-1','600',1);
INSERT INTO commercant VALUES (8,'Fromagerie mervant','2013-1-1','600',4);
INSERT INTO commercant VALUES (9,'Boulangerie Laure','2013-1-1','800',2);
INSERT INTO commercant VALUES (10,'Boucherie Laurent','2013-1-1','800',1);
INSERT INTO commercant VALUES (11,'Fromagerie dupuis','2014-1-1','600',4);


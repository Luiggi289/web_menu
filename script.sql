

-- CREAR TIPO DE PLATOS
CREATE TABLE IF NOT EXISTS wp_type_dish (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(100) NULL,
PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS wp_category (
id INT NOT NULL,
name VARCHAR(250) NOT NULL ,
PRIMARY KEY (id)
);
-- CREAR PLATO
CREATE TABLE IF NOT EXISTS wp_dish (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(250) NULL,
id_type_dish INT NOT NULL,
description TEXT NULL,
id_user bigint  NOT NULL,
PRIMARY KEY (id),
CONSTRAINT FOREIGN KEY fk_wp_dish_wp_type_dish (id_type_dish) REFERENCES wp_type_dish (id)
);

-- CREAR DIA
CREATE TABLE IF NOT EXISTS wp_day (
id INT NOT NULL ,
name VARCHAR(45) NULL,
PRIMARY KEY (id)
);
//CREAR part_day 
CREATE TABLE IF NOT EXISTS wp_part_day (
id INT NOT NULL,
name VARCHAR(45) NULL,
PRIMARY KEY (id)
);
//CREAR MENU_WEEK 
CREATE TABLE IF NOT EXISTS wp_menu_week (
id INT NOT NULL AUTO_INCREMENT,
id_dish INT NOT NULL,
id_day INT NOT NULL,
id_part_day INT NOT NULL,

PRIMARY KEY (id),
CONSTRAINT FOREIGN KEY fk_wp_menu_wp_week_dish (id_dish) REFERENCES wp_dish (id),
CONSTRAINT FOREIGN KEY fk_wp_menu_week_wp_day (id_day) REFERENCES wp_day (id),
CONSTRAINT FOREIGN KEY fk_wp_menu_week_wp_part_day (id_part_day) REFERENCES wp_part_day (id)
);



--INSERT CATEGORY
INSERT INTO wp_category (id,name) VALUES (1,'Abarrotes');
INSERT INTO wp_category (id,name) VALUES (2,'Verduras');
INSERT INTO wp_category (id,name) VALUES (3,'Lacteos y Fiambres');
INSERT INTO wp_category (id,name) VALUES (4,'Frutas');
--INSERTAR TABLA DAY
INSERT INTO wp_day(name) VALUES (0,'Domingo');
INSERT INTO wp_day (name) VALUES (1,'Lunes');
INSERT INTO wp_day(name) VALUES (2,'Martes');
INSERT INTO wp_day(name) VALUES (3,'Miercoles');
INSERT INTO wp_day(name) VALUES (4,'Jueves');
INSERT INTO wp_day(name) VALUES (5,'Viernes');
INSERT INTO wp_day(name) VALUES (6,'Sabado');


--INSERT PARTE DEL DIA (valores importantes en duro)

INSERT INTO wp_part_day (name) VALUES (1,'Almuerzo');
INSERT INTO wp_part_day (name) VALUES (2,'Desayuno');
INSERT INTO wp_part_day (name) VALUES (3,'Cena');

--INSERT TIPO_PLATO
INSERT INTO wp_type_dish (name) VALUES ('Guiso');
INSERT INTO wp_type_dish (name) VALUES ('Saltado');
INSERT INTO wp_type_dish (id,name) VALUES (3,'Sopa');

--INSERT PLATO
INSERT INTO wp_dish (name, id_type_dish) VALUES ('Estofado de Pollo',1);
INSERT INTO wp_dish (name,id_type_dish) VALUES ('Seco de Pollo',1);
INSERT INTO wp_dish (name,id_type_dish) VALUES ('Saltado de Vainita',2);
INSERT INTO wp_dish (name,id_type_dish) VALUES ('Saltado de Coliflor',2);


-- TEST


CREATE TABLE IF NOT EXISTS wp_menu_week (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(250) NULL,
            shortcode VARCHAR(45) NULL,
	created_date date not null,
created_date_time timestamp DEFAULT CURRENT_TIMESTAMP   not null,
start_date date not null,
end_date date not null,
last_modify_date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
id_user bigint  NOT NULL,
            PRIMARY KEY (id)
            );

ALTER TABLE `wp_menu_week` CHANGE `last_modify_date_time` `last_modify_date_time` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

insert into wp_menu_week(name,shortcode,created_date,start_date,end_date)
VALUES('prueba',null,'2023-02-18', '2023-02-06', '2023-02-12') ;





CREATE TABLE IF NOT EXISTS wp_menu_week_det (
            id_menu_week   INT NOT NULL,
            id_dish INT  NOT NULL,
            date_menu date  NOT NULL,
            id_part_day INT NOT NULL,
            id_day INT NOT NULL,

            PRIMARY KEY (id_menu_week  , id_dish, date_menu, id_part_day) ,
CONSTRAINT FOREIGN KEY fk_wp_menu_week_det_wp_dish (id_dish) REFERENCES wp_dish (id),
CONSTRAINT FOREIGN KEY fk_wp_menu_week_det_wp_menu_week (id_menu_week  ) REFERENCES wp_menu_week (id),
CONSTRAINT FOREIGN KEY fk_wp_menu_week_det_wp_part_day (id_part_day) REFERENCES wp_part_day (id),
CONSTRAINT FOREIGN KEY fk_wp_menu_week_det_wp_day (id_day) REFERENCES wp_day (id)


            );

insert into wp_menu_week_det (id_menu_week , id_dish, date_menu, id_part_day,id_day)
 values 
(1,1,'2023-02-06',1,1), 
(1,2,'2023-02-07',1,2), 
(1,3,'2023-02-08',1,3), 
(1,4,'2023-02-09',1,4), 
(1,5,'2023-02-10',1,5), 
(1,6,'2023-02-11',1,6), 
(1,7,'2023-02-12',1,7);

INSERT INTO `wp_menu` (`idmenu`, `name`, `shortcode`) VALUES (NULL, 'Mi menu Semana 1 Noviembre', '[ENC id=\'1\']'), (NULL, 'Mi menu Semana 2 Diciembre', '[ENC id=\'2\']');

-- INGREDIENT
CREATE TABLE IF NOT EXISTS wp_ingredient (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(250) NULL,
PRIMARY KEY (id)
);
DROP TABLE FOREIGN KEY
ALTER TABLE wp_dish DROP FOREIGN KEY fk_wp_dish_wp_type_dish ;
-- CREAR UNIDAD MEDIDA
CREATE TABLE IF NOT EXISTS wp_unit_measure (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(50) NULL,
PRIMARY KEY (id)
);

INSERT INTO `wp_unit_measure` (`id`, `name`) VALUES
(1, 'UNIDADES'),
(2, 'KILOS');
INSERT INTO `wp_unit_measure` (`id`, `name`) VALUES (3, 'GRAMOS');
INSERT INTO `wp_unit_measure` (`id`, `name`) VALUES (4, 'LITROS');
INSERT INTO `wp_unit_measure` (`id`, `name`) VALUES (5, 'MILILITROS');

INSERT INTO `wp_unit_measure` (`id`, `name`) VALUES (6, 'PRESAS');


CREATE TABLE IF NOT EXISTS wp_dish_part_day (
id_dish int ,
id_part_day int
);



--CREAR DISH_DETAIL
CREATE TABLE IF NOT EXISTS wp_dish_ingredient (
sequence INT NOT NULL,
id_dish INT NOT NULL,
id_ingredient INT NOT NULL,
id_unit_measure INT NOT NULL,
quantity decimal(10,2) NULL,
PRIMARY KEY (id_dish , sequence),
CONSTRAINT fk_wp_dish_ingredient_wp_dish FOREIGN KEY (id_dish) REFERENCES wp_dish(id),
CONSTRAINT fk_wp_dish_ingredient_wp_ingredient FOREIGN KEY (id_ingredient) REFERENCES wp_ingredient(id),
CONSTRAINT fk_wp_dish_ingrediente_wp_unit_measure FOREIGN KEY (id_unit_measure) REFERENCES wp_unit_measure(id)
);





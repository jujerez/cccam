DROP TABLE IF EXISTS usuarios CASCADE;
CREATE TABLE usuarios
(
    id          bigserial     PRIMARY KEY
  , nick        varchar(255)  NOT NULL UNIQUE
  , password    varchar(255)  NOT NULL
  , email       varchar(255)  NOT NULL
);

DROP TABLE IF EXISTS clientes CASCADE;
CREATE TABLE clientes
(
    id          bigserial     PRIMARY KEY
  , nombre      varchar(255)  NOT NULL 
  , telefono    varchar(9)    NOT NULL
  , direccion   varchar(255)  CONSTRAINT ck_direccion_no_vacia
                              CHECK (direccion != '')
  , nota        varchar(255)  CONSTRAINT ck_nota_no_vacia
                              CHECK (nota != '')
  , usuario_id  bigint        NOT NULL REFERENCES usuarios (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE

);


DROP TABLE IF EXISTS clines CASCADE;
CREATE TABLE clines
(
    id          bigserial     PRIMARY KEY
  , servidor    varchar(255)  NOT NULL
  , puerto      numeric(5)    NOT NULL
  , usuario     varchar(255)  NOT NULL
  , password    varchar(255)  NOT NULL
  , fecha_alta  date          NOT NULL
  , cliente_id  bigint        NOT NULL REFERENCES clientes (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE
  , usuario_id  bigint        NOT NULL REFERENCES usuarios (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE 
);


DROP TABLE IF EXISTS descodificadores CASCADE;
CREATE TABLE descodificadores
(
    id           bigserial     PRIMARY KEY
  , marca        varchar(255)  NOT NULL
  , modelo       varchar(255)  NOT NULL
  , serial       varchar(255)  NOT NULL
  , fecha_compra date          NOT NULL
  , lugar_compra varchar(255)  NOT NULL
  , cliente_id   bigint        NOT NULL REFERENCES clientes (id)
                               ON DELETE NO ACTION ON UPDATE CASCADE 
  , usuario_id  bigint        NOT NULL REFERENCES usuarios (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE
);

INSERT INTO usuarios (nick, password, email)
VALUES ('juan', crypt('juan', gen_salt('bf', 10)), 'pepe@pepe.com');

INSERT INTO clientes (nombre, telefono, direccion, nota, usuario_id )
VALUES ('Juan Antonio', '666777666', 'C/ Sevilla nº5', NULL, 1);

INSERT INTO clines (servidor, puerto, usuario, password, fecha_alta, cliente_id, usuario_id )
VALUES ('10.255.255.254', '65535', 'usuario', '999999', '2019-09-22', 1, 1 );

INSERT INTO descodificadores (marca, modelo, serial, fecha_compra, lugar_compra, cliente_id, usuario_id )
VALUES ('Freesat', 'v7', 'V723823488383848','2019-09-22','ebay', 1, 1 );


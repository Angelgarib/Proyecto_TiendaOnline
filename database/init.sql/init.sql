SELECT 'CREATE DATABASE nombre_de_la_base_de_datos' 
	WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'tienda');

DROP TABLE IF EXISTS "productos";
DROP SEQUENCE IF EXISTS productos_id_seq;
DROP TABLE IF EXISTS "user_roles";
DROP TABLE IF EXISTS "usuarios";
DROP SEQUENCE IF EXISTS usuarios_id_seq;
DROP TABLE IF EXISTS "generos";

-- Cuidado con las secuencias, si se borran se pierde el autoincremento, ponemos el start a 6 para que empiece en 6
CREATE SEQUENCE productos_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 6 CACHE 1;

CREATE TABLE "public"."productos"
(
	"nombre"       character varying(255)                               NOT NULL,
    "precio"       double precision DEFAULT '0.0',
    "stock"        integer          DEFAULT '0',
    "id"           bigint           DEFAULT nextval('productos_id_seq') NOT NULL,
    "genero_id"    uuid,
    "uuid"         uuid                                                 NOT NULL,
    "imagen"       text             DEFAULT 'https://via.placeholder.com/150',
    "num_jugadores"integer          DEFAULT '0',
    "tipo"       character varying(255),
    CONSTRAINT "productos_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "productos_uuid_key" UNIQUE ("uuid")
) WITH (oids = false);

INSERT INTO "productos" ("nombre", "precio", "stock", "id", "genero_id", "uuid","imagen",
                        "num_jugadores", "tipo")
VALUES ('Catan', 30.99, 5, 1, 'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9', '19135792-b778-441f-871e-d6e6096e0ddc',
        'https://via.placeholder.com/150', 4, 'tablero'),
        ('Exploding kittens', 18.50, 12, 2, 'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9', '88142492-b7b8-474f-87c2-d6e6228e0bac',
        'https://via.placeholder.com/150', 5, 'cartas'),
        ('Party & CO', 37.95, 8, 3, 'bb51d00d-13fb-4b09-acc9-948185636f79', '98397792-c118-465f-873f-e6c5077e0aba',
        'https://via.placeholder.com/150', 12, 'tablero'),
        ('Trivial Pursuit', 45.20, 3, 4, '9def16db-362b-44c4-9fc9-77117758b5b0', '45874298-d887-444e-573d-f3d5127e0ede',
        'https://via.placeholder.com/150', 6, 'fichas y tarjetas');

CREATE TABLE "public"."user_roles"
(
    "user_id" bigint NOT NULL,
    "roles"   character varying(255)
) WITH (oids = false);

INSERT INTO "user_roles" ("user_id", "roles")
VALUES (1, 'USER'),
       (1, 'ADMIN'),
       (2, 'USER'),
       (2, 'USER'),
       (3, 'USER');

CREATE SEQUENCE usuarios_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 5 CACHE 1;

CREATE TABLE "public"."usuarios"
(
    "id"         bigint    DEFAULT nextval('usuarios_id_seq') NOT NULL,
    "apellidos"  character varying(255)                       NOT NULL,
    "email"      character varying(255)                       NOT NULL,
    "nombre"     character varying(255)                       NOT NULL,
    "password"   character varying(255)                       NOT NULL,
    "username"   character varying(255)                       NOT NULL,
    CONSTRAINT "usuarios_email_key" UNIQUE ("email"),
    CONSTRAINT "usuarios_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "usuarios_username_key" UNIQUE ("username")
) WITH (oids = false);

-- Contraseña: admin Admin1
-- Contraseña: user User1234
-- Contraseña: test test1234


INSERT INTO "usuarios" ("id", "apellidos", "email", "nombre", "password", "username")
VALUES (1, 'Admin Admin', 'admin@prueba.net', 'Admin', '$2a$10$vPaqZvZkz6jhb7U7k/V/v.5vprfNdOnh4sxi/qpPRkYTzPmFlI9p2', 'admin'),
       (2, 'User User', 'user@prueba.net', 'User', '$2a$12$RUq2ScW1Kiizu5K4gKoK4OTz80.DWaruhdyfi2lZCB.KeuXTBh0S.', 'user'),
       (3, 'Test Test', 'test@prueba.net', 'Test', '$2a$10$Pd1yyq2NowcsDf4Cpf/ZXObYFkcycswqHAqBndE1wWJvYwRxlb.Pu', 'test'),
       (4, 'Otro Otro', 'otro@prueba.net', 'otro', '$2a$12$3Q4.UZbvBMBEvIwwjGEjae/zrIr6S50NusUlBcCNmBd2382eyU0bS', 'otro');


CREATE TABLE "public"."generos"
(
    "id"         uuid                                NOT NULL,
    "nombre"     character varying(255)              NOT NULL,
    CONSTRAINT "generos_nombre_key" UNIQUE ("nombre"),
    CONSTRAINT "generos_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "generos" ("id", "nombre")
VALUES ('d69cf3db-b77d-4181-b3cd-5ca8107fb6a9', 'ESTRATEGIA'),
       ('6dbcbf5e-8e1c-47cc-8578-7b0a33ebc154', 'ROL'),
       ('9def16db-362b-44c4-9fc9-77117758b5b0', 'PREGUNTAS'),
       ('bb51d00d-13fb-4b09-acc9-948185636f79', 'FIESTA');

ALTER TABLE ONLY "public"."productos"
    ADD CONSTRAINT "fk2fwq10nwymfv7fumctxt9vpgb" FOREIGN KEY (genero_id) REFERENCES generos (id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."user_roles"
    ADD CONSTRAINT "fk2chxp26bnpqjibydrikgq4t9e" FOREIGN KEY (user_id) REFERENCES usuarios (id) NOT DEFERRABLE;

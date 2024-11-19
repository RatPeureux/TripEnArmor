-- Note : pour insérer dans les tables, en raison de l'héritage
--        [!!!][!!!][!!!][!!!][!!!][!!!][!!!]
--        il faut UNIQUEMENT insérer dans les tables enfants qui ont des contraintes bien définies
--        si une insertion se fait sur une table abstrate (_compte, _offre...),
--        il y aura des problèmes de cohérence, de contraintes, de doublons... etc.
--        [!!!][!!!][!!!][!!!][!!!][!!!][!!!]

-- Listing des tables abstraites
--  _compte
--  _professionnel
--  _offre



-- Initialisation du schéma
DROP SCHEMA IF EXISTS "sae_db" CASCADE;
CREATE SCHEMA sae_db;
SET SCHEMA 'sae_db';





-- -------------------------------------------------------------------------------------------Adresse----- début
-- Table Adresse
CREATE TABLE _adresse (
    adresse_id SERIAL PRIMARY KEY,
    code_postal CHAR(5) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    numero varchar(255) not null,
    odonyme varchar(255) not null,
    complement_adresse varchar(255)
);
-- ------------------------------------------------------------------------------------------------------- fin





-- -----------------------------------------------------------------------------------------Comptes-------début
-- ARCHITECTURE DES TABLES CI-DESSOUS :
-- _compte (abstract)
--     _membre
--     _professionnel (abstract)
--         _pro_prive
--         _pro_public

-- Table abstraite _compte (abstr.)
CREATE TABLE _compte (
    id_compte SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    mdp_hash VARCHAR(255) NOT NULL,
    num_tel VARCHAR(255) NOT NULL,
    adresse_id integer
);

-- Table _membre
CREATE TABLE _membre (
    pseudo VARCHAR(255) UNIQUE,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL
) INHERITS (_compte);

-- Héritage des types de _compte (abstr.)
CREATE TABLE _professionnel (
    nomPro varchar(255) not null
) INHERITS (_compte);
CREATE TABLE _pro_public (
    type_orga VARCHAR(255) NOT NULL
) INHERITS (_professionnel);
CREATE TABLE _pro_prive (
    num_siren VARCHAR(255) UNIQUE NOT NULL
) INHERITS (_professionnel);

-- Rajouter les contraintes principales perdues à cause de l'héritage (clés primaires & étrangères & UNIQUE);
ALTER TABLE _professionnel
    ADD CONSTRAINT pk_professionnel PRIMARY KEY (id_compte);

ALTER TABLE _membre
    ADD CONSTRAINT pk_membre PRIMARY KEY (id_compte);
ALTER TABLE _membre
    ADD CONSTRAINT unique_mail_membre UNIQUE (email);
ALTER TABLE _membre
    ADD CONSTRAINT unique_tel_membre UNIQUE (num_tel);
ALTER TABLE _membre
    ADD CONSTRAINT fk_membre FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);

ALTER TABLE _pro_public
    ADD CONSTRAINT pk_pro_public PRIMARY KEY (id_compte);
ALTER TABLE _pro_public
    ADD CONSTRAINT unique_mail_pro_public UNIQUE (email);
ALTER TABLE _pro_public
    ADD CONSTRAINT unique_tel_pro_public UNIQUE (num_tel);
ALTER TABLE _pro_public
    ADD CONSTRAINT fk_pro_public FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);

ALTER TABLE _pro_prive
    ADD CONSTRAINT pk_pro_prive PRIMARY KEY (id_compte);
ALTER TABLE _pro_prive
    ADD CONSTRAINT unique_mail_pro_prive UNIQUE (email);
ALTER TABLE _pro_prive
    ADD CONSTRAINT unique_tel_pro_prive UNIQUE (num_tel);
ALTER TABLE _pro_prive
    ADD CONSTRAINT fk_pro_prive FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);
-- ------------------------------------------------------------------------------------------------------- fin





-- ----------------------------------------------------------------------------------------------RIB------ début
-- Table _RIB
CREATE TABLE _RIB (
    rib_id SERIAL PRIMARY KEY,
    code_banque varchar(255) NOT NULL,
    code_guichet varchar(255) NOT NULL,
    numero_compte varchar(255) NOT NULL,
    cle_rib varchar(255) NOT NULL,
    compte_id serial REFERENCES _pro_prive(id_compte) UNIQUE
);

-- ------------------------------------------------------------------------------------------------------- fin





-- -----------------------------------------------------------------------------------------------TAG----- début
-- Table _tag
CREATE TABLE _tag (
    tag_id SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);
-- -------------------------------------------------------------------------------------------------------- fin





-- ---------------------------------------------------------------------------------------------Offre----- début
-- Table _type_offre (gratuite OU standard OU prenium)
create table _type_offre (
    type_offre_id SERIAL PRIMARY KEY not null,
    nom_type_offre varchar(255) not null
);

-- ARCHITECTURE DES ENFANTS DE _offre :
-- _offre (abstract)
--     _restauration
--     _activite
--     _parc_attraction
--     _spectacle
--     _visite

-- Table globale _offre (abstr.)
CREATE TABLE _offre (
    offre_id SERIAL PRIMARY KEY,
    est_en_ligne BOOLEAN NOT NULL,
    description_offre TEXT,
    resume_offre TEXT,
    prix_mini FLOAT,
    titre varchar(255) NOT NULL,
    date_creation DATE NOT NULL,
    date_mise_a_jour DATE,
    date_suppression DATE,
    id_pro integer REFERENCES _professionnel(id_compte),
    type_offre_id integer REFERENCES _type_offre(type_offre_id),
    adresse_id serial REFERENCES _adresse(adresse_id),
    option VARCHAR(10)
);
-- ------------------------------------------------------------------------------------------------------ fin




-- Sécurité --------------------------------------------------------------
/*
 -- créer une sécurité sur la table _offre
 ALTER TABLE _offre ENABLE ROW LEVEL SECURITY;
 
 -- créer une politique RLS (les professionnels uniquement peuvent accéder à leur offre=
 CREATE POLICY offre_filter_pro ON _offre
 USING (id_pro = current_setting('app.current_professional')::INTEGER);
 
 -- créer une politique RLS (les visiteurs peuvent accéder à toutes les offres)
 CREATE POLICY offre_filter_visiteur ON _offre
 FOR SELECT -- Uniquement sur le select
 USING (current_setting('app.current_professional', true) IS NULL);
 
 
 -- créer politique RLS sur l'insertion
 CREATE POLICY offre_insert_pro ON _offre
 FOR INSERT
 WITH CHECK (id_pro = current_setting('app.current_professional')::INTEGER);
 
 -- créer politique RLS sur la mise à jour
 CREATE POLICY offre_update_pro ON _offre
 FOR UPDATE
 USING (id_pro = current_setting('app.current_professional')::INTEGER);
 
 -- créer politique RLS sur la supression
 CREATE POLICY offre_delete_pro ON _offre
 FOR DELETE
 USING (id_pro = current_setting('app.current_professional')::INTEGER);
 
 -- assure que même les supers utilisateurs respectent la politique de sécurité
 ALTER TABLE _offre FORCE ROW LEVEL SECURITY;
 */
--------------------------------------------------------------------------------- fin





-- TAGs Offre ------------------------------------------------------------ début
CREATE TABLE _tag_offre (
    offre_id serial REFERENCES _offre(offre_id),
    tag_id serial REFERENCES _tag(tag_id),
    PRIMARY KEY (offre_id, tag_id)
);
-- ------------------------------------------------------------------------------------------------------- fin





-- --------------------------------------------------------------------------------------------Facture---- début
CREATE TABLE _facture (
    facture_id SERIAL PRIMARY KEY,
    jour_en_ligne DATE NOT NULL,
    offre_id serial REFERENCES _offre(offre_id)
);

-- ------------------------------------------------------------------------------------------------------- fin





-- -----------------------------------------------------------------------------------------------Logs---- début
CREATE TABLE _log_changement_status (
    id SERIAL PRIMARY KEY,
    offre_id serial REFERENCES _offre(offre_id),
    date_changement DATE NOT NULL
);
-- ------------------------------------------------------------------------------------------------------- fin





-- -------------------------------------------------------------------------------------Restaurants------- début
-- Type de repas 'petit dej' 'diner' etc...
create table _type_repas (
    type_repas_id SERIAL PRIMARY KEY,
    nom_type_repas VARCHAR(255) NOT NULL UNIQUE
);

-- Table _restauration (hérite _offre)
CREATE TABLE _restauration (
    gamme_prix varchar(3) NOT NULL,
    type_repas_id integer references _type_repas(type_repas_id)
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _restauration à cause de l'héritage
ALTER TABLE _restauration
    ADD CONSTRAINT pk_restauration PRIMARY KEY (offre_id);    
ALTER TABLE _restauration
    ADD CONSTRAINT fk_restauration_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);
ALTER TABLE _restauration
    ADD CONSTRAINT fk_restauration_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre(type_offre_id);
ALTER TABLE _restauration
    ADD CONSTRAINT fk_restauration_professionnel FOREIGN KEY (id_pro) REFERENCES _professionnel(id_compte);

-- Lien entre restauration et type_repas
create table _restaurant_type_repas (
    offre_id serial REFERENCES _restauration(offre_id) ON DELETE CASCADE,
    type_repas_id serial REFERENCES _type_repas(type_repas_id) ON DELETE CASCADE,
    PRIMARY KEY (offre_id, type_repas_id)
);

-- Type de restaurant : gastronomie, kebab, etc..
create table _tag_restaurant (
    tag_restaurant_id serial primary key,
    nom_tag varchar(255) not null
);

-- table 1 restaurant <-> 1..* tag
create table _tag_restaurant_restauration (
    offre_id serial references _restauration(offre_id),
    tag_restaurant_id serial references _tag_restaurant(tag_restaurant_id),
    primary key (offre_id, tag_restaurant_id)
);
-- ------------------------------------------------------------------------------------------------------- fin





-- ----------------------------------------------------------------------------------------Activités------ début
-- Table _activite (hérite de _offre)
CREATE TABLE _activite (
    duree_activite TIME,
    age_requis INTEGER,
    prestations VARCHAR(255)
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _activite à cause de l'héritage
ALTER TABLE _activite
    ADD CONSTRAINT pk_activite PRIMARY KEY (offre_id);    
ALTER TABLE _activite
    ADD CONSTRAINT fk_activite_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);
ALTER TABLE _activite
    ADD CONSTRAINT fk_activite_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre(type_offre_id);
ALTER TABLE _activite
    ADD CONSTRAINT fk_activite_professionnel FOREIGN KEY (id_pro) REFERENCES _professionnel(id_compte);

-- TAGs Activité---------------------------------------------
create table _tag_activite (
    offre_id serial references _activite(offre_id),
    tag_id serial references _tag(tag_id),
    primary key (offre_id, tag_id)
);
-- ------------------------------------------------------------------------------------------------------- fin





-- -----------------------------------------------------------------------------------------Spectacles---- début
-- Table _spectacle (hérite de _offre)
CREATE TABLE _spectacle (
    capacite_spectacle INTEGER,
    duree_spectacle TIME
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _spectacle à cause de l'héritage
ALTER TABLE _spectacle
    ADD CONSTRAINT pk_spectacle PRIMARY KEY (offre_id);    
ALTER TABLE _spectacle
    ADD CONSTRAINT fk_spectacle_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);
ALTER TABLE _spectacle
    ADD CONSTRAINT fk_spectacle_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre(type_offre_id);
ALTER TABLE _spectacle
    ADD CONSTRAINT fk_spectacle_professionnel FOREIGN KEY (id_pro) REFERENCES _professionnel(id_compte);

-- TAG Spectacles 
create table _tag_spectacle (
    offre_id serial references _spectacle(offre_id),
    tag_id serial references _tag(tag_id),
    primary key (offre_id, tag_id)
);
-- ------------------------------------------------------------------------------------------------------- fin





-- --------------------------------------------------------------------------------------------Visites---- début
-- Table _visite (hérite de _offre)
CREATE TABLE _visite (
    duree_visite TIME,
    guide_visite BOOLEAN
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _visite à cause de l'héritage
ALTER TABLE _visite
    ADD CONSTRAINT pk_visite PRIMARY KEY (offre_id);    
ALTER TABLE _visite
    ADD CONSTRAINT fk_visite_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);
ALTER TABLE _visite
    ADD CONSTRAINT fk_visite_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre(type_offre_id);
ALTER TABLE _visite
    ADD CONSTRAINT fk_visite_professionnel FOREIGN KEY (id_pro) REFERENCES _professionnel(id_compte);

-- langues parlées durant la visite
CREATE TABLE _langue (
    langue_id SERIAL PRIMARY KEY,
    nom_langue VARCHAR(255)
);

-- Table de lien pour les langues parlées durant les visites
CREATE TABLE _visite_langue (
    offre_id serial REFERENCES _visite(offre_id),
    langue_id serial REFERENCES _langue(langue_id)
);

-- TAG Visites 
create table _tag_visite (
    offre_id serial references _visite(offre_id),
    tag_id serial references _tag(tag_id),
    primary key (offre_id, tag_id)
);
-- ------------------------------------------------------------------------------------------------------- fin




-- -------------------------------------------------------------------------------Parcs d'attractions----- début
-- Table _parc_attraction (hérite de _offre)
CREATE TABLE _parc_attraction (
    nb_attractions INTEGER,
    age_requis integer
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _parc_attraction à cause de l'héritage
ALTER TABLE _parc_attraction
    ADD CONSTRAINT pk_parc_attraction PRIMARY KEY (offre_id);    
ALTER TABLE _parc_attraction
    ADD CONSTRAINT fk_parc_attraction_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse(adresse_id);
ALTER TABLE _parc_attraction
    ADD CONSTRAINT fk_parc_attraction_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre(type_offre_id);
ALTER TABLE _parc_attraction
    ADD CONSTRAINT fk_parc_attraction_professionnel FOREIGN KEY (id_pro) REFERENCES _professionnel(id_compte);

-- TAG Parcs
create table _tag_parc_attraction (
    offre_id serial references _parc_attraction(offre_id),
    tag_id serial references _tag(tag_id),
    primary key (offre_id, tag_id)
);
-- ------------------------------------------------------------------------------------------------------- fin




----------------------------------------------------------------------------------------- autres -- début
-- Table Horaire
CREATE TABLE _horaire (
    horaire_id SERIAL PRIMARY KEY,
    ouverture TIME NOT NULL,
    fermeture TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    offre_id serial REFERENCES _offre(offre_id)
);

-- Table TARIF public
CREATE TABLE _tarif_public (
    tarif_id SERIAL PRIMARY KEY,
    titre_tarif VARCHAR(255) NOT NULL,
    age_min INTEGER,
    age_max INTEGER,
    prix INTEGER,
    offre_id INTEGER NOT NULL
);

-- Table T_IMAGE_IMG
CREATE TABLE T_Image_Img (
    -- IMG = IMaGe
    img_path varchar(255) primary key,
    img_date_creation DATE NOT NULL,
    img_description TEXT,
    img_date_suppression DATE,
    offre_id INTEGER REFERENCES _offre(offre_id) ON DELETE CASCADE,
    parc_id INTEGER REFERENCES _parc_attraction(offre_id) ON DELETE CASCADE,
    -- Contrainte d'exclusivité : soit offre_id, soit parc_id doit être non nul, mais pas les deux
    CONSTRAINT chk_offre_parc_exclusif CHECK (
        (
            offre_id IS NOT NULL
            AND parc_id IS NULL
        )
        OR (
            offre_id IS NULL
            AND parc_id IS NOT NULL
        )
    )
);
-- ------------------------------------------------------------------------------------------------------- fin

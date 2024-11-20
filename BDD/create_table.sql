DROP SCHEMA IF EXISTS "sae_db" CASCADE;
CREATE SCHEMA sae_db;
SET SCHEMA 'sae_db';


-- -------------------------------------------------------------------------------------------Adresse----- début

-- Table Adresse
CREATE TABLE _adresse (
    id_adresse SERIAL PRIMARY KEY,
    code_postal CHAR(5) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    numero varchar(255) not null,
    odonyme varchar(255) not null,
    complement_adresse varchar(255)
);
-- ------------------------------------------------------------------------------------------------------- fin
-- -----------------------------------------------------------------------------------------Comptes-------début 
-- Table abstraite Compte
CREATE TABLE _compte (
    id_compte SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    mdp_hash VARCHAR(255) NOT NULL,
    num_tel VARCHAR(255) NOT NULL,
    id_adresse integer REFERENCES _adresse(id_adresse) NOT NULL
);

-- Table Membre
CREATE TABLE _membre (
    pseudo VARCHAR(255) PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL
) INHERITS (_compte);

-- Héritage des types de comptes
CREATE TABLE _professionnel (
  nomPro varchar(255) not null
) INHERITS (_compte);

ALTER TABLE _professionnel ADD CONSTRAINT unique_id_compte_Professionnel UNIQUE (id_compte);


CREATE TABLE _pro_prive (
    num_siren VARCHAR(255) NOT NULL
) INHERITS (_professionnel);

ALTER TABLE _pro_prive ADD CONSTRAINT unique_id_compte_Pro_Prive UNIQUE (id_compte);


CREATE TABLE _pro_public (
    type_orga VARCHAR(255) NOT NULL
) INHERITS (_professionnel);

ALTER TABLE _pro_public ADD CONSTRAINT unique_id_compte_Pro_Public UNIQUE (id_compte);

-- ------------------------------------------------------------------------------------------------------- fin
-- ----------------------------------------------------------------------------------------------Avis----- début

CREATE TABLE _avis (
  date_publication DATE,
  date_experience DATE,
  reponse_pro varchar(1024),
  titre varchar(50),
  commentaire varchar(1024)
);
-- ----------------------------------------------------------------------------------------------RIB------ début
-- Table RIB
CREATE TABLE _RIB (
    id_rib SERIAL PRIMARY KEY,
    code_banque varchar(255) NOT NULL,
    code_guichet varchar(255) NOT NULL,
    numero_compte varchar(255) NOT NULL,
    cle_rib varchar(255) NOT NULL,
    compte_id serial REFERENCES _pro_prive(id_compte) UNIQUE
);

-- ------------------------------------------------------------------------------------------------------- fin
-- -----------------------------------------------------------------------------------------------TAG----- début
-- Table TAG

CREATE TABLE _tag (
    id_tag SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);

-- ---------------------------------------------------------------------------------------------Offre----- début
create table _type_offre (
  type_offre_id SERIAL PRIMARY KEY not null,
  nom_type_offre varchar(255) not null
);


CREATE TABLE _offre (
    id_offre SERIAL PRIMARY KEY,
    est_en_ligne BOOLEAN NOT NULL,
    description_offre TEXT,
    resume_offre TEXT,
    prix_mini FLOAT,
    titre varchar(255) NOT NULL,
    date_creation DATE NOT NULL,
    date_mise_a_jour DATE,
    date_suppression DATE,
    id_pro integer references _professionnel(id_compte),
    type_offre_id integer references _type_offre(type_offre_id),
    id_adresse serial REFERENCES _adresse(id_adresse),
    option VARCHAR(10)
);


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


-- TAGs Offre ------------------------------------------------------------

CREATE TABLE _tag_offre (
    id_offre serial REFERENCES _offre(id_offre),
    id_tag serial REFERENCES _tag(id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- fin
-- --------------------------------------------------------------------------------------------Facture---- début

CREATE TABLE _facture (
    id_facture SERIAL PRIMARY KEY,
    jour_en_ligne DATE NOT NULL,
    id_offre serial REFERENCES _offre(id_offre)
);
-- ------------------------------------------------------------------------------------------------------- fin
-- -----------------------------------------------------------------------------------------------Logs---- début

CREATE TABLE _log_changement_status (
    id_log_changement_status SERIAL PRIMARY KEY,
    id_offre serial REFERENCES _offre(id_offre),
    date_changement DATE NOT NULL
);

-- ------------------------------------------------------------------------------------------------------- fin
-- -------------------------------------------------------------------------------------Restaurants------- début

-- Types de repas pour les restaurants --------------------------------------------------------

-- Table de lien
-- Type de repas 'petit dej' 'diner' etc...
create table _type_repas (
    type_repas_id SERIAL PRIMARY KEY,
    nom_type_repas VARCHAR(255) NOT NULL UNIQUE
);


-- Héritage pour les types d'offres
CREATE TABLE _restauration (
    id_restauration SERIAL PRIMARY KEY,
    gamme_prix varchar(3) NOT NULL,
    type_repas_id integer references _type_repas(type_repas_id)
) INHERITS (_offre);

create table _restaurant_type_repas (
    id_restauration serial REFERENCES _restauration(id_restauration) ON DELETE CASCADE,
    type_repas_id serial REFERENCES _type_repas(type_repas_id) ON DELETE CASCADE,
    PRIMARY KEY (id_restauration, type_repas_id)
);

-- TAGs Restaurants --------------------------------------------------------
-- Type de restaurant : gastronomie, kebab, etc..
create table _tag_restaurant (
  id_tag_restaurant serial primary key,
  nom_tag varchar(255) not null
);

-- table qui dit que 1 restaurant à 1 tag
create table _tag_restaurant_restauration (
  id_restauration serial references _restauration(id_restauration),
  id_tag_restaurant serial references _tag_restaurant(id_tag_restaurant),
  primary key (id_restauration, id_tag_restaurant)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- ----------------------------------------------------------------------------------------Activités------ début

CREATE TABLE _activite (
    id_activite SERIAL PRIMARY KEY,
    duree_activite TIME,
    age_requis INTEGER,
    prestations VARCHAR(255)
) INHERITS (_offre);

-- TAGs Activité---------------------------------------------
create table _tag_activite (
  id_activite serial references _activite(id_activite),
  id_tag serial references _tag(id_tag),
  primary key (id_activite, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- -----------------------------------------------------------------------------------------Spectacles---- début
-- Spectacles ---------------------------------------------------

CREATE TABLE _spectacle (
    id_spectacle SERIAL PRIMARY KEY,
    capacite_spectacle INTEGER,
    duree_spectacle TIME
) INHERITS (_offre);


-- TAG Spectacles 
create table _tag_spectacle (
  id_spectacle serial references _spectacle(id_spectacle),
  id_tag serial references _tag(id_tag),
  primary key (id_spectacle, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- --------------------------------------------------------------------------------------------Visites---- début

CREATE TABLE _visite (
    id_visite SERIAL PRIMARY KEY,
    duree_visite TIME,
    guide_visite BOOLEAN
) INHERITS (_offre);

-- langues parlées durant la visite
CREATE TABLE _langue (
    id_langue SERIAL PRIMARY KEY,
    nom_langue VARCHAR(255)
);

-- Table de lien pour les langues parlées durant les visites
CREATE TABLE _visite_langue (
    id_visite serial REFERENCES _visite(id_visite),
    id_langue serial REFERENCES _langue(id_langue)
);

-- TAG Visites 
create table _tag_visite (
  id_visite serial references _visite(id_visite),
  id_tag serial references _tag(id_tag),
  primary key (id_visite, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- -------------------------------------------------------------------------------Parcs d'attractions----- début

CREATE TABLE _parc_attraction (
    id_parc_attraction SERIAL PRIMARY KEY,
    nb_attractions INTEGER,
    age_requis integer
) INHERITS (_offre);

-- TAG Parcs
create table _tag_parc_attraction (
  id_parc_attraction serial references _parc_attraction(id_parc_attraction),
  id_tag serial references _tag(id_tag),
  primary key (id_parc_attraction, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
----------------------------------------------------------------------------------------- autres
-- Table Horaire
CREATE TABLE _horaire (
    id_horaire SERIAL PRIMARY KEY,
    ouverture TIME NOT NULL,
    fermeture TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    id_offre serial REFERENCES _offre(id_offre)
);

-- Table TARIF public
CREATE TABLE _tarif_public (
    id_tarif SERIAL PRIMARY KEY,
    titre_tarif VARCHAR(255) NOT NULL,
    age_min INTEGER,
    age_max INTEGER,
    prix FLOAT,
    id_offre INTEGER NOT NULL
);

------------------------------------------------------------------ stockage images




-- Table T_IMAGE_IMG
CREATE TABLE T_Image_Img ( -- IMG = IMaGe
    img_path varchar(255) primary key,
    img_date_creation DATE NOT NULL,
    img_description TEXT,
    img_date_suppression DATE,
    id_offre INTEGER REFERENCES _offre(id_offre) ON DELETE CASCADE,
    parc_id INTEGER REFERENCES _parc_attraction(id_parc_attraction) ON DELETE CASCADE,
    
    -- Contrainte d'exclusivité : soit id_offre, soit parc_id doit être non nul, mais pas les deux
    CONSTRAINT chk_offre_parc_exclusif CHECK (
        (id_offre IS NOT NULL AND parc_id IS NULL) OR 
        (id_offre IS NULL AND parc_id IS NOT NULL)
    )
);

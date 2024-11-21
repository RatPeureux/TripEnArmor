DROP SCHEMA IF EXISTS "sae_db" CASCADE;
CREATE SCHEMA sae_db;
SET SCHEMA 'sae_db';


-- -------------------------------------------------------------------------------------------Adresse----- début

-- Table Adresse
CREATE TABLE _adresse ( -- Léo
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


CREATE TABLE _pro_prive ( -- V-A
    num_siren VARCHAR(255) NOT NULL
) INHERITS (_professionnel);

ALTER TABLE _pro_prive ADD CONSTRAINT unique_id_compte_Pro_Prive UNIQUE (id_compte);


CREATE TABLE _pro_public ( -- V-A
    type_orga VARCHAR(255) UNIQUE NOT NULL
) INHERITS (_professionnel);

CREATE TABLE _pro_prive (
    num_siren VARCHAR(255) UNIQUE NOT NULL
) INHERITS (_professionnel);

-- Rajouter les contraintes principales perdues à cause de l'héritage (clés primaires & étrangères & UNIQUE);
ALTER TABLE _professionnel
ADD CONSTRAINT pk_professionnel PRIMARY KEY (id_compte);

ALTER TABLE _professionnel
ADD CONSTRAINT unique_mail_professionnel UNIQUE (email);

ALTER TABLE _membre ADD CONSTRAINT pk_membre PRIMARY KEY (id_compte);

ALTER TABLE _membre ADD CONSTRAINT unique_mail_membre UNIQUE (email);

ALTER TABLE _membre
ADD CONSTRAINT unique_tel_membre UNIQUE (num_tel);

ALTER TABLE _membre
ADD CONSTRAINT fk_membre FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

CREATE TRIGGER tg_unique_vals_compte BEFORE
INSERT
    ON _membre FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte ();

ALTER TABLE _pro_public
ADD CONSTRAINT pk_pro_public PRIMARY KEY (id_compte);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_mail_pro_public UNIQUE (email);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_tel_pro_public UNIQUE (num_tel);

ALTER TABLE _pro_public
ADD CONSTRAINT fk_pro_public FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

CREATE TRIGGER tg_unique_vals_compte BEFORE
INSERT
    ON _pro_public FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte ();

ALTER TABLE _pro_prive
ADD CONSTRAINT pk_pro_prive PRIMARY KEY (id_compte);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_mail_pro_prive UNIQUE (email);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_tel_pro_prive UNIQUE (num_tel);

ALTER TABLE _pro_prive
ADD CONSTRAINT fk_pro_prive FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

CREATE TRIGGER tg_unique_vals_compte BEFORE
INSERT
    ON _pro_prive FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte ();

-- ------------------------------------------------------------------------------------------------------- fin
-- ----------------------------------------------------------------------------------------------Avis----- début

CREATE TABLE _avis ( -- Maxime
  date_publication DATE,
  date_experience DATE,
  reponse_pro varchar(1024),
  titre varchar(50),
  commentaire varchar(1024)
);
-- ----------------------------------------------------------------------------------------------RIB------ début
-- Table RIB
CREATE TABLE _RIB ( -- Léo
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

CREATE TABLE _tag ( -- V-A
    id_tag SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);

-- ---------------------------------------------------------------------------------------------Offre----- début
create table _type_offre ( -- V-A
  id_type_offre SERIAL PRIMARY KEY not null,
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

CREATE TABLE _tag_offre ( -- Maxime
    id_offre serial REFERENCES _offre(id_offre),
    id_tag serial REFERENCES _tag(id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- fin
-- --------------------------------------------------------------------------------------------Facture---- début

CREATE TABLE _facture ( -- Maxime
    id_facture SERIAL PRIMARY KEY,
    jour_en_ligne DATE NOT NULL,
    id_offre serial REFERENCES _offre(id_offre)
);
-- ------------------------------------------------------------------------------------------------------- fin

-- -----------------------------------------------------------------------------------------------Logs---- début

CREATE TABLE _log_changement_status ( -- Maxime
    id_log_changement_status SERIAL PRIMARY KEY,
    id_offre serial REFERENCES _offre(id_offre),
    date_changement DATE NOT NULL
);

-- ------------------------------------------------------------------------------------------------------- fin

-- Fonction pour vérifier une clé étrangère manuellement, car sinon pb avec raisons de double héritage
CREATE OR REPLACE FUNCTION fk_vers_professionnel() RETURNS TRIGGER AS $$
BEGIN
    -- Alerter quand la clé étrangère n'est pas respecté
    IF NOT EXISTS (SELECT 1 FROM _pro_prive WHERE id_compte = NEW.id_pro)
    AND NOT EXISTS (SELECT 1 FROM _pro_public WHERE id_compte = NEW.id_pro) THEN
        RAISE EXCEPTION 'Foreign key violation: id_pro does not exist in _pro_prive or _pro_public';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- -------------------------------------------------------------------------------------Restaurants------- début

-- Types de repas pour les restaurants --------------------------------------------------------

-- Table de lien
-- Type de repas 'petit dej' 'diner' etc...
create table _type_repas ( -- Baptiste
    type_repas_id SERIAL PRIMARY KEY,
    nom_type_repas VARCHAR(255) NOT NULL UNIQUE
);


-- Héritage pour les types d'offres
CREATE TABLE _restauration ( -- (MVC) Léo
    id_restauration SERIAL PRIMARY KEY,
    gamme_prix varchar(3) NOT NULL,
    type_repas_id integer references _type_repas(type_repas_id)
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _restauration à cause de l'héritage
ALTER TABLE _restauration
ADD CONSTRAINT pk_restauration PRIMARY KEY (offre_id);

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre (type_offre_id);

CREATE TRIGGER fk_restauration_professionnel BEFORE
INSERT
    ON _restauration FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- ALTER TABLE _restauration
-- ADD CONSTRAINT fk_restauration_professionnel FOREIGN KEY (id_pro) REFERENCES _pro_prive (id_compte);

-- Lien entre restauration et type_repas
create table _restaurant_type_repas ( -- Baptiste
    id_restauration serial REFERENCES _restauration(id_restauration) ON DELETE CASCADE,
    type_repas_id serial REFERENCES _type_repas(type_repas_id) ON DELETE CASCADE,
    PRIMARY KEY (id_restauration, type_repas_id)
);

-- TAGs Restaurants --------------------------------------------------------
-- Type de restaurant : gastronomie, kebab, etc..
create table _tag_restaurant ( -- Maxime
  id_tag_restaurant serial primary key,
  nom_tag varchar(255) not null
);

-- table qui dit que 1 restaurant à 1 tag
create table _tag_restaurant_restauration ( -- Maxime
  id_restauration serial references _restauration(id_restauration),
  id_tag_restaurant serial references _tag_restaurant(id_tag_restaurant),
  primary key (id_restauration, id_tag_restaurant)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- ----------------------------------------------------------------------------------------Activités------ début

CREATE TABLE _activite ( -- MVC (Léo)
    id_activite SERIAL PRIMARY KEY,
    duree_activite TIME,
    age_requis INTEGER,
    prestations VARCHAR(255)
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _activite à cause de l'héritage
ALTER TABLE _activite
ADD CONSTRAINT pk_activite PRIMARY KEY (offre_id);

ALTER TABLE _activite
ADD CONSTRAINT fk_activite_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

ALTER TABLE _activite
ADD CONSTRAINT fk_activite_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre (type_offre_id);

CREATE TRIGGER fk_restauration_professionnel BEFORE
INSERT
    ON _activite FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- TAGs Activité---------------------------------------------
create table _tag_activite ( -- Maxime
  id_activite serial references _activite(id_activite),
  id_tag serial references _tag(id_tag),
  primary key (id_activite, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- -----------------------------------------------------------------------------------------Spectacles---- début
-- Spectacles ---------------------------------------------------

CREATE TABLE _spectacle ( -- MVC (Léo)
    id_spectacle SERIAL PRIMARY KEY,
    capacite_spectacle INTEGER,
    duree_spectacle TIME
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _spectacle à cause de l'héritage
ALTER TABLE _spectacle
ADD CONSTRAINT pk_spectacle PRIMARY KEY (offre_id);

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre (type_offre_id);

CREATE TRIGGER fk_restauration_professionnel BEFORE
INSERT
    ON _spectacle FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- TAG Spectacles 
create table _tag_spectacle ( -- Maxime
  id_spectacle serial references _spectacle(id_spectacle),
  id_tag serial references _tag(id_tag),
  primary key (id_spectacle, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- --------------------------------------------------------------------------------------------Visites---- début

CREATE TABLE _visite ( -- MVC (Léo)
    id_visite SERIAL PRIMARY KEY,
    duree_visite TIME,
    guide_visite BOOLEAN
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _visite à cause de l'héritage
ALTER TABLE _visite ADD CONSTRAINT pk_visite PRIMARY KEY (offre_id);

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre (type_offre_id);

CREATE TRIGGER fk_restauration_professionnel BEFORE
INSERT
    ON _visite FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- langues parlées durant la visite
CREATE TABLE _langue ( -- V-A
    id_langue SERIAL PRIMARY KEY,
    nom_langue VARCHAR(255)
);

-- Table de lien pour les langues parlées durant les visites
CREATE TABLE _visite_langue ( -- Antoine
    id_visite serial REFERENCES _visite(id_visite),
    id_langue serial REFERENCES _langue(id_langue)
);

-- TAG Visites 
create table _tag_visite ( -- Maxime
  id_visite serial references _visite(id_visite),
  id_tag serial references _tag(id_tag),
  primary key (id_visite, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
-- -------------------------------------------------------------------------------Parcs d'attractions----- début

CREATE TABLE _parc_attraction ( -- MVC (Léo)
    id_parc_attraction SERIAL PRIMARY KEY,
    nb_attractions INTEGER,
    age_requis integer
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _parc_attraction à cause de l'héritage
ALTER TABLE _parc_attraction
ADD CONSTRAINT pk_parc_attraction PRIMARY KEY (offre_id);

ALTER TABLE _parc_attraction
ADD CONSTRAINT fk_parc_attraction_adresse FOREIGN KEY (adresse_id) REFERENCES _adresse (adresse_id);

ALTER TABLE _parc_attraction
ADD CONSTRAINT fk_parc_attraction_type_offre FOREIGN KEY (type_offre_id) REFERENCES _type_offre (type_offre_id);

CREATE TRIGGER fk_restauration_professionnel BEFORE
INSERT
    ON _parc_attraction FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- TAG Parcs
create table _tag_parc_attraction ( -- Maxime
  id_parc_attraction serial references _parc_attraction(id_parc_attraction),
  id_tag serial references _tag(id_tag),
  primary key (id_parc_attraction, id_tag)
);

-- ------------------------------------------------------------------------------------------------------- fin
----------------------------------------------------------------------------------------- autres
-- Table Horaire
CREATE TABLE _horaire ( -- Antoine
    id_horaire SERIAL PRIMARY KEY,
    ouverture TIME NOT NULL,
    fermeture TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    id_offre serial REFERENCES _offre(id_offre)
);

-- Table TARIF public
CREATE TABLE _tarif_public ( -- Baptiste
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
-- ------------------------------------------------------------------------------------------------------- fin
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
CREATE TABLE _adresse ( -- Léo
    id_adresse SERIAL PRIMARY KEY,
    code_postal CHAR(5) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    numero VARCHAR(255) NOT NULL,
    odonyme VARCHAR(255) NOT NULL,
    complement_adresse VARCHAR(255)
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
    id_adresse INTEGER
);

-- Fonction pour vérifier que tous les comptes ont bien des identifiants différents (~priamry key & UNIQUE constraints perdues par inherits)
CREATE OR REPLACE FUNCTION unique_vals_compte() RETURNS TRIGGER AS $$
BEGIN
    -- Check pour l'id
    IF EXISTS (SELECT 1 FROM sae_db._compte WHERE email = NEW.email) THEN
        RAISE EXCEPTION 'Erreur : valeur dupliquée pour l''adresse email dans deux comptes différents';
    END IF;
    -- Check pour le mail
    IF EXISTS (SELECT 1 FROM sae_db._compte WHERE email = NEW.email) THEN
        RAISE EXCEPTION 'Erreur : valeur dupliquée pour l''adresse email dans deux comptes différents';
    END IF;
    -- Check pour le numero de tel
    IF EXISTS (SELECT 1 FROM sae_db._compte WHERE email = NEW.email) THEN
        RAISE EXCEPTION 'Erreur : valeur dupliquée pour l''adresse email dans deux comptes différents';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Table _membre
CREATE TABLE _membre (
    pseudo VARCHAR(255) UNIQUE,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL
) INHERITS (_compte);

-- Héritage des types de _compte (abstr.)
CREATE TABLE _professionnel (nom_pro VARCHAR(255) NOT NULL) INHERITS (_compte);

CREATE TABLE _pro_public ( -- Antoine
    type_orga VARCHAR(255) NOT NULL
) INHERITS (_professionnel);

CREATE TABLE _pro_prive ( -- Antoine
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
ADD CONSTRAINT fk_membre FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

CREATE TRIGGER tg_unique_vals_compte
BEFORE INSERT ON _membre
FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte();




ALTER TABLE _pro_public
ADD CONSTRAINT pk_pro_public PRIMARY KEY (id_compte);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_mail_pro_public UNIQUE (email);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_tel_pro_public UNIQUE (num_tel);

ALTER TABLE _pro_public
ADD CONSTRAINT fk_pro_public FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

CREATE TRIGGER tg_unique_vals_compte
BEFORE INSERT ON _pro_public
FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte();





ALTER TABLE _pro_prive
ADD CONSTRAINT pk_pro_prive PRIMARY KEY (id_compte);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_mail_pro_prive UNIQUE (email);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_tel_pro_prive UNIQUE (num_tel);

ALTER TABLE _pro_prive
ADD CONSTRAINT fk_pro_prive FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

CREATE TRIGGER tg_unique_vals_compte
BEFORE INSERT ON _pro_prive
FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte();

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
    code_banque VARCHAR(255) NOT NULL,
    code_guichet VARCHAR(255) NOT NULL,
    numero_compte VARCHAR(255) NOT NULL,
    cle_rib VARCHAR(255) NOT NULL,
    id_compte SERIAL REFERENCES _pro_prive (id_compte) UNIQUE
);

-- ------------------------------------------------------------------------------------------------------- fin

-- -----------------------------------------------------------------------------------------------TAG----- début
-- Table TAG

CREATE TABLE _tag ( -- Antoine
    id_tag SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);
-- -------------------------------------------------------------------------------------------------------- fin

-- ---------------------------------------------------------------------------------------------Offre----- début
-- Table _type_offre (gratuite OU standard OU prenium)
-- Antoine
create table _type_offre (
    id_type_offre SERIAL PRIMARY KEY NOT NULL,
    nom_type_offre VARCHAR(255) NOT NULL
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
    id_offre SERIAL PRIMARY KEY,
    est_en_ligne BOOLEAN NOT NULL,
    description TEXT,
    resume TEXT,
    prix_mini FLOAT,
    titre VARCHAR(255) NOT NULL,
    date_creation DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATE,
    date_suppression DATE,
    id_type_offre INTEGER REFERENCES _type_offre (id_type_offre),
    id_pro INTEGER REFERENCES _professionnel (id_compte),
    id_adresse SERIAL REFERENCES _adresse (id_adresse),
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
-- Maxime
CREATE TABLE _tag_offre (
    id_offre SERIAL REFERENCES _offre (id_offre),
    id_tag SERIAL REFERENCES _tag (id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- fin

-- --------------------------------------------------------------------------------------------Facture---- début
-- Maxime
CREATE TABLE _facture (
    id_facture SERIAL PRIMARY KEY,
    jour_en_ligne DATE NOT NULL,
    id_offre SERIAL REFERENCES _offre (id_offre)
);

-- ------------------------------------------------------------------------------------------------------- fin



-- -----------------------------------------------------------------------------------------------Logs---- début
CREATE TABLE _log_changement_status ( -- Maxime
    id SERIAL PRIMARY KEY,
    id_offre SERIAL REFERENCES _offre (id_offre),
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
-- Type de repas 'petit dej' 'diner' etc...
create table _type_repas ( -- Baptiste
    id_type_repas SERIAL PRIMARY KEY,
    nom_type_repas VARCHAR(255) NOT NULL UNIQUE
);

-- Table _restauration (hérite _offre)
-- (MVC) Léo
CREATE TABLE _restauration (
    gamme_prix VARCHAR(3) NOT NULL,
    id_type_repas INTEGER REFERENCES _type_repas (id_type_repas)
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _restauration à cause de l'héritage
ALTER TABLE _restauration
ADD CONSTRAINT pk_restauration PRIMARY KEY (id_offre);

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);

CREATE TRIGGER fk_restauration_professionnel
BEFORE INSERT ON _restauration
FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel();

-- ALTER TABLE _restauration
-- ADD CONSTRAINT fk_restauration_professionnel FOREIGN KEY (id_pro) REFERENCES _pro_prive (id_compte);

-- Lien entre restauration et type_repas
create table _restaurant_type_repas ( -- Baptiste
    id_offre SERIAL REFERENCES _restauration (id_offre) ON DELETE CASCADE,
    id_type_repas SERIAL REFERENCES _type_repas (id_type_repas) ON DELETE CASCADE,
    PRIMARY KEY (id_offre, id_type_repas)
);

-- Type de restaurant : gastronomie, kebab, etc..
create table _tag_restaurant (
    -- Maxime
    id_tag_restaurant SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);

-- table 1 restaurant <-> 1..* tag
-- Maxime
create table _tag_restaurant_restauration (
    id_offre SERIAL REFERENCES _restauration (id_offre),
    id_tag_restaurant SERIAL REFERENCES _tag_restaurant (id_tag_restaurant),
    PRIMARY KEY (id_offre, id_tag_restaurant)
);
-- ------------------------------------------------------------------------------------------------------- fin

-- ----------------------------------------------------------------------------------------Activités------ début
-- Table _activite (hérite de _offre)
-- (MVC) Léo
CREATE TABLE _activite (
    duree_activite TIME,
    age_requis INTEGER,
    prestations VARCHAR(255)
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _activite à cause de l'héritage
ALTER TABLE _activite
ADD CONSTRAINT pk_activite PRIMARY KEY (id_offre);

ALTER TABLE _activite
ADD CONSTRAINT fk_activite_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _activite
ADD CONSTRAINT fk_activite_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);

CREATE TRIGGER fk_restauration_professionnel
BEFORE INSERT ON _activite
FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel();

-- TAGs Activité---------------------------------------------
create table _tag_activite ( -- Maxime
    id_offre SERIAL REFERENCES _activite (id_offre),
    id_tag SERIAL REFERENCES _tag (id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- fin

-- -----------------------------------------------------------------------------------------Spectacles---- début
-- Table _spectacle (hérite de _offre)
CREATE TABLE _spectacle ( -- (MVC) Léo
    capacite_spectacle INTEGER,
    duree_spectacle TIME
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _spectacle à cause de l'héritage
ALTER TABLE _spectacle
ADD CONSTRAINT pk_spectacle PRIMARY KEY (id_offre);

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);

CREATE TRIGGER fk_restauration_professionnel
BEFORE INSERT ON _spectacle
FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel();

-- TAG Spectacles
create table _tag_spectacle ( -- Maxime
    id_offre SERIAL REFERENCES _spectacle (id_offre),
    id_tag SERIAL REFERENCES _tag (id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- fin

-- --------------------------------------------------------------------------------------------Visites---- début
-- Table _visite (hérite de _offre)
-- (MVC) Léo
CREATE TABLE _visite (
    duree_visite TIME,
    guide_visite BOOLEAN
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _visite à cause de l'héritage
ALTER TABLE _visite ADD CONSTRAINT pk_visite PRIMARY KEY (id_offre);

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);

CREATE TRIGGER fk_restauration_professionnel
BEFORE INSERT ON _visite
FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel();

-- langues parlées durant la visite
CREATE TABLE _langue ( -- Antoine
    id_langue SERIAL PRIMARY KEY,
    nom_langue VARCHAR(255)
);

-- Table de lien pour les langues parlées durant les visites
CREATE TABLE _visite_langue ( -- Antoine
    id_offre SERIAL REFERENCES _visite (id_offre),
    id_langue SERIAL REFERENCES _langue (id_langue)
);

-- TAG Visites
create table _tag_visite ( -- Maxime
    id_offre SERIAL REFERENCES _visite (id_offre),
    id_tag SERIAL REFERENCES _tag (id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- fin

-- -------------------------------------------------------------------------------Parcs d'attractions----- début
-- Table _parc_attraction (hérite de _offre)
CREATE TABLE _parc_attraction ( -- (MVC) Léo
    nb_attractions INTEGER,
    age_requis INTEGER
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _parc_attraction à cause de l'héritage
ALTER TABLE _parc_attraction
ADD CONSTRAINT pk_parc_attraction PRIMARY KEY (id_offre);

ALTER TABLE _parc_attraction
ADD CONSTRAINT fk_parc_attraction_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _parc_attraction
ADD CONSTRAINT fk_parc_attraction_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);

CREATE TRIGGER fk_restauration_professionnel
BEFORE INSERT ON _parc_attraction
FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel();

-- TAG Parcs
create table _tag_parc_attraction ( -- Maxime
    id_offre SERIAL REFERENCES _parc_attraction (id_offre),
    id_tag SERIAL REFERENCES _tag (id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- fin

----------------------------------------------------------------------------------------- autres -- début
-- Table Horaire
CREATE TABLE _horaire ( -- Antoine
    id_horaire SERIAL PRIMARY KEY,
    ouverture TIME NOT NULL,
    fermeture TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    id_offre SERIAL REFERENCES _offre (id_offre)
);

-- Table TARIF public
CREATE TABLE _tarif_public ( -- Baptiste
    id_tarif SERIAL PRIMARY KEY,
    titre_tarif VARCHAR(255) NOT NULL,
    age_min INTEGER,
    age_max INTEGER,
    prix INTEGER,
    id_offre INTEGER NOT NULL
);

-- Table T_IMAGE_IMG
CREATE TABLE T_Image_Img (
    -- IMG = IMaGe
    img_path VARCHAR(255) PRIMARY KEY,
    img_date_creation DATE NOT NULL,
    img_description TEXT,
    img_date_suppression DATE,
    id_offre INTEGER REFERENCES _offre (id_offre) ON DELETE CASCADE,
    id_parc INTEGER REFERENCES _parc_attraction (id_offre) ON DELETE CASCADE,
    -- Contrainte d'exclusivité : soit id_offre, soit id_parc doit être non nul, mais pas les deux
    CONSTRAINT chk_offre_parc_exclusif CHECK (
        (
            id_offre IS NOT NULL
            AND id_parc IS NULL
        )
        OR (
            id_offre IS NULL
            AND id_parc IS NOT NULL
        )
    )
);
-- ------------------------------------------------------------------------------------------------------- fin

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

-- ------------------------------------------------------------------------------------------------------- Adresse
-- Table Adresse
CREATE TABLE _adresse (
    id_adresse SERIAL PRIMARY KEY,
    code_postal CHAR(5) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    numero VARCHAR(255) NOT NULL,
    odonyme VARCHAR(255) NOT NULL,
    complement VARCHAR(255)
);
-- ------------------------------------------------------------------------------------------------------- Comptes
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

-- Table _membre
CREATE TABLE _membre (
    pseudo VARCHAR(255) UNIQUE,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL
) INHERITS (_compte);

-- Héritage des types de _compte (abstr.)
CREATE TABLE _professionnel (nom_pro VARCHAR(255) NOT NULL) INHERITS (_compte);

CREATE TABLE _pro_public (
    type_orga VARCHAR(255) NOT NULL
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
ADD CONSTRAINT fk_membre FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _pro_public
ADD CONSTRAINT pk_pro_public PRIMARY KEY (id_compte);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_mail_pro_public UNIQUE (email);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_tel_pro_public UNIQUE (num_tel);

ALTER TABLE _pro_public
ADD CONSTRAINT fk_pro_public FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _pro_prive
ADD CONSTRAINT pk_pro_prive PRIMARY KEY (id_compte);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_mail_pro_prive UNIQUE (email);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_tel_pro_prive UNIQUE (num_tel);

ALTER TABLE _pro_prive
ADD CONSTRAINT fk_pro_prive FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

-- ------------------------------------------------------------------------------------------------------- RIB
-- Table RIB
CREATE TABLE _RIB (
    id_rib SERIAL PRIMARY KEY,
    code_banque VARCHAR(255) NOT NULL,
    code_guichet VARCHAR(255) NOT NULL,
    numero_compte VARCHAR(255) NOT NULL,
    cle VARCHAR(255) NOT NULL,
    id_compte SERIAL REFERENCES _pro_prive (id_compte) UNIQUE
);

-- ------------------------------------------------------------------------------------------------------- TAG
-- Table TAG

CREATE TABLE _tag (
    id_tag SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- ------------------------------------------------------------------------------------------------------- Offre
-- Table _type_offre (gratuite OU standard OU prenium)

create table _type_offre (
    id_type_offre SERIAL PRIMARY KEY NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prix_ttc FLOAT,
    prix_ht FLOAT
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
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    resume TEXT,
    prix_mini FLOAT,
    date_creation DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATE,
    date_suppression DATE,
    est_en_ligne BOOLEAN NOT NULL,
    id_type_offre INTEGER REFERENCES _type_offre (id_type_offre),
    id_pro INTEGER REFERENCES _professionnel (id_compte),
    id_adresse SERIAL REFERENCES _adresse (id_adresse),
    option VARCHAR(10)
);

--  ------------------------------------------------------------------------------------------------------ TAGs Offre
-- Maxime
CREATE TABLE _tag_offre (
    id_offre SERIAL REFERENCES _offre (id_offre),
    id_tag SERIAL REFERENCES _tag (id_tag),
    PRIMARY KEY (id_offre, id_tag)
);
-- ------------------------------------------------------------------------------------------------------- Avis

-- Création de la table _avis
CREATE TABLE _avis (
    id_avis SERIAL PRIMARY KEY, -- id unique
    date_publication DATE NOT NULL, 
    date_experience DATE NOT NULL, -- date où la personne a visité/mangé/...
    titre VARCHAR(50), -- titre de l'avis
    commentaire VARCHAR(1024), -- commentaire de l'avis
    id_compte INT NOT NULL, -- compte de l'utilisateur  |
    id_offre INT NOT NULL, -- Offre à laquelle est lié l'avis
    id_avis_reponse INT REFERENCES _avis(id_avis), -- id de l'avis de la réponse du pro
    -- Contrainte pour empêcher plusieurs avis initiaux d'un même membre sur une offre
    CONSTRAINT unique_avis_per_member UNIQUE (id_compte, id_offre)
);

-- ------------------------------------------------------------------------------------------------------- Facture
-- Maxime
CREATE TABLE _facture (
    id_facture SERIAL PRIMARY KEY,
    jour_en_ligne DATE NOT NULL,
    id_offre SERIAL REFERENCES _offre (id_offre)
);
-- ------------------------------------------------------------------------------------------------------- Logs
CREATE TABLE _log_changement_status ( -- Maxime
    id_log_changement_status SERIAL PRIMARY KEY,
    id_offre SERIAL REFERENCES _offre (id_offre),
    date_changement DATE NOT NULL
);

-- ------------------------------------------------------------------------------------------------------- Restaurants
-- Type de repas 'petit dej' 'diner' etc...
create table _type_repas (
    id_type_repas SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

-- Table _restauration (hérite _offre)

CREATE TABLE _restauration (
    gamme_prix VARCHAR(3) NOT NULL,
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _restauration à cause de l'héritage
ALTER TABLE _restauration
ADD CONSTRAINT pk_restauration PRIMARY KEY (id_offre);

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);

-- Lien entre restauration et type_repas
create table _restaurant_type_repas (
    id_offre SERIAL REFERENCES _restauration (id_offre) ON DELETE CASCADE,
    id_type_repas SERIAL REFERENCES _type_repas (id_type_repas) ON DELETE CASCADE,
    PRIMARY KEY (id_offre, id_type_repas)
);

-- Type de restaurant : gastronomie, kebab, etc..
create table _tag_restaurant (
    -- Maxime
    id_tag_restaurant SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- table 1 restaurant <-> 1..* tag
-- Maxime
create table _tag_restaurant_restauration (
    id_offre SERIAL REFERENCES _restauration (id_offre),
    id_tag_restaurant SERIAL REFERENCES _tag_restaurant (id_tag_restaurant),
    PRIMARY KEY (id_offre, id_tag_restaurant)
);
-- ------------------------------------------------------------------------------------------------------- Activités
-- Table _activite (hérite de _offre)

CREATE TABLE _activite (
    duree TIME,
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
-- ------------------------------------------------------------------------------------------------------- Spectacles
-- Table _spectacle (hérite de _offre)
CREATE TABLE _spectacle (capacite INTEGER, duree TIME) INHERITS (_offre);

-- Rajout des contraintes perdues pour _spectacle à cause de l'héritage_activite
ALTER TABLE _spectacle
ADD CONSTRAINT pk_spectacle PRIMARY KEY (id_offre);

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);
-- ------------------------------------------------------------------------------------------------------- Visites
-- Table _visite (hérite de _offre)

CREATE TABLE _visite (
    duree TIME,
    avec_guide BOOLEAN
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _visite à cause de l'héritage
ALTER TABLE _visite ADD CONSTRAINT pk_visite PRIMARY KEY (id_offre);

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse);

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre);

-- langues parlées durant la visite
CREATE TABLE _langue (
    id_langue SERIAL PRIMARY KEY,
    nom VARCHAR(255)
);

-- Table de lien pour les langues parlées durant les visites
CREATE TABLE _visite_langue (
    id_offre SERIAL REFERENCES _visite (id_offre),
    id_langue SERIAL REFERENCES _langue (id_langue)
);
-- ------------------------------------------------------------------------------------------------------- Parcs d'attractions
-- Table _parc_attraction (hérite de _offre)
CREATE TABLE _parc_attraction (
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
-- ------------------------------------------------------------------------------------------------------- Horaire
-- Table Horaire
CREATE TABLE _horaire (
    id_horaire SERIAL PRIMARY KEY,
    ouverture TIME NOT NULL,
    fermeture TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    id_offre SERIAL REFERENCES _offre (id_offre)
);
-- ------------------------------------------------------------------------------------------------------- Tarif Publique
-- Table TARIF public
CREATE TABLE _tarif_public (
    id_tarif SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    prix INTEGER,
    id_offre INTEGER NOT NULL
);
-- ------------------------------------------------------------------------------------------------------- Images
-- Table T_IMAGE_IMG
CREATE TABLE T_Image_Img (
    -- IMG = IMaGe
    img_path VARCHAR(255) PRIMARY KEY,
    img_date_creation DATE NOT NULL,
    img_description TEXT,
    img_date_suppression DATE,
    id_offre INTEGER REFERENCES _offre (id_offre) ON DELETE CASCADE,
    id_parc INTEGER REFERENCES _parc_attraction (id_offre) ON DELETE CASCADE,
    -- Contrainte d'exclusivité : soit offre_id, soit id_parc doit être non nul, mais pas les deux
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
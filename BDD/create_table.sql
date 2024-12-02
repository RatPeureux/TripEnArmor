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
CREATE TABLE _adresse ( -- Léo -- Léo
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
CREATE TABLE _professionnel (
    nom_pro VARCHAR(255) NOT NULL,
    CONSTRAINT unique_nom_pro UNIQUE (nom_pro)
) INHERITS (_compte);

CREATE TABLE _pro_public ( -- Antoine -- Antoine
    type_orga VARCHAR(255) NOT NULL
) INHERITS (_professionnel);

-- ------------------------------------------------------------------------------------------------------- RIB
-- Table RIB
CREATE TABLE _RIB (
    id_rib SERIAL PRIMARY KEY,
    code_banque VARCHAR(255) NOT NULL,
    code_guichet VARCHAR(255) NOT NULL,
    numero_compte VARCHAR(255) NOT NULL,
    cle VARCHAR(255) NOT NULL
);
-- ------------------------------------------------------------------------------------------------------- TAG

CREATE TABLE _pro_prive ( -- Antoine
    num_siren VARCHAR(255) UNIQUE NOT NULL,
    id_rib INTEGER REFERENCES _rib (id_rib) DEFERRABLE INITIALLY IMMEDIATE
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
ADD CONSTRAINT fk_membre FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _pro_public
ADD CONSTRAINT pk_pro_public PRIMARY KEY (id_compte);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_mail_pro_public UNIQUE (email);

ALTER TABLE _pro_public
ADD CONSTRAINT unique_tel_pro_public UNIQUE (num_tel);

ALTER TABLE _pro_public
ADD CONSTRAINT fk_pro_public FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _pro_prive
ADD CONSTRAINT pk_pro_prive PRIMARY KEY (id_compte);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_mail_pro_prive UNIQUE (email);

ALTER TABLE _pro_prive
ADD CONSTRAINT unique_tel_pro_prive UNIQUE (num_tel);

ALTER TABLE _pro_prive
ADD CONSTRAINT fk_pro_prive_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _pro_prive
ADD CONSTRAINT fk_pro_prive_rib FOREIGN KEY (id_rib) REFERENCES _rib (id_rib) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- TAG
-- Table TAG

CREATE TABLE _tag ( -- Antoine
    id_tag SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);
-- ------------------------------------------------------------------------------------------------------- Option
CREATE TABLE _option (
    nom VARCHAR(50) PRIMARY KEY NOT NULL, -- A la une ou En relief
    prix_ht FLOAT NOT NULL,
    prix_ttc FLOAT, -- déduit par prix_unitaire*nb_semaines
    prix_unitaire FLOAT


);
-- ------------------------------------------------------------------------------------------------------- Souscription
CREATE TABLE _souscription (
    id_souscription SERIAL PRIMARY KEY,
    nb_semaines INTEGER NOT NULL,
    date_lancement DATE NOT NULL
);
-- ------------------------------------------------------------------------------------------------------- Offre
-- Table _type_offre (gratuite OU standard OU premium)
-- Antoine
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
    id_type_offre INTEGER REFERENCES _type_offre (id_type_offre) DEFERRABLE INITIALLY IMMEDIATE,
    id_pro INTEGER REFERENCES _professionnel (id_compte) DEFERRABLE INITIALLY IMMEDIATE,
    id_adresse SERIAL REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE,
    option VARCHAR(10)
);

ALTER TABLE _offre
ADD CONSTRAINT fk_offre_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _offre
ADD CONSTRAINT fk_offre_pro FOREIGN KEY (id_pro) REFERENCES _professionnel (id_compte) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _offre
ADD CONSTRAINT fk_offre_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Relation ternaire entre Offre, Souscription et Option
-- Création de la table de relation ternaire entre _offre, _souscription et _option
CREATE TABLE _offre_souscription_option (
    id_offre INTEGER NOT NULL,
    id_souscription INTEGER NOT NULL,
    nom_option VARCHAR(50) NOT NULL,
    date_association DATE NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY (
        id_offre,
        id_souscription,
        nom_option
    ),
    FOREIGN KEY (id_souscription) REFERENCES _souscription (id_souscription) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE,
    FOREIGN KEY (nom_option) REFERENCES _option (nom) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE
);

--  ------------------------------------------------------------------------------------------------------ TAGs Offre
-- Maxime
CREATE TABLE _tag_offre (
    id_offre INTEGER,
    id_tag SERIAL REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE,
    PRIMARY KEY (id_offre, id_tag)
);

ALTER TABLE _tag_offre
ADD CONSTRAINT fk_tag FOREIGN KEY (id_tag) REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Avis

-- Création de la table _avis
CREATE TABLE _avis (
    id_avis SERIAL PRIMARY KEY, -- id unique
    date_publication DATE NOT NULL,
    date_experience DATE NOT NULL, -- date où la personne a visité/mangé/...
    titre VARCHAR(50), -- titre de l'avis
    commentaire VARCHAR(1024), -- commentaire de l'avis
    note FLOAT,
    contexte_passage VARCHAR(255) NOT NULL,
    id_membre INT NOT NULL, -- compte de l'utilisateur  |
    id_offre INT NOT NULL, -- Offre à laquelle est lié l'avis
    id_avis_reponse INT REFERENCES _avis (id_avis), -- id de l'avis de la réponse du pro
    -- Contrainte pour empêcher plusieurs avis initiaux d'un même membre sur une offre
    CONSTRAINT unique_avis_per_member UNIQUE (id_membre, id_offre)
);

-- ------------------------------------------------------------------------------------------------------- Facture
-- Maxime
CREATE TABLE _facture (
    id_offre INTEGER NOT NULL,
    numero VARCHAR(255),
    designation VARCHAR(255) NOT NULL,
    date_emission DATE NOT NULL,
    date_prestation DATE NOT NULL,
    date_echeance DATE NOT NULL,
    date_lancement DATE NOT NULL,
    nbjours_abonnement INTEGER NOT NULL,
    quantite INTEGER NOT NULL,
    prix_unitaire_HT FLOAT NOT NULL,
    prix_unitaire_TTC FLOAT NOT NULL,
    PRIMARY KEY (numero, designation) -- Clé primaire composite
);

-- ------------------------------------------------------------------------------------------------------- Logs
CREATE TABLE _log_changement_status ( -- Maxime
    id SERIAL PRIMARY KEY,
    id_offre INTEGER NOT NULL,
    date_changement DATE NOT NULL
);

-- ------------------------------------------------------------------------------------------------------- Restaurants
-- Type de repas 'petit dej' 'diner' etc...
create table _type_repas ( -- Baptiste
    id_type_repas SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

-- Table _restauration (hérite _offre)
-- (MVC) Léo
CREATE TABLE _restauration (
    gamme_prix VARCHAR(3) NOT NULL
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _restauration à cause de l'héritage
ALTER TABLE _restauration
ADD CONSTRAINT pk_restauration PRIMARY KEY (id_offre);

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _restauration
ADD CONSTRAINT fk_restauration_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre) DEFERRABLE INITIALLY IMMEDIATE;

-- Lien entre restauration et type_repas
create table _restaurant_type_repas ( -- Baptiste
    id_offre SERIAL REFERENCES _restauration (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE,
    id_type_repas SERIAL REFERENCES _type_repas (id_type_repas) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE,
    PRIMARY KEY (id_offre, id_type_repas)
);

ALTER TABLE _restaurant_type_repas
ADD CONSTRAINT fk_restaurant_type_repas_offre FOREIGN KEY (id_offre) REFERENCES _restauration (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _restaurant_type_repas
ADD CONSTRAINT fk_restaurant_type_repas_type FOREIGN KEY (id_type_repas) REFERENCES _type_repas (id_type_repas) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

-- Type de restaurant : gastronomie, kebab, etc..
create table _tag_restaurant (
    -- Maxime
    id_tag_restaurant SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);

-- table 1 restaurant <-> 1..* tag
-- Maxime
create table _tag_restaurant_restauration (
    id_offre SERIAL REFERENCES _restauration (id_offre) DEFERRABLE INITIALLY IMMEDIATE,
    id_tag_restaurant SERIAL REFERENCES _tag_restaurant (id_tag_restaurant) DEFERRABLE INITIALLY IMMEDIATE,
    PRIMARY KEY (id_offre, id_tag_restaurant)
);

ALTER TABLE _tag_restaurant_restauration
ADD CONSTRAINT fk_tag_restaurant_restauration_offre FOREIGN KEY (id_offre) REFERENCES _restauration (id_offre) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _tag_restaurant_restauration
ADD CONSTRAINT fk_tag_restaurant_restauration_tag FOREIGN KEY (id_tag_restaurant) REFERENCES _tag_restaurant (id_tag_restaurant) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Activités
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
ADD CONSTRAINT fk_activite_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _activite
ADD CONSTRAINT fk_activite_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- TAG Activité
create table _tag_activite ( -- Maxime
    id_offre SERIAL REFERENCES _activite (id_offre) DEFERRABLE INITIALLY IMMEDIATE,
    id_tag SERIAL REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE,
    PRIMARY KEY (id_offre, id_tag)
);

ALTER TABLE _tag_activite
ADD CONSTRAINT fk_tag_activite_offre FOREIGN KEY (id_offre) REFERENCES _activite (id_offre) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _tag_activite
ADD CONSTRAINT fk_tag_activite_tag FOREIGN KEY (id_tag) REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Spectacles
-- Table _spectacle (hérite de _offre)
CREATE TABLE _spectacle (capacite INTEGER, duree TIME) INHERITS (_offre);
-- Rajout des contraintes perdues pour _spectacle à cause de l'héritage
ALTER TABLE _spectacle
ADD CONSTRAINT pk_spectacle PRIMARY KEY (id_offre);

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _spectacle
ADD CONSTRAINT fk_spectacle_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- TAG Spectacles
create table _tag_spectacle ( -- Maxime
    id_offre SERIAL REFERENCES _spectacle (id_offre) DEFERRABLE INITIALLY IMMEDIATE,
    id_tag SERIAL REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE,
    PRIMARY KEY (id_offre, id_tag)
);

ALTER TABLE _tag_spectacle
ADD CONSTRAINT fk_tag_spectacle_offre FOREIGN KEY (id_offre) REFERENCES _spectacle (id_offre) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _tag_spectacle
ADD CONSTRAINT fk_tag_spectacle_tag FOREIGN KEY (id_tag) REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Visites
-- Table _visite (hérite de _offre)
-- (MVC) Léo
CREATE TABLE _visite (
    duree_visite TIME,
    avec_guide BOOLEAN
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _visite à cause de l'héritage
ALTER TABLE _visite ADD CONSTRAINT pk_visite PRIMARY KEY (id_offre);

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _visite
ADD CONSTRAINT fk_visite_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre) DEFERRABLE INITIALLY IMMEDIATE;

-- langues parlées durant la visite
CREATE TABLE _langue ( -- Antoine
    id_langue SERIAL PRIMARY KEY,
    nom VARCHAR(255)
);

-- Table de lien pour les langues parlées durant les visites
CREATE TABLE _visite_langue ( -- Antoine
    id_offre SERIAL REFERENCES _visite (id_offre) DEFERRABLE INITIALLY IMMEDIATE,
    id_langue SERIAL REFERENCES _langue (id_langue) DEFERRABLE INITIALLY IMMEDIATE
);

ALTER TABLE _visite_langue
ADD CONSTRAINT fk_visite_langue_offre FOREIGN KEY (id_offre) REFERENCES _visite (id_offre) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _visite_langue
ADD CONSTRAINT fk_visite_langue_langue FOREIGN KEY (id_langue) REFERENCES _langue (id_langue) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- TAG Visites
create table _tag_visite ( -- Maxime
    id_offre SERIAL REFERENCES _visite (id_offre) DEFERRABLE INITIALLY IMMEDIATE,
    id_tag SERIAL REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE,
    PRIMARY KEY (id_offre, id_tag)
);

ALTER TABLE _tag_visite
ADD CONSTRAINT fk_tag_visite_offre FOREIGN KEY (id_offre) REFERENCES _visite (id_offre) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _tag_visite
ADD CONSTRAINT fk_tag_visite_tag FOREIGN KEY (id_tag) REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Parcs d'attractions
-- Table _parc_attraction (hérite de _offre)
CREATE TABLE _parc_attraction ( -- (MVC) Léo
    nb_attractions INTEGER,
    age_requis INTEGER
) INHERITS (_offre);

-- Rajout des contraintes perdues pour _parc_attraction à cause de l'héritage
ALTER TABLE _parc_attraction
ADD CONSTRAINT pk_parc_attraction PRIMARY KEY (id_offre);

ALTER TABLE _parc_attraction
ADD CONSTRAINT fk_parc_attraction_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _parc_attraction
ADD CONSTRAINT fk_parc_attraction_type_offre FOREIGN KEY (id_type_offre) REFERENCES _type_offre (id_type_offre) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- TAG Parcs
create table _tag_parc_attraction ( -- Maxime
    id_offre SERIAL REFERENCES _parc_attraction (id_offre) DEFERRABLE INITIALLY IMMEDIATE,
    id_tag SERIAL REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE,
    PRIMARY KEY (id_offre, id_tag)
);

ALTER TABLE _tag_parc_attraction
ADD CONSTRAINT fk_tag_parc_attraction_offre FOREIGN KEY (id_offre) REFERENCES _parc_attraction (id_offre) DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _tag_parc_attraction
ADD CONSTRAINT fk_tag_parc_attraction_tag FOREIGN KEY (id_tag) REFERENCES _tag (id_tag) DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- ²
-- Table Horaire
CREATE TABLE _horaire ( -- Antoine
    id_horaire SERIAL PRIMARY KEY,
    jour VARCHAR(8) NOT NULL,
    ouverture TIME NOT NULL,
    fermeture TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    id_offre INTEGER NOT NULL
);

-- ------------------------------------------------------------------------------------------------------- Tarif Publique
-- Table TARIF public
CREATE TABLE _tarif_public ( -- Baptiste
    id_tarif SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    prix INTEGER,
    id_offre INTEGER NOT NULL
);

-- ------------------------------------------------------------------------------------------------------- Tarif Facture

-- ------------------------------------------------------------------------------------------------------- Table ternaire restauration avis et note détaillée
CREATE TABLE _avis_restauration_note (
    id_avis INT REFERENCES _avis (id_avis) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE,
    id_restauration INT REFERENCES _restauration (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE,
    note_ambiance INT CHECK (note_ambiance BETWEEN 1 AND 5),
    note_service INT CHECK (note_service BETWEEN 1 AND 5),
    note_cuisine INT CHECK (note_cuisine BETWEEN 1 AND 5),
    rapport_qualite_prix INT CHECK (
        rapport_qualite_prix BETWEEN 1 AND 5
    ),
    PRIMARY KEY (id_avis, id_restauration)
);

ALTER TABLE _avis_restauration_note
ADD CONSTRAINT fk_avis_restauration_note_avis FOREIGN KEY (id_avis) REFERENCES _avis (id_avis) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE _avis_restauration_note
ADD CONSTRAINT fk_avis_restauration_note_restauration FOREIGN KEY (id_restauration) REFERENCES _restauration (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Prestations
CREATE TABLE _prestation ( -- Prestations des activités
    id_prestation SERIAL PRIMARY KEY,
    id_offre INT,
    nom VARCHAR(50) NOT NULL,
    inclus BOOLEAN
);

ALTER TABLE _prestation
ADD CONSTRAINT fk_prestation_activite FOREIGN KEY (id_offre) REFERENCES _activite (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

-- ------------------------------------------------------------------------------------------------------- Liaison prestation et activité     **** Prestation à revoir, ça ne marche pas ****
CREATE TABLE _activite_prestation (
    id_activite INTEGER NOT NULL REFERENCES _activite (id_offre),
    id_prestation INTEGER NOT NULL REFERENCES _prestation (id_prestation),
    PRIMARY KEY (id_activite, id_prestation)
);
-- ------------------------------------------------------------------------------------------------------- Images
-- Table T_IMAGE_IMG
CREATE TABLE T_Image_Img (
    -- IMG = IMaGe
    img_path VARCHAR(255) PRIMARY KEY,
    img_date_creation DATE NOT NULL,
    img_description TEXT,
    img_date_suppression DATE,
    id_offre INTEGER REFERENCES _offre (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE,
    id_parc INTEGER REFERENCES _parc_attraction (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE,
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

ALTER TABLE T_Image_Img
ADD CONSTRAINT fk_image_offre FOREIGN KEY (id_offre) REFERENCES _offre (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE T_Image_Img
ADD CONSTRAINT fk_image_parc FOREIGN KEY (id_parc) REFERENCES _parc_attraction (id_offre) ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE;

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
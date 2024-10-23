DROP SCHEMA IF EXISTS "sae_db" CASCADE;
CREATE SCHEMA sae_db;
SET SCHEMA 'sae_db';

-- Table T_STOCKAGE_IMAGE_SIM
CREATE TABLE T_Stockage_Image_Sim ( -- SIM = Stockage IMage
    sim_id SERIAL PRIMARY KEY,
    sim_date_creation DATE NOT NULL,
    sim_path VARCHAR(255) NOT NULL
);

-- Table T_TYPE_IMAGE_TIM
CREATE TABLE T_Type_Image_Tim ( -- TIM = Type IMage
    tim_id SERIAL PRIMARY KEY,
    tim_format CHAR(4) NOT NULL,
    tim_libelle varchar(255)
);

-- Table T_IMAGE_IMG
CREATE TABLE T_Image_Img ( -- IMG = IMaGe
    img_id SERIAL PRIMARY KEY,
    img_id_remplacement INTEGER REFERENCES T_Image_Img(img_id),
    sim_id INTEGER REFERENCES T_Stockage_Image_Sim(sim_id),
    tim_id INTEGER REFERENCES T_Type_Image_Tim(tim_id),
    img_date_creation DATE NOT NULL,
    img_description TEXT,
    img_date_suppression DATE
);


CREATE TABLE _image_remplacement (
    img_id_original INTEGER REFERENCES T_Image_Img(img_id),
    img_id_remplacement INTEGER REFERENCES T_Image_Img(img_id),
    PRIMARY KEY (img_id_original, img_id_remplacement)
);


-- -------------------------------------------------------------------------------------------------------

-- Table Adresse
CREATE TABLE _adresse (
    adresse_id SERIAL PRIMARY KEY,
    code_postal CHAR(5) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    numero varchar(255) not null,
    odonyme varchar(255) not null,
    complement_adresse varchar(255) not null
);

-- Table abstraite Compte
CREATE TABLE _compte (
    id_compte SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    mdp_hash VARCHAR(255) NOT NULL,
    num_tel VARCHAR(255) NOT NULL,
    photoProfil
    adresse_id serial REFERENCES _adresse(adresse_id) NOT NULL
);

-- Table Membre
CREATE TABLE _membre (
    pseudo VARCHAR(255) PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL
) INHERITS (_compte);

-- Héritage des types de comptes
CREATE TABLE _professionnel (
   
) INHERITS (_compte);

ALTER TABLE _professionnel ADD CONSTRAINT unique_id_compte_Professionnel UNIQUE (id_compte);

CREATE TABLE _pro_prive (
    num_siren VARCHAR(255) NOT NULL,
    denomination VARCHAR(255) NOT NULL
) INHERITS (_professionnel);

ALTER TABLE _pro_prive ADD CONSTRAINT unique_id_compte_Pro_Prive UNIQUE (id_compte);

CREATE TABLE _pro_public (
    type_orga VARCHAR(255) NOT NULL,
    nom_orga varchar(255) not null
) INHERITS (_professionnel);

ALTER TABLE _pro_public ADD CONSTRAINT unique_id_compte_Pro_Public UNIQUE (id_compte);

-- Table RIB
CREATE TABLE _RIB (
    rib_id SERIAL PRIMARY KEY,
    code_banque varchar(255) NOT NULL,
    code_guichet varchar(255) NOT NULL,
    numero_compte varchar(255) NOT NULL,
    cle_rib varchar(255) NOT NULL,
    compte_id serial REFERENCES _pro_prive(id_compte) UNIQUE
);


-- ----------------------------------------------------------------------

-- Table abstraite Offre
CREATE TABLE _offre (
    offre_id SERIAL PRIMARY KEY,
    est_en_ligne BOOLEAN NOT NULL,
    description_offre TEXT,
    resume_offre TEXT,
    prix_mini FLOAT,
    date_creation DATE NOT NULL,
    date_mise_a_jour DATE,
    date_suppression DATE,
    idPro integer references _professionnel(id_compte),
    adresse_id serial REFERENCES _adresse(adresse_id)
);

-- Table TAG
CREATE TABLE _tag (
    tag_id SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);

CREATE TABLE _tag_offre (
    offre_id serial REFERENCES _offre(offre_id),
    tag_id serial REFERENCES _tag(tag_id),
    PRIMARY KEY (offre_id, tag_id)
);

-- Table Facture
CREATE TABLE _facture (
    facture_id SERIAL PRIMARY KEY,
    jour_en_ligne DATE NOT NULL,
    offre_id serial REFERENCES _offre(offre_id)
);

-- Refaire les mêmes tables entre chaque table en lien avec Offre

CREATE TABLE _log_changement_status (
    id SERIAL PRIMARY KEY,
    offre_id serial REFERENCES _offre(offre_id),
    date_changement DATE NOT NULL
);

-- Héritage pour les types d'offres
CREATE TABLE _restauration (
    restauration_id SERIAL PRIMARY KEY,
    gamme_prix varchar(3) NOT NULL
) INHERITS (_offre);

create table _tag_restaurant (
  tag_restaurant_id serial primary key,
  nom_tag varchar(255) not null
);

create table _tag_restaurant_restauration (
  restauration_id serial references _restauration(restauration_id),
  tag_restaurant_id serial references _tag_restaurant(tag_restaurant_id),
  primary key (restauration_id, tag_restaurant_id)
);

CREATE TABLE _activite (
    id_active SERIAL PRIMARY KEY,
    duree_activite TIME,
    age_requis INTEGER,
    prestations VARCHAR(255)
) INHERITS (_offre);

create table _tag_activite (
  id_active serial references _activite(id_active),
  tag_id serial references _tag(tag_id),
  primary key (id_active, tag_id)
);


CREATE TABLE _spectacle (
    spectacle_id SERIAL PRIMARY KEY,
    capacite_spectacle INTEGER,
    duree_spectacle TIME
) INHERITS (_offre);


create table _tag_spectacle (
  spectacle_id serial references _spectacle(spectacle_id),
  tag_id serial references _tag(tag_id),
  primary key (spectacle_id, tag_id)
);


CREATE TABLE _visite (
    visite_id SERIAL PRIMARY KEY,
    duree_visite TIME,
    guide_visite BOOLEAN
) INHERITS (_offre);

CREATE TABLE _langue (
    langue_id SERIAL PRIMARY KEY,
    nom_langue VARCHAR(255)
);

create table _tag_visite (
  visite_id serial references _visite(visite_id),
  tag_id serial references _tag(tag_id),
  primary key (visite_id, tag_id)
);


CREATE TABLE _visite_langue (
    visite_id serial REFERENCES _visite(visite_id),
    langue_id serial REFERENCES _langue(langue_id)
);

CREATE TABLE _parc_attraction (
    parc_id SERIAL PRIMARY KEY,
    nb_attractions INTEGER,
    age_requis BOOLEAN
) INHERITS (_offre);


create table _tag_parc (
  parc_id serial references _parc_attraction(parc_id),
  tag_id serial references _tag(tag_id),
  primary key (parc_id, tag_id)
);


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
    offre_id serial REFERENCES _offre(offre_id)
);

------------------------------------------------------------------ stockage images

-- Pour stocker le chemin de l'image de profil dans _compte
ALTER TABLE _compte
ADD COLUMN photo_profil_path VARCHAR(255) NULL;  -- Optionnel, stockera les chemins d'accès aux images dans 'photoProfils'

-- Table de relation entre _offre et les images
CREATE TABLE _offre_image (
    offre_id INTEGER REFERENCES _offre(offre_id) ON DELETE CASCADE,
    sim_id INTEGER REFERENCES T_Stockage_Image_Sim(sim_id) ON DELETE CASCADE,
    PRIMARY KEY (offre_id, sim_id)
);

-- Ajout d'une colonne obligatoire dans _parc_attraction pour le plan du parc
ALTER TABLE _parc_attraction
ADD COLUMN plan_sim_id INTEGER NOT NULL REFERENCES T_Stockage_Image_Sim(sim_id) ON DELETE CASCADE;


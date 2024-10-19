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
    tim_libelle BOOLEAN
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

-- -------------------------------------------------------------------------------------------------------

-- Table abstraite Compte
CREATE TABLE Compte (
    id_compte SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    mdp_hash VARCHAR(255) NOT NULL,
    num_tel VARCHAR(255) NOT NULL
);

-- Table Membre
CREATE TABLE Membre (
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    pseudo VARCHAR(255) PRIMARY KEY
) INHERITS (Compte);

-- Héritage des types de comptes
CREATE TABLE Professionnel (
    nom_orga VARCHAR(255) NOT NULL
) INHERITS (Compte);

ALTER TABLE Professionnel ADD CONSTRAINT unique_id_compte_Professionnel UNIQUE (id_compte);

CREATE TABLE Pro_Prive (
    num_siren VARCHAR(255) NOT NULL,
    denomination VARCHAR(255) NOT NULL
) INHERITS (Professionnel);

ALTER TABLE Pro_Prive ADD CONSTRAINT unique_id_compte_Pro_Prive UNIQUE (id_compte);

CREATE TABLE Pro_Public (
    type_orga VARCHAR(255) NOT NULL
) INHERITS (Professionnel);

ALTER TABLE Pro_Public ADD CONSTRAINT unique_id_compte_Pro_Public UNIQUE (id_compte);

-- Table RIB
CREATE TABLE RIB (
    rib_id SERIAL PRIMARY KEY,
    code_banque INTEGER NOT NULL,
    code_guichet INTEGER NOT NULL,
    numero_compte INTEGER NOT NULL,
    cle_rib INTEGER NOT NULL,
    compte_id INTEGER REFERENCES Pro_prive(id_compte) UNIQUE
);

-- ----------------------------------------------------------------------

-- Table Adresse
CREATE TABLE Adresse (
    adresse_id SERIAL PRIMARY KEY,
    code_postale INTEGER NOT NULL,
    ville VARCHAR(255) NOT NULL,
    numero INTEGER NOT NULL,
    odonyme VARCHAR(255),
    complement_adresse VARCHAR(255)
);

-- Table abstraite Offre
CREATE TABLE Offre (
    offre_id SERIAL PRIMARY KEY,
    est_en_ligne BOOLEAN NOT NULL,
    description_offre TEXT,
    resume_offre TEXT,
    prix_mini FLOAT,
    date_creation DATE NOT NULL,
    date_mise_a_jour DATE,
    date_suppression DATE,
    adresse_id INTEGER REFERENCES Adresse(adresse_id)
);

-- Table TAG
CREATE TABLE Tag (
    tag_id SERIAL PRIMARY KEY,
    nom_tag VARCHAR(255) NOT NULL
);

CREATE TABLE Tag_Offre (
    offre_id INTEGER REFERENCES Offre(offre_id),
    tag_id INTEGER REFERENCES Tag(tag_id),
        PRIMARY KEY (offre_id, tag_id)
);

-- Table Facture
CREATE TABLE Facture (
    facture_id SERIAL PRIMARY KEY,
    jour_en_ligne DATE NOT NULL,
    offre_id INTEGER REFERENCES Offre(offre_id)
);

-- Refaire les mêmes tables entre chaque table en lien avec Offre

CREATE TABLE Log_Changement_Status (
    id SERIAL PRIMARY KEY,
    offre_id INTEGER REFERENCES Offre(offre_id),
    date_changement DATE NOT NULL
);

-- Héritage pour les types d'offres
CREATE TABLE Restauration (
    restauration_id SERIAL PRIMARY KEY,
    gamme_prix INTEGER NOT NULL
) INHERITS (Offre);

CREATE TABLE Activite (
    id_active SERIAL PRIMARY KEY,
    duree_activite TIME,
    age_requis INTEGER,
    prestations VARCHAR(255)
) INHERITS (Offre);

CREATE TABLE Spectacle (
    spectacle_id SERIAL PRIMARY KEY,
    capacite_spectacle INTEGER,
    duree_spectacle TIME
) INHERITS (Offre);

CREATE TABLE Visite (
    visite_id SERIAL PRIMARY KEY,
    duree_visite TIME,
    guide_visite BOOLEAN
) INHERITS (Offre);

CREATE TABLE Langue (
    langue_id SERIAL PRIMARY KEY,
    nom_langue VARCHAR(255)
);

CREATE TABLE Visite_Langue (
    visite_id INTEGER REFERENCES Visite(visite_id),
    langue_id INTEGER REFERENCES Langue(langue_id)
);

CREATE TABLE Parc_Attraction (
    parc_id SERIAL PRIMARY KEY,
    nb_attractions INTEGER,
    age_requis BOOLEAN
) INHERITS (Offre);

-- Table Horaire
CREATE TABLE Horaire (
    horaire_id SERIAL PRIMARY KEY,
    ouverture TIME NOT NULL,
    fermeture TIME NOT NULL,
    pause_debut TIME,
    pause_fin TIME,
    offre_id INTEGER REFERENCES Offre(offre_id)
);

-- Table TARIF public
CREATE TABLE Tarif_Public (
    tarif_id SERIAL PRIMARY KEY,
    titre_tarif VARCHAR(255) NOT NULL,
    age_min INTEGER,
    age_max INTEGER,
    offre_id INTEGER REFERENCES Offre(offre_id)
);

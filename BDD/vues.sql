set schema 'sae_db';

-- vue pour accéder à un compte pro mais sans voir son rib

CREATE VIEW vue_pro_prive_sans_rib AS
SELECT 
    pp.num_siren,
    a.ville,
    a.code_postal,
    c.email,
    c.num_tel
FROM 
    _pro_prive pp
JOIN 
    _compte c ON pp.id_compte = c.id_compte
JOIN 
    _adresse a ON c.id_adresse = a.id_adresse;
    

-- créer une vue des offres que les membres et visiteurs verront
CREATE VIEW vue_offres_publiques AS
SELECT 
    titre,
    description,
    resume,
    prix_mini,
    date_creation
FROM 
    _offre
WHERE 
    est_en_ligne = TRUE;  -- Seules les offres en ligne sont visibles publiquement

-- création de la vue permettant de voire les types d'offres 
CREATE OR REPLACE VIEW vue_offre_categorie AS
SELECT 
    o.id_offre,
    'restauration' AS type_offre
FROM _restauration o
UNION ALL
SELECT 
    o.id_offre,
    'parc_attraction' AS type_offre
FROM _parc_attraction o
UNION ALL
SELECT 
    o.id_offre,
    'visite' AS type_offre
FROM _visite o
UNION ALL
SELECT 
    o.id_offre,
    'activite' AS type_offre
FROM _activite o
UNION ALL
SELECT 
    o.id_offre,
    'spectacle' AS type_offre
FROM _spectacle o;


------------ vue type d'offre


create or replace view vue_offre_type as 
select id_offre, nom
from _offre 
join _type_offre on  
_type_offre.id_type_offre = _offre.id_type_offre;

-- -------------------------------------------------------------------- Connexion Compte

CREATE OR REPLACE VIEW vue_connexion_compte AS
SELECT email, mdp_hash
FROM sae_db._compte;


-- -------------------------------------------------------------------- Recherche Offre
CREATE OR REPLACE VIEW sae_db.vue_recherche_offre AS
SELECT est_en_ligne, description, resume, prix_mini, titre, date_mise_a_jour, nom
FROM _offre 
INNER JOIN _type_offre
ON _offre.id_type_offre = _type_offre.id_type_offre;

-- -------------------------------------------------------------------- Tri Offre
-- -------------------------------------------------------------------- Filtres multicritères Offre 
-- -------------------------------------------------------------------- Mise à la une Offre
-- -------------------------------------------------------------------- Mise en relief Offre
-- -------------------------------------------------------------------- Facturation (générer PDF) 
-- -------------------------------------------------------------------- Vue 
-- -------------------------------------------------------------------- Vue 
 

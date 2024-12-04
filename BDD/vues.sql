set schema 'sae_db';

-- Seules les offres en ligne sont visibles publiquement
-- création de la vue permettant de voire les types d'offres
CREATE OR REPLACE VIEW vue_offre_categorie AS
SELECT o.id_offre, 'restauration' AS type_offre
FROM _restauration o
UNION ALL
SELECT o.id_offre, 'parc_attraction' AS type_offre
FROM _parc_attraction o
UNION ALL
SELECT o.id_offre, 'visite' AS type_offre
FROM _visite o
UNION ALL
SELECT o.id_offre, 'activite' AS type_offre
FROM _activite o
UNION ALL
SELECT o.id_offre, 'spectacle' AS type_offre
FROM _spectacle o;

------------ vue type d'offre
create or replace view vue_offre_type as
select id_offre, nom
from _offre
    join _type_offre on _type_offre.id_type_offre = _offre.id_type_offre;

-- -------------------------------------------------------------------- Connexion Compte
CREATE OR REPLACE VIEW vue_connexion_compte AS
SELECT email, mdp_hash
FROM sae_db._compte;

-- -------------------------------------------------------------------- id compte affilié au compte
CREATE OR REPLACE VIEW vue_comptes AS
SELECT
    m.id_compte AS id_compte,
    'Membre' AS type_compte,
    m.email,
    m.pseudo AS nom_ou_pseudo
FROM _membre m
UNION ALL
SELECT
    ppr.id_compte AS id_compte,
    'Professionnel Privé' AS type_compte,
    ppr.email,
    ppr.nom_pro AS nom_ou_pseudo
FROM _pro_prive ppr
UNION ALL
SELECT
    ppu.id_compte AS id_compte,
    'Professionnel Public' AS type_compte,
    ppu.email,
    ppu.nom_pro AS nom_ou_pseudo
FROM _pro_public ppu;

-- -------------------------------------------------------------------- offre lié au pro
CREATE OR REPLACE VIEW vue_pro_offres AS
SELECT
    ppr.id_compte AS id_pro,
    'Professionnel Privé' AS type_pro,
    ppr.nom_pro AS nom_pro,
    ppr.email AS email_pro,
    o.id_offre AS id_offre,
    o.titre AS titre_offre,
    o.description AS description_offre
FROM _pro_prive ppr
    JOIN _offre o ON ppr.id_compte = o.id_pro
UNION ALL
SELECT
    ppu.id_compte AS id_pro,
    'Professionnel Public' AS type_pro,
    ppu.nom_pro AS nom_pro,
    ppu.email AS email_pro,
    o.id_offre AS id_offre,
    o.titre AS titre_offre,
    o.description AS description_offre
FROM _pro_public ppu
    JOIN _offre o ON ppu.id_compte = o.id_pro;

CREATE OR REPLACE VIEW vue_facture_quantite AS
SELECT 
    f.numero AS "Numéro de Facture",
    f.designation AS "Service",
    f.date_emission AS "Date d'émission",
    f.date_prestation AS "Date de Prestation",
    f.date_echeance AS "Date d'échéance",
    f.date_lancement AS "Date de Lancement",
    CASE
        -- Recherche des mots clÃ©s dans la dÃ©signation pour dÃ©cider si c'est une option ou un abonnement
        WHEN LOWER(f.designation) LIKE '%abonnement%' THEN CONCAT(f.nbjours_abonnement, ' jours')
        WHEN LOWER(f.designation) LIKE '%option%' OR LOWER(f.designation) IN ('a la une', 'en relief') THEN CONCAT(f.quantite, ' semaines')
        ELSE 'Quantité inconnue'
    END AS "Quantité",
    f.prix_unitaire_HT AS "Prix Unitaire HT (â‚¬)",
    f.prix_unitaire_TTC AS "Prix Unitaire TTC (â‚¬)",
    f.quantite * f.prix_unitaire_HT AS "Montant HT (â‚¬)",
    f.quantite * f.prix_unitaire_TTC AS "Montant TTC (â‚¬)"
FROM _facture f;



-- vue de la facture avec les montants totaux 
CREATE OR REPLACE VIEW vue_facture_totaux AS
SELECT 
    numero AS "Numéro de Facture",
    SUM(quantite * prix_unitaire_HT) AS "Total HT (â‚¬)",
    SUM(quantite * prix_unitaire_TTC) AS "Total TTC (â‚¬)"
FROM _facture
GROUP BY numero;

-- -------------------------------------------------------------------- Moyenne des notes pour chaque offre (id_offre);
CREATE OR REPLACE VIEW vue_moyenne AS
SELECT _offre.id_offre, AVG(_avis.note), COUNT(_avis.note)
FROM _offre
    JOIN _avis ON _avis.id_offre = _offre.id_offre
GROUP BY
    _offre.id_offre;
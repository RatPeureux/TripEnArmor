set schema 'sae_db';

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

------------------------------- Vue pratique pour visualiser les jours de mise en ligne durant le mois acutel (date_debut & date_fin incluses)
CREATE OR REPLACE VIEW vue_periodes_en_ligne AS
WITH periodes_brutes AS (
    -- Ta vue de périodes sans fusion des chevauchements
    SELECT
        c1.id_offre,
        c1.date_changement AS date_debut,
        COALESCE(c2.date_changement, CURRENT_DATE) AS date_fin
    FROM 
        _log_changement_status c1
    LEFT JOIN 
        _log_changement_status c2
        ON c1.id_offre = c2.id_offre
        AND c1.en_ligne = TRUE
        AND c2.en_ligne = FALSE
        AND c1.date_changement < c2.date_changement
    WHERE 
        c1.en_ligne = TRUE
        AND NOT EXISTS (
            SELECT 1
            FROM _log_changement_status c3
            WHERE c3.id_offre = c1.id_offre
              AND c3.en_ligne = FALSE
              AND c3.date_changement > c1.date_changement
              AND c3.date_changement < c2.date_changement
        )
),
-- Étape 1 : Fusionner les périodes sur une base continue
periodes_avec_fusion AS (
    SELECT
        id_offre,
        date_debut,
        date_fin,
        -- Créer un numéro de groupe en fonction des chevauchements
        ROW_NUMBER() OVER (PARTITION BY id_offre ORDER BY date_debut) 
        - ROW_NUMBER() OVER (PARTITION BY id_offre ORDER BY date_fin) AS groupe
    FROM periodes_brutes
),
-- Étape 2 : Fusionner les périodes en fonction des groupes
periodes_fusionnees AS (
    SELECT
        id_offre,
        MIN(date_debut) AS date_debut,
        MAX(date_fin) AS date_fin
    FROM periodes_avec_fusion
    GROUP BY id_offre, groupe
)
-- Sélectionner les périodes fusionnées
SELECT
    id_offre,
    date_debut,
    date_fin
FROM periodes_fusionnees
ORDER BY id_offre, date_debut;

------------------------------- Vue pratique pour visualiser les jours de mise en ligne durant le mois acutel (date_debut & date_fin incluses)
CREATE OR REPLACE VIEW vue_periodes_en_ligne_du_mois AS
SELECT 
    id_offre,
    -- Si date_debut est antérieure à date_fin et dans un mois différent, on remplace par le 1er jour du mois de date_fin
    CASE
        WHEN date_debut < date_fin
             AND (EXTRACT(MONTH FROM date_debut) != EXTRACT(MONTH FROM date_fin) 
                  OR EXTRACT(YEAR FROM date_debut) != EXTRACT(YEAR FROM date_fin))
        THEN DATE_TRUNC('MONTH', date_fin)::DATE
        ELSE date_debut
    END AS date_debut,
    date_fin
FROM 
    periodes_en_ligne
WHERE
	EXTRACT(YEAR FROM date_fin) = EXTRACT(YEAR FROM CURRENT_DATE)
    AND EXTRACT(MONTH FROM date_fin) = EXTRACT(MONTH FROM CURRENT_DATE)
ORDER BY 
    id_offre, date_debut;

----------------------------- Vue pour connaître les détails d'une souscription de chaque offre dans temps
create or replace view vue_souscription_offre_option_details as
select
	*,
	(date_lancement + (nb_semaines * INTERVAL '1 week'))::DATE AS date_fin,
	(nb_semaines * prix_ht) as prix_ht_total,
	(nb_semaines * prix_ttc) as prix_ttc_total,
	CASE
        WHEN date_annulation IS NOT NULL AND date_annulation < date_lancement THEN true
        ELSE false
    END AS est_remboursee
from _offre_souscription_option
natural join _souscription
join _option on nom_option = nom;

------------------------------------ Vue pour connaître les détails des souscriptions de chaque offre durant le mois actuel
create or replace view vue_souscription_offre_option_details_du_mois as
select *
from vue_souscription_offre_option_details_du_mois
WHERE
	EXTRACT(YEAR FROM date_lancement) = EXTRACT(YEAR FROM CURRENT_DATE)
    AND EXTRACT(MONTH FROM date_lancement) = EXTRACT(MONTH FROM CURRENT_DATE);

------------------------------------ Vue pour connaître les données d'une facture simulée (preview)
CREATE or replace view vue_preview_facture as
...

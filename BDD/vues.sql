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
SELECT numero AS "Numéro de Facture", SUM(quantite * prix_unitaire_HT) AS "Total HT (â‚¬)", SUM(quantite * prix_unitaire_TTC) AS "Total TTC (â‚¬)"
FROM _facture
GROUP BY
    numero;

--------------------------------------------------------------------- Moyenne des notes pour chaque offre (id_offre)
CREATE OR REPLACE VIEW vue_moyenne AS
SELECT _offre.id_offre, AVG(_avis.note), COUNT(_avis.note)
FROM _offre
    JOIN _avis ON _avis.id_offre = _offre.id_offre
GROUP BY
    _offre.id_offre;

--------------------------------------------------------------------- vue pour connaître les tags d'une offre quelconque (restaurant + autres offres)
create or replace view vue_offre_tag as
-- Les tags pour les offres communes
select id_offre, nom
from _tag_offre
    natural join _tag
UNION
-- Les tags pour les restaurants
select id_offre, nom
from
    _tag_restaurant_restauration
    natural join _tag_restaurant
order by id_offre;

--------------------------------------------------------------------- vue pour connaître les noms de type de repas pour chaque restaurant
create view vue_restaurant_type_repas as
select id_offre, nom
from
    _restaurant_type_repas
    natural join _type_repas;

----------------------------- Vue pour connaître les périodes en ligne d'une offre pour le mois actuel
CREATE OR REPLACE VIEW vue_periodes_en_ligne_du_mois AS
SELECT
    id_offre,
    type_offre,
    prix_ht,
    -- Calcul du prix total HT (duree * prix_ht)
    ROUND(((COALESCE(date_fin, CURRENT_DATE) - date_debut + 1) * prix_ht)::NUMERIC, 2) AS prix_ht_total,
    prix_ttc,
    -- Calcul du prix total TTC (duree * prix_ttc)
	ROUND(((COALESCE(date_fin, CURRENT_DATE) - date_debut + 1) * prix_ttc)::NUMERIC, 2) AS prix_ttc_total,
    -- Calcul de la TVA (arrondi à 2 décimales)
    ROUND(((prix_ttc::NUMERIC / prix_ht::NUMERIC) - 1), 2) * 100 AS tva,
    -- Si date_debut est antérieure à date_fin et dans un mois différent, on remplace par le 1er jour du mois de date_fin
    CASE
        WHEN
            EXTRACT(MONTH FROM date_debut) != EXTRACT(MONTH FROM CURRENT_DATE)
            OR EXTRACT(YEAR FROM date_debut) != EXTRACT(YEAR FROM CURRENT_DATE)
        THEN DATE_TRUNC('MONTH', CURRENT_DATE)::DATE
        ELSE date_debut
    END AS date_debut,
    COALESCE(date_fin, CURRENT_DATE) AS date_fin,
    -- Calcul de la durée (nombre de jours entre date_debut et date_fin)
    COALESCE(date_fin, CURRENT_DATE) - date_debut + 1 AS duree
FROM 
    _periodes_en_ligne
WHERE
    date_fin IS NULL
    OR
    (
        EXTRACT(YEAR FROM date_fin) = EXTRACT(YEAR FROM CURRENT_DATE)
        AND EXTRACT(MONTH FROM date_fin) = EXTRACT(MONTH FROM CURRENT_DATE)
    )
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
from
    vue_souscription_offre_option_details_du_mois
WHERE
    EXTRACT(
        YEAR
        FROM date_lancement
    ) = EXTRACT(
        YEAR
        FROM CURRENT_DATE
    )
    AND EXTRACT(
        MONTH
        FROM date_lancement
    ) = EXTRACT(
        MONTH
        FROM CURRENT_DATE
    );

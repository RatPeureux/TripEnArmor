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

-------------------------------------------------------------------- Périodes en ligne pour chaque offre (date_fin vaut CURRENT_DATE si la période n'est pas finie)
CREATE OR REPLACE VIEW vue_periodes_en_ligne AS
SELECT
    c1.id_offre,
    c1.date_changement AS date_debut,
    COALESCE(c2.date_changement, CURRENT_DATE) AS date_fin
    _log_changement_status c1
LEFT JOIN 
    _log_changement_status c2
    ON c1.id_offre = c2.id_offre
    AND c1.en_ligne = TRUE
    AND c2.en_ligne = FALSE
    AND c1.date_changement < c2.date_changement  -- La mise hors ligne doit être après la mise en ligne
WHERE 
    c1.en_ligne = TRUE  -- Ajout de cette condition pour s'assurer que ce sont bien des mises en ligne
    AND NOT EXISTS (
        SELECT 1
        FROM _log_changement_status c3
        WHERE c3.id_offre = c1.id_offre
          AND c3.en_ligne = FALSE
          AND c3.date_changement > c1.date_changement
          AND c3.date_changement < c2.date_changement
    )
ORDER BY 
    c1.id_offre, c1.date_changement;

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

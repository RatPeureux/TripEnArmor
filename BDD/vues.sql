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
    _adresse a ON c.adresse_id = a.adresse_id;
    

-- créer une vue des offres que les membres et visiteurs verront
CREATE VIEW vue_offres_publiques AS
SELECT 
    titre,
    description_offre,
    resume_offre,
    prix_mini,
    date_creation
FROM 
    _offre
WHERE 
    est_en_ligne = TRUE;  -- Seules les offres en ligne sont visibles publiquement


 -- vue qui vérifie la validité de l'adresse mail
 
CREATE OR REPLACE FUNCTION verifier_email_connexion(email_input VARCHAR)
RETURNS TEXT AS $$
DECLARE
    email_count INT;
BEGIN
        -- Vérifier si l'email existe dans la table _compte
    SELECT COUNT(*) INTO email_count
    FROM _compte
    WHERE _compte.email = email_input;

    -- Si l'email existe
    IF email_count > 0 THEN
        RETURN 'Email valide et existant';
    ELSE
        RETURN 'Email non trouvé dans la base';
    END IF;
END;
$$ LANGUAGE plpgsql;


-- création de la vue permettant de voire les types d'offres 
CREATE OR REPLACE VIEW vue_offre_categorie AS
SELECT 
    o.offre_id,
    'restauration' AS type_offre
FROM _restauration o
UNION ALL
SELECT 
    o.offre_id,
    'parc_attraction' AS type_offre
FROM _parc_attraction o
UNION ALL
SELECT 
    o.offre_id,
    'visite' AS type_offre
FROM _visite o
UNION ALL
SELECT 
    o.offre_id,
    'activite' AS type_offre
FROM _activite o
UNION ALL
SELECT 
    o.offre_id,
    'spectacle' AS type_offre
FROM _spectacle o;


------------ vue type d'offre


create or replace view vue_offre_type as 
select offre_id, nom_type_offre
from _offre 
join _type_offre on  
_type_offre.type_offre_id = _offre.type_offre_id;

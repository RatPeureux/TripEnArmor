set schema 'sae_db';


-- vue pour accéder à un compte pro mais sans voir son rib

CREATE VIEW vue_pro_prive_sans_rib AS
SELECT 
    pp.num_siren,
    pp.denomination,
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
    description_offre,
    resume_offre,
    prix_mini,
    date_creation,
    adresse_id
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



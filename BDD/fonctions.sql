set schema 'sae_db';

-- fonctions

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

set schema 'sae_db';

-- vérifie que l'email est valide
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

-- Mise à jour de automatique de la date de mise à jour des offres
CREATE OR REPLACE FUNCTION update_offer_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    NEW.date_mise_a_jour = CURRENT_DATE;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER offer_update_timestamp
BEFORE UPDATE ON _offre
FOR EACH ROW
EXECUTE FUNCTION update_offer_timestamp();

-- mise à jour du statut 'en ligne' de l'offre
CREATE OR REPLACE FUNCTION trigger_log_changement_statut()
RETURNS TRIGGER AS $$
BEGIN
    -- Enregistrement de la date de changement de statut
    INSERT INTO _log_changement_status (offre_id, date_changement)
    VALUES (NEW.offre_id, CURRENT_DATE);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER log_changement_statut
AFTER UPDATE ON _offre
FOR EACH ROW
WHEN (OLD.est_en_ligne IS DISTINCT FROM NEW.est_en_ligne)
EXECUTE FUNCTION trigger_log_changement_statut();


--- modification d'une offre du professionnel

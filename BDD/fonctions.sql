set schema 'sae_db';

/*             
Fonctions            
*/

-- la prestation est créée par un pro et insérée dans la bdd, si un pro différent créé la mêm eprestation alors la prestation est réutilisée et non créée.

CREATE OR REPLACE FUNCTION creer_prestation(
    p_nom VARCHAR,
    p_inclus BOOLEAN,
    p_id_pro INTEGER,
    p_id_activite INTEGER
)
RETURNS INTEGER AS $$
DECLARE
    prestation_existante_id INTEGER;
    new_prestation_id INTEGER;
BEGIN
    -- Vérifier si la prestation existe déjà dans la base (même nom)
    SELECT id_prestation
    INTO prestation_existante_id
    FROM sae_db._prestation
    WHERE nom = p_nom;

    -- Si la prestation existe, on réutilise son ID
    IF prestation_existante_id IS NOT NULL THEN
        -- Associer cette prestation à l'activité donnée
        INSERT INTO _activite_prestation (id_activite, id_prestation)
        VALUES (p_id_activite, prestation_existante_id)
        ON CONFLICT DO NOTHING;

        RETURN prestation_existante_id; -- Retourner l'ID de la prestation existante
    END IF;

    -- Sinon, insérer une nouvelle prestation
    INSERT INTO _prestation (nom, inclus, id_pro)
    VALUES (p_nom, p_inclus, p_id_pro)
    RETURNING id_prestation INTO new_prestation_id;

    -- Associer la nouvelle prestation à l'activité donnée
    INSERT INTO _activite_prestation (id_activite, id_prestation)
    VALUES (p_id_activite, new_prestation_id);

    RETURN new_prestation_id; -- Retourner l'ID de la nouvelle prestation
END;
$$ LANGUAGE plpgsql;

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

-- mise à jour du statut 'en ligne' de l'offre
CREATE OR REPLACE FUNCTION trigger_log_changement_statut()
RETURNS TRIGGER AS $$
BEGIN
    -- Enregistrement de la date de changement de statut
    INSERT INTO _log_changement_status (id_offre, date_changement)
    VALUES (NEW.id_offre, CURRENT_DATE);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Vérification des clés étrangères avec les comptes et offres
CREATE OR REPLACE FUNCTION fk_avis()
RETURNS TRIGGER AS $$
BEGIN
    -- Vérification de l'existence de l'utilisateur (id_compte)
    IF NOT EXISTS (SELECT 1 FROM sae_db._membre WHERE id_compte = NEW.id_membre)
    THEN
        RAISE EXCEPTION 'L''id_compte % ne correspond à aucun utilisateur valide.', NEW.id_membre;
    END IF;
    
    -- Vérification de l'existence de l'offre (id_offre)
    IF NOT EXISTS (SELECT 1 FROM sae_db._restauration WHERE id_offre = NEW.id_offre)
    AND NOT EXISTS (SELECT 1 FROM sae_db._activite WHERE id_offre = NEW.id_offre)
    AND NOT EXISTS (SELECT 1 FROM sae_db._parc_attraction WHERE id_offre = NEW.id_offre)
    AND NOT EXISTS (SELECT 1 FROM sae_db._visite WHERE id_offre = NEW.id_offre)
    AND NOT EXISTS (SELECT 1 FROM sae_db._spectacle WHERE id_offre = NEW.id_offre)
    THEN
        RAISE EXCEPTION 'L''id_offre % ne correspond à aucune offre valide.', NEW.id_offre;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Vérification des règles métier (avis/réponses)
CREATE OR REPLACE FUNCTION check_contraintes_avis()
RETURNS TRIGGER AS $$
DECLARE
    auteur_offre INT;
    avis_reponse_offre INT;
    is_response BOOLEAN := (NEW.id_avis_reponse IS NOT NULL);
BEGIN
    -- Récupérer l'auteur de l'offre
    SELECT id_pro INTO auteur_offre
    FROM sae_db._offre
    WHERE id_offre = NEW.id_offre;

    -- Si l'avis est une réponse
    IF is_response THEN
        -- Vérifier que l'avis parent existe et récupérer son id_offre
        SELECT id_offre INTO avis_reponse_offre
        FROM sae_db._avis
        WHERE id_avis = NEW.id_avis_reponse;

        -- Vérifier que l'avis parent appartient à la même offre
        IF avis_reponse_offre IS DISTINCT FROM NEW.id_offre THEN
            RAISE EXCEPTION 'L''avis auquel vous répondez n''appartient pas à la même offre.';
        END IF;

        -- Vérifier que l'avis parent n'est pas une réponse lui-même
        IF EXISTS (SELECT 1 FROM _avis WHERE id_avis = NEW.id_avis_reponse AND id_avis_reponse IS NOT NULL) THEN
            RAISE EXCEPTION 'Vous ne pouvez pas répondre à une réponse.';
        END IF;

        -- Vérifier que l'auteur est un professionnel (auteur de l'offre)
        IF NEW.id_membre != auteur_offre THEN
            RAISE EXCEPTION 'Seul le professionnel peut répondre à un avis.';
        END IF;
    ELSE
        -- Sinon, c'est un avis initial
        -- Vérifier que l'auteur de l'avis n'est pas l'auteur de l'offre
        IF NEW.id_membre = auteur_offre THEN
            RAISE EXCEPTION 'Le professionnel ne peut pas laisser un avis sur sa propre offre.';
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Fonction pour vérifier une clé étrangère manuellement, car sinon pb avec raisons de double héritage
CREATE OR REPLACE FUNCTION fk_vers_professionnel() RETURNS TRIGGER AS $$
BEGIN
    -- Alerter quand la clé étrangère n'est pas respecté
    IF NOT EXISTS (SELECT 1 FROM sae_db._pro_prive WHERE id_compte = NEW.id_pro)
    AND NOT EXISTS (SELECT 1 FROM sae_db._pro_public WHERE id_compte = NEW.id_pro) THEN
        RAISE EXCEPTION 'Foreign key violation: id_pro does not exist in _pro_prive or _pro_public';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Fonction pour vérifier que tous les comptes ont bien des identifiants différents (~priamry key & UNIQUE constraints perdues par inherits)
CREATE OR REPLACE FUNCTION unique_vals_compte() RETURNS TRIGGER AS $$
BEGIN
    -- Check pour l'id
    IF EXISTS (SELECT 1 FROM sae_db._compte WHERE email = NEW.email) THEN
        RAISE EXCEPTION 'Erreur : valeur dupliquée pour l''adresse email dans deux comptes différents';
    END IF;
    -- Check pour le mail
    IF EXISTS (SELECT 1 FROM sae_db._compte WHERE email = NEW.email) THEN
        RAISE EXCEPTION 'Erreur : valeur dupliquée pour l''adresse email dans deux comptes différents';
    END IF;
    -- Check pour le numero de tel
    IF EXISTS (SELECT 1 FROM sae_db._compte WHERE email = NEW.email) THEN
        RAISE EXCEPTION 'Erreur : valeur dupliquée pour l''adresse email dans deux comptes différents';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
/*             
Triggers           
*/

-- Trigger pour valider les règles métier
CREATE OR REPLACE TRIGGER tg_check_contraintes_avis BEFORE
INSERT
    OR
UPDATE ON sae_db._avis FOR EACH ROW
EXECUTE FUNCTION check_contraintes_avis ();

-- Trigger pour valider les clés étrangères
CREATE OR REPLACE TRIGGER tg_fk_avis BEFORE
INSERT
    ON sae_db._avis FOR EACH ROW
EXECUTE FUNCTION fk_avis ();

-- trigger pour vérifier les id de la table activite
CREATE OR REPLACE TRIGGER fk_activite_professionnel BEFORE
INSERT
    ON _activite FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- trigger pour vérifier les id de la table spectacle
CREATE OR REPLACE TRIGGER fk_spectacle_professionnel BEFORE
INSERT
    ON _spectacle FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- trigger pour vérifier les id de la table visite
CREATE OR REPLACE TRIGGER fk_visite_professionnel BEFORE
INSERT
    ON _visite FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- trigger pour vérifier les id de la table parc d'attraction
CREATE OR REPLACE TRIGGER fk_parc_attraction_professionnel BEFORE
INSERT
    ON _parc_attraction FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- trigger pour vérifier les id de la table restauration
CREATE OR REPLACE TRIGGER fk_restauration_professionnel BEFORE
INSERT
    ON _restauration FOR EACH ROW
EXECUTE FUNCTION fk_vers_professionnel ();

-- trigger changement de statut
CREATE OR REPLACE TRIGGER log_changement_statut AFTER
UPDATE ON _offre FOR EACH ROW WHEN (
    OLD.est_en_ligne IS DISTINCT
    FROM NEW.est_en_ligne
)
EXECUTE FUNCTION trigger_log_changement_statut ();

-- trigger pour la mise à jour de la date de mise à jour d'une offre
CREATE OR REPLACE TRIGGER offer_update_timestamp BEFORE
UPDATE ON _offre FOR EACH ROW
EXECUTE FUNCTION update_offer_timestamp ();

-- trigers de vérification d'un unique compte professionnel privé puisse rentrer des valeurs (pas très explicit ça)
CREATE OR REPLACE TRIGGER tg_unique_vals_compte BEFORE
INSERT
    ON _pro_prive FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte ();

-- trigers de vérification d'un unique compte professionnel publique puisse rentrer des valeurs (pas très explicit ça)
CREATE OR REPLACE TRIGGER tg_unique_vals_compte BEFORE
INSERT
    ON _pro_public FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte ();

-- trigers de vérification d'un unique compte membre puisse rentrer des valeurs (pas très explicit ça)
CREATE OR REPLACE TRIGGER tg_unique_vals_compte BEFORE
INSERT
    ON _membre FOR EACH ROW
EXECUTE FUNCTION unique_vals_compte ();
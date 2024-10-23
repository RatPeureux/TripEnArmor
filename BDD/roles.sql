set schema 'sae_db';

-- Rôle pour les visiteurs non connectés
CREATE ROLE visiteur_non_connecte;

-- Rôle pour les professionnels
CREATE ROLE professionnel;

-- Rôle pour les visiteurs connectés (membres)
CREATE ROLE visiteur_connecte;


-- Accès des rôles --------------------------------------------------

-- accès aux offres publiques pour les visiteurs non connectés
GRANT SELECT ON vue_offres_publiques TO visiteur_non_connecte;





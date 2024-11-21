-- Note : pour insérer dans les tables, en raison de l'héritage
--        [!!!][!!!][!!!][!!!][!!!][!!!][!!!]
--        il faut UNIQUEMENT insérer dans les tables enfants qui ont des contraintes bien définies
--        si une insertion se fait sur une table abstrate (_compte, _offre...),
--        il y aura des problèmes de cohérence, de contraintes, de doublons... etc.
--        [!!!][!!!][!!!][!!!][!!!][!!!][!!!]

-- Listing des tables abstraites
--  _compte
--  _professionnel
--  _offre

SET SCHEMA 'sae_db';

-- insertion d'adresses
INSERT INTO
    _adresse (
        code_postal,
        ville,
        numero,
        odonyme,
        complement_adresse
    )
VALUES (
        '29200',
        'Brest',
        '123',
        'Rue',
        'des Fleurs'
    ),
    (
        '29600',
        'Morlaix',
        '45',
        'Avenue',
        'de la Paix'
    ),
    (
        '22000',
        'Saint-Brieuc',
        '',
        'Hôtel de Ville',
        ''
    ),
    (
        '22300',
        'Lannion',
        '78',
        'Boulevard',
        'de Marie'
    ),
    (
        '22200',
        'Guimgamp',
        '23',
        'Rue',
        'du Tourisme'
    ),
    (
        '22100',
        'Dinan',
        '12',
        'Rue',
        'de Jean-Macé'
    ),
    (
        '35400',
        'Saint-Malo',
        '89',
        'Rue',
        'des Gourmets'
    ),
    (
        '29000',
        'Quimper',
        '22',
        'Rue',
        'des Athlètes'
    ),
    (
        '35000',
        'Rennes',
        '78',
        'Boulevard',
        'de la Culture'
    ),
    (
        '56100',
        'Lannion',
        '2',
        'Pl.',
        'du Général Leclerc'
    );

-- insertion des comptes et pros
INSERT INTO
    _membre (
        email,
        mdp_hash,
        num_tel,
        id_adresse,
        pseudo,
        nom,
        prenom
    )
VALUES
    -- hash_mdp_1
    -- ... etc
    (
        'jdupont@example.com',
        '$2y$10$AWRYO2FfYz77O0FcIPfssuidZTyw9T3Y7kDn5WAwk77AJQ1clSmlm',
        '0600000001',
        1,
        'jdupont',
        'Dupont',
        'Jean'
    ),
    (
        'mlavigne@example.com',
        '$2y$10$lVNjBftVCaAAPt35NeQFRewdK0aRM9BfwAMNmTQ4HV4YmvBY47SSy',
        '0600000002',
        2,
        'mlavigne',
        'Lavigne',
        'Marie'
    );

INSERT INTO
    _pro_prive (
        id_adresse,
        email,
        mdp_hash,
        num_tel,
        num_siren,
        nom_pro
    )
VALUES
    -- LeMeilleurChateau/5
    (
        4,
        'chateau2kergrist@kergrist.fr',
        '$2y$10$GXXQOLrRyCjKrO2SLmTm2.kLs1HIWugRuelUynpBATOu9mhORFA1a',
        '0296463271',
        '948058375',
        'Château de Kergrist'
    ),
    -- nonnonnon
    (
        5,
        'leo.blas@gmail.com',
        '$2y$10$DZNXjLZgFIX1B7TDkPtz9O1IP3frVdkgAoySH4V8EkPVfg7vpSOIS',
        '0658457412',
        '123456789',
        'Amazon'
    );

INSERT INTO
    _pro_public (
        id_adresse,
        email,
        mdp_hash,
        num_tel,
        type_orga,
        nom_pro
    )
VALUES (
        6,
        'gouvernement.macron@gmail.com',
        'onFeraPas493',
        '0254152245',
        'Associatif',
        'France'
    ),
    (
        7,
        'gouvernement.trump@gmail.com',
        'camalaLaBest',
        '0256965584',
        'Organisation Publique',
        'USA'
    ),
    (
        8,
        'test.okok@outlook.com',
        'lalaland',
        '0256521245',
        'Associatif',
        'Dev Unirfou'
    ),
    (
        9,
        'adresse.mail@hotmail.fr',
        'appleEstSupASamsung',
        '0256988884',
        'Organisation Publique',
        'PluDI D'
    );

-- insertion des types d'offres (Standard, Premium, Gratuite)
INSERT INTO
    _type_offre (id_type_offre, nom)
VALUES (1, 'Premium'),
    (2, 'Standard'),
    (3, 'Gratuit');

-- Insertion dans les différents types d'offres, 2 chacunes -----------------------------------------------------------------------------------------------
INSERT INTO
    _restauration (
        est_en_ligne,
        titre,
        description,
        resume,
        prix_mini,
        date_creation,
        date_mise_a_jour,
        id_adresse,
        gamme_prix,
        id_pro,
        id_type_offre
    )
VALUES (
        true,
        'Fougères',
        'Restaurant gastronomique offrant une expérience culinaire haut de gamme avec des plats raffinés à base de produits locaux et de saison. Notre chef vous propose un menu dégustation inoubliable.',
        'Restaurant gastronomique avec menu dégustation.',
        50.00,
        '2024-10-01',
        '2024-10-15',
        1,
        '€€€',
        5,
        1
    ),

(
    true,
    'Le Bartab',
    'Bistro convivial offrant des plats traditionnels français dans un cadre chaleureux. Le menu change chaque jour, basé sur les produits frais du marché.',
    'Bistro avec cuisine française traditionnelle.',
    25.00,
    '2024-09-20',
    '2024-10-01',
    2,
    '€€',
    5,
    1
);

INSERT INTO
    _activite (
        est_en_ligne,
        titre,
        description,
        resume,
        prix_mini,
        date_creation,
        date_mise_a_jour,
        id_adresse,
        duree_activite,
        age_requis,
        prestations,
        id_pro,
        id_type_offre
    )
VALUES (
        true,
        'Lannion Parcours',
        'Parcours de randonnée guidé à travers les montagnes avec des vues imprenables et des explications sur la faune et la flore locale. Idéal pour les amateurs de nature et de marche.',
        'Randonnée guidée en montagne avec paysages spectaculaires.',
        30.00,
        '2024-10-10',
        '2024-10-20',
        2,
        '02:00:00',
        12,
        'Guide expérimenté, équipement fourni.',
        4,
        2
    ),

(
    true,
    'Surfing Sports',
    'Cours de surf pour débutants sur la côte Atlantique. Apprenez les bases du surf avec un instructeur certifié dans un environnement sécurisé.',
    'Cours de surf pour débutants.',
    40.00,
    '2024-07-15',
    '2024-07-30',
    6,
    '01:30:00',
    10,
    'Planche de surf fournie, instructeur qualifié.',
    4,
    3
);

INSERT INTO
    _spectacle (
        est_en_ligne,
        titre,
        description,
        resume,
        prix_mini,
        date_creation,
        date_mise_a_jour,
        id_adresse,
        capacite_spectacle,
        duree_spectacle,
        id_pro,
        id_type_offre
    )
VALUES (
        true,
        'Carpediem',
        'Concert de musique symphonique avec orchestre philharmonique de la ville, jouant des oeuvres classiques de Mozart et Beethoven. Une soirée inoubliable pour les amateurs de musique.',
        'Concert symphonique avec orchestre.',
        45.00,
        '2024-10-05',
        '2024-10-15',
        3,
        300,
        '01:30:00',
        4,
        1
    ),

(
    true,
    'Circus',
    'Spectacle de cirque moderne avec des acrobates, jongleurs, et numéros de trapèze impressionnants. Un divertissement pour toute la famille.',
    'Spectacle de cirque moderne avec acrobates.',
    30.00,
    '2024-11-01',
    '2024-11-10',
    7,
    500,
    '02:00:00',
    5,
    1
);

INSERT INTO
    _visite (
        est_en_ligne,
        titre,
        description,
        resume,
        prix_mini,
        date_creation,
        date_mise_a_jour,
        id_adresse,
        duree_visite,
        guide_visite,
        id_pro,
        id_type_offre
    )
VALUES (
        true,
        'Remontez dans le temps',
        'Visite guidée du château médiéval avec explication de son histoire et des événements marquants. Une plongée dans le passé pour découvrir la vie au Moyen Âge.',
        'Visite guidée du château médiéval.',
        20.00,
        '2024-09-15',
        '2024-09-30',
        4,
        '01:00:00',
        true,
        6,
        2
    ),

(
    true,
    'Raisains frais',
    'Visite guidée des caves à vin de la région avec dégustation de vins locaux. Une immersion dans l histoire viticole et un parcours à travers les vignes.',
    'Visite des caves à vin avec dégustation.',
    50.00,
    '2024-06-10',
    '2024-06-20',
    8,
    '01:30:00',
    true,
    7,
    3
);

INSERT INTO
    _parc_attraction (
        est_en_ligne,
        titre,
        description,
        resume,
        prix_mini,
        date_creation,
        date_mise_a_jour,
        id_adresse,
        nb_attractions,
        age_requis,
        id_pro,
        id_type_offre
    )
VALUES (
        true,
        'Pour les gaullois',
        'Parc d attractions pour toute la famille, avec plus de 20 manèges et attractions adaptés à tous les âges. Un lieu de divertissement incontournable pour petits et grands.',
        'Parc d attractions familial avec plus de 20 manèges.',
        35.00,
        '2024-08-01',
        '2024-08-15',
        5,
        20,
        11,
        7,
        2
    ),

(
    true,
    'Vive les baleines',
    'Parc aquatique avec toboggans géants, piscines à vagues, et espaces détente. Parfait pour se rafraîchir en été et s amuser en famille ou entre amis.',
    'Parc aquatique avec attractions et piscines.',
    45.00,
    '2024-05-01',
    '2024-05-10',
    9,
    15,
    8,
    8,
    1
);

------ insertion types repas
INSERT INTO
    _type_repas (nom_type_repas)
VALUES ('Petit dej'),
    ('Déjeuner'),
    ('Dîner'),
    ('Boissons'),
    ('Brunch');

INSERT INTO
    _tarif_public (
        titre_tarif,
        age_min,
        age_max,
        prix,
        id_offre
    )
VALUES (
        'pour les petits',
        3,
        10,
        45,
        5
    ),
    (
        'pour les grands',
        11,
        99,
        50,
        5
    );
    

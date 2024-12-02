-- Note : pour insérer dans les tables, en raison de l'héritage
--        [!!!]
--        Il faut UNIQUEMENT insérer dans les tables enfants qui ont des contraintes bien définies
--        si une insertion se fait sur une table abstraite (_compte, _professionnel ou _offre),
--        il y pourra y avoir des problèmes de cohérence, de contraintes, de doublons, etc...
--        [!!!]

-- Tables abstraites
--  _compte
--  _professionnel
--  _offre

SET SCHEMA 'sae_db';


INSERT INTO _option (nom, prix_ht, prix_ttc, prix_unitaire)
VALUES 
('A la une', 16.68, 20.00, 16.68),
('En relief', 8.34, 10.00, 8.34);



-- Insertion de plusieurs lignes pour chaque facture
INSERT INTO _facture (numero, designation, date_emission, date_prestation, date_echeance, date_lancement, nbjours_abonnement, quantite, prix_unitaire_HT, prix_unitaire_TTC)
VALUES 
('FAC-2024-001', 'Abonnement Standard', '2024-11-01', '2024-11-01', '2024-12-01', '2024-11-01', 30, 30, 1.67, 2.00),
('FAC-2024-001', 'Option "À la une"', '2024-11-01', '2024-11-01', '2024-12-01', '2024-11-01', 0, 2, 16.68, 20.00),
('FAC-2024-001', 'Option "En relief"', '2024-11-01', '2024-11-01', '2024-12-01', '2024-11-01', 0, 1, 8.34, 10.00),
('FAC-2024-002', 'Abonnement Premium', '2024-11-01', '2024-11-01', '2024-12-01', '2024-11-01', 30, 30, 3.34, 4.00),
('FAC-2024-002', 'Option "À la une"', '2024-11-01', '2024-11-01', '2024-12-01', '2024-11-01', 0, 4, 16.68, 20.00),
('FAC-2024-002', 'Option "En relief"', '2024-11-01', '2024-11-01', '2024-12-01', '2024-11-01', 0, 3, 8.34, 10.00);


INSERT INTO
    _adresse (
        code_postal,
        ville,
        numero,
        odonyme,
        complement_adresse
    )
VALUES ( -- Léo
        '29670',
        'Henvic',
        '3',
        'Maison',
        'Place de l''École'
    ),
    ( -- Antoine et Baptiste
        '29600',
        'Morlaix',
        '45',
        'Avenue',
        'de la Paix'
    ),
    ( -- Eliott
        '22300',
        'Lannion',
        '5',
        '',
        'Rue des Augustins'
    ),
    ( -- Jean Dupont
        '29000',
        'Quimper',
        '22',
        'Rue',
        'des Athlètes'
    ),
    ( -- Richard
        '35000',
        'Rennes',
        '78',
        'Boulevard',
        'de la Culture'
    ),
    ( -- Maxime
        '56100',
        'Lannion',
        '2',
        'Pl.',
        'du Général Leclerc'
    ),
    ( -- Le Gourmet
        '22000',
        'Saint-Brieuc',
        '10',
        'Rue du Général de Gaulle',
        'Proche du centre-ville'
    ),
    ( -- Le Bateau Ivre 
        '22300',
        'Lannion',
        '5',
        'Avenue de la Mer',
        'En bord de mer'
    ),
    ( -- Randonnée en forêt
        '22300',
        'Lannion',
        '15',
        'Route de la Forêt',
        'Entrée principale de la forêt'
    ),
    ( -- Kayak sur la rivière
        '22300',
        'Lannion',
        '30',
        'Rivière du Trégor',
        'Proche du port de plaisance'
    ),
    ( -- Spectacle de magie
        '22300',
        'Lannion',
        '20',
        'Place du Château',
        'À proximité du château historique'
    ),
    ( -- Concert acoustique en plein air
        '22300',
        'Lannion',
        '20',
        'Place du Château',
        'Concert en plein air à proximité du château historique'
    ),
    ( -- Visite du centre historique de Lannion
        '22300',
        'Lannion',
        '30',
        'Rivière du Trégor',
        'Proche du port de plaisance'
    ),
    ( -- Excursion au château
        '22300',
        'Lannion',
        '20',
        'Place du Château',
        'À proximité du château historique'
    ),
    ( -- Parc Astérix
        '22950',
        'Plounéour-Brignogan',
        '8',
        'Route de la Plage',
        'Proche des attractions familiales'
    ),
    ( -- La Récré des Trois Curés
        '22750',
        'Ploufragan',
        '12',
        'Boulevard de la Mer',
        'Accès facile à la plage'
    );

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
VALUES ( -- Quifaitca13
        'eliott.janot@hotmail.com',
        '$2y$10$oxar/t75Fg8yWjKluYG6PO8RQWODQsTMmoKDGYmH1tWW2OjeYz6oS',
        '06 01 02 03 04',
        4,
        'LotiPACT',
        'Dupont',
        'Jean'
    ),
    ( -- Abcd1234
        'jdupont@yahoo.fr',
        '$2y$10$WxJFrlgRsqBkjs16zoO44umqDmugUNOuTi38XXwTJsGINK9Nlp.CW',
        '06 59 64 11 08',
        5,
        'jjdup',
        'Dupont',
        'Jean'
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
VALUES ( -- Caca123-
        1,
        'leobleas@gmail.com',
        '$2y$10$SKmv1CW9n.OBcF.N.lx/3.iCpIIx7Z4ov5AqM246/21dUlp7flzm2',
        '07 69 24 73 22',
        '438 107 845',
        'Henvic&Associates'
    ),
    ( -- MaGalie2511
        6,
        'zenpoxa@gmail.com',
        '$2y$10$OmocaPOye7BG1feUJfQACeEFublSKfrcRi7r58JiYp0k6.pLuMc9W',
        '01 23 45 67 89',
        '591 321 423',
        'PlusUnCanvaRaté'
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
VALUES ( -- Phenixis_05
        7,
        'max.duh22@gmail.com',
        '$2y$10$7JTlezr2H6kVw5F0nuqHPuhe2X8AVbgenBADfX/sr0XEHCNyZ3OT6',
        '02 54  815 22 45',
        'Associatif',
        'Wisecart'
    ),
    ( -- Freez_05
        2,
        'antoinetoullec9@gmail.com',
        '$2y$10$XQtoQcmJjcW1b/JWo7QFrOrH2OXbp3Ye9.CR0uIwM6j6fnJqg5i6u',
        '02 56 96 55 84',
        'Associatif',
        'RBRS'
    ),
    ( -- azertyuiop1A
        3,
        'frollabaptiste@gmail.com',
        '$2y$10$4BhlQ4XtwVhJ/3.FtndS/O2zHHetFinIsAawXQicbbwsaqKLrAEau',
        '02 56 52 12 45',
        'Organisation publique',
        'Jigobu'
    );

INSERT INTO
    _type_offre (nom, prix_ht, prix_ttc)
VALUES ('Premium', 3.34, 4),
    ('Standard', 1.67, 2),
    ('Gratuit', NULL, NULL);

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
        'Le Gourmet',
        'Le Gourmet est un restaurant gastronomique situé à Saint-Brieuc, offrant une expérience culinaire raffinée où la cuisine traditionnelle française rencontre des touches modernes. Chaque plat est une œuvre d''art, préparée avec des produits locaux et de saison, soigneusement sélectionnés pour leur qualité et leur saveur. L''ambiance du restaurant est élégante et chaleureuse, idéale pour un dîner romantique ou un repas d''affaires. Le chef met un point d''honneur à revisiter les classiques de la gastronomie française avec créativité et finesse.',
        'Restaurant français haut de gamme, offrant une expérience gastronomique à base de produits locaux.',
        30,
        '2024-05-10',
        '2024-11-25',
        7,
        '€€€',
        7,
        3
    ),

(
    true,
    'Le Bateau Ivre',
    'Le Bateau Ivre est un restaurant réputé de Lannion, situé en bord de mer, où vous pourrez savourer une cuisine bretonne traditionnelle, axée sur des produits locaux et frais. Spécialisé dans les fruits de mer, le restaurant propose une carte variée mettant en avant les spécialités régionales, accompagnées de vins soigneusement sélectionnés. Le cadre maritime et chaleureux, avec vue sur la mer, ajoute à l''expérience culinaire, créant une atmosphère agréable pour un repas en famille, en couple ou entre amis.',
    'Spécialisé dans les fruits de mer et la cuisine bretonne, avec une vue magnifique sur la mer.',
    25,
    '2024-06-20',
    '2024-11-25',
    8,
    '€€',
    7,
    3
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
        'Randonnée en forêt',
        'Partez à la découverte de la forêt de Lannion lors d''une randonnée guidée qui vous permettra de vous reconnecter à la nature. Votre guide vous fera découvrir les secrets de cette forêt, en vous expliquant la diversité de sa faune et de sa flore. Vous traverserez des sentiers ombragés, découvrirez des points de vue panoramiques et profiterez de la sérénité des lieux. Cette excursion est idéale pour les amateurs de randonnée et ceux qui souhaitent passer une journée en pleine nature tout en apprenant davantage sur l''écosystème local.',
        'Randonnée guidée d''une journée dans la forêt de Lannion, idéale pour les amoureux de la nature.',
        15,
        '2024-07-15',
        '2024-11-25',
        9,
        '05:00:00',
        10,
        'Prévoir des chaussures de marche et de l''eau. Service de guide inclus.',
        5,
        3
    ),

(
    true,
    'Kayak sur la rivière',
    'Embarquez pour une aventure en kayak sur la rivière de Lannion et découvrez des paysages époustouflants au fil de l''eau. Vous serez guidé à travers les méandres de la rivière, un environnement naturel où faune et flore se mêlent harmonieusement. Cette activité est idéale pour les amoureux de la nature et les amateurs de sports nautiques, qu''ils soient débutants ou expérimentés. Le guide vous fournira tout le matériel nécessaire, y compris le kayak et le gilet de sauvetage, tout en vous offrant des conseils pour une expérience agréable et sécurisée.',
    'Excursion en kayak sur la rivière de Lannion, offrant une vue imprenable sur la nature environnante.',
    20,
    '2024-06-01',
    '2024-11-25',
    10,
    '02:00:00',
    8,
    'Kayak, gilet de sauvetage, et guide fourni. Prévoir des vêtements adaptés.',
    5,
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
        duree,
        id_pro,
        id_type_offre
    )
VALUES (
        true,
        'Spectacle de magie',
        'Venez assister à un spectacle de magie incroyable qui mélange illusions, prestidigitation et humour. Ce show interactif, adapté à tous les âges, vous plonge dans un univers fascinant où les limites de la réalité sont constamment repoussées. Le magicien, avec son charisme et ses talents, émerveillera petits et grands avec des tours étonnants. Ce spectacle est idéal pour passer un moment inoubliable en famille, en particulier pour les enfants, qui participeront activement aux tours de magie, rendant l''expérience encore plus magique.',
        'Magicien local présente un show interactif avec des tours de magie et des illusions impressionnantes.',
        10,
        '2024-09-01',
        '2024-11-25',
        11,
        200,
        '01:30:00',
        4,
        1
    ),

(
    true,
    'Concert acoustique en plein air',
    'Le concert acoustique en plein air est une expérience musicale unique qui vous permet de découvrir des artistes locaux dans un cadre naturel exceptionnel. Profitez de la musique bretonne traditionnelle et folk, jouée par des musiciens talentueux, tout en admirant les paysages environnants. Que ce soit sur une place publique, dans un parc ou près d''un monument historique, ce concert en plein air offre une atmosphère détendue et conviviale, idéale pour passer un agréable moment en famille ou entre amis. L''événement est accessible à tous et promet de belles découvertes musicales.',
    'Concert en plein air avec des artistes locaux pour découvrir la musique bretonne traditionnelle et contemporaine.',
    12,
    '2024-06-10',
    '2024-11-25',
    12,
    500,
    '02:00:00',
    5,
    3
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
        avec_guide,
        id_pro,
        id_type_offre
    )
VALUES (
        true,
        'Visite du centre historique de Lannion',
        'La visite guidée du centre historique de Lannion vous plonge au cœur de l''histoire de cette belle cité bretonne. Lors de cette excursion, vous découvrirez ses rues pavées, ses maisons à colombages typiques et ses monuments emblématiques, tels que l''église Saint-Jean et la tour de l''Horloge. Votre guide partagera avec vous des anecdotes sur le passé de la ville et ses habitants. Cette visite est l''occasion idéale de comprendre l''évolution de Lannion, de son époque médiévale à aujourd''hui, tout en explorant ses joyaux architecturaux.',
        'Visite guidée du centre historique de Lannion, avec un guide expert sur l''histoire locale.',
        12,
        '2024-03-01',
        '2024-11-25',
        13,
        '02:00:00',
        true,
        6,
        3
    ),

(
    true,
    'Excursion au château',
    'L''excursion au château de Lannion vous invite à découvrir l''histoire fascinante de ce monument historique, qui a joué un rôle important dans la défense et le développement de la ville. Accompagné d''un guide passionné, vous parcourrez les salles majestueuses, les remparts et les jardins, tout en apprenant les secrets de la forteresse. De là, vous profiterez également d''une vue imprenable sur la ville et la campagne environnante. C''est une expérience inoubliable pour les amateurs d''histoire et de patrimoine.',
    'Excursion au château de Lannion avec un guide passionné pour explorer l''histoire du site et son rôle dans la région.',
    15,
    '2024-06-15',
    '2024-11-25',
    14,
    '03:00:00',
    true,
    6,
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
        'Parc Astérix',
        'Le Parc Astérix, inspiré des célèbres bandes dessinées d''Astérix le Gaulois, offre une expérience unique à travers des attractions à thèmes gaulois, romains, et égyptiens. Vous y découvrirez des montagnes russes effrayantes, des spectacles historiques, ainsi que des zones interactives pour les enfants. Le parc, qui fait la part belle à l''humour et à l''aventure, accueille les visiteurs dans un univers magique où se mêlent rires et sensations fortes. Idéal pour les familles, le parc propose également des espaces de restauration et de détente.',
        'Parc à thème basé sur l''univers d''Astérix, avec des attractions pour tous les âges.',
        30,
        '1989-04-30',
        '2024-11-25',
        15,
        40,
        3,
        3,
        2
    ),
    (
        true,
        'La Récré des Trois Curés',
        'La Récré des Trois Curés est un parc d''attractions familial situé en Bretagne, parfait pour une journée de divertissement en famille. Ce parc propose des manèges adaptés à tous les âges, des montagnes russes palpitantes aux attractions douces pour les plus jeunes. Vous pourrez aussi profiter d''espaces de détente en plein air, d''aires de pique-nique, et d''animations tout au long de la journée. En plus des manèges, le parc met en valeur la nature environnante, offrant ainsi un cadre agréable pour toute la famille.',
        'Parc familial avec des attractions adaptées à toute la famille, situé en Bretagne.',
        18,
        '1989-07-01',
        '2024-11-25',
        16,
        30,
        8,
        3,
        1
    );

INSERT INTO
    _type_repas (nom)
VALUES 
    ('Petit déj'),
    ('Déjeuner'),
    ('Dîner'),
    ('Boissons'),
    ('Brunch');

INSERT INTO
    _tarif_public (titre, prix, id_offre)
VALUES 
    ('Enfant', 12, 5),
    ('Adulte', 30, 5);

INSERT INTO
    _langue (nom)
VALUES 
    ('Français'),
    ('Breton'),
    ('Anglais');

INSERT INTO
    _visite_langue (id_offre, id_langue)
VALUES 
    (7, 1),
    (7, 2),
    (8, 1),
    (8, 3);
    
-- insertion avis
INSERT INTO _avis (date_publication, date_experience, titre, commentaire, note, note_ambiance, note_service, note_cuisine, rapport_qualite_prix, id_membre, id_offre)
VALUES ('2024-11-01', '2024-10-20', 'Super expérience', 'Très bon restaurant', 5, 5, 4, 5, 4, 1, 1); 

-- insertion réponse
INSERT INTO _reponses (reponse, id_avis, id_pro)
VALUES ('Merci pour votre avis !', 1, 2); -- id_compte = professionnel propriétaire de l'offre

-- Insertion pour la relation ternaire

INSERT INTO _offre_souscription_option (id_offre, id_souscription, nom_option, date_association)
VALUES
-- Offre 1 (Le Gourmet) avec Souscription 1 et Option "A la une"
(1, 1, 'A la une', '2024-01-15'),

-- Offre 1 (Le Gourmet) avec Souscription 2 et Option "En relief"
(1, 2, 'En relief', '2024-02-20'),

-- Offre 2 (Le Bateau Ivre) avec Souscription 1 et Option "A la une"
(2, 1, 'A la une', '2024-03-05'),

-- Offre 2 (Le Bateau Ivre) avec Souscription 2 et Option "En relief"
(2, 2, 'En relief', '2024-04-10'),

-- Offre 3 (Randonnée en forêt) avec Souscription 1 et Option "A la une"
(3, 1, 'A la une', '2024-01-12'),

-- Offre 4 (Kayak sur la rivière) avec Souscription 2 et Option "En relief"
(4, 2, 'En relief', '2024-02-18'),

-- Offre 5 (Spectacle de magie) avec Souscription 1 et Option "A la une"
(5, 1, 'A la une', '2024-03-12'),

-- Offre 6 (Concert acoustique en plein air) avec Souscription 2 et Option "En relief"
(6, 2, 'En relief', '2024-04-22');

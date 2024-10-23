set schema 'sae_db';

-- insertion d'adresses
INSERT INTO _adresse (code_postal, ville, numero, odonyme, complement_adresse)
VALUES
('75001', 'Paris', '123', 'Rue des Fleurs', ''),
('69002', 'Lyon', '45', 'Avenue de la Paix', ''),
('33000', 'Bordeaux', '78', 'Boulevard de Marie', ''),
('75004', 'Paris', '', 'Hôtel de Ville', ''),
('84000', 'Avignon', '23', 'Rue du Tourisme', ''),
('75010', 'Paris', '12', 'Rue de Jean-Macé', ''),
('31000', 'Toulouse', '89', 'Rue des Gourmets', ''),
('69000', 'Lyon', '22', 'Rue des Athlètes', ''),
('44000', 'Nantes', '78', 'Boulevard de la Culture', '');


-- insertion des comptes et pros

INSERT INTO _membre (email, mdp_hash, num_tel, adresse_id, pseudo, nom, prenom)
VALUES 
('jdupont@example.com', 'hash_mdp_1', '0600000001', 1, 'jdupont', 'Dupont', 'Jean'),
('mlavigne@example.com', 'hash_mdp_2', '0600000002', 2, 'mlavigne', 'Lavigne', 'Marie'),
('pbernard@example.com', 'hash_mdp_3', '0600000003', 3, 'pbernard', 'Bernard', 'Paul');


insert into _pro_prive(adresse_id, email, mdp_hash, num_tel, num_siren, denomination)
values
  (4, 'eliott.janot@hotmail.com', 'ouioui', '0607080904','125214526', 'Google'),
  (5, 'leo.blas@gmail.com', 'nonon', '0658457412','123456789', 'Amazon');
 
insert into _pro_public(adresse_id, email, mdp_hash, num_tel, type_orga, nom_orga)
values
  (6, 'gouvernement.macron@gmail.com', '$2y$10$91w3BYtTehtFEtxpQ6VY8ujpcBoB9GAGyUNBZI3lzKWlZlJnEvDba', '0254152245', 'Associatif', 'France'), -- MDP = abc
  (7, 'gouvernement.trump@gmail.com', 'camalaLaBest', '0256965584', 'Organisation Publique', 'USA'),
  (8, 'test.okok@outlook.com', 'lalaland', '0256521245', 'Associatif', 'Dev Unirfou'),
  (9, 'adresse.mail@hotmail.fr', 'appleEstSupASamsung', '0256988884', 'Organisation Publique', 'PluDI D');
  
insert into _RIB (code_banque, code_guichet, numero_compte, cle_rib, compte_id)
values 
  ('12345', '67890', '123456789012', '12', 4),
  ('54321', '86589', '236524184856', '36', 5);
  

-- Insert Offres, 2 chacunes -----------------------------------------------------------------------------------------------

INSERT INTO _restauration (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, adresse_id, gamme_prix)
VALUES 
(true, 
'Restaurant gastronomique offrant une expérience culinaire haut de gamme avec des plats raffinés à base de produits locaux et de saison. Notre chef vous propose un menu dégustation inoubliable.',
'Restaurant gastronomique avec menu dégustation.', 
50.00, 
'2024-10-01', 
'2024-10-15', 
1, 
'$$$'),

(true, 
'Bistro convivial offrant des plats traditionnels français dans un cadre chaleureux. Le menu change chaque jour, basé sur les produits frais du marché.',
'Bistro avec cuisine française traditionnelle.', 
25.00, 
'2024-09-20', 
'2024-10-01', 
2, 
'$$');



INSERT INTO _activite (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, adresse_id, duree_activite, age_requis, prestations)
VALUES 
(true, 
'Parcours de randonnée guidé à travers les montagnes avec des vues imprenables et des explications sur la faune et la flore locale. Idéal pour les amateurs de nature et de marche.',
'Randonnée guidée en montagne avec paysages spectaculaires.', 
30.00, 
'2024-10-10', 
'2024-10-20', 
2, 
'02:00:00', 
12, 
'Guide expérimenté, équipement fourni.'),

(true, 
'Cours de surf pour débutants sur la côte Atlantique. Apprenez les bases du surf avec un instructeur certifié dans un environnement sécurisé.',
'Cours de surf pour débutants.', 
40.00, 
'2024-07-15', 
'2024-07-30', 
6, 
'01:30:00', 
10, 
'Planche de surf fournie, instructeur qualifié.');



INSERT INTO _spectacle (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, adresse_id, capacite_spectacle, duree_spectacle)
VALUES 
(true, 
'Concert de musique symphonique avec orchestre philharmonique de la ville, jouant des oeuvres classiques de Mozart et Beethoven. Une soirée inoubliable pour les amateurs de musique.',
'Concert symphonique avec orchestre.', 
45.00, 
'2024-10-05', 
'2024-10-15', 
3, 
300, 
'01:30:00'),

(true, 
'Spectacle de cirque moderne avec des acrobates, jongleurs, et numéros de trapèze impressionnants. Un divertissement pour toute la famille.',
'Spectacle de cirque moderne avec acrobates.', 
30.00, 
'2024-11-01', 
'2024-11-10', 
7, 
500, 
'02:00:00');



INSERT INTO _visite (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, adresse_id, duree_visite, guide_visite)
VALUES 
(true, 
'Visite guidée du château médiéval avec explication de son histoire et des événements marquants. Une plongée dans le passé pour découvrir la vie au Moyen Âge.',
'Visite guidée du château médiéval.', 
20.00, 
'2024-09-15', 
'2024-09-30', 
4, 
'01:00:00', 
true),

(true, 
'Visite guidée des caves à vin de la région avec dégustation de vins locaux. Une immersion dans l histoire viticole et un parcours à travers les vignes.',
'Visite des caves à vin avec dégustation.', 
50.00, 
'2024-06-10', 
'2024-06-20', 
8, 
'01:30:00', 
true);


INSERT INTO _parc_attraction (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, adresse_id, nb_attractions, age_requis)
VALUES 
(true, 
'Parc d attractions pour toute la famille, avec plus de 20 manèges et attractions adaptés à tous les âges. Un lieu de divertissement incontournable pour petits et grands.',
'Parc d attractions familial avec plus de 20 manèges.', 
35.00, 
'2024-08-01', 
'2024-08-15', 
5, 
20, 
true),

(true, 
'Parc aquatique avec toboggans géants, piscines à vagues, et espaces détente. Parfait pour se rafraîchir en été et s amuser en famille ou entre amis.',
'Parc aquatique avec attractions et piscines.', 
45.00, 
'2024-05-01', 
'2024-05-10', 
9, 
15, 
false)
;


set schema 'sae_db';


-- fonction permettant de récupérer les offres les 10 offres les plus récentes
-- create or replace function _offres_recentes()

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
  (6, 'gouvernement.macron@gmail.com', 'onFeraPas493', '0254152245', 'Associatif', 'France'),
  (7, 'gouvernement.trump@gmail.com', 'camalaLaBest', '0256965584', 'Organisation Publique', 'USA'),
  (8, 'test.okok@outlook.com', 'lalaland', '0256521245', 'Associatif', 'Dev Unirfou'),
  (9, 'adresse.mail@hotmail.fr', 'appleEstSupASamsung', '0256988884', 'Organisation Publique', 'PluDI D');
  
insert into _RIB (code_banque, code_guichet, numero_compte, cle_rib, compte_id)
values 
  ('12345', '67890', '123456789012', '12', 4),
  ('54321', '86589', '236524184856', '36', 5);
  

-- Insert Offres, 2 chacunes -----------------------------------------------------------------------------------------------

INSERT INTO _restauration (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, adresse_id, restauration_id, gamme_prix)
VALUES 
(TRUE, 'Offre restaurant - Menu gastronomique', 'Menu gastronomique', 30, '2024-01-10', '2024-01-15', 6, 1, '€€€'),
(TRUE, 'Jolie restaurant en bord de mer avec une vue imprenable sur la côte de granit rose', 'Petit restaurant avec du charme sur une vue mer', 15, '2024-01-12', '2024-01-18', 7, 2, '€');

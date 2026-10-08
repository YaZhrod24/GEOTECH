USE geotech;

-- Données de test pour le planning. Les employés 1 et 2 proviennent de geotech.sql.
INSERT IGNORE INTO clients
    (id_client, raison_social, email, tel, adresse, cp, ville)
VALUES
    (101, 'Bâtiment Nord', 'contact@batiment-nord.test', '03 20 10 10 10', '12 rue des Forges', '59000', 'Lille'),
    (102, 'Ateliers du Centre', 'contact@ateliers-centre.test', '03 20 20 20 20', '8 avenue du Centre', '59800', 'Lille'),
    (103, 'Logistique Grand Ouest', 'contact@logistique-go.test', '02 40 30 30 30', '4 rue des Quais', '44000', 'Nantes');

INSERT IGNORE INTO equipements
    (id_equipement, nom, type, num_serie, id_client)
VALUES
    (101, 'Serveur principal', 'Serveur', 'TEST-SRV-001', 101),
    (102, 'Routeur atelier', 'Routeur', 'TEST-RTR-002', 102),
    (103, 'Poste de supervision', 'Poste informatique', 'TEST-PCS-003', 103);

INSERT IGNORE INTO interventions
    (id_intervention, desc_panne, date_intervention, date_cloture, statut, rapport, id_equipement, id_employe)
VALUES
    (101, 'Vérification préventive du serveur principal', '2026-10-08 09:00:00', '2026-10-08 10:30:00', 'OUVERTE', NULL, 101, 2),
    (102, 'Remplacement du routeur atelier', '2026-10-08 14:00:00', '2026-10-08 16:00:00', 'EN COURS', NULL, 102, 2),
    (103, 'Diagnostic du poste de supervision', '2026-10-09 11:00:00', '2026-10-09 12:00:00', 'OUVERTE', NULL, 103, 1);

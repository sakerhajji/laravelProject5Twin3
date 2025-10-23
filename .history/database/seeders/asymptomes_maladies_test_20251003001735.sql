-- Insert real Asymptômes
INSERT INTO asymptomes (nom, created_at, updated_at) VALUES
('Fièvre', NOW(), NOW()),
('Toux', NOW(), NOW()),
('Maux de tête', NOW(), NOW()),
('Fatigue', NOW(), NOW()),
('Douleurs musculaires', NOW(), NOW()),
('Perte d’appétit', NOW(), NOW()),
('Nausée', NOW(), NOW()),
('Vomissements', NOW(), NOW()),
('Diarrhée', NOW(), NOW()),
('Essoufflement', NOW(), NOW()),
('Douleur thoracique', NOW(), NOW()),
('Éruption cutanée', NOW(), NOW()),
('Frissons', NOW(), NOW()),
('Sueurs nocturnes', NOW(), NOW()),
('Perte de poids', NOW(), NOW()),
('Vertiges', NOW(), NOW()),
('Saignement de nez', NOW(), NOW()),
('Douleur abdominale', NOW(), NOW()),
('Gonflement des ganglions', NOW(), NOW()),
('Troubles de la vision', NOW(), NOW());

-- Insert real Maladies
INSERT INTO maladies (nom, created_at, updated_at) VALUES
('Grippe', NOW(), NOW()),
('COVID-19', NOW(), NOW()),
('Gastro-entérite', NOW(), NOW()),
('Migraine', NOW(), NOW()),
('Tuberculose', NOW(), NOW()),
('Mononucléose', NOW(), NOW()),
('Rougeole', NOW(), NOW()),
('Hypertension', NOW(), NOW()),
('Hépatite', NOW(), NOW()),
('Pneumonie', NOW(), NOW());

-- Link Asymptômes to Maladies (asymptome_maladie pivot table)
-- Example for Grippe (assuming IDs start at 1)
INSERT INTO asymptome_maladie (maladie_id, asymptome_id, created_at, updated_at) VALUES
(1, 1, NOW(), NOW()), -- Fièvre
(1, 2, NOW(), NOW()), -- Toux
(1, 4, NOW(), NOW()), -- Fatigue
(1, 5, NOW(), NOW()), -- Douleurs musculaires
(1, 3, NOW(), NOW()); -- Maux de tête
-- Add similar blocks for other maladies using their respective symptom IDs
-- Example for COVID-19
INSERT INTO asymptome_maladie (maladie_id, asymptome_id, created_at, updated_at) VALUES
(2, 1, NOW(), NOW()), -- Fièvre
(2, 2, NOW(), NOW()), -- Toux
(2, 4, NOW(), NOW()), -- Fatigue
(2, 6, NOW(), NOW()), -- Perte d’appétit
(2, 10, NOW(), NOW()), -- Essoufflement
(2, 11, NOW(), NOW()); -- Douleur thoracique
-- Continue for other maladies...

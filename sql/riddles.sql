CREATE TABLE riddles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    riddle VARCHAR(255) NOT NULL,
    answer VARCHAR(100) NOT NULL,
    hint VARCHAR(255),
    roomId INT NOT NULL
);

-- Eigen raadsels voor escape room kamers
INSERT INTO riddles (riddle, answer, hint, roomId)
VALUES
    -- Kamer 1
    ('Ik heb handen maar kan niet kloppen. Wat ben ik?', 'Klok', 'Kijk naar je pols of aan de muur.', 1),
    ('Hoe meer je neemt, hoe meer je achterlaat. Wat ben ik?', 'Voetafdrukken', 'Je maakt dit in het zand of op de vloer.', 1),
    ('Wat heeft een hoofd en een staart, maar geen lichaam?', 'Munt', 'Je gooit dit voor kop of munt.', 1),
    
    -- Kamer 2
    ('Ik ben licht als een veer, maar de sterkste man ter wereld kan me niet vasthouden. Wat ben ik?', 'Adem', 'Je hebt dit nodig om te leven.', 2),
    ('Wat kun je zien, maar niet aanraken, en kan niet vast zitten?', 'Schaduw', 'Volgt je op doffe dagen en zonnige dagen.', 2),
    ('Ik heb een gezicht en twee handen, maar geen armen of benen. Wat ben ik?', 'Horloge', 'Wijst de tijd aan.', 2),
    
    -- Kamer 3
    ('Je kunt het vangen, maar niet gooien. Wat ben ik?', 'Kou', 'Je voelt dit in de winter.', 3),
    ('Wat wordt natter naarmate het droogt?', 'Handdoek', 'Je gebruikt dit na het douchen.', 3),
    ('Ik ben nooit nass, maar bij regen ben ik het eerste wat nat wordt. Wat ben ik?', 'Regenjas', 'Protection tegen water.', 3);

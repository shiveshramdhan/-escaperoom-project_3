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
    -- Horrorvragen kamer 1
    ('Je hoort gefluister in het donker, maar ziet niemand. Wat is het?', 'de wind', 'Het is overal, maar je ziet het niet.', 1),
    ('In een verlaten huis tikt iets zonder klok. Wat is het?', 'het hart', 'Het klopt, maar je ziet het niet.', 1),
    ('Welke schaduw volgt je alleen als het licht uitgaat?', 'angst', 'Het zit in je hoofd.', 1),
    
    -- Kamer 2
    ('Ik ben licht als een veer, maar de sterkste man ter wereld kan me niet vasthouden. Wat ben ik?', 'Adem', 'Je hebt dit nodig om te leven.', 2),
    ('Wat kun je zien, maar niet aanraken, en kan niet vast zitten?', 'Schaduw', 'Volgt je op doffe dagen en zonnige dagen.', 2),
    ('Ik heb een gezicht en twee handen, maar geen armen of benen. Wat ben ik?', 'Horloge', 'Wijst de tijd aan.', 2),
    
    -- Kamer 3
    ('HOE MEER JE ME VOEDT, HOE GROTER IK WORD. WATER DOODT ME. Wat ben ik?', 'Vuur', 'Element', 3),
    ('ƎԀ∀ƆƧƎ', 'ESCAPE', 'Draai het om...', 3),
    ('3 – 6 – 12 – 24 – ?', '48', 'Denk na.', 3);

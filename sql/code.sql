CREATE TABLE Pays(
   ID_Pays VARCHAR(50),
   Libéle VARCHAR(255),
   Code_Lettre VARCHAR(10),
   Code_Chiffre VARCHAR(10),
   PRIMARY KEY(ID_Pays),
   UNIQUE(Code_Lettre),
   UNIQUE(Code_Chiffre)
);

CREATE TABLE Virus(
   ID_Virus VARCHAR(50),
   Libéle VARCHAR(255),
   Date_Apparition DATE,
   Date_Fin DATE,
   Description TEXT,
   PRIMARY KEY(ID_Virus)
);

CREATE TABLE Données_Virus(
   ID_Donnees VARCHAR(50),
   Date_du_jour DATE,
   Total_Cas INT,
   Total_Mort INT,
   Nouveau_Cas INT,
   Nouveau_Mort INT,
   ID_Pays VARCHAR(50) NOT NULL,
   ID_Virus VARCHAR(50) NOT NULL,
   PRIMARY KEY(ID_Donnees),
   FOREIGN KEY(ID_Pays) REFERENCES Pays(ID_Pays),
   FOREIGN KEY(ID_Virus) REFERENCES Virus(ID_Virus)
);
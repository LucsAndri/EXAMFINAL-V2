create database examfinalS2;
use examfinalS2;
CREATE TABLE membre (
    id_membre INT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre VARCHAR(1),
    email VARCHAR(100) UNIQUE,
    ville VARCHAR(100),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);
CREATE TABLE categorie_objet (
    id_categorie INT PRIMARY KEY,
    nom_categorie VARCHAR(100) UNIQUE
);
CREATE TABLE objet (
    id_objet INT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);
CREATE TABLE images_objet (
    id_image INT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);
CREATE TABLE emprunt (
    id_emprunt INT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);


INSERT INTO membre (id_membre, nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
(1, 'Alice Dupont', '1990-05-12', 'F', 'alice.dupont@email.com', 'Paris', 'password1', 'alice.jpg'),
(2, 'Bob Martin', '1985-08-23', 'M', 'bob.martin@email.com', 'Lyon', 'password2', 'bob.jpg'),
(3, 'Claire Petit', '1992-11-03', 'F', 'claire.petit@email.com', 'Marseille', 'password3', 'claire.jpg'),
(4, 'David Leroy', '1988-02-17', 'M', 'david.leroy@email.com', 'Toulouse', 'password4', 'david.jpg');

INSERT INTO categorie_objet (id_categorie, nom_categorie) VALUES
(1, 'Esthétique'),
(2, 'Bricolage'),
(3, 'Mécanique'),
(4, 'Cuisine');

INSERT INTO objet (id_objet, nom_objet, id_categorie, id_membre) VALUES
(1, 'Rouge à lèvres', 1, 1),
(2, 'Perceuse', 2, 2),
(3, 'Clé à molette', 3, 3),
(4, 'Robot de cuisine', 4, 4),
(5, 'Fond de teint', 1, 1),
(6, 'Scie sauteuse', 2, 2),
(7, 'Tournevis', 3, 3),
(8, 'Mixeur', 4, 4),
(9, 'Mascara', 1, 1),
(10, 'Ponceuse', 2, 2);

INSERT INTO emprunt (id_emprunt, id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 1, 2, '2023-10-01', '2023-10-15'),
(2, 2, 3, '2023-10-02', '2023-10-16'),
(3, 3, 4, '2023-10-03', '2023-10-17'),
(4, 4, 1, '2023-10-04', '2023-10-18'),
(5, 5, 2, '2023-10-05', '2023-10-19'),
(6, 6, 3, '2023-10-06', '2023-10-20'),
(7, 7, 4, '2023-10-07', '2023-10-21'),
(8, 8, 1, '2023-10-08', '2023-10-22'),
(9, 9, 2, '2023-10-09', '2023-10-23'),
(10, 10, 3, '2023-10-10', '2023-10-24');

INSERT INTO images_objet (id_image, id_objet, nom_image) VALUES
(1, 1, 'rouge_a_levres.jpg'),
(2, 2, 'perceuse.jpg'),
(3, 3, 'cle_a_molette.jpg'),
(4, 4, 'robot_cuisine.jpg'),
(5, 5, 'fond_de_teint.jpg'),
(6, 6, 'scie_sauteuse.jpg'),
(7, 7, 'tournevis.jpg'),
(8, 8, 'mixeur.jpg'),
(9, 9, 'mascara.jpg'),
(10, 10, 'ponceuse.jpg');

UPDATE emprunt
SET etat_retour = 'Abîmé'
WHERE id_emprunt = 11;
# Unity-Care-Clinic---Version-2

## Description du projet
Unity Care Clinic V2 est un système de gestion de clinique médicale développé en PHP orienté objet. Cette application permet la gestion complète du parcours patient : authentification sécurisée, prise de rendez-vous, consultations et prescriptions médicales.

## Fonctionnalités principales
### Système d'authentification

Connexion sécurisée avec email et mot de passe
Gestion des sessions PHP
Déconnexion sécurisée
Protection des mots de passe (hashage)

### Gestion des utilisateurs (3 rôles)

Admin : gestion complète du système
Doctor : gestion des rendez-vous et prescriptions
Patient : prise de rendez-vous et consultation des prescriptions

### Gestion des rendez-vous

Création de rendez-vous
Visualisation des rendez-vous
Annulation de rendez-vous
Modification du statut (programmé, effectué, annulé)

### Gestion des prescriptions

Création de prescriptions par les médecins
Consultation de l'historique par les patients
Gestion du catalogue de médicaments

### Sécurité

Protection contre les attaques XSS
Protection contre les injections SQL (requêtes préparées)
Protection CSRF (tokens sur les formulaires)
Hashage sécurisé des mots de passe

### Statistiques

Tableau de bord avec statistiques des rendez-vous
Statistiques par statut et par médecin
Médicaments les plus prescrits

## Technologies utilisées

Backend : PHP 7.4+
Base de données : MySQL
Frontend : HTML5, CSS3, JavaScript
Architecture : POO (Programmation Orientée Objet)
Sécurité : Sessions PHP, PDO, password_hash()

## Structure du projet
unity-care-clinic/
├── config/
│   └── database.php          # Configuration de la base de données
├── classes/
│   ├── Database.php          # Connexion à la base de données
│   ├── User.php              # Classe abstraite User
│   ├── Admin.php             # Classe Admin
│   ├── Doctor.php            # Classe Doctor
│   ├── Patient.php           # Classe Patient
│   ├── Appointment.php       # Gestion des rendez-vous
│   ├── Medication.php        # Gestion des médicaments
│   └── Prescription.php      # Gestion des prescriptions
├── public/
│   ├── index.php             # Page d'accueil
│   ├── login.php             # Page de connexion
│   ├── logout.php            # Déconnexion
│   ├── admin/                # Pages administrateur
│   ├── doctor/               # Pages médecin
│   └── patient/              # Pages patient
├── assets/
│   ├── css/                  # Fichiers CSS
│   └── js/                   # Fichiers JavaScript
└── README.md

## Installation
### Prérequis

PHP 7.4 ou supérieur
MySQL 5.7 ou supérieur
Serveur Apache ou Nginx

### Étapes d'installation

#### Cloner le repository

bashgit clone [https://github.com/votre-username/unity-care-clinic.git](https://github.com/elmouhilih044-a11y/Unity-Care-Clinic---Version-2.git)

#### Créer la base de données

sqlCREATE DATABASE unity_care_clinic_V2;

#### Configurer la connexion à la base de données
Modifier le fichier config/database.php avec vos identifiants :

phpdefine('DB_HOST', 'localhost');
define('DB_NAME', 'unity_care_clinic');
define('DB_USER', 'votre_username');
define('DB_PASS', 'votre_password');


## Comptes de test
### Administrateur

Email : admin@clinic.com
Mot de passe : Admin123!

### Médecin

Email : doctor@clinic.com
Mot de passe : Doctor123!

### Patient

Email : patient@clinic.com
Mot de passe : Patient123!


## Base de données
### Tables principales

users : tous les utilisateurs (admin, doctor, patient)
departments : départements médicaux
appointments : rendez-vous médicaux
medications : catalogue de médicaments
prescriptions : prescriptions médicales

## Sécurité

Mots de passe : hashés avec password_hash()
Sessions : gestion sécurisée avec $_SESSION
XSS : échappement de toutes les sorties avec htmlspecialchars()
SQL Injection : requêtes préparées PDO
CSRF : tokens sur tous les formulaires

## Auteur
Hajar Elmouhili
## Date de réalisation
Projet réalisé entre le 29/12/2025 et le 09/01/2026

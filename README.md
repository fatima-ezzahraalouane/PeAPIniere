# 🌿 PéAPInière API - Connecter la nature au digital

Bienvenue dans **PéAPInière** 🌱, l'API officielle d'une pépinière en pleine croissance qui vise à révolutionner la gestion des plantes, des commandes et des rôles utilisateurs à travers une plateforme robuste, sécurisée et moderne.

---

## 🔍 Contexte du projet

Notre pépinière connaît une belle croissance et nous souhaitons **améliorer notre efficacité, automatiser nos processus** et **offrir une meilleure expérience client**.  
Ce projet a pour but de centraliser la **gestion des stocks, des ventes** et de proposer une interface fluide pour nos clients, nos employés et notre équipe administrative.

---

## 🎯 Objectifs

- 🚀 Développer une API RESTful performante et sécurisée
- 🪴 Gérer les plantes (CRUD + images)
- 🧾 Suivre les commandes des clients
- 👥 Gérer les utilisateurs avec différents rôles (`Client`, `Employé`, `Admin`)
- 📊 Offrir des statistiques avancées pour l’administrateur
- 🧪 Intégrer des tests unitaires & une documentation complète

---

## 🧑‍💻 Technologies utilisées

- ⚙️ **Laravel** 10+ (architecture MVC + Repository Pattern)
- 🐘 **PostgreSQL**
- 🔐 **JWT Authentication** pour une sécurité optimale
- 📂 **Spatie/Sluggable** pour des slugs SEO-friendly
- 🧪 **PHPUnit** pour les tests unitaires
- 📮 **Postman** pour tester l’API
- 🧾 **Swagger** pour documenter chaque endpoint
- 🛠️ **DTO (Data Transfer Objects)** pour structurer les données

---

## 📚 User Stories

### 👤 Clients
- 🔐 S’inscrire / se connecter via JWT
- 🪴 Voir toutes les plantes disponibles (nom, description, prix, images, catégorie)
- 🔎 Voir les détails d’une plante via son **slug**  
  `GET /api/plants/basilic-aromatique`
- 🧺 Passer une commande en choisissant les slugs + quantités
- 🆗 Suivre l’état de la commande : `en attente`, `en préparation`, `livrée`
- ❌ Annuler une commande si elle n’a pas encore été préparée

### 🧑‍🏭 Employés
- 🔐 Se connecter avec des permissions adaptées
- ⌛ Marquer une commande comme **en préparation** ou **livrée**

### 👑 Administrateurs
- 🎍 Gérer les plantes & catégories (CRUD complet)
- 📊 Accéder à des statistiques :
  - Total des commandes
  - Revenu total
  - Plantes les plus commandées
  - Ventes par catégorie

### 💻 Développeurs
- ✅ Écrire des **tests unitaires** :
  - Authentification
  - Slugs
  - CRUD catégories
- 📮 Documenter chaque endpoint avec **Swagger**
- 🚧 Gérer les exceptions avec messages & codes HTTP adaptés
- 📦 Implémenter un **DAO (Data Access Object)** pour une couche data propre
- 🧠 Documenter chaque requête **Query Builder** pour faciliter la compréhension
- 🛡️ Utiliser des **DTO** pour valider les données échangées
- 🖼️ Limiter chaque plante à **4 images maximum**, avec message d’erreur clair :  
  `"Limite de 4 images par plante dépassée."`

---

## 🧪 Tests et documentation

- 🧾 Swagger UI disponible à `/api/documentation`
- ✔️ Tests unitaires via `PHPUnit`
- 🔬 Tester l’API avec Postman grâce à une collection exportable

---

## 📫 Contact

Développé avec ❤️ par **Fatima-Ezzahra Alouane**  
📧 [falouane38@exemple.com]  
🔗 [LinkedIn](https://www.linkedin.com/in/fatima-ezzahra-alouane/)

---

> "L'innovation, c’est rendre les choses complexes accessibles à tous – même la nature 🌱."  
> — *PéAPInière, la tech au service du vivant.*


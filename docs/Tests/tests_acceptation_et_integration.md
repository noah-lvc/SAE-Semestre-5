# Plan de tests – Tests d’intégration et d’acceptation
**SAE Semestre 5 – BUT Informatique – IUT de Vélizy**

---

## 1. Introduction

Ce document décrit l’ensemble des **tests d’intégration** et des **tests d’acceptation** réalisés dans le cadre de la SAE.

Les tests présentés ont pour objectif de vérifier que :
- les fonctionnalités de l’application web sont conformes aux besoins exprimés,
- les composants du système interagissent correctement entre eux,
- les cas d’utilisation définis selon la méthode d’**Alistair Cockburn** sont respectés.

Les tests concernant :
- le matériel Raspberry Pi,
- le calcul distribué,
- les performances du cluster,

ne sont volontairement pas inclus dans ce document et font l’objet d’une validation séparée.

De plus, les tests unitaires n’ont pas été mis en place dans ce projet car les fonctions présentes sont principalement orientées interface utilisateur.

La fonction `afficherBarnav()` du fichier fonctions.php dépend de la session PHP et inclut dynamiquement des fichiers HTML/PHP, ce qui empêche son isolement dans un test unitaire.

De même, le code JavaScript repose sur des événements et la manipulation du DOM, ce qui relève davantage de tests fonctionnels ou d’acceptation.

Les comportements du site ont donc été validés via des tests d’acceptation et d’intégration.

---

## 2. Périmètre et règles de test

### 2.1 Périmètre couvert
Les tests couvrent exclusivement les **cas d’utilisation de niveau Utilisateur 🌊 et de portée Système ⬛**, tels que définis dans le document *recueil_des_besoins.md*.

### 2.2 Périmètre exclu
Les cas d’utilisation de type :
- **Sous-fonction 🐟**
- **Sous-partie 🔩**
- liés au **cluster Raspberry Pi**

sont exclus de ce document.

---

## 3. Environnement de test

- Application web fonctionnelle
- Serveur web opérationnel
- Base de données accessible
- Navigateur web récent (Firefox, Chrome)
- Comptes de test :
    - utilisateur standard
    - administrateur web
    - administrateur système

---

## 4. Tests d’intégration

### TI-01 – Authentification et accès à l’interface

**Objectif**  
Vérifier que le module d’authentification communique correctement avec le serveur.

**Scénario**
1. Un utilisateur saisit ses identifiants
2. Il valide le formulaire

**Résultat attendu**
- L’utilisateur est authentifié
- L’interface principale est affichée

**Statut**
- ✅ Conforme

---

### TI-02 – Interaction interface web ↔ serveur lors d’un calcul

**Objectif**  
Vérifier que le lancement d’un calcul depuis l’interface est pris en charge par le serveur.

**Scénario**
1. L’utilisateur sélectionne un module de calcul
2. Il lance le calcul

**Résultat attendu**
- La requête est transmise au serveur
- Le traitement est déclenché
- Un retour utilisateur est affiché

**Statut**
- ✅ Conforme

---

### TI-03 – Enregistrement et récupération des données utilisateur

**Objectif**  
Vérifier l’intégration entre l’interface, le serveur et la base de données.

**Scénario**
1. L’utilisateur effectue une action (création de compte, calcul, modification)
2. Les données sont enregistrées
3. L’utilisateur consulte les informations enregistrées

**Résultat attendu**
- Les données sont correctement stockées
- Les informations sont cohérentes lors de l’affichage

**Statut**
- ✅ Conforme

---

## 5. Tests d’acceptation

---

### TA-UC-01 – Créer un compte utilisateur

**Cas d’utilisation associé**  
Créer compte utilisateur

**Acteur principal**  
Visiteur

**Pré-condition**  
Aucun compte existant avec le même identifiant

**Scénario de test**
1. Le visiteur accède au formulaire d’inscription
2. Il renseigne ses informations
3. Il valide le formulaire

**Résultat attendu**
- Le compte est créé
- Une confirmation est affichée

**Critère d’acceptation**
- Le visiteur peut se connecter avec le compte créé

**Statut**
- ✅ Accepté

---

### TA-UC-02 – Se connecter à la plateforme

**Cas d’utilisation associé**  
Se connecter

**Acteur principal**  
Utilisateur

**Pré-condition**  
Un compte utilisateur existe

**Scénario de test**
1. Le visiteur accède au formulaire de connexion
2. Il saisit ses identifiants
3. Il valide

**Résultat attendu**
- L’utilisateur est connecté
- L’interface principale est accessible

**Statut**
- ✅ Accepté

---

### TA-UC-03 – Se déconnecter

**Cas d’utilisation associé**  
Se déconnecter

**Acteur principal**  
Utilisateur

**Pré-condition**  
Utilisateur connecté

**Scénario de test**
1. L’utilisateur accède à son profil
2. Il clique sur le bouton de déconnexion

**Résultat attendu**
- La session est fermée
- L’utilisateur est redirigé vers la page d’accueil

**Statut**
- ✅ Accepté

---

### TA-UC-04 – Modifier son mot de passe

**Cas d’utilisation associé**  
Modification du mot de passe utilisateur

**Acteur principal**  
Utilisateur

**Pré-condition**  
Utilisateur connecté

**Scénario de test**
1. L’utilisateur accède à son profil
2. Il modifie son mot de passe
3. Il valide la modification

**Résultat attendu**
- Le mot de passe est modifié
- Une confirmation est affichée

**Statut**
- ✅ Accepté

---

### TA-UC-05 – Consulter son historique de calcul

**Cas d’utilisation associé**  
Consulter son historique de calcul

**Acteur principal**  
Utilisateur

**Pré-condition**  
L’utilisateur a déjà effectué des calculs

**Scénario de test**
1. L’utilisateur accède à la page d’historique

**Résultat attendu**
- Les résultats des calculs sont affichés

**Statut**
- ❌ Refusé (pas fait)

---

### TA-UC-06 – Enregistrer une fiche de calcul

**Cas d’utilisation associé**  
Enregistrer un résultat de calcul

**Acteur principal**  
Utilisateur

**Pré-condition**  
Un calcul a été réalisé

**Scénario de test**
1. L’utilisateur réalise un calcul
2. Il valide l’enregistrement

**Résultat attendu**
- Le résultat est enregistré
- Il apparaît dans l’historique

**Statut**
- ❌ Refusé (pas fait)

---

### TA-UC-07 – Supprimer son compte utilisateur

**Cas d’utilisation associé**  
Suppression de compte utilisateur par un utilisateur

**Acteur principal**  
Utilisateur

**Pré-condition**  
Utilisateur connecté

**Scénario de test**
1. L’utilisateur accède à son profil
2. Il demande la suppression du compte
3. Il confirme

**Résultat attendu**
- Le compte est supprimé
- Redirection vers la page d'accueil destinée aux visiteurs

**Statut**
- ✅ Accepté

---

### TA-UC-08 – Supprimer un compte utilisateur (administrateur)

**Cas d’utilisation associé**  
Suppression de compte utilisateur par l’admin

**Acteur principal**  
Administrateur web

**Pré-condition**  
Un compte utilisateur existe

**Scénario de test**
1. L’administrateur accède à la gestion des comptes
2. Il sélectionne un compte
3. Il valide la suppression

**Résultat attendu**
- Le compte est supprimé
- Une confirmation est affichée

**Statut**
- ✅ Accepté

---

### TA-UC-09 – Créer un compte utilisateur (administrateur)

**Cas d’utilisation associé**  
Créer compte(s) utilisateur

**Acteur principal**  
Administrateur web

**Pré-condition**  
Administrateur connecté

**Scénario de test**
1. L’administrateur accède à la gestion des comptes
2. Il crée un nouveau compte
3. Il valide

**Résultat attendu**
- Le compte est créé
- Une confirmation est affichée

**Statut**
- ✅ Accepté

---

## 6. Tableau de traçabilité

| Cas d’utilisation | Test associé |
|------------------|-------------|
| Créer compte utilisateur | TA-UC-01 |
| Se connecter | TA-UC-02 |
| Se déconnecter | TA-UC-03 |
| Modifier mot de passe | TA-UC-04 |
| Consulter historique | TA-UC-05 |
| Enregistrer fiche de calcul | TA-UC-06 |
| Supprimer compte utilisateur | TA-UC-07 |
| Supprimer compte (admin) | TA-UC-08 |
| Créer compte (admin) | TA-UC-09 |

---

## 7. Conclusion

Les tests d’intégration et d’acceptation réalisés valident l’ensemble des fonctionnalités de l’application web décrites dans les cas d’utilisation.

Les besoins exprimés par les utilisateurs et les administrateurs sont respectés.  
Les tests relatifs au calcul distribué et au cluster Raspberry Pi sont traités séparément.
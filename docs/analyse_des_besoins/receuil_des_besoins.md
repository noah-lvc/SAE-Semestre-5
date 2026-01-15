### Cas d'utilisation système : 

| Cas d'utilisation : | Créer compte utilisateur                                                                                                                                                                   |
|---------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Description**         | Un visiteur veut créer un compte utilisateur pour accéder aux modules de calculs                                                                                                                |
|**Portée**              | Système ⬛                                                                                                                                                                                       |
| **Niveau**              | Utlisateur 🌊                                                                                                                                                                                   |
| **Acteur Principale**   | Visiteur                                                                                                                                                                                        |                                                                                |
|**Scénario nominal**    | 1. Le visiteur se rends sur le formulaire d'inscription <br/> 2. Le visiteur rentre ses informations <br/>3. Une confirmation est affiché au visiteur <br/> |
| **Scénario alternatifs** |                                                                                                                                                                                                 |
| **Scénario exceptionnel** | 1. Le login existe déja <br/>  &nbsp; &nbsp; &nbsp; &nbsp; a. Le visiteur se rends sur le formulaire d'inscription <br/> &nbsp; &nbsp; &nbsp; &nbsp; b. Le visiteur rentre ses informations <br/>     &nbsp; &nbsp; &nbsp;    &nbsp; c. Renvoie une erreur lui indiquant que le login est déja pris <br/>2. Le login ne possède pas le nombre de caractères requis <br/>  &nbsp; &nbsp; &nbsp; &nbsp; a. Le visiteur se rends sur le formulaire d'inscription <br/> &nbsp; &nbsp; &nbsp; &nbsp; b. Le visiteur rentre ses informations                 <br/>                &nbsp; &nbsp; &nbsp;                         &nbsp; c. Renvoie une erreur lui indiquant que le login ne possède pas le nombre nécessaire de                                   caractères                                                                                                                                                                                   |
| **Post-Conditions**     | Un compte utilisateur a été crée.                                                                                                                                                               |



| Cas d'utilisation : | Se connecter                                                                                                                                                                                         |
|--------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Description**        | Un visiteur veut se connecter à son compte utilisateur                                                                                                                                               |
| **Portée**            | Système ⬛                                                                                                                                                                                            |
| **Niveau**             | Utlisateur 🌊                                                                                                                                                                                        |
| **Acteur Principale**  | Visiteur                                                                                                                                                                                             |                                                                                |
| **Scénario nominal**   | 1. Le visiteur se rends sur le formulaire de connexion <br/> 2. Le visiteur rentre ses informations <br/> 3. Le visiteur valide <br/> 4. Une confirmation de connexion est affiché au visiteur <br/> |
| **Scénario alternatifs**|  1. L'utilisateur se connecte grace a un cookie                                                                                                                                                      |
| **Scénario exceptionnel**|1. L'utilisateur se trompe de mot de passe <br/>  &nbsp; &nbsp; &nbsp; &nbsp; a. Le visiteur se rends sur le formulaire de connexion <br/>  &nbsp; &nbsp; &nbsp; &nbsp; b. Le visiteur rentre ses                             informations <br/> &nbsp; &nbsp; &nbsp; &nbsp; c. Le visiteur valide <br/>  &nbsp; &nbsp; &nbsp; &nbsp; d.  Renvoie une erreur lui indiquant que le mot de passe ne correspond pas  <br/> 2. Le                               login n'existe pas <br/>  &nbsp; &nbsp; &nbsp; &nbsp; a. Le visiteur se rends sur le formulaire de connexion <br/>  &nbsp; &nbsp; &nbsp; &nbsp; b. Le visiteur rentre ses informations <br/> &nbsp;                           &nbsp; &nbsp; &nbsp; c. Le visiteur valide <br/>  &nbsp; &nbsp; &nbsp; &nbsp; d. Renvoie une erreur lui indiquant que le login n'existe pas                                                         |
| **Pré-condition**      | Il existe un compte utilisateur correspondant aux informations du visiteur.                                                                                                                          |
| **Post-Conditions**    | Le visiteur est désormais connectés en tant qu'utilisateur.                                                                                                                                          |


| Cas d'utilisation :| Consulter son historique de calcul                              |
|--------------------|-----------------------------------------------------------------|
| **Description**        | Un utilisateur veut consulter ses fiches de calcul précédentes. |
| **Portée**             | Système ⬛                                                       |
| **Niveau**             | Utlisateur 🌊                                                   |
| **Acteur Principale**  | Utilisateur                                                     |                                                                          
| **Scénario nominal**   | 1. L'utilisateur se rends sur la page historique des calculs<br/>          |
| **Scénario alternatifs** |                                                                 |
| **Scénario exceptionnel** |                                                                 |
| **Pré-condition**      | L'utilisateur a déjà réalisé des calculs avec les modules<br/> Il existe dejà un compte correspondant aux informations de l'utilisateur      |


| Cas d'utilisation :| Enregistrer une fiche de calcul                                                                                                             |
|--------------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| **Description**        | Un utilisateur veut consulter ses fiches de calcul précédentes.                                                                             |
| **Portée**             | Système ⬛                                                                                                                                   |
| **Niveau**             | Utlisateur 🌊                                                                                                                               |
| **Acteur Principale**  | Utilisateur                                                                                                                                 |                                                                          
| **Scénario nominal**   | 1. L'utilisateur se rends sur le module de calcul <br/> 2. Réalise un calcul <br/> 3. Valide son calcul <br/> 4. Indique qu'il veut stocker le calcul |
| **Scénario alternatifs** |                                                                                                                                             |
| **Scénario exceptionnel** |                                                                                                                                             |
| **Pré-condition**      | L'utilisateur est connecté                                                            |
| **Post-condition**      | La fiche de calcul est désormais enregistrer dans l'historique de l'utilisateur.                                                            |


| Cas d'utilisation :| Modification du mot de passe utilisateur                                                                                                             |
|--------------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| **Description**        | Un utilisateur peux modifier son mot de passe lorsqu'il est connecté                                                                          |
| **Portée**             | Système ⬛                                                                                                                                   |
| **Niveau**             | Utlisateur 🌊                                                                                                                               |
| **Acteur Principale**  | Utilisateur                                                                                                                                 |                                                                          
| **Scénario nominal**   | 1. L'utilisateur se connecte <br/> 2. Entre son ancien mot de passe </br> 3. Entre son nouveau mot de passe </br> 4. Entre une deuxième fois son nouveau mot de passe <br/> 5. Confirme sa modification <br/> 6. Un message confirmant sa modification s'affiche </br> |
| **Scénario alternatifs** |                                                                                                                                             |
| **Scénario exceptionnel** | 1. L'utilisateur se trompe d'ancien mot de passe <br/>  &nbsp; &nbsp; &nbsp; &nbsp; a. L'utilisateur se connecte <br/>  &nbsp; &nbsp; &nbsp; &nbsp; b. Entre un mauvais ancien mot de passe <br/> &nbsp; &nbsp; &nbsp; &nbsp; c. Entre son nouveau mot de passe <br/> &nbsp; &nbsp; &nbsp; &nbsp; d. Entre une deuxième fois son nouveau mot de passe </br> &nbsp; &nbsp; &nbsp; &nbsp; e. Confirme sa modification <br/>  &nbsp; &nbsp; &nbsp; &nbsp; f. Renvoie une erreur Ancien mot de passe incorrect <br/> 2. Le nouveau mot de passe et sa confirmation ne correspondent pas <br/>  &nbsp; &nbsp; &nbsp; &nbsp; a. L'utilisateur se connecte <br/>  &nbsp; &nbsp; &nbsp; &nbsp; b. Entre l'ancien mot de passe <br/> &nbsp; &nbsp; &nbsp; &nbsp; c. Entre son nouveau mot de passe <br/> &nbsp; &nbsp; &nbsp; &nbsp; d. Entre un autre mot de passe </br> &nbsp; &nbsp; &nbsp; &nbsp; e. Confirme sa modification <br/>  &nbsp; &nbsp; &nbsp; &nbsp; f. Renvoie une erreur Les nouveaux mots de passe ne correspondent pas  <br/> |
| **Pré-condition**      | L'utilisateur a un compte                                                                                                            |
| **Post-condition**      | Le mot de passe de l'utilisateur a été changé                                                                                                            |

| Cas d'utilisation :| Supression de compte utilisateur par un utilisateur                                                                                                             |
|--------------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| **Description**        | Un utilisateur veut supprimer un compte utilisateur                                                                         |
| **Portée**             | Système ⬛                                                                                                                                   |
| **Niveau**             | Utlisateur 🌊                                                                                                                               |
| **Acteur Principale**  | Utilisateur                                                                                                                                 |                                                                          
| **Scénario nominal**   | 1. L'utilisateur se connecte <br/> 2. Se rends sur son profil <br/> 3. Supprime son compte <br/> 4. Confirme la suppression 
| **Scénario alternatifs** |                                                                                                                                             |
| **Scénario exceptionnel** |                                                                                                                                             |
| **Pré-condition**      | L'utilisateur a un compte |
| **Post-condition**      | Le compte a été supprimé |

**ADMIN WEB**

| Cas d'utilisation :| Supression de compte utilisateur par l'admin                                                                                                             |
|--------------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| **Description**        | L'administrateur web veut supprimer un compte utilisateur                                                                         |
| **Portée**             | Système ⬛                                                                                                                                   |
| **Niveau**             | Utlisateur 🌊                                                                                                                               |
| **Acteur Principale**  | Administrateur web                                                                                                                                 |                                                                          
| **Scénario nominal**   | 1. L'admin se connecte <br/> 2. Se rends sur la page de gestion des comptes <br/> 3. Selectionne un compte a supprimer <br/> 4. Valide la suppression <br/> 5. Une confirmation est affiché 
| **Scénario alternatifs** |                                                                                                                                             |
| **Scénario exceptionnel** |                                                                                                                                             |
| **Pré-condition**      | Il existe au moins un ou plus compte utilisateur                                                           |

| Cas d'utilisation :| Créer compte(s) utilisateur                                                                                                        |
|--------------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| **Description**        | L'admin veux créer des comptes utilisateurs                                                                          |
| **Portée**             | Système ⬛                                                                                                                                   |
| **Niveau**             | Utlisateur 🌊                                                                                                                               |
| **Acteur Principale**  | Administrateur Web                                                                                                                                 |                                                                          
| **Scénario nominal**   | 1. L'admin se connecte <br/> 2. Se rends sur la page de création de comptes <br/> 3. Entre un login <br/> 4. Entre un mot de passe <br/> 5. Valide le mot de passe <br/> 6. Valide la création <br/> 7. Une confirmation est affiché |
| **Scénario alternatifs** | 1. L'admin souhaite ajouter plusieurs utilisateurs à la fois avec un fichier CSV </br> &nbsp; &nbsp; &nbsp; &nbsp; a. L'admin se connecte <br/> &nbsp; &nbsp; &nbsp; &nbsp; b. Se rends sur la page de création de comptes <br/> &nbsp; &nbsp; &nbsp; &nbsp; c. Sélectionne le fichier CSV souhaité <br/>  &nbsp; &nbsp; &nbsp; &nbsp; d. Confirme l'importation </br> &nbsp; &nbsp; &nbsp; &nbsp; e. Une confirmation est affichée  <br/> |
| **Scénario exceptionnel** |                                                                                                                                             |
| **Pré-condition**      |                                                                                                                                                |

|Cas d'utilisation :| Enregistrer un fichier log |
|--------------------|-----------------------|
|**Description**| Enresgitrement d'un fichier log décrivant une action spécifique prédéterminée dans le système |
|**Portée**| Sous-partie 🔩 |
|**Niveau**| Sous-fonction 🐟 |
|**Acteur Principal**| Administrateur Système |
|**Scénario Nominal**|1. Un visiteur accède au formulaire d'inscription du site</br>2. Le visiteur créer un nouveau compte</br>|
|**Scénario Alternatif**|I.1. Un admistrateur Web se connecte au compte ***adminweb***</br>2. L'administrateur supprime un compte
|**Scénario Exceptionnel**||
|**Pré-condition**||

|Cas d'utilisation :| Se déconnecter |
|--------------------|-----------------------|
|**Description**| Un utilisateur veut se déconnecter de son compte|
|**Portée**| Système ⬛ |
|**Niveau**| Utilisateur 🌊|
|**Acteur Principale**| Utilisateur |
|**Scénario Nominal**|1. L'utilisateur va sur son profil <br/> 2. Appuie sur le bouton pour se déconnecter|
|**Scénario alternatif**||
|**Scénario Exceptionnel**||
|**Pré-condition**|Possède un compte|
|**Post-condition**|L'utilisateur est déconnecté et sur la page d'accueil du site|

|Cas d'utilisation :| Verifier la validité du contenu du formulaire de d'inscription |
|--------------------|-----------------------|
|**Description**| Un utilisateur veut vérifier que les informations d'inscription  qu'il a entré dans le formulaire sont correctes |
|**Portée**| Sous-partie 🔩 |
|**Niveau**| Utilisateur 🌊 |
|**Acteur Principale**| utilisateur |
|**Scénario Nominal**|1. L'utilisateur accède au formulaire d'inscription du site</br>2. L'utilisateur entre ses informations personelles pour se connecter </br>3. L'utilisateur clique sur le bouton de vérification du formulaire</br>|
|**Scénario alternatif**||
|**Scénario Exceptionnel**||
|**Pré-condition**||

|Cas d'utilisation :| Verifier la validité du contenu du formulaire de connexion |
|--------------------|-----------------------|
|**Description**| Un utilisateur veut vérifier que les informations qu'il a entré dans le formulaire sont correctes |
|**Portée**| Sous-partie 🔩 |
|**Niveau**| Utilisateur 🌊 |
|**Acteur Principale**| utilisateur |
|**Scénario Nominal**|1. L'utilisateur accède au formulaire d'inscription du site</br>2. L'utilisateur entre ses informations de connexions </br>2. L'utilisateur clique sur le bouton de vérification du formulaire</br>|
|**Scénario alternatif**||
|**Scénario Exceptionnel**||
|**Pré-condition**||

|Cas d'utilisation :| Exécuter les tâches reçues du serveur web |
|--------------------|-----------------------|
|**Description**| Le cluster de Raspberry Pi reçoit des instructions de l'utilisateur transmises par le serveur web |
|**Portée**| Sous-partie 🔩 |
|**Niveau**| Sous-fonction 🌊 |
|**Acteur Principale**| Cluster de Raspberry Pi |
|**Scénario Nominal**|1. Le cluster recoit les instructions sur le calcul à effectuer</br>2. Le clsuter effectue le calcul </br>3. Le cluster renvoit les informations au serveur web</br>|
|**Scénario alternatif**||
|**Scénario Exceptionnel**||
|**Pré-condition**|Le cluster est connecté au réseau|

|Cas d'utilisation :| 	Répartir le calcul sur les nœuds du cluster|
|--------------------|-----------------------|
|**Description**| Le cluster de Raspberry Pi reçoit des instructions de calcul depuis le serveur web et répartit la charge entre ses nœuds. |
|**Portée**| Sous-partie 🔩 |
|**Niveau**| Sous-fonction 🌊 |
|**Acteur Principale**| Cluster de Raspberry Pi |
|**Scénario Nominal**|1. Le serveur web transmet les instructions de calcul au cluster.</br>2. Le cluster reçoit la requête et analyse la tâche. </br>3. Le cluster répartit le calcul entre les différents nœuds</br>|
|**Scénario alternatif**||
|**Scénario Exceptionnel**||
|**Pré-condition**|Le cluster est connecté au réseau|

|Cas d'utilisation :| Communiquer les résultats au serveur maître |
|--------------------|-----------------------|
|**Description**| Les Raspberry Pi esclaves renvoient le résultat au Raspberry Pi maître |
|**Portée**| Sous-partie 🔩 |
|**Niveau**| Sous-fonction 🌊 |
|**Acteur Principale**| Cluster de Raspberry Pi |
|**Scénario Nominal**|1. Les noeuds esclaves renvoient le résultat vers le noeud maître</br>|
|**Scénario alternatif**||
|**Scénario Exceptionnel**||
|**Pré-condition**|Le cluster est connecté au réseau|

|Cas d'utilisation :| Transmettre les résultats au serveur web |
|--------------------|-----------------------|
|**Description**| Transmettre les résultats du calcul au serveur web |
|**Portée**| Sous-partie 🔩 |
|**Niveau**| Sous-fonction 🌊 |
|**Acteur Principale**| Cluster de Raspberry Pi |
|**Scénario Nominal**|1. Les résultats sont transmis au serveur web|
|**Scénario alternatif**||
|**Scénario Exceptionnel**||
|**Pré-condition**|Le cluster est connecté au réseau|

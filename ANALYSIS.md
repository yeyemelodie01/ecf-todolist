# Audit de testabilité

## Classification des fichiers

| Fichier/Classe             | Type de test recommandé | Outils utilisables       | Justification                                                                                                                                                                                                              | Priorité (1-3) |
|----------------------------|-------------------------|--------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------|
| TaskService.php            | Unitaire                | PHPUnit (TestCase)       | Ce service contient la logique métier principale. Il interagit avec l’EntityManager et le repository. Il est donc essentiel de vérifier qu’il fonctionne comme prévu, tout en restant facile à tester grâce au mocking.    | 1              |
| TaskRepository.php         | Intégration             | PHPUnit (KernelTestCase) | Ce repository contient une méthode personnalisée pour filtrer les tâches par utilisateur. Un test d'intégration est adapté pour vérifier le bon fonctionnement de la requête avec la base de données.                      | 3              |
| TaskController.php         | Intégration             | PHPUnit (WebTestCase)    | Ce contrôleur gère les actions principales liées aux tâches (ajout, édition, suppression). Il fait appel à plusieurs services. Un test d’intégration permet de s'assurer que toutes les couches communiquent correctement. | 2              |
| TaskVoter.php              | Unitaire                | PHPUnit (TestCase)       | Cette classe contient la logique d'autorisation d’accès. Elle est simple à tester avec des mocks pour simuler différents rôles utilisateurs.                                                                               | 2              |
| Task.php                   | Unitaire                | PHPUnit (TestCase)       | Il s'agit d'une entité simple. Tester ses getters/setters permet de s’assurer du bon fonctionnement de la structure des données.                                                                                           | 1              |
| User.php                   | Unitaire                | PHPUnit (TestCase)       | Comme l’entité Task, cette classe est facile à tester. Elle implémente UserInterface, il est donc pertinent de valider son comportement.                                                                                   | 1              |
| RegistrationController.php | Intégration             | PHPUnit (KernelTestCase) | Ce contrôleur gère l'inscription d'un utilisateur. Il combine formulaire, sécurité, hash de mot de passe, et base de données. Un test d’intégration permet de vérifier tout le processus.                                  | 3              |
| LoginFormAuthenticator.php | Unitaire                | PHPUnit (TestCase)       | Cette classe implémente le processus de connexion utilisateur. Elle contient plusieurs dépendances Symfony. Il est pertinent de tester le comportement des méthodes critiques avec des mocks.                              | 3              |

## Stratégie de test adoptée

### Priorité 1 (Tests immédiats)
- Justifiez pourquoi ces fichiers sont prioritaires
- Expliquez leur impact sur l'application

### Priorité 2 (Tests importants)
- Fichiers avec dépendances modérées
- Nécessitent des mocks/stubs

### Priorité 3 (Tests complexes)
- Tests d'intégration
- Workflows complets

## Difficultés identifiées
Pendant l’analyse du projet, plusieurs difficultés techniques ont été identifiées concernant la mise en place des tests :
- Absence de certains getters et setters dans les entités : Certaines propriétés des entités, comme User, ne disposaient pas de tous les accesseurs nécessaires, ce qui a bloqué certains tests (ex. : getEmail() ou getPassword() manquants). Il a fallu compléter les entités manuellement pour permettre la lecture ou la modification des données pendant les tests.
- Configuration de l’environnement de test : L’activation d’une base de données dédiée pour les tests (.env.test) et la gestion du schéma (doctrine:schema:update) ont été essentielles, mais non intuitives au départ. J’ai mis en place par la suite une base de données pour l’environnement de test.
- Tests d’intégration avec Doctrine : Lors des tests du TaskRepository, la contrainte de relations entre entités a généré des erreurs SQL lors de la suppression des données. Il a fallu adapter la stratégie de nettoyage pour préserver l’intégrité référentielle.
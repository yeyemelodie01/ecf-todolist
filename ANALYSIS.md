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

- Certaines classes, comme TaskService RegistrationController, dépendent de services du framework Symfony comme EntityManagerInterface. Il a donc fallu utiliser des mocks natifs pour isoler ces dépendances et garantir que les tests restent unitaires.
- Pour les tests d’intégration, notamment ceux des repositories et contrôleurs, il a été nécessaire de prévoir un environnement de test distinct, avec une configuration adaptée (.env.test) et une base de données dédiée. Cela demande un peu plus de configuration avant d’exécuter les tests.

Solutions apportées :
- J’ai décidé de m’appuyer uniquement sur les mocks fournis par PHPUnit.
- J’ai défini une priorité pour chaque fichier afin de concentrer mes efforts sur les classes à fort impact fonctionnel.

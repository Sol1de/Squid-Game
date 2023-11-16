<?php

//Classe Utils (abstraite)
//  Propriétés : Aucune propriété spécifique, mais des méthodes abstraites.
//  Méthodes :
//      randomizeChoice(): Méthode abstraite pour effectuer des choix aléatoires.
//      cheatOrNot(): Méthode abstraite pour gérer la possibilité de tricher lors de l'affrontement avec un joueur de plus de 70 ans.

//Classe Game
//  Propriétés :
//      difficultyLevels (private array) : Contient le nombre de niveaux pour chaque difficulté.
//      players (private array) : Les joueurs disponibles à affronter.
//      chosenLevel (private int) : Niveau de difficulté choisi.
//      playerCharacter (private Character) : Personnage choisi par le joueur.
//      currentMarbles (private int) : Nombre actuel de billes du joueur.
//      victoryThreshold (private int) : Nombre minimum de billes pour gagner.
//      wonGame (private bool) : Indique si le jeu a été remporté.
//  Méthodes :
//      startGame(): Initialise le jeu en sélectionnant le niveau de difficulté et le personnage.
//      encounterOpponent(): Choix aléatoire de l'adversaire.
//      guessEvenOrOdd(): Devine si le nombre de billes de l'adversaire est pair ou impair.
//      handleEncounterResult(): Gère le résultat de l'affrontement.
//      play(): Boucle principale pour jouer aux rencontres jusqu'à la fin du jeu.
//      displayEndGameMessage(): Affiche le message de fin de jeu en fonction du résultat.
//      eliminateOpponent(): Élimine l'adversaire du jeu.

//Classe Character
//  Propriétés :
//      name (protected string) : Nom du personnage.
//      marbles (protected int) : Nombre de billes.
//      loss (protected int) : Malus en cas de défaite.
//      gain (protected int) : Bonus en cas de victoire.
//      screamWar (protected string) : Cri de guerre en cas de victoire.
//  Méthodes :
//      getName(): Renvoie le nom du personnage.
//      getMarbles(): Renvoie le nombre de billes actuel.
//      getLoss(): Renvoie le malus en cas de défaite.
//      getGain(): Renvoie le bonus en cas de victoire.
//      getScreamWar(): Renvoie le cri de guerre.

//Classe Hero (extends Character)
//  Méthodes (héritées de Character) : Aucune méthode supplémentaire.

//Classe Enemy (extends Character)
//  Propriétés :
//      age (private int) : L'âge du joueur ennemi.

?>

<?php 

    // classe utils 
    abstract class Utils {
        abstract public function randomizeChoice();
        abstract public function cheatOrNot();
    }



    // classe Game 
    class Game extends Utils {
        private $difficultyLevels;
        private $players;
        private $chosenLevel;
        private $playerCharacter;
        private $currentMarbles;
        private $victoryThreshold;
        private $wonGame;

        // constructeur 
        public function __construct($difficultyLevels, $players, $chosenLevel, $playerCharacter, $currentMarbles, $victoryThreshold, $wonGame) {
            $this->difficultyLevels = $difficultyLevels;
            $this->players = $players;
            $this->chosenLevel = $chosenLevel;
            $this->playerCharacter = $playerCharacter;
            $this->currentMarbles = $currentMarbles;
            $this->victoryThreshold = $victoryThreshold;
            $this->wonGame = $wonGame;
        }

        // fonctions

        public function startGame() {
            
            echo "Bienvenue dans le jeu du Squid Game ! Le jeu va commencer... <br>";

             // Choix aléatoire du niveau de difficulté
            $this->chosenLevel = $this->difficultyLevels[array_rand($this->difficultyLevels)]; 

            echo "Niveau de difficulté choisi : $this->chosenLevel <br>";

            // Sélection aléatoire d'un personnage pour le joueur
            $availableCharacters = [
            new Hero("Seong Gi-hun", 15, 2, 1, "Seong Gi-hun lance son cri de victoire !"),
            new Hero("Kang Sae-byeok", 25, 1, 2, "Kang Sae-byeok lance son cri de victoire !"),
            new Hero("Cho Sang-woo", 35, 0, 3, "Cho Sang-woo lance son cri de victoire !"),
            ];

            $this->playerCharacter = $availableCharacters[array_rand($availableCharacters)];

            echo "Personnage choisi : " . $this->playerCharacter->getName() . " <br>";

            // Initialisation des billes du joueur avec le personnage choisi
            $this->currentMarbles = $this->playerCharacter->getMarbles(); 

            // Calcul du seuil de victoire (garder au moins une bille)
            $this->victoryThreshold = 1;

            // Début du jeu
            echo "Lancement de la partie...  <br>";
            $this->play();
        }

        public function encounterOpponent() {

            // Sélection aléatoire d'un personnage pour l'ennemi
            $opponent = $this->players[array_rand($this->players)];

            echo "Adversaire choisi : " . $opponent->getName() . " <br>";
            echo "Vous avez actuellement " . $this->currentMarbles . " billes. <br>";
            return $opponent;
        }  

        public function guessEvenOrOdd($opponent) {

            // Choix aléatoire pair (0) ou impair (1)
            $playerGuess = $this->randomizeChoice();

            // Affichage du choix aléatoire du joueur
            if ($playerGuess === 0) {
                echo "Vous pariez sur pair. <br>";
            } else {
                echo "Vous pariez sur impair. <br>";
            }

            // Vérification si le choix du joueur correspond au nombre de billes de l'adversaire
            $opponentMarbles = $opponent->getMarbles();
            $isEven = $opponentMarbles % 2 === 0;

            // Résultat de la rencontre
            if (($isEven && $playerGuess === 0) || (!$isEven && $playerGuess === 1)) {
                echo "Bonne réponse ! Un bonus vous sera attribué. <br>";
                return true; 
            } else {
                if ($isEven) {
                    echo "Mauvaise réponse ! Le nombre de billes était pair. <br>";
                } else {
                    echo "Mauvaise réponse ! Le nombre de billes était impair. <br>";
                }
                return false; 
            }
        }

        public function handleEncounterResult($playerGuess, $opponent) {
            // Devine si le nombre de billes de l'adversaire est pair ou impair
            $correctGuess = $this->guessEvenOrOdd($opponent);

            // Récupération des valeurs de gain et de perte du joueur et de l'adversaire
            $playerGain = $this->playerCharacter->getGain();
            $playerLoss = $this->playerCharacter->getLoss();
            $opponentMarbles = $opponent->getMarbles();

            // Gestion du résultat en fonction de la devinette
            if ($correctGuess) {
                // Si le joueur a deviné correctement le joueur gagne les billes de l'adversaire plus son bonus
                $this->currentMarbles += $opponentMarbles + $playerGain; 
                echo "Vous remportez " . ($opponentMarbles + $playerGain) . " billes ! <br>";
            } else {
                // Si le joueur a deviné incorrectement le joueur perd les billes de l'adversaire moins son malus
                $this->currentMarbles -= $opponentMarbles - $playerLoss; 
                echo "Vous perdez " . ($opponentMarbles - $playerLoss) . " billes... <br>";
            }

            // Affichage du nombre de billes restantes pour le joueur
            echo "Il vous reste " . $this->currentMarbles . " billes. <br>";

            // Élimination de l'adversaire du jeu
            $this->eliminateOpponent($opponent);
        }

        public function play() {
            // Nombre d'opposants restants
            $opponentsLeft = count($this->players); 

            // tant que le joueur a plus de billes que le seuil de victoire et qu'il reste des adversaires 
            // on lui fait rencontrer un adversaire et choisir pair ou impair
            while ($this->currentMarbles >= $this->victoryThreshold && $opponentsLeft > 0) {
                echo "<br>";
                echo "Il reste " . $opponentsLeft . " adversaires. <br>";
                $opponent = $this->encounterOpponent();
                $playerGuess = $this->randomizeChoice(); 

                // On gère le résultat de l'affrontement
                $this->handleEncounterResult($playerGuess, $opponent); 

                // On réduit le nombre d'opposants restants
                $opponentsLeft--; 
            }

            // Vérification de la fin du jeu
            if ($this->currentMarbles >= $this->victoryThreshold) {
                $this->wonGame = true;
                $this->displayEndGameMessage(true); 
            } else {
                $this->wonGame = false;
                $this->displayEndGameMessage(false); 
            }
        }

        public function displayEndGameMessage($isVictory) {
            // Affichage du message de fin de jeu en fonction du résultat
            if ($isVictory) {
                echo "Félicitations ! Vous avez survécu au jeu Squid Game et avez gagné 45,6 milliards de Won ! <br>";
            } else {
                echo "Dommage ! Vous avez perdu le jeu Squid Game. Mieux vaut devenir un maître du jeu la prochaine fois ! <br>";
            }
        }

        public function eliminateOpponent($opponent) {
            // On parcour l'array à la recherche de l'adversaire
            foreach ($this->players as $key => $player) {
                if ($player === $opponent) {
                    unset($this->players[$key]);
                    // On sort de la boucle une fois que l'adversaire est éliminé
                    break; 
                }
            }
        }

        public function randomizeChoice() {
            return rand(0, 1);
        }

        public function cheatOrNot() {
            $choice = $this->randomizeChoice(); 
            return $choice === 1;
        }
    }

    // classe character
    class Character {
        protected string $name;
        protected int $marbles;
        protected int $loss;
        protected int $gain;
        protected string $screamWar;

        // constructeur 
        public function __construct($name, $marbles, $loss, $gain, $screamWar) {
            $this->name = $name;
            $this->marbles = $marbles;
            $this->loss = $loss;
            $this->gain = $gain;
            $this->screamWar = $screamWar;
        }

        //getter/setter
        public function getName() {
            return $this->name;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function getMarbles() {
            return $this->marbles;
        }

        public function setMarbles($marbles) {
            $this->marbles = $marbles;
        }

        public function getLoss() {
            return $this->loss;
        }

        public function setLoss($loss) {
            $this->loss = $loss;
        }

        public function getGain() {
            return $this->gain;
        }

        public function setGain($gain) {
            $this->gain = $gain;
        }

        public function getScreamWar() {
            return $this->screamWar;
        }

        public function setScreamWar($screamWar) {
            $this->screamWar = $screamWar;
        }
    }

    // classe hero
    class Hero extends Character {
        // constructeur 
        public function __construct($name, $marbles, $loss, $gain, $screamWar) {
            parent::__construct($name, $marbles, $loss, $gain, $screamWar);
        }
    }

    // classe enemy
    class Enemy extends Character {
        private int $age;

        // constructeur 
        public function __construct($name, $marbles, $loss, $gain, $screamWar, $age) {
            parent::__construct($name, $marbles, $loss, $gain, $screamWar);
            $this->age = $age;
        }

        // getter/setter
        public function getAge() {
            return $this->age;
        }

        public function setAge($age) {
            $this->age = $age;
        }
    }

    // Instanciation des adversaires
    $opponents = [
        new Enemy("Enemy-1", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-2", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-3", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-4", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-5", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-6", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-7", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-8", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-9", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-10", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-11", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-12", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-13", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-14", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-15", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-16", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-17", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-18", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-19", rand(1, 20), 0, 0, "", rand(10, 80)),
        new Enemy("Enemy-20", rand(1, 20), 0, 0, "", rand(10, 80)),
    ];

    // Instanciation de la classe Game
    $game = new Game([5, 10, 20], $opponents, 0, null, 0, 0, false);
    $game->startGame();
?>
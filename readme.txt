Date de développement : 2008 / maj 2023
Nicolas Lion : nlion49@gmail.com / https://www.nlion.fr
Compatible PHP 4-5 (phpsudo-4-5.zip) / 7-8 (phpsudo_pourPHP7-8.zip) / wp-phpsudo.zip (pluggin pour wordpress)

licensed under the Creative Commons - Attribution - Non-Commercial license.

Jouer en ligne : https://sudoku.nlion.fr/

Script PHP Sudoku gratuit
Il y a quelques années, j’ai développé cette application, parfaitement opérationnelle sur les versions PHP 4 et 5. Dernièrement, j’ai adapté un peu le code pour que le script fonctionne sur php 7 et 8.
L’application permet de générer des grilles de tout type, garantissant toujours une solution unique. L’application propose des grilles de divers formats, allant de 4×4 à 16×16, offrant la possibilité d’utiliser des lettres, des chiffres ou même un mélange des deux.
L’application intègre une correction en temps réel et inclut un chronomètre pour vous permettre de tenter de battre votre meilleur score.

Cette version ne repose pas sur une base de données. Les grilles sont générées à chaque utilisation. Il serait bénéfique d’envisager le stockage des grilles pour améliorer considérablement la performance de l’application. 
La grille utilise un “template”, vous pouvez donc très facilement changer les couleurs, la police, la disposition du jeu.
Le fichier index.php vous présente un exemple d’utilisation. Le script est paramétrable.

<head>
<script src="js/jquery-3.7.1.min.js" type="text/javascript"></script>
<script src="js/phpsudo.js.php" type="text/javascript"></script>

...
</head>
<body>
<div id="grille"></div>

<script type="text/javascript">
$(function() {
    $('#grille').PhpSudo({
        'TailleCasePX': '35',
        'Dimension': '9*3',
        'AfficheSelectionGrilles': true,
        'AfficherLaGrille': true,
        'TailleTexte': '22',
        'GenererNouvelleGrille': true
    });
});
</script>

...
</body>
TailleCasePX : dimension d’une case en pixel / Dimension of a cell in pixels
Dimension : vous pouvez afficher par défaut un type de grille (9*3, 6*2, …) / You can default to display a grid type (9x3, 6x2, ...)
AfficheSelectionGrilles : affiche les liste déroulantes pour le choix de la grille (true ou false) / Display dropdown lists for grid selection (true or false)
AfficherLaGrille : On affiche directement la grille au démarrage à l’affichage de la page / Display the grid directly upon page load
TailleTexte : dimension du texte dans les cases / Dimension of text within cells

La grille utilise un “template”, vous pouvez donc très facilement changer les couleurs, la police, la disposition du jeu. /

Présentation technique de la classe SuDoKu en PHP :
:Classe calculGrillePleine
Cette classe contient les méthodes de base pour la génération et la résolution des grilles de Sudoku.
Elle possède des propriétés telles que $SudoVide (une grille vide), $tirage (les chiffres à utiliser), $sudo (la grille en cours de résolution), $max et $min (les dimensions de la grille), $NbCasesHZone et $NbCasesVZone (le nombre de cases horizontales et verticales dans une zone), $TimeLimitCalcul (le temps limite de calcul), $TimeOut (un indicateur de dépassement de temps) et $fin (un indicateur de fin de résolution).
Les méthodes principales sont :
zone() : retourne les valeurs d’une zone de la grille
colonne() : retourne les valeurs d’une colonne de la grille
Grille() : résout la grille de manière récursive en essayant toutes les valeurs possibles dans les cases vides
Classe SuDoKu

Cette classe hérite de calculGrillePleine et ajoute des fonctionnalités supplémentaires.
Propriétés :
$level, $LevelHard, $TabValeursPossible, $YTabValeursPossible, $XTabValeursPossible, $LimitNiveau, $GrillePleine, $IncompleteGrille, $TestGrilleSudoku, $ValidIncompleteGrille, $CasesVidesCoordonneesX, $CasesVidesCoordonneesY, $WithSymbol, $t0
Méthodes :
__construct() : constructeur de la classe, initialise les propriétés
init() : initialise une nouvelle grille de Sudoku
GetGrille() : génère une grille de Sudoku complète
NiveauDifficulte() : détermine le niveau de difficulté de la grille
create_grille_sudoku() : crée une grille de Sudoku incomplète en fonction du niveau de difficulté
replace_by_symbol() : remplace les chiffres par des symboles (lettres) dans l’affichage
lineariser_grilles() : convertit la grille en une chaîne de caractères pour la stocker en base de données
drawing() : affiche la grille de Sudoku avec un système de mise en forme CSS
Correction() : vérifie et corrige la grille remplie
UnsetInconnus() : supprime les coordonnées d’une case vide de la liste des cases à remplir
ValeursInterdites() : retourne les valeurs interdites dans une case en fonction de la ligne, de la colonne et de la zone
DeductionParValeurInterdites() : retourne les valeurs possibles dans une case en fonction des valeurs interdites
DeductionSolitaireNu() : résout la grille en utilisant la méthode de déduction “Solitaire Nu”
SearchPairesNues() et SearchPairesNuesCache() : résout la grille en utilisant la méthode de déduction “Paires Nues”
SelectValues() : sélectionne les valeurs possibles dans une ligne, une colonne ou une zone
CalculSolutionDeduction() : résout la grille en utilisant les méthodes de déduction
Debug() : vérifie la validité de la grille remplie par l’utilisateur
Cette classe offre une implémentation complète de la génération et de la résolution de grilles de Sudoku, avec différents niveaux de difficulté et des méthodes de résolution avancées. Elle peut être utilisée comme base pour développer des applications de Sudoku plus complexes.

Méthodes de résolutions :
Les méthodes de résolution basées sur des raisonnements humains qui sont présentes :

Méthode de la “Déduction par Valeurs Interdites”
Implémentée dans la méthode ValeursInterdites() et DeductionParValeurInterdites().
Objectif : Identifier les valeurs interdites dans une case en fonction de la ligne, de la colonne et de la zone, puis en déduire les valeurs possibles.
Méthode de la “Paire Nue”
Implémentée dans les méthodes SearchPairesNues() et SearchPairesNuesCache().
Objectif : Identifier les paires de valeurs qui ne peuvent apparaître que dans deux cases d’une ligne, d’une colonne ou d’une zone, et en déduire les valeurs à placer.
Méthode du “Solitaire Nu”
Implémentée dans la méthode DeductionSolitaireNu().
Objectif : Identifier les valeurs uniques dans une ligne, une colonne ou une zone, et les placer dans la grille.
La classe SuDoKu implémente déjà trois méthodes de résolution basées sur des raisonnements humains :

Ces méthodes permettent de résoudre une partie des grilles de Sudoku en utilisant des techniques similaires à celles employées par les joueurs humains. Cependant, il serait possible d’ajouter d’autres méthodes de résolution humaine, comme la recherche de paires cachées, de chaînes cachées, de poissons, de poissons cachés ou encore la méthode de la supposition.

Voici quelques limites et points à améliorer dans le code de la classe SuDoKu :
Performances et temps d’exécution:
Bien que le code gère le temps d’exécution pour éviter les timeouts, il pourrait être optimisé davantage pour améliorer les performances, notamment pour les grilles de grande taille.
L’utilisation de techniques comme le multithreading ou la parallélisation pourrait être envisagée pour accélérer la résolution des grilles.
Lisibilité et maintenabilité du code:
Certaines parties du code sont un peu complexes et difficiles à comprendre, notamment les méthodes de résolution par déduction.
Une refactorisation du code, avec une meilleure séparation des responsabilités et une documentation plus détaillée, pourrait améliorer la lisibilité et la maintenabilité.
La classe SuDoKu offre une implémentation de base pour la génération et la résolution de grilles de Sudoku, il existe plusieurs opportunités d’amélioration.

Revoir un peu le code l'adpater aux éxigences de la programmation sur php 8, la programmation d'origine date de 2008. 


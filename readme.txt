Date de développement : 2008 / maj 2023
Nicolas Lion : nlion49@gmail.com / https://www.nlion.fr
Compatible PHP 4-5 (phpsudo-4-5.zip) / 7-8 (phpsudo_pourPHP7-8.zip)

licensed under the Creative Commons - Attribution - Non-Commercial license.

Play Online : https://sudoku.nlion.fr/

-----------------

Sudoku Game Developed in PHP
A few years ago, I developed this application, fully operational on PHP versions 4 and 5. Recently, I made some adjustments to the code to ensure that the script works on PHP 7 and 8.

The application features real-time correction and includes a timer to allow you to try to beat your best score.

This version does not rely on a database. The grids are generated each time the application is used. It would be beneficial to consider storing the grids to significantly improve the application's performance. Generating a grid can sometimes take a significant amount of time, ranging from 5 seconds to 1 minute, depending on the selected size and difficulty.

The application generates grids of various types, always ensuring a unique solution. It offers grids of different formats, ranging from 4×4 to 16×16, allowing the use of letters, numbers, or a combination of both. The application is coded in PHP, JavaScript, and CSS.
The grid utilizes a template, so you can easily change colors, fonts, and game layout.
The index.php file presents an example of usage. The script is customizable.

-----------------

Script PHP Sudoku gratuit
Il y a quelques années, j’ai développé cette application, parfaitement opérationnelle sur les versions PHP 4 et 5. Dernièrement, j’ai adapté un peu le code pour que le script fonctionne sur php 7 et 8.
L’application permet de générer des grilles de tout type, garantissant toujours une solution unique. L’application propose des grilles de divers formats, allant de 4×4 à 16×16, offrant la possibilité d’utiliser des lettres, des chiffres ou même un mélange des deux.
L’application intègre une correction en temps réel et inclut un chronomètre pour vous permettre de tenter de battre votre meilleur score.

Cette version ne repose pas sur une base de données. Les grilles sont générées à chaque utilisation. Il serait bénéfique d’envisager le stockage des grilles pour améliorer considérablement la performance de l’application. Le calcul d’une grille peut parfois demander un temps significatif, allant de 5 secondes à 1 minute, en fonction de la taille et de la difficulté sélectionnées
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


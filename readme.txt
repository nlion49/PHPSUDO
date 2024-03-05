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


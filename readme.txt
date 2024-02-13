Date de développement : 2008 / maj 2023
Nicolas Lion : nlion49@gmail.com / https://www.nlion.fr
Compatible PHP 4-5 (phpsudo-4-5.zip) / 7-8 (phpsudo_pourPHP7-8.zip)

licensed under the Creative Commons - Attribution - Non-Commercial license.

JEU de Sudoku gratuit

L’application permet de générer des grilles de tout type, garantissant toujours une solution unique. L’application propose des grilles de divers formats, allant de 4×4 à 16×16, offrant la possibilité d’utiliser des lettres, des chiffres ou même un mélange des deux. L'application est codé en php, javascript et css. / The application generates grids of various types, always guaranteeing a unique solution. It offers grids in various formats, ranging from 4×4 to 16×16, allowing the use of letters, numbers, or even a mix of both. The application is coded in PHP, JavaScript, and CSS.

Le fichier index.php vous présente un exemple d’utilisation. Le script est paramétrable. /
The file index.php presents an example of usage. The script is configurable.

Intégration /Integration :

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

La grille utilise un “template”, vous pouvez donc très facilement changer les couleurs, la police, la disposition du jeu. / The grid utilizes a template, so you can easily change colors, fonts, and game layout.


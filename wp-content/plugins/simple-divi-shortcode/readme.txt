=== Simple Divi Shortcode ===
Contributors: creaweb2b
Tags: Divi, Divi theme, Divi Modules, Divi Library, Divi Sections, Divi Builder, Elegant Themes, Shortcode
Donate link: https://www.creaweb2b.com
Requires at least: 4.0
Tested up to: 5.0
Requires PHP: 5.6 or higher (tested up to 7.2.1)
Stable tag: trunk.
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to use a shortcode to insert a section, a module or a layout from the DIVI Library inside another module content or inside a template using a shortcode 
`[showmodule id="xxx"]` where xxx is the ID of the section, module or layout inside the DIVI Library. (Read description to learn how to find out this ID)

Cet outil vous donne la possibilité d'utiliser un shortcode pour insérer une section, un module ou un modèle depuis la bibliothèque Divi au sein d'un autre module ou au sein d'un template php. Votre shortcode utilisera la syntaxe suivante : `[showmodule id="xxx"]` où xxx sera remplacé par l'ID de votre section, module ou modèle existant dans la bibliothèque. (Lisez la description longue pour savoir où trouver cet identifiant)

== Description ==
Using this tool you will be able to embed any Divi Library item inside another module content or inside a php template by using a simple shortcode.

Avec cet outil, vous pourrez insérer n'importe quel élément de la bibliothèque Divi au sein d'un autre module ou au sein d'un template php par le biais d'un simple shortcode.

You just need to build a layout, section or module inside the Divi library.

Vous devez construire un modèle, une section ou un module au sein de la Bibliothèque DIVI.

The item ID can be found by navigating to the layout editor and looking at the URL. For example, let's have a look at the following URL : 
https://mywebsite.com/wp-admin/post.php?post=866&action=edit. 
Here the item ID is : 866.

Vous trouverez l'ID de votre élément à insérer en regardant le contenu de l'URL pendant l'édition. Par exemple si nous avons l'URL :
https://mywebsite.com/wp-admin/post.php?post=866&action=edit. 
Alors l'ID de votre élément est : 866.

The ID is also available by hovering over the word Edit in the layout page : ID is shown on the link displayed at the bottom of the screen.

Vous pouvez aussi déterminer l'ID en survolant le mot "modifier" de votre élément dans la bibliothèque Divi : l'ID est visible dans le lien affiché en bas de page.

Once you get the item ID, just call it by using a shortcode `[showmodule id="866"]`

Une fois que vous aurez l'ID, appelez votre élément en utilisant un shortcode `[showmodule id="866"]`

I made a tutorial explaining how to use it : "How to add a DIVI section or module inside another module" available at the following URL :
https://www.creaweb2b.com/en/how-to-add-a-divi-section-or-module-inside-another-module/

Le tutoriel disponible à l'URL ci dessous explique comment utiliser ce shortcode :
https://www.creaweb2b.com/ajouter-module-section-divi-module/

A premium version of the plugin, offering some more functionnality is available for purchase at the following URL :
https://www.creaweb2b.com/en/plugins/simple-divi-shortcode-en/

Une version améliorée, qui offre des fonctionnalités supplémentaires est disponible à l'achat en suivant l'URL :
https://www.creaweb2b.com/plugins/simple-divi-shortcode-fr/

== Installation ==
Install the plugin like any other plugin, directly from your plugins page but looking it up in the WordPress.org repository, or manually upload it to your server to the /wp-content/plugins/ folder.

Le plugin s'installe comme n'importe quel autre plugin, directement depuis la page d'ajout des extensions, ou manuellement en le téléchargeant sur votre serveur dans le répertoire /wp-content/plugins/

Activate the plugin using the "activate" button at the end of install process, or through the "Plugins" menu in WordPress.

Activez le plugin depuis le bouton "activer" disponible en fin d'installation ou depuis le menu "extensions" de wordpress.

== Changelog ==
- 0.9 - Initial release. - Version initiale
- 1.0 - Updated deprecated extract attribute method - Modification de la méthode d'extraction des attributs (l'ancienne étant dépréciée)
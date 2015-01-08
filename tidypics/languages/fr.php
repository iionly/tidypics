<?php

return array(
	'tidypics:disabled' => 'Désactivé',
	'tidypics:enabled' => 'Activé',
	'admin:settings:photos' => 'Tidypics',
	'photos:add' => 'Créer un album',
	'photos:group' => 'Photos du groupe',
	'tidypics:nophotosingroup' => 'Ce groupe n\'a aucune photo pour le moment',
	'tidypics:upgrade' => 'Mettre à jour',
	'tidypics:sort' => 'Tri de l\'album %s',
	'tidypics:none' => 'Aucun album photo',
	'tidypics:settings:main' => 'Paramètres primaires',
	'tidypics:settings:help' => 'Aide',
	'tidypics:settings:uploader' => 'Utiliser l\'outil de chargement Flash',
	'tidypics:settings:tinysize' => 'Taille des miniatures',
	'tidypics:settings:sizes:instructs' => 'Il est possible que vous ayez à modifier les CSS si vous modifiez les dimensions par défaut',
	'tidypics:settings:heading:img_lib' => 'Paramètres de la bibliothèque d\'image',
	'tidypics:settings:heading:main' => 'Paramètres principaux',
	'tidypics:settings:heading:river' => 'Options d\'intégration dans la rivière d\'activité',
	'tidypics:settings:heading:sizes' => 'Taille des miniatures',
	'tidypics:settings:heading:groups' => 'Paramètres du groupe',
	'tidypics:option:all' => 'Tout',
	'tidypics:option:none' => 'Aucun',
	'tidypics:option:cover' => 'Couverture',
	'tidypics:option:set' => 'Jeu d\'images',
	'tidypics:server_info' => 'Informations du serveur',
	'tidypics:server_info:gd_desc' => 'Elgg a besoin que l\'extension GD soit chargée',
	'tidypics:server_info:exec_desc' => 'Nécessaire pour la ligne de commande d\'ImageMagick',
	'tidypics:server_info:memory_limit_desc' => 'Augmentez la valeur de memory_limit',
	'tidypics:server_info:peak_usage_desc' => 'Ceci est à peu près le minimum par page',
	'tidypics:server_info:upload_max_filesize_desc' => 'Taille maximale d\'une image chargée sur le serveur',
	'tidypics:server_info:post_max_size_desc' => 'Taille maximale d\'une requête POST = somme des tailles des images + formulaire HTML',
	'tidypics:server_info:max_input_time_desc' => 'Durée qu\'attend le script avant la fin de l\'envoi des fichiers',
	'tidypics:server_info:max_execution_time_desc' => 'Durée maximale d\'exécution d\'un script',
	'tidypics:server_info:use_only_cookies_desc' => 'Les sessions utilisant seulement des cookies peuvent affecter le fonctionnement de l\'outil d\'envoi en Flash',
	'tidypics:server_info:php_version' => 'Version de PHP',
	'tidypics:server_info:memory_limit' => 'Mémoire disponible pour PHP',
	'tidypics:server_info:peak_usage' => 'Mémoire utilisée pour charger cette page',
	'tidypics:server_info:upload_max_filesize' => 'Taille maximale des fichiers à envoyer sur le serveur',
	'tidypics:server_info:post_max_size' => 'Taille maximale des requêtes POST',
	'tidypics:server_info:max_input_time' => 'Durée maximale de saisie',
	'tidypics:server_info:max_execution_time' => 'Durée d\'exécution maximale',
	'tidypics:server_info:use_only_cookies' => 'Sessions utilisant seulement des cookies',
	'tidypics:server_config' => 'Configuration du serveur',
	'tidypics:server_configuration_doc' => 'Documentation de la configuration du serveur',
	'tidypics:lib_tools:testing' => 'Tidypics a besoin de connaître l\'emplacement des exécutables de ImageMagick si vous l\'avez choisie comme bibliothèque graphique. Votre hébergeur devrait pouvoir vous renseigner à ce sujet. Vous pouvez tester si l\'emplacement est correct ci-dessous. S\'il est bon, cela devrait afficher la version d\'ImageMagick installée sur votre serveur.',
	'tidypics:thumbnail_tool' => 'Création des miniatures',
	'tidypics:thumbnail_tool_blurb' => 'Cette page vous permet de créer des miniatures pour les images quand la création de miniatures a échoué durant l\'envoi des fichiers.
Vous pouvez rencontrer des problèmes avec la création de miniatures si votre bibliothèque graphique n\'est pas configurée correctement ou s\'il n\'y a pas suffisamment de mémoire disponible pour la bibliothèque GD pour charger et redimensionner une image. Si les membres du site rencontrent des problèmes avec la création de miniatures et que vous avez modifié votre configuration, vous pouvez essayer de recréer les miniatures. Cherchez l\'identifiant unique de la photo (c\'est le nombre affiché près de la dernière partie de l\'URL quand vous êtes sur la page de la photo) et saisissez-le ci-dessous.',
	'tidypics:thumbnail_tool:unknown_image' => 'Impossible de récupérer l\'image d\'origine',
	'tidypics:thumbnail_tool:invalid_image_info' => 'Erreur lors de la récupération des informations de l\'image.',
	'tidypics:thumbnail_tool:create_failed' => 'Echec de la création des miniatures.',
	'tidypics:thumbnail_tool:created' => 'Miniatures créées.',
	'album:sort' => 'Trier',
	'album:cover_link' => 'Créer une couverture',
	'tidypics:title:quota' => 'Quota',
	'tidypics:uploader:choose' => 'Choisir des photos',
	'tidypics:uploader:upload' => 'Charger des photos',
	'tidypics:uploader:describe' => 'Décrire les photos',
	'tidypics:uploader:filedesc' => 'Fichiers image (jpeg, png, gif)',
	'tidypics:uploader:instructs' => 'Il y a 3 étapes simples pour ajouter des photos à votre album en utilisant cet outil d\'envoi : les choisir, les envoyer, et les décrire.
Il y a une limite de %s Mo maximum par photo. Si vous n\'utilisez pas Flash, un <a href="%s">autre outil d\'envoi simple</a> est également disponible.',
	'tidypics:uploader:basic' => 'Vous pouvez charger jusqu\'à 10 photos à la fois (%s Mo maximum par photo)',
	'tidypics:sort:instruct' => 'Triez l\'album photo en cliquant et en déplaçant les images. Puis cliquez sur le bouton Enregistrer.',
	'tidypics:sort:no_images' => 'Aucune image à trier. Chargez d\'abord des images en cliquant sur le lien ci-dessus.',
	'album:num' => 'Photos de %s',
	'image:index' => '%u de %u',
	'tidypics:phototagging:delete:success' => 'Le tag de la photo a été retiré.',
	'tidypics:phototagging:delete:error' => 'Erreur non non identifiée lors de la suppression du tag de la photo.',
	'tidypics:phototagging:delete:confirm' => 'Retirer ce tag ?',
	'river:create:object:image' => '%s a publié la photo %s',
	'image:river:created:multiple' => '%s a ajouté %u photos à l\'album %s',
	'image:river:tagged:unknown' => '%s a identifié %s dans une photo',
	'river:create:object:album' => '%s a créé un nouvel album photo %s',
	'river:comment:object:image' => '%s a commenté la photo %s',
	'river:comment:object:album' => '%s a commenté l\'album %s',
	'tidypics:newalbum_subject' => 'Nouvel album photo',
	'tidypics:updatealbum' => '%s a publié de nouvelles photos dans l\'album %s',
	'album:save_cover_image' => 'Image de couverture enregistrée.',
	'tidypics:album:sorted' => 'L\'album %s est trié',
	'tidypics:album:could_not_sort' => 'Impossible de trier l\'album %s. Veuillez vérifier qu\'il y a bien des images dans l\'album et réessayez.',
	'tidypics:baduploadform' => 'Une erreur s\'est produite avec le formulaire d\'envoi',
	'tidypics:deletefailed' => 'Désolé, la suppression a échoué.',
	'tidypics:deleted' => 'Suppression réussie.',
	'tidypics:cannot_upload_exceeds_quota' => 'Image non chargée. La taille du fichier dépasse la limite autorisée.',
	'album:invalid_album' => 'Album invalide',
	'album:cannot_save_cover_image' => 'Impossible d\'enregistrer l\'image de couverture',
	'image:blank' => 'Veuillez donner un titre à cette image.',
	'image:error' => 'Impossible d\'enregistrer l\'image.',
	'untitled' => 'Sans titre',
	'image' => 'Image',
	'images' => 'Images',
	'caption' => 'Légende',
	'photos' => 'Photos',
	'images:upload' => 'Charger des images',
	'album' => 'Album photo',
	'albums' => 'Albums photo',
	'album:slideshow' => 'Voir diaporama',
	'album:yours' => 'Vos albums photo',
	'album:yours:friends' => 'Les albums photo de vos amis',
	'album:user' => 'Albums photo de %s',
	'album:friends' => 'Albums photo des amis de %s',
	'album:all' => 'Tout les albums photo',
	'item:object:image' => 'Photos',
	'item:object:album' => 'Albums',
	'tidypics:uploading:images' => 'Veuillez patientez, nous chargeons les fichiers.',
	'tidypics:enablephotos' => 'Activer les albums photo pour les groupes',
	'tidypics:editprops' => 'Modifier les propriétés des images',
	'tidypics:mostcommented' => 'Images les plus commentées.',
	'tidypics:mostcommentedthismonth' => 'Les plus commentés ce mois-ci',
	'tidypics:mostcommentedtoday' => 'Les plus commentés ce jour-ci',
	'tidypics:mostviewed' => 'Images les plus vues',
	'tidypics:mostvieweddashboard' => 'Tableaux de bord les plus vus',
	'tidypics:mostviewedthisyear' => 'Les plus vus cette année.',
	'tidypics:mostviewedthismonth' => 'Les plus vus ce mois-ci',
	'tidypics:mostviewedlastmonth' => 'Les plus vus le mois dernier',
	'tidypics:mostviewedtoday' => 'Les plus vus aujourd\'hui',
	'tidypics:recentlyviewed' => 'Images récemment vues',
	'tidypics:recentlycommented' => 'Image récemment commentées',
	'tidypics:mostrecent' => 'Images les plus récentes',
	'tidypics:yourmostviewed' => 'Vos images les plus vues',
	'tidypics:yourmostrecent' => 'Vos images les plus récentes',
	'tidypics:friendmostviewed' => 'Les images les plus vues de %s',
	'tidypics:friendmostrecent' => 'Les images les plus récentes de %s',
	'tidypics:highestrated' => 'Les images les mieux notées',
	'tidypics:views' => 'Vues: %s',
	'tidypics:viewsbyowner' => 'par % membres (vous exclus)',
	'tidypics:viewsbyothers' => '(%s par vous)',
	'tidypics:administration' => 'Administration Tydipics',
	'tidypics:stats' => 'Stats',
	'tidypics:settings' => 'Paramétrages',
	'tidypics:settings:image_lib' => 'Librairie graphique',
	'tidypics:settings:thumbnail' => 'Création des vignettes',
	'tidypics:settings:download_link' => 'Voir le lien de download',
	'tidypics:settings:tagging' => 'Activer les tags sur les photos',
	'tidypics:settings:photo_ratings' => 'Activer les notations des photos (nécessite le rate plugin de Miguel Montes ou compatible)',
	'tidypics:settings:exif' => 'Voir les données EXIF',
	'tidypics:settings:view_count' => 'Voir le compteur',
	'tidypics:settings:grp_perm_override' => 'Autoriser l\'acés total aux membres du groupe',
	'tidypics:settings:maxfilesize' => 'Taille maximum des images	en Mb:',
	'tidypics:settings:quota' => 'Quota Utilisateur/Groupe (Mb) - O égal pas de quota',
	'tidypics:settings:watermark' => 'Entrez le texte qui doit figure sur le WaterMark - fonction non vraiment sure.',
	'tidypics:settings:im_path' => 'Chemin de l\'exécutable ImageMagick, terminé par un slash',
	'tidypics:settings:img_river_view' => 'Combien d\'entrées dans le river pour chaque lot de traitement des fichiers chargés',
	'tidypics:settings:album_river_view' => 'Montrer la couverture de l\'album ou un ensemble de photos pour tout nouvel album',
	'tidypics:settings:largesize' => 'Taille initiale de l\'image',
	'tidypics:settings:smallsize' => 'Taille de la vue de l\'album',
	'tidypics:settings:im_id' => 'Identifiant de l\'image',
	'album:create' => 'Créer un nouvel album',
	'album:add' => 'Ajouter un Album photo',
	'album:addpix' => 'Ajouter des photos à l\'album',
	'album:edit' => 'Modifier l\'album',
	'album:delete' => 'Supprimer l\'album',
	'image:edit' => 'Modifier l\'image',
	'image:delete' => 'Supprimer l\'image',
	'image:download' => 'Télécharger l\'image',
	'album:title' => 'Titre',
	'album:desc' => 'Description',
	'album:tags' => 'Tags',
	'album:cover' => 'Faire de cette image la couverture de l\'album',
	'tidypics:quota' => 'Quota utilisé:',
	'image:total' => 'Images dans l\'album:',
	'image:by' => 'Image ajoutée par',
	'album:by' => 'Album créé par',
	'album:created:on' => 'Création',
	'image:none' => 'Aucune image n\'a encore été ajoutée',
	'image:back' => 'Précédent',
	'image:next' => 'Suivant',
	'tidypics:taginstruct' => 'Sélectionnez la zone que vous souhaitez tagger',
	'tidypics:finish_tagging' => 'Arrêter de tagger',
	'tidypics:tagthisphoto' => 'Tagger cette photo',
	'tidypics:actiontag' => 'Tag',
	'tidypics:actioncancel' => 'Annuler',
	'tidypics:inthisphoto' => 'Dans cette photo',
	'tidypics:usertag' => 'Photo taggée par %s',
	'tidypics:phototagging:success' => 'La photo a été correctement taggée.',
	'tidypics:phototagging:error' => 'Erreur innatendue durant le taggage',
	'tidypics:tag:subject' => 'Vous avez été taggé dans une photo !!!',
	'tidypics:tag:body' => 'Vous avez été taggé dans la photo %s par %s !!!
La photo peut être consultée ici: %s',
	'tidypics:posted' => 'a posté une photo',
	'tidypics:widget:albums' => 'Albums photo',
	'tidypics:widget:album_descr' => 'Échantillon de vos albums photo',
	'tidypics:widget:num_albums' => 'Nombre de photos à montrer',
	'tidypics:widget:latest' => 'Dernières photos',
	'tidypics:widget:latest_descr' => 'Montrer les dernières photos',
	'tidypics:widget:num_latest' => 'Nombre d\'images à montrer',
	'album:more' => 'Voir tout les albums',
	'image:river:created' => '%s a ajouté la photo %s à l\'album %s',
	'image:river:item' => 'une photo',
	'image:river:annotate' => 'commentaire sur la photo',
	'image:river:tagged' => 'a été taggé sur la photo',
	'album:river:group' => 'dans le groupe',
	'album:river:item' => 'un album',
	'album:river:annotate' => 'un commentaire sur l\'album photo',
	'tidypics:newalbum' => 'Nouvel album photo',
	'tidypics:upl_success' => 'Vos images ont été correctement chargées.',
	'image:saved' => 'Votre image a été correctement enregistrée',
	'images:saved' => 'Toutes les images ont été correctement enregistrées',
	'image:deleted' => 'Votre image a correctement été supprimée',
	'image:delete:confirm' => 'Confirmez-vous la suppression de cette image',
	'images:edited' => 'Vos images ont été correctement mises à jour',
	'album:edited' => 'Votre album a correctement été mis à jour',
	'album:saved' => 'Votre album a correctement été enregistré',
	'album:deleted' => 'Votre album a correctement été supprimé',
	'album:delete:confirm' => 'Confirmez-vous la suppression de cet album',
	'album:created' => 'Votre nouvel album a été créé',
	'tidypics:settings:save:ok' => 'Réglages du plugin Tydipics enregistrés',
	'tidypics:upgrade:success' => 'Mise à jour de Tydipics effectuée',
	'tidypics:partialuploadfailure' => 'Des erreurs sont survenues durant le chargement des images (%s sur %s images)',
	'tidypics:completeuploadfailure' => 'Echec du chargement des images ',
	'tidypics:exceedpostlimit' => 'Trop d\'images trop lourdes - essayez de charges des images plus petites',
	'tidypics:noimages' => 'Aucune image sélectionnée',
	'tidypics:image_mem' => 'Image trop large - taille trop grosse',
	'tidypics:image_pixels' => 'L\'image a trop de pixels',
	'tidypics:unk_error' => 'Erreur inconnue de chargement',
	'tidypics:save_error' => 'Erreur inconnue lors de l\'enregistrement de l\'image sur le serveur',
	'tidypics:not_image' => 'Type d\'image non reconnu',
	'image:downloadfailed' => 'Désolé, image indisponible pour le moment',
	'tidypics:nosettings' => 'L\'administrateur n\'a pas effectué les reglages minimaux des albums',
	'tidypics:exceed_quota' => 'Quota fixé par l\'administrateur dépassé',
	'images:notedited' => 'Toutes les images n\'ont pas été correctement mises à jour',
	'album:none' => 'Aucun album encore créé',
	'album:uploadfailed' => 'Désolé, nous ne pouvons pas enregistrer l\'album',
	'album:deletefailed' => 'Votre album ne peut pas être supprimé pour le moment',
	'album:blank' => 'Donnez un titre et une description à cet album',
	'tidypics:upgrade:failed' => 'Mise à jour de Tydipics infructueuse',
);


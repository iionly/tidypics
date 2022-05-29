define(['jquery', 'elgg', 'elgg/Ajax', 'tidypics-jquery-ui/core', 'tidypics-jquery-ui/widget', 'tidypics-jquery-ui/widgets/button', 'tidypics-jquery-ui/widgets/progressbar', 'tidypics-jquery-ui/widgets/sortable', 'jquery.plupload-tp', 'jquery.plupload.ui-tp'], function($, elgg, Ajax) {
	var messages = require('elgg/system_messages');
	var security = require('elgg/security');
	var i18n = require('elgg/i18n');

	// manage Spinner manually
	var ajax = new Ajax(false);

	var pluploadLanguageArray = {"de" : {"Stop Upload":"Hochladen stoppen","Upload URL might be wrong or doesn't exist.":"Upload-URL ist falsch oder existiert nicht.","tb":"TB","Size":"Größe","Close":"Schließen","You must specify either browse_button or drop_element.":"","Init error.":"Initialisierungsfehler","Add files to the upload queue and click the start button.":"Dateien hinzufügen und auf 'Hochladen' klicken.","List":"","Filename":"Dateiname","%s specified, but cannot be found.":"","Image format either wrong or not supported.":"Bildformat falsch oder nicht unterstützt.","Status":"Status","HTTP Error.":"HTTP-Fehler","Start Upload":"Hochladen beginnen","Error: File too large:":"Fehler: Datei zu groß:","kb":"KB","Duplicate file error.":"Datei bereits hochgeladen","File size error.":"Fehler bei Dateigröße","N/A":"Nicht verfügbar","gb":"GB","Error: Invalid file extension:":"Fehler: Ungültige Dateiendung:","Select files":"Dateien auswählen","%s already present in the queue.":"%s ist bereits in der Warteschlange","Resoultion out of boundaries! <b>%s</b> runtime supports images only up to %wx%hpx.":"","File: %s":"Datei: %s","b":"B","Uploaded %d/%d files":"%d/%d Dateien wurden hochgeladen","Upload element accepts only %d file(s) at a time. Extra files were stripped.":"Pro Durchgang können nur %d Datei(en) akzeptiert werden. Überzählige Dateien wurden ignoriert.","%d files queued":"%d Dateien in der Warteschlange","File: %s, size: %d, max file size: %d":"Datei: %s, Größe: %d, maximale Dateigröße: %d","Thumbnails":"","Drag files here.":"Dateien hier hin ziehen.","Runtime ran out of available memory.":"Nicht genügend Speicher verfügbar.","File count error.":"Fehlerhafte Dateianzahl.","File extension error.":"Fehler bei Dateiendung","mb":"MB","Add Files":"Dateien hinzufügen"},
	"en" : {"Stop Upload":"Stop Upload","Upload URL might be wrong or doesn't exist.":"Upload URL might be wrong or doesn't exist.","tb":"tb","Size":"Size","Close":"Close","You must specify either browse_button or drop_element.":"You must specify either browse_button or drop_element.","Init error.":"Init error.","Add files to the upload queue and click the start button.":"Add files to the upload queue and click the start button.","List":"List","Filename":"Filename","%s specified, but cannot be found.":"%s specified, but cannot be found.","Image format either wrong or not supported.":"Image format either wrong or not supported.","Status":"Status","HTTP Error.":"HTTP Error.","Start Upload":"Start Upload","Error: File too large:":"Error: File too large:","kb":"kb","Duplicate file error.":"Duplicate file error.","File size error.":"File size error.","N/A":"N/A","gb":"gb","Error: Invalid file extension:":"Error: Invalid file extension:","Select files":"Select files","%s already present in the queue.":"%s already present in the queue.","Resoultion out of boundaries! <b>%s</b> runtime supports images only up to %wx%hpx.":"Resoultion out of boundaries! <b>%s</b> runtime supports images only up to %wx%hpx.","File: %s":"File: %s","b":"b","Uploaded %d/%d files":"Uploaded %d/%d files","Upload element accepts only %d file(s) at a time. Extra files were stripped.":"Upload element accepts only %d file(s) at a time. Extra files were stripped.","%d files queued":"%d files queued","File: %s, size: %d, max file size: %d":"File: %s, size: %d, max file size: %d","Thumbnails":"Thumbnails","Drag files here.":"Drag files here.","Runtime ran out of available memory.":"Runtime ran out of available memory.","File count error.":"File count error.","File extension error.":"File extension error.","mb":"mb","Add Files":"Add Files"},
	"es" : {"Stop Upload":"Detener Subida.","Upload URL might be wrong or doesn't exist.":"URL de carga inexistente.","tb":"TB","Size":"Tamaño","Close":"Cerrar","You must specify either browse_button or drop_element.":"","Init error.":"Error de inicialización.","Add files to the upload queue and click the start button.":"Agregue archivos a la lista de subida y pulse clic en el botón de Iniciar carga","List":"Lista","Filename":"Nombre de archivo","%s specified, but cannot be found.":"","Image format either wrong or not supported.":"Formato de imagen no soportada.","Status":"Estado","HTTP Error.":"Error de HTTP.","Start Upload":"Iniciar carga","Error: File too large:":"Error: archivo demasiado grande:","kb":"KB","Duplicate file error.":"Error, archivo duplicado","File size error.":"Error de tamaño de archivo.","N/A":"No disponible","gb":"GB","Error: Invalid file extension:":"Error: Extensión de archivo inválida:","Select files":"Elija archivos","%s already present in the queue.":"%s ya se encuentra en la lista.","Resoultion out of boundaries! <b>%s</b> runtime supports images only up to %wx%hpx.":"","File: %s":"Archivo: %s","b":"B","Uploaded %d/%d files":"Subidos %d/%d archivos","Upload element accepts only %d file(s) at a time. Extra files were stripped.":"Se aceptan sólo %d archivo(s) al tiempo. Más, no se tienen en cuenta.","%d files queued":"%d archivos en cola.","File: %s, size: %d, max file size: %d":"Archivo: %s, tamaño: %d, tamaño máximo de archivo: %d","Thumbnails":"Miniaturas","Drag files here.":"Arrastre archivos aquí","Runtime ran out of available memory.":"No hay memoria disponible.","File count error.":"Error en contador de archivos.","File extension error.":"Error de extensión de archivo.","mb":"MB","Add Files":"Agregar archivos"},
	"fi" : {"Stop Upload":"Pysäytä lähetys","Upload URL might be wrong or doesn't exist.":"Lähetyksen URL-osoite saattaa olla väärä tai sitä ei ole olemassa.","tb":"TB","Size":"Koko","Close":"Sulje","You must specify either browse_button or drop_element.":"","Init error.":"Init virhe.","Add files to the upload queue and click the start button.":"Lisää tiedostoja lähetysjonoon ja klikkaa aloita-nappia.","List":"","Filename":"Tiedostonimi","%s specified, but cannot be found.":"","Image format either wrong or not supported.":"Kuvaformaatti on joko väärä tai ei tuettu.","Status":"Tila","HTTP Error.":"HTTP-virhe.","Start Upload":"Aloita lähetys","Error: File too large:":"Virhe: Liian suuri tiedosto:","kb":"kB","Duplicate file error.":"Tuplatiedostovirhe.","File size error.":"Tiedostokokovirhe.","N/A":"N/A","gb":"GB","Error: Invalid file extension:":"Virhe: Virheellinen tiedostopääte:","Select files":"Valitse tiedostoja","%s already present in the queue.":"%s on jo jonossa.","Resoultion out of boundaries! <b>%s</b> runtime supports images only up to %wx%hpx.":"","File: %s":"Tiedosto: %s","b":"B","Uploaded %d/%d files":"Lähetetty %d/%d tiedostoa","Upload element accepts only %d file(s) at a time. Extra files were stripped.":"Vain %d tiedosto(a) voidaan lähettää kerralla. Ylimääräiset tiedostot ohitettiin.","%d files queued":"%d tiedostoa jonossa","File: %s, size: %d, max file size: %d":"Tiedosto: %s, koko: %d, suurin sallittu tiedostokoko: %d","Thumbnails":"","Drag files here.":"Raahaa tiedostot tähän.","Runtime ran out of available memory.":"Toiminnon käytettävissä oleva muisti loppui kesken.","File count error.":"Tiedostolaskentavirhe.","File extension error.":"Tiedostopäätevirhe.","mb":"MB","Add Files":"Lisää tiedostoja"},
	"fr" : {"Stop Upload":"Arrêter l'envoi.","Upload URL might be wrong or doesn't exist.":"L'URL d'envoi est soit erronée soit n'existe pas.","tb":"To","Size":"Taille","Close":"Fermer","You must specify either browse_button or drop_element.":"Vous devez spécifier browse_button ou drop_element.","Init error.":"Erreur d'initialisation.","Add files to the upload queue and click the start button.":"Ajoutez des fichiers à la file d'attente de téléchargement et appuyez sur le bouton 'Démarrer l'envoi'","List":"Liste","Filename":"Nom du fichier","%s specified, but cannot be found.":"%s spécifié, mais ne peut pas être trouvé.","Image format either wrong or not supported.":"Le format d'image est soit erroné soit pas géré.","Status":"État","HTTP Error.":"Erreur HTTP.","Start Upload":"Démarrer l'envoi","Error: File too large:":"Erreur: Fichier trop volumineux:","kb":"Ko","Duplicate file error.":"Erreur: Fichier déjà sélectionné.","File size error.":"Erreur de taille de fichier.","N/A":"Non applicable","gb":"Go","Error: Invalid file extension:":"Erreur: Extension de fichier non valide:","Select files":"Sélectionnez les fichiers","%s already present in the queue.":"%s déjà présent dans la file d'attente.","Resoultion out of boundaries! <b>%s</b> runtime supports images only up to %wx%hpx.":"Résolution sur les frontières ! L'exécution de <b>%s</b> supporte seulement les images de %wx%hpx","File: %s":"Fichier: %s","b":"o","Uploaded %d/%d files":"%d fichiers sur %d ont été envoyés","Upload element accepts only %d file(s) at a time. Extra files were stripped.":"Que %d fichier(s) peuvent être envoyé(s) à la fois. Les fichiers supplémentaires ont été ignorés.","%d files queued":"%d fichiers en attente","File: %s, size: %d, max file size: %d":"Fichier: %s, taille: %d, taille max. d'un fichier: %d","Thumbnails":"Miniatures","Drag files here.":"Déposez les fichiers ici.","Runtime ran out of available memory.":"Le traitement a manqué de mémoire disponible.","File count error.":"Erreur: Nombre de fichiers.","File extension error.":"Erreur d'extension de fichier","mb":"Mo","Add Files":"Ajouter des fichiers"}};

	function init() {
		var fields = ['Elgg', 'user_guid', 'album_guid', 'batch', 'tidypics_token', 'plupload_language'];

		var data;
		data = security.addToken(data);

		$(fields).each(function(i, name) {
			var value = $('input[name=' + name + ']').val();
			if (value) {
				data[name] = value;
			}
		});

		var allowed_ext = $("#uploader").data('allext');
		var maxfilesize = $("#uploader").data('maxfilesize');
		var maxfiles = $("#uploader").data('maxnumber');
		
		var client_resizing = $("#uploader").data('client-resizing');

		if (client_resizing == true) {
			var client_width = $("#uploader").data('client-width');
			var client_height = $("#uploader").data('client-height');
			var remove_exif = $("#uploader").data('remove-exif');
			var preserve_exif = !remove_exif;

			var resizing_parameters = {
				width: client_width,
				height: client_height,
				quality: 100,
				preserve_headers: preserve_exif
			};
		} else {
			var resizing_parameters = false;
		}

		plupload.addI18n(pluploadLanguageArray[data.plupload_language]);

		$("#uploader").plupload({
			// General settings
			runtimes : 'html5,html4',
			url : elgg.get_site_url() + 'action/photos/image/ajax_upload',
			file_data_name : 'Image',

			dragdrop: true,
			sortable: true,
			multipart_params : data,
			max_file_size : maxfilesize + 'mb',

			filters : [
				{title : i18n.echo('tidypics:uploader:filetype'), extensions : allowed_ext}
			],

			// Views to activate
			views: {
				list: true,
				thumbs: true,
				active: 'thumbs'
			},
			
			resize : resizing_parameters,

			init : {
				UploadComplete: function(up, files) {
					// Called when all files are either uploaded or failed
					ajax.action('photos/image/ajax_upload_complete', {
						data: {
							album_guid: data.album_guid,
							batch: data.batch
						}
					}).done(function(json, status, jqXHR) {
						if (jqXHR.AjaxData.status == -1) {
							if (!json.error.message.length) {
								window.location.href = elgg.normalize_url('photos/siteimagesall');
							} else {
								location.reload();
							}
							return;
						}
						var url = elgg.normalize_url('photos/edit/' + json.batch_guid);
						window.location.href = url;
						return;
					});
				},

				FilesAdded: function(up, files) {
					if (up.files.length > maxfiles ) {
						alert(i18n.echo('tidypics:exceedmax_number', [maxfiles]));
					}
					if (up.files.length > maxfiles ) {
						up.splice(maxfiles);
					}
					if (up.files.length >= maxfiles) {
						up.disableBrowse(true);
					}
				},

				FilesRemoved: function(up, files) {
					if (up.files.length < maxfiles) {
						up.disableBrowse(false);
					}
				},

				FileUploaded: function(up, file, info) {
					var response = JSON.parse(info.response);
					if (response.error.message.length) {
						messages.error(response.error.message);
						up.stop();
						return;
					}
				}
			}
		});
	}

	init();
});

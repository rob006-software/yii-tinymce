<?php

/*
 * This file is part of the yii-tinymce.
 *
 * Copyright (c) 2017 Robert Korulczyk <robert@korulczyk.pl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

/**
 * TinyMCE input widget.
 *
 * ```php
 * $form->textArea($model, 'value', ['rows' => 6, 'span' => 8]);
 * $this->widget('TinyMceWidget', [
 * 	'model' => $model,
 * 	'attribute' => 'value',
 * 	'dry_run' => true,
 * 	'fileManager' => [
 * 		'class' => 'TinyMceElFinder',
 * 		'popupConnectorRoute' => 'pageAssetsPopup',
 * 		'popupTitle' => 'Files',
 * 	],
 * 	'settings' => [
 * 		'content_css' => $this->getEditorStyles(),
 * 	],
 * ]);
 * ```
 *
 * @author Robert Korulczyk <robert@korulczyk.pl>
 * @since 1.0.0
 */
class TinyMceWidget extends CInputWidget {

	/**
	 * If `true`, widget don't create a textarea, only register necessary scripts.
	 *
	 * @var bool
	 */
	public $dry_run = false;

	/**
	 * @var bool|array FileManager configuration.
	 * For example:
	 * 'fileManager' => [
	 *      'class' => 'TinyMceElFinder',
	 *      'connectorRoute' => 'admin/elfinder/connector',
	 * ]
	 */
	public $fileManager = false;

	/**
	 * Default widget configuration.
	 *
	 * @var array
	 */
	public $defaultSettings = [
		'plugins' => [
			'accordion', 'advlist', 'anchor', 'autolink', 'autoresize', 'autosave', 'charmap', 'code', 'codesample',
			'directionality', 'emoticons', 'fullscreen', 'help', 'image', 'importcss', 'insertdatetime', 'link',
			'lists', 'media', 'nonbreaking', 'pagebreak', 'preview', 'quickbars', 'save', 'searchreplace', 'table',
			'visualblocks', 'visualchars', 'wordcount',
		],
		'toolbar' => [
			'undo redo | styles | forecolor backcolor | bold italic quote underline strikethrough subscript superscript | removeformat | alignleft aligncenter alignright alignjustify | ltr rtl',
			'bullist numlist outdent indent | link unlink anchor footnote index | image gallery media | nonbreaking charmap pagebreak hr searchreplace',
		],
		'indentation' => '2em',
		'paste_as_text' => true,
		'browser_spellcheck' => true,
		'style_formats' => [
			'headers' => [
				'title' => 'Headers',
				'items' => [
					'h2' => ['title' => 'Header 2', 'format' => 'h2'],
					'h3' => ['title' => 'Header 3', 'format' => 'h3'],
					'h4' => ['title' => 'Header 4', 'format' => 'h4'],
					'h5' => ['title' => 'Header 5', 'format' => 'h5'],
					'h6' => ['title' => 'Header 6', 'format' => 'h6'],
				],
			],
			'others' => [
				'title' => 'Others',
				'items' => [
					'p' => ['title' => 'Paragraph', 'format' => 'p'],
					'div' => ['title' => 'Div', 'format' => 'div'],
					'pre' => ['title' => 'Pre', 'format' => 'pre'],
					'code' => ['title' => 'Code', 'icon' => 'code', 'format' => 'code'],
				],
			],
		],
		'toolbar_items_size' => 'small',
		'image_advtab' => true,
		'relative_urls' => false,
		'resize' => true,
		'content_css' => [],
		// do not allow change list style
		'advlist_bullet_styles' => 'default',
		// alow embed js files
		'extended_valid_elements' => 'script[src]',
		// new features in TinyMCE 4.3.x
		'image_caption' => true,
		'media_live_embeds' => true,
		// new features in TinyMCE 4.5.x
		'style_formats_autohide' => true,
	];

	/**
	 * Widget settings will override defaultSettings
	 *
	 * @var array
	 */
	public $settings = [];

	/**
	 * @var string
	 */
	private $tinymceAssetsDir;

	/**
	 * {@inheritdoc}
	 *
	 * @throws CException
	 */
	public function init() {
		$this->tinymceAssetsDir = Yii::app()->assetManager->publish(Yii::getPathOfAlias('vendor.tinymce.tinymce'));

		$this->settings = CMap::mergeArray($this->defaultSettings, $this->settings);
		if (!empty($this->settings['style_formats'])) {
			$this->settings['style_formats'] = array_values($this->settings['style_formats']);
		}
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws CException
	 */
	public function run() {
		list($name, $id) = $this->resolveNameID();
		if (isset($this->htmlOptions['id'])) {
			$id = $this->htmlOptions['id'];
		} else {
			$this->htmlOptions['id'] = $id;
		}
		if (isset($this->htmlOptions['name'])) {
			$name = $this->htmlOptions['name'];
		}

		if (!$this->dry_run) {
			if ($this->model !== null) {
				echo CHtml::textArea($name, CHtml::resolveValue($this->model, $this->attribute), $this->htmlOptions);
			} else {
				echo CHtml::textArea($name, $this->value, $this->htmlOptions);
			}
		}
		$this->registerAssets($id);
	}

	private function registerAssets($id) {
		$cs = Yii::app()->getClientScript();
		if (YII_DEBUG) {
			$cs->registerScriptFile($this->tinymceAssetsDir . '/tinymce.js');
		} else {
			$cs->registerScriptFile($this->tinymceAssetsDir . '/tinymce.min.js');
		}

		if ($this->fileManager !== false) {
			/* @var $fm TinyMceFileManager */
			$fm = Yii::createComponent($this->fileManager);
			$fm->init();
			$this->settings['file_picker_callback'] = $fm->getFileBrowserCallback();
		}

		$this->settings['selector'] = "#{$id}";
		$settings = CJavaScript::encode($this->settings);

		$cs->registerScript("{$id}_tinyMce_init", "tinymce.init({$settings});");
	}
}

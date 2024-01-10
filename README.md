TinyMCE 6.x integration for Yii 1.1
===================================

Yii 1.1 extension that provides basic integration with [TinyMce editor](https://www.tinymce.com/).

Based on https://github.com/zxbodya/yii-tinymce.


Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```shell
composer require rob006/yii-tinymce
```

or add

```json
"rob006/yii2-tinymce": "^2.0"
```

to the `require` section of your `composer.json` file.


Usage
-----

Basic usage:

```php
<?php $this->widget('TinyMceWidget', [
	'model' => $model,
	'attribute' => 'value',
]) ?>
```

Usage with custom settings and [Yiistrap](http://www.getyiistrap.com/) and [elFinder](https://github.com/rob006/yii-elfinder2) extensions integration.

```php
<?= $form->textAreaControlGroup($model, 'value', ['rows' => 6, 'span' => 8,]) ?>
<?php $this->widget('TinyMceWidget', [
	'model' => $model,
	'attribute' => 'value',
	'dry_run' => true,
	'fileManager' => [
		'class' => 'TinyMceElFinder',
		'popupConnectorRoute' => 'pageAssetsPopup',
		'popupTitle' => 'Files',
	],
	'settings' => [
		'content_css' => $this->getEditorStyles(),
	],
]) ?>
```


Resources
---------

 * [Extension page](https://github.com/rob006-software/yii-tinymce)
 * [elFinder extension](https://github.com/rob006-software/yii-elfinder2)
 * [TinyMce page](https://www.tinymce.com/)

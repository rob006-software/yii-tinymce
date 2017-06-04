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
 * Abstract FileManager class to use with TinyMce.
 * For example see elFinder extension.
 *
 * @see https://github.com/rob006/yii-elfinder2
 *
 * @author Robert Korulczyk <robert@korulczyk.pl>
 * @since 1.0.0
 */
abstract class TinyMceFileManager extends CComponent {

	/**
	 * Initialize FileManager component, registers required JS.
	 */
	public function init() {
	}

	/**
	 * @return string JavaScript callback function, starts with "js:".
	 */
	abstract public function getFileBrowserCallback();
}

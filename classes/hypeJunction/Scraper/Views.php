<?php

namespace hypeJunction\Scraper;

use ElggRiverItem;

/**
 * @access private
 */
class Views {

	/**
	 * Output metatags for a URL
	 *
	 * @param string $hook   'extract:meta'
	 * @param string $type   'embed'
	 * @param array  $return Metatags
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function getCard($hook, $type, $return, $params) {
		$url = elgg_extract('url', $params);
		return hypeapps_scrape($url);
	}

	/**
	 * Preview a URL card
	 *
	 * @param string $hook   'format:src'
	 * @param string $type   'all'
	 * @param array  $return Metatags
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function viewCard($hook, $type, $return, $params) {
		$url = elgg_extract('url', $params);
		return elgg_view('output/card', [
			'href' => $url,
		]);
	}

	/**
	 * Extract qualifiers such as hashtags, usernames, urls, and emails
	 *
	 * @param string $hook   Equals 'extract:qualifiers'
	 * @param string $type   Equals 'scraper'
	 * @param array  $return Qualifiers
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function extractTokens($hook, $type, $return, $params) {
		$source = elgg_extract('source', $params);
		return hypeapps_extract_tokens($source);
	}

	/**
	 * Linkify qualifiers such as hashtags, usernames, urls, and emails
	 *
	 * @param string $hook   Equals 'link:qualifiers'
	 * @param string $type   Equals 'scraper'
	 * @param array  $return Qualifiers
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function linkTokens($hook, $type, $return, $params) {
		$source = elgg_extract('source', $params);
		$types = elgg_extract('types', $params, [
			'urls', ''
		]);
		return hypeapps_linkify_tokens($source);
	}

	/**
	 * Display a preview of a bookmark
	 *
	 * @param string $hook   'view_vars'
	 * @param string $type   "river/elements/layout"
	 * @param array  $return View vars
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function addBookmarkRiverPreview($hook, $type, $return, $params) {

		if (!elgg_get_plugin_setting('bookmarks', 'hypeScraper')) {
			return;
		}

		$item = elgg_extract('item', $return);
		if (!$item instanceof ElggRiverItem) {
			return;
		}

		if ($item->view != 'river/object/bookmarks/create') {
			return;
		}

		$object = $item->getObjectEntity();
		if (!elgg_instanceof($object, 'object', 'bookmarks')) {
			return;
		}

		$return['attachments'] = elgg_view('output/card', [
			'href' => $object->address,
		]);

		return $return;
	}

	/**
	 * Display a preview of a bookmark
	 *
	 * @param string $hook   'view_vars'
	 * @param string $type   "object/elements/full"
	 * @param array  $return View vars
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function addBookmarkProfilePreview($hook, $type, $return, $params) {

		if (!elgg_get_plugin_setting('bookmarks', 'hypeScraper')) {
			return;
		}

		$entity = elgg_extract('entity', $return);
		if (!elgg_instanceof($entity, 'object', 'bookmarks')) {
			return;
		}

		$return['body'] .= elgg_view('output/player', [
			'href' => $entity->address,
		]);

		return $return;
	}

	/**
	 * Linkify longtext output
	 *
	 * @param string $hook   "view"
	 * @param string $type   "output/longtext""
	 * @param array  $return View vars
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function linkifyLongtext($hook, $type, $return, $params) {
		if (!elgg_get_plugin_setting('linkify', 'hypeScraper')) {
			return;
		}
		return hypeapps_linkify_tokens($return, $params['vars']);
	}

}

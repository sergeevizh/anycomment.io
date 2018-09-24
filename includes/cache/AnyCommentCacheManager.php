<?php

namespace anycomment\cache;

/**
 * Class AnyCommentCacheManager is used as base class for managing cache of the plugin.
 */
class AnyCommentCacheManager {

	/**
	 * @var string Root namespace. Can be used to flush cache globally.
	 */
	public static $rootNamespace = '/anycomment/%s';

	/**
	 * Flush all cache globally.
	 */
	public static function flushAll() {
		return AnyComment()->cache->deleteItem( static::getRootNamespace() );
	}

	/**
	 * Get available namespaces.
	 *
	 * @return array
	 */
	public static function getCacheNamespaces() {
		return [
			'rest' => '\anycomment\cache\rest\AnyCommentRestCacheManager',
		];
	}

	/**
	 * Get root namespace.
	 *
	 * @return string
	 */
	public static function getRootNamespace() {
		return sprintf( static::$rootNamespace, AnyComment()->version );
	}
}
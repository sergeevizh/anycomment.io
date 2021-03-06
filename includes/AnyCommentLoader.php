<?php

namespace AnyComment;

/**
 * Class AnyCommentLoader is used as main plugin loader.
 *
 * @author Alexander Teshabaev <sasha.tesh@gmail.com>
 * @package AnyComment
 */
class AnyCommentLoader {
	/**
	 * @var array List of classes to invoke immediately.
	 */
	public static $load = [
		// Rest
		'AnyComment\Rest\AnyCommentRestComment',
		'AnyComment\Rest\AnyCommentRestLikes',
		'AnyComment\Rest\AnyCommentRestUsers',
		'AnyComment\Rest\AnyCommentRestDocuments',
		'AnyComment\Rest\AnyCommentRestRate',
		'AnyComment\Rest\AnyCommentRestSubscriptions',
		'AnyComment\Rest\AnyCommentSocialAuth',

		// Admin
		'AnyComment\Admin\AnyCommentAdminPages',
		'AnyComment\Admin\AnyCommentStatistics',
		'AnyComment\Admin\AnyCommentFilesPage',
		'AnyComment\Admin\AnyCommentRatingPage',
		'AnyComment\Admin\AnyCommentSubscriptionsPage',
		'AnyComment\Admin\AnyCommentEmailQueuePage',

		// Other
		'AnyComment\Admin\AnyCommentWPComments',

		// Hooks
		'AnyComment\Hooks\AnyCommentCommonHooks',
		'AnyComment\Hooks\AnyCommentCommentHooks',
		'AnyComment\Hooks\AnyCommentUserHooks',
		'AnyComment\Hooks\AnyCommentNativeLoginForm',

		'AnyComment\Integrations\AnyCommentWooCommerce',

		// Crontabs
		'AnyComment\Cron\AnyCommentEmailQueueCron',

		// Emails
		'AnyComment\EmailEndpoints',

		// Widgets
		'AnyComment\Widgets\Native\CommentList',

		// Main
		'AnyComment\AnyCommentRender',
	];

	/**
	 * Load list of classes to be invoked immediately.
	 */
	public static function load() {
		if ( ! empty( static::$load ) ) {
			foreach ( static::$load as $namespace ) {
				if ( class_exists( $namespace ) ) {
					new $namespace();
				}

				if ( strpos( $namespace, 'AnyComment\Widgets' ) !== false ) {
					add_action( 'widgets_init', function () use ( $namespace ) {
						register_widget( $namespace );
					} );
				}
			}
		}
	}
}
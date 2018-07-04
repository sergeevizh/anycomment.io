<?php

/**
 * Class AnyCommentLikes.
 *
 * @property int $ID
 * @property int|null $user_ID
 * @property int $comment_ID
 * @property int $post_ID
 * @property string $user_agent
 * @property string $ip
 * @property string $liked_at
 *
 * @since 0.0.3
 */
class AnyCommentLikes {

	public $ID;
	public $user_ID;
	public $comment_ID;
	public $post_ID;
	public $user_agent;
	public $ip;
	public $liked_at;

	/**
	 * Get table name.
	 *
	 * @return string
	 */
	public static function tableName() {
		return 'anycomment_likes';
	}

	/**
	 * @param $commentId
	 *
	 * @return bool|int
	 */
	public static function isCurrentUserHasLike( $commentId ) {
		if ( ! ( $comment = get_comment( $commentId ) ) instanceof WP_Comment ) {
			return false;
		}

		if ( ! ( $user = wp_get_current_user() ) instanceof WP_User ) {
			return false;
		}

		global $wpdb;


		$tableName = static::tableName();

		$sql   = $wpdb->prepare( "SELECT COUNT(*) FROM $tableName WHERE `user_ID` =%d AND `comment_ID`=%s", $user->ID, $comment->comment_ID );
		$count = $wpdb->get_var( $sql );

		return $count >= 1;
	}

	/**
	 * Get likes count per comment.
	 *
	 * @param int $commentId Comment ID to be searched for.
	 *
	 * @return int
	 */
	public static function getLikesCount( $commentId ) {
		global $wpdb;

		$table_name = static::tableName();
		$sql        = "SELECT COUNT(*) FROM $table_name WHERE `comment_ID`=%d";
		$value      = $wpdb->get_var( $wpdb->prepare( $sql, [ $commentId ] ) );

		if ( $value === null ) {
			return 0;
		}

		return (int) $value;
	}

	/**
	 * Delete single like.
	 *
	 * @since 0.0.3
	 *
	 * @param int $commentId ID of the comment to delete like from.
	 *
	 * @return bool
	 */
	public static function deleteLike( $commentId ) {
		if ( empty( $commentId ) ) {
			return false;
		}

		global $wpdb;

		$rows = $wpdb->delete( static::tableName(), [ 'comment_ID' => $commentId ] );

		return $rows !== false && $rows > 0;
	}

	/**
	 * Inserts a comment into the database.
	 *
	 * @since 0.0.3
	 *
	 * @param AnyCommentLikes $like Object to be used and in
	 *
	 * @return false|AnyCommentLikes|object
	 */
	public static function addLike( $like ) {

		if ( ! $like instanceof AnyCommentLikes ) {
			return false;
		}

		if ( ! isset( $like->ip ) ) {
			$like->ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null;
		}

		if ( ! isset( $like->liked_at ) ) {
			$like->liked_at = current_time( 'mysql' );
		}

		global $wpdb;

		$tableName = static::tableName();

		unset( $like->ID );

		$count = $wpdb->insert( $tableName, (array) $like );


		if ( $count !== false && $count > 0 ) {
			$lastId = $wpdb->insert_id;

			if ( empty( $lastId ) ) {
				return false;
			}

			return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tableName WHERE `id`=%d", $lastId ) );
		}

		return false;
	}
}
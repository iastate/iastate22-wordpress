<?php

class Iastate22_Plugin_Relevanssi_Premium {
	/**
	 * @var bool|Iastate22_Plugin_Relevanssi_Premium
	 */
	public static $instance = false;
	/**
	 * @var int
	 */
	protected $current_blog_id;
	/**
	 * @var int
	 */
	protected $original_blog_id;
	/**
	 * @var bool
	 */
	protected $switched = false;

	public function __construct() {
		$this->original_blog_id = (int) get_current_blog_id();
		$this->current_blog_id  = (int) $this->original_blog_id;
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return Iastate22_Plugin_Relevanssi_Premium
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register hooks and actions
	 */
	public function init(): void {
		add_action( 'iastate22_loop_post', array( $this, 'action_post_check' ), 10, 1 );
		add_action( 'iastate22_loop_end', array( $this, 'action_post_cleanup' ), 10, 1 );
	}

	/**
	 * Hook to trigger blog_id cleanup
	 */
	public function action_post_cleanup(): void {
		$this->restore_blog();
	}

	/**
	 * Run blog restore functions if switch flag is true
	 */
	protected function restore_blog(): void {
		if ( true !== $this->switched ) {
			return;
		}

		restore_current_blog();
		$this->current_blog_id = get_current_blog_id();
		$this->switched        = false;
	}

	/**
	 * Switches the blog if necessary.
	 *
	 * @param WP_Post $WP_post The post object.
	 */
	public function action_post_check( $WP_post ): void {
		if ( ! isset( $WP_post->blog_id ) ) {
			return;
		}

		$this->maybe_switch_blog( $WP_post->blog_id );
	}

	/**
	 * Run functions to switch to a new multi-site blog and set flag.
	 *
	 * If the current post blog is different from the current blog, switches the blog.
	 * If the blog has been switched, makes sure it's restored first to keep the switch
	 * stack clean.
	 *
	 * @param int $blog_id
	 */
	protected function maybe_switch_blog( $blog_id ): void {
		$blog_id_int = (int) $blog_id;

		if ( $blog_id_int === $this->current_blog_id ) {
			return;
		}

		if ( $blog_id_int === $this->original_blog_id ) {
			$this->restore_blog();

			return;
		}

		switch_to_blog( $blog_id_int );
		$this->current_blog_id = $blog_id_int;
		$this->switched        = true;
	}
}
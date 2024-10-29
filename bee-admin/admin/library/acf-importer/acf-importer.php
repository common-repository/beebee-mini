<?php


if ( !class_exists( 'ACF_Options_Importer' ) ) :

class ACF_Options_Importer {

	/**
	 * Stores the singleton instance.
	 *
	 * @access private
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * The attachment ID.
	 *
	 * @access private
	 *
	 * @var int
	 */
	private $file_id;

	/**
	 * The transient key template used to store the options after upload.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $transient_key = 'options-import-%d';

	/**
	 * The plugin version.
	 */
	const VERSION = 5;

	/**
	 * The minimum file version the importer will allow.
	 *
	 * @access private
	 *
	 * @var int
	 */
	private $min_version = 2;

	/**
	 * Stores the import data from the uploaded file.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $import_data;


	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function __clone() { wp_die( "请不要克隆 ACF_Options_Importer" ); }

	public function __wakeup() { wp_die( "Please don't __wakeup ACF_Options_Importer" ); }

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new ACF_Options_Importer;
			self::$instance->setup();
		}
		return self::$instance;
	}


	/**
	 * Initialize the singleton.
	 *
	 * @return void
	 */
	public function setup() {
		add_action( 'export_filters', array( $this, 'export_filters' ) );
		add_filter( 'export_args', array( $this, 'export_args' ) );
		add_action( 'export_wp', array( $this, 'export_wp' ) );
		add_action( 'admin_init', array( $this, 'register_importer' ) );
	}


	/**
	 * Register our importer.
	 *
	 * @return void
	 */
	public function register_importer() {
		if ( function_exists( 'register_importer' ) ) {
			register_importer( 'acf-options-import', __( '导入比比主题', 'acf-options-importer' ), __( '此处可一键导入比比主题json文件，请从比比公众号【APP比比】获取更多的比比主题数据包', 'acf-options-importer' ), array( $this, 'dispatch' ) );
		}
	}


	/**
	 * Add a radio option to export options.
	 *
	 * @return void
	 */
	public function export_filters() {
		?>
		<p><label><input type="radio" name="content" value="options" /> <?php _e( '比比主题', 'acf-options-importer' ); ?></label></p>
		<p class="description">比比主题请单独导出，不要和上面的内容一起导出。如遇到问题，请访问公众号【APP比比】</p>
		<?php
	}


	/**
	 * If the user selected that they want to export options, indicate that in the args and
	 * discard anything else. This will get picked up by ACF_Options_Importer::export_wp().
	 *
	 * @param  array $args The export args being filtered.
	 * @return array The (possibly modified) export args.
	 */
	public function export_args( $args ) {
		if ( ! empty( $_GET['content'] ) && 'options' == $_GET['content'] ) {
			return array( 'options' => true );
		}
		return $args;
	}


	/**
	 * Export options as a JSON file if that's what the user wants to do.
	 *
	 * @param  array $args The export arguments.
	 * @return void
	 */
	public function export_wp( $args ) {
		if ( ! empty( $args['options'] ) ) {
			global $wpdb;

			// $sitename = sanitize_key( get_bloginfo( 'name' ) );
			$sitename = 'beebee';
			if ( ! empty( $sitename ) ) {
				$sitename .= '.';
			}
			$filename = $sitename . 'options.' . date( 'Y-m-d' ) . '.json';

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ), true );

			// Ignore multisite-specific keys
			$multisite_exclude = '';
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				$multisite_exclude = $wpdb->prepare( "AND `option_name` NOT LIKE 'wp_%d_%%'", get_current_blog_id() );
			}

			// $option_names = $wpdb->get_col( "SELECT DISTINCT `option_name` FROM $wpdb->options WHERE `option_name` NOT LIKE '_transient_%' {$multisite_exclude}" );
			$option_names = $wpdb->get_col( "SELECT DISTINCT `option_name` FROM $wpdb->options WHERE `option_name` LIKE 'options_mode_%' OR `option_name` LIKE '_options_mode_%' OR `option_name` LIKE 'beeapp%' {$multisite_exclude}" );
			if ( ! empty( $option_names ) ) {

				// Allow others to be able to exclude their options from exporting
				$blacklist = apply_filters( 'options_export_blacklist', array() );

				$export_options = array();
				// we're going to use a random hash as our default, to know if something is set or not
				$hash = '048f8580e913efe41ca7d402cc51e848';
				foreach ( $option_names as $option_name ) {
					if ( in_array( $option_name, $blacklist ) ) {
						continue;
					}

					// Allow an installation to define a regular expression export blacklist for security purposes. It's entirely possible
					// that sensitive data might be installed in an option, or you may not want anyone to even know that a key exists.
					// For instance, if you run a multsite installation, you could add in an mu-plugin:
					// 		define( 'WP_OPTION_EXPORT_BLACKLIST_REGEX', '/^(mailserver_(login|pass|port|url))$/' );
					// to ensure that none of your sites could export your mailserver settings.
					if ( defined( 'WP_OPTION_EXPORT_BLACKLIST_REGEX' ) && preg_match( WP_OPTION_EXPORT_BLACKLIST_REGEX, $option_name ) ) {
						continue;
					}

					$option_value = get_option( $option_name, $hash );
					// only export the setting if it's present
					if ( $option_value !== $hash ) {
						$export_options[ $option_name ] = maybe_serialize( $option_value );
					}
				}

				$no_autoload = $wpdb->get_col( "SELECT DISTINCT `option_name` FROM $wpdb->options WHERE `option_name` LIKE 'options_mode_%' OR `option_name` LIKE '_options_mode_%' OR `option_name` LIKE 'beeapp%' {$multisite_exclude} AND `autoload`='no'" );
				if ( empty( $no_autoload ) ) {
					$no_autoload = array();
				}

				$JSON_PRETTY_PRINT = defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : null;
				echo json_encode( array( 'version' => self::VERSION, 'options' => $export_options, 'no_autoload' => $no_autoload ), $JSON_PRETTY_PRINT );
			}

			exit;
		}
	}


	/**
	 * Registered callback function for the Options Importer
	 *
	 * Manages the three separate stages of the import process.
	 *
	 * @return void
	 */
	public function dispatch() {
		$this->header();

		if ( empty( $_GET['step'] ) ) {
			$_GET['step'] = 0;
		}

		switch ( intval( $_GET['step'] ) ) {
			case 0:
				$this->greet();
				break;
			case 1:
				check_admin_referer( 'import-upload' );
				if ( $this->handle_upload() ) {
					$this->pre_import();
				} else {
					echo '<p><a href="' . esc_url( admin_url( 'admin.php?import=acf-options-import' ) ) . '">' . __( '前往上传文件', 'acf-options-importer' ) . '</a></p>';
				}
				break;
			case 2:
				check_admin_referer( 'import-wordpress-options' );
				$this->file_id = intval( $_POST['import_id'] );
				if ( false !== ( $this->import_data = get_transient( $this->transient_key() ) ) ) {
					$this->import();
				}
				break;
		}

		$this->footer();
	}


	/**
	 * Start the options import page HTML.
	 *
	 * @return void
	 */
	private function header() {
		echo '<div class="wrap">';
		echo '<h2>' . __( '导入比比主题数据', 'acf-options-importer' ) . '</h2>';
	}


	/**
	 * End the options import page HTML.
	 *
	 * @return void
	 */
	private function footer() {
		echo '</div>';
	}


	/**
	 * Display introductory text and file upload form.
	 *
	 * @return void
	 */
	private function greet() {
		echo '<div class="narrow">';
		echo '<p>'.__( '提醒! 在进行比比主题导入操作前请务必先进行主题导出备份操作，否则新的数据将覆盖原有的所有主题设置', 'acf-options-importer' ).'</p>';
		echo '<p>'.__( '请选择比比主题 JSON (.json) 文件并上传', 'acf-options-importer' ).'</p>';
		wp_import_upload_form( 'admin.php?import=acf-options-import&amp;step=1' );
		echo '</div>';
	}


	/**
	 * Handles the JSON upload and initial parsing of the file to prepare for
	 * displaying author import options
	 *
	 * @return bool False if error uploading or invalid file, true otherwise
	 */
	private function handle_upload() {
		$file = wp_import_handle_upload();

		if ( isset( $file['error'] ) ) {
			return $this->error_message(
				__( '抱歉，出现了一个错误。', 'acf-options-importer' ),
				esc_html( $file['error'] )
			);
		}

		if ( ! isset( $file['file'], $file['id'] ) ) {
			return $this->error_message(
				__( '抱歉，出现了一个错误。', 'acf-options-importer' ),
				__( '文件没有上载成功。请再试一次。', 'acf-options-importer' )
			);
		}

		$this->file_id = intval( $file['id'] );

		if ( ! file_exists( $file['file'] ) ) {
			wp_import_cleanup( $this->file_id );
			return $this->error_message(
				__( '抱歉，出现了一个错误。', 'acf-options-importer' ),
				sprintf( __( '在<code>%s</code>处找不到导出文件。这可能是由权限问题引起的。', 'acf-options-importer' ), esc_html( $file['file'] ) )
			);
		}

		if ( ! is_file( $file['file'] ) ) {
			wp_import_cleanup( $this->file_id );
			return $this->error_message(
				__( '抱歉，出现了一个错误。', 'wordpress-importer' ),
				__( '选择的不是文件，请重试。', 'wordpress-importer' )
			);
		}

		$file_contents = file_get_contents( $file['file'] );
		$this->import_data = json_decode( $file_contents, true );
		set_transient( $this->transient_key(), $this->import_data, DAY_IN_SECONDS );
		wp_import_cleanup( $this->file_id );

		return $this->run_data_check();
	}


	/**
	 * Get an array of known options which we would want checked by default when importing.
	 *
	 * @return array
	 */
	private function get_whitelist_options() {
		return apply_filters( 'options_import_whitelist', array(
			// 'active_plugins',
			'admin_email',
			'advanced_edit',
			'avatar_default',
			'avatar_rating',
			'blacklist_keys',
			'blogdescription',
			'blogname',
			'blog_charset',
			'blog_public',
			'blog_upload_space',
			'category_base',
			'category_children',
			'close_comments_days_old',
			'close_comments_for_old_posts',
			'comments_notify',
			'comments_per_page',
			'comment_max_links',
			'comment_moderation',
			'comment_order',
			'comment_registration',
			'comment_whitelist',
			'cron',
			// 'current_theme',
			'date_format',
			'default_category',
			'default_comments_page',
			'default_comment_status',
			'default_email_category',
			'default_link_category',
			'default_pingback_flag',
			'default_ping_status',
			'default_post_format',
			'default_role',
			'gmt_offset',
			'gzipcompression',
			'hack_file',
			'html_type',
			'image_default_align',
			'image_default_link_type',
			'image_default_size',
			'large_size_h',
			'large_size_w',
			'links_recently_updated_append',
			'links_recently_updated_prepend',
			'links_recently_updated_time',
			'links_updated_date_format',
			'link_manager_enabled',
			'mailserver_login',
			'mailserver_pass',
			'mailserver_port',
			'mailserver_url',
			'medium_size_h',
			'medium_size_w',
			'moderation_keys',
			'moderation_notify',
			'ms_robotstxt',
			'ms_robotstxt_sitemap',
			'nav_menu_options',
			'page_comments',
			'page_for_posts',
			'page_on_front',
			'permalink_structure',
			'ping_sites',
			'posts_per_page',
			'posts_per_rss',
			'recently_activated',
			'recently_edited',
			'require_name_email',
			'rss_use_excerpt',
			'show_avatars',
			'show_on_front',
			'sidebars_widgets',
			'start_of_week',
			'sticky_posts',
			// 'stylesheet',
			'subscription_options',
			'tag_base',
			// 'template',
			'theme_switched',
			'thread_comments',
			'thread_comments_depth',
			'thumbnail_crop',
			'thumbnail_size_h',
			'thumbnail_size_w',
			'timezone_string',
			'time_format',
			'uninstall_plugins',
			'uploads_use_yearmonth_folders',
			'upload_path',
			'upload_url_path',
			'users_can_register',
			'use_balanceTags',
			'use_smilies',
			'use_trackback',
			'widget_archives',
			'widget_categories',
			'widget_image',
			'widget_meta',
			'widget_nav_menu',
			'widget_recent-comments',
			'widget_recent-posts',
			'widget_rss',
			'widget_rss_links',
			'widget_search',
			'widget_text',
			'widget_top-posts',
			'WPLANG',
		) );
	}


	/**
	 * Get an array of blacklisted options which we never want to import.
	 *
	 * @return array
	 */
	private function get_blacklist_options() {
		return apply_filters( 'options_import_blacklist', array() );
	}


	/**
	 * Provide the user with a choice of which options to import from the JSON
	 * file, pre-selecting known options.
	 *
	 * @return void
	 */
	private function pre_import() {
		$whitelist = $this->get_whitelist_options();

		// Allow others to prevent their options from importing
		$blacklist = $this->get_blacklist_options();

		?>
		<style type="text/css">
		#importing_options {
			border-collapse: collapse;
		}
		#importing_options th {
			text-align: left;
		}
		#importing_options td, #importing_options th {
			padding: 5px 10px;
			border-bottom: 1px solid #dfdfdf;
		}
		#importing_options pre {
			white-space: pre-wrap;
			max-height: 100px;
			overflow-y: auto;
			background: #fff;
			padding: 5px;
		}
		div.error#import_all_warning {
			margin: 25px 0 5px;
		}
		</style>
		<script type="text/javascript">
		jQuery( function( $ ) {
			$('#option_importer_details,#import_all_warning').hide();
			options_override_all_warning = function() {
				$('#import_all_warning').toggle( $('input.which-options[value="all"]').is( ':checked' ) && $('#override_current').is( ':checked' ) );
			};
			$('.which-options').change( function() {
				options_override_all_warning();
				switch ( $(this).val() ) {
					case 'specific' : $('#option_importer_details').fadeIn(); break;
					default : $('#option_importer_details').fadeOut(); break;
				}
			} );
			$('#override_current').click( options_override_all_warning );
			$('#importing_options input:checkbox').each( function() {
				$(this).data( 'default', $(this).is(':checked') );
			} );
			$('.options-bulk-select').click( function( event ) {
				event.preventDefault();
				switch ( $(this).data('select') ) {
					case 'all' : $('#importing_options input:checkbox').prop( 'checked', true ); break;
					case 'none' : $('#importing_options input:checkbox').prop( 'checked', false ); break;
					case 'defaults' : $('#importing_options input:checkbox').each( function() { $(this).prop( 'checked', $(this).data( 'default' ) ); } ); break;
				}
			} );
		} );
		</script>
		<form action="<?php echo admin_url( 'admin.php?import=acf-options-import&amp;step=2' ); ?>" method="post">
			<?php wp_nonce_field( 'import-wordpress-options' ); ?>
			<input type="hidden" name="import_id" value="<?php echo absint( $this->file_id ); ?>" />

			<h3><?php _e( '确定要导入吗?', 'acf-options-importer' ) ?></h3>
			<p>
				<!-- <label><input type="radio" class="which-options" name="settings[which_options]" value="default" checked="checked" /> <?php _e( '默认选项' ); ?></label> -->
				<label><input type="radio" class="which-options" name="settings[which_options]" value="all" checked="checked" /> <?php _e( '所有选项' ); ?></label>
				<br /><label><input type="radio" class="which-options" name="settings[which_options]" value="specific" /> <?php _e( '自己勾选选项' ); ?></label>
			</p>

			<div id="option_importer_details">
				<h3><?php _e( '勾选要导入的选项', 'acf-options-importer' ); ?></h3>
				<p>
					<a href="#" class="options-bulk-select" data-select="all"><?php _e( '选择全部', 'acf-options-importer' ); ?></a>
					| <a href="#" class="options-bulk-select" data-select="none"><?php _e( '取消全部', 'acf-options-importer' ); ?></a>
				</p>
				<table id="importing_options">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th><?php _e( 'Option Name', 'acf-options-importer' ); ?></th>
							<th><?php _e( 'New Value', 'acf-options-importer' ) ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $this->import_data['options'] as $option_name => $option_value ) : ?>
							<?php
							// See ACF_Options_Importer::import() for an explanation of this.
							if ( defined( 'WP_OPTION_IMPORT_BLACKLIST_REGEX' ) && preg_match( WP_OPTION_IMPORT_BLACKLIST_REGEX, $option_name ) ) {
								continue;
							}
							?>
							<tr>
								<td><input type="checkbox" name="options[]" value="<?php echo esc_attr( $option_name ) ?>" <?php checked( in_array( $option_name, $whitelist ) ) ?> /></td>
								<td><?php echo esc_html( $option_name ) ?></td>
								<?php if ( null === $option_value ) : ?>
									<td><em>null</em></td>
								<?php elseif ( '' === $option_value ) : ?>
									<td><em>empty string</em></td>
								<?php elseif ( false === $option_value ) : ?>
									<td><em>false</em></td>
								<?php else : ?>
									<td><pre><?php echo esc_html( $option_value ) ?></pre></td>
								<?php endif ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<h3><?php _e( '附加设置', 'acf-options-importer' ); ?></h3>
			<p>
				<input type="checkbox" value="1" name="settings[override]" id="override_current" checked="checked" />
				<label for="override_current"><?php _e( '覆盖现有选项', 'acf-options-importer' ); ?></label>
			</p>
			<p class="description"><?php _e( '如果取消选中此框，将跳过当前存在的选项。可能导致无法更新主题。', 'acf-options-importer' ); ?></p>

			<div class="error inline" id="import_all_warning">
				<p class="description"><?php _e( 'Caution! Importing all options with the override option set could break this site. For instance, it may change the site URL, the active theme, and active plugins. Only proceed if you know exactly what you&#8217;re doing.', 'acf-options-importer' ); ?></p>
			</div>

			<?php submit_button( __( '确认并开始导入工作', 'acf-options-importer' ) ); ?>
		</form>
		<?php
	}


	/**
	 * The main controller for the actual import stage.
	 *
	 * @return void
	 */
	private function import() {
		if ( $this->run_data_check() ) {
			if ( empty( $_POST['settings']['which_options'] ) ) {
				$this->error_message( __( '发布的数据看起来并不完整。请再试一次。', 'acf-options-importer' ) );
				$this->pre_import();
				return;
			}

			$options_to_import = array();
			if ( 'all' == $_POST['settings']['which_options'] ) {
				$options_to_import = array_keys( $this->import_data['options'] );
			} elseif ( 'default' == $_POST['settings']['which_options'] ) {
				$options_to_import = $this->get_whitelist_options();
			} elseif ( 'specific' == $_POST['settings']['which_options'] ) {
				if ( empty( $_POST['options'] ) ) {
					$this->error_message( __( '似乎没有任何可导入的选项。你选了吗？', 'acf-options-importer' ) );
					$this->pre_import();
					return;
				}

				$options_to_import = $_POST['options'];
			}

			$override = ( ! empty( $_POST['settings']['override'] ) && '1' === $_POST['settings']['override'] );

			$hash = '048f8580e913efe41ca7d402cc51e848';

			// Allow others to prevent their options from importing
			$blacklist = $this->get_blacklist_options();

			foreach ( (array) $options_to_import as $option_name ) {
				if ( isset( $this->import_data['options'][ $option_name ] ) ) {
					if ( in_array( $option_name, $blacklist ) ) {
						echo "\n<p>" . sprintf( __( '跳过选项“%s”，因为插件或主题不允许导入该选项。', 'acf-options-importer' ), esc_html( $option_name ) ) . '</p>';
						continue;
					}

					// As an absolute last resort for security purposes, allow an installation to define a regular expression
					// blacklist. For instance, if you run a multsite installation, you could add in an mu-plugin:
					// 		define( 'WP_OPTION_IMPORT_BLACKLIST_REGEX', '/^(home|siteurl)$/' );
					// to ensure that none of your sites could change their own url using this tool.
					if ( defined( 'WP_OPTION_IMPORT_BLACKLIST_REGEX' ) && preg_match( WP_OPTION_IMPORT_BLACKLIST_REGEX, $option_name ) ) {
						echo "\n<p>" . sprintf( __( '跳过选项“%s”，因为此WordPress不允许安装该选项。', 'acf-options-importer' ), esc_html( $option_name ) ) . '</p>';
						continue;
					}

					if ( ! $override ) {
						// we're going to use a random hash as our default, to know if something is set or not
						$old_value = get_option( $option_name, $hash );

						// only import the setting if it's not present
						if ( $old_value !== $hash ) {
							echo "\n<p>" . sprintf( __( '已跳过选项“%s”，因为它当前存在。', 'acf-options-importer' ), esc_html( $option_name ) ) . '</p>';
							continue;
						}
					}

					$option_value = maybe_unserialize( $this->import_data['options'][ $option_name ] );
					if ( in_array( $option_name, $this->import_data['no_autoload'] ) ) {
						delete_option( $option_name );
						add_option( $option_name, $option_value, '', 'no' );
					} else {
						update_option( $option_name, $option_value );
					}
				} elseif ( 'specific' == $_POST['settings']['which_options'] ) {
					echo "\n<p>" . sprintf( __( '无法导入选项“%s”；它似乎不在导入文件中。', 'acf-options-importer' ), esc_html( $option_name ) ) . '</p>';
				}
			}

			$this->clean_up();
			echo '<p>' . __( '完成了，去看看你的主题设置吧~', 'acf-options-importer' ) . ' <a href="' . add_query_arg(array('page' => 'appbeebee'), admin_url('admin.php')) . '">' . __( '前往主题设置', 'acf-options-importer' ) . '</a>' . '</p>';
		}
	}


	/**
	 * Run a series of checks to ensure we're working with a valid JSON export.
	 *
	 * @return bool true if the file and data appear valid, false otherwise.
	 */
	private function run_data_check() {
		if ( empty( $this->import_data['version'] ) ) {
			$this->clean_up();
			return $this->error_message( __( '抱歉，出现了一个错误。此文件可能不包含数据或已损坏。', 'acf-options-importer' ) );
		}

		if ( $this->import_data['version'] < $this->min_version ) {
			$this->clean_up();
			return $this->error_message( sprintf( __( '此版本的导入程序不支持此JSON文件（版本%s）。请在源代码中更新该插件，或将该插件的旧版本下载到此安装中。', 'acf-options-importer' ), intval( $this->import_data['version'] ) ) );
		}

		if ( $this->import_data['version'] > self::VERSION ) {
			$this->clean_up();
			return $this->error_message( sprintf( __( '此JSON文件（版本%s）来自此插件的较新版本，可能不兼容。请更新这个插件。', 'acf-options-importer' ), intval( $this->import_data['version'] ) ) );
		}

		if ( empty( $this->import_data['options'] ) ) {
			$this->clean_up();
			return $this->error_message( __( '抱歉，出现了一个错误。此文件似乎有效，但似乎没有任何选项。', 'acf-options-importer' ) );
		}

		return true;
	}


	private function transient_key() {
		return sprintf( $this->transient_key, $this->file_id );
	}


	private function clean_up() {
		delete_transient( $this->transient_key() );
	}


	/**
	 * A helper method to keep DRY with our error messages. Note that the error messages
	 * must be escaped prior to being passed to this method (this allows us to send HTML).
	 *
	 * @param  string $message The main message to output.
	 * @param  string $details Optional. Additional details.
	 * @return bool false
	 */
	private function error_message( $message, $details = '' ) {
		echo '<div class="error"><p><strong>' . $message . '</strong>';
		if ( ! empty( $details ) ) {
			echo '<br />' . $details;
		}
		echo '</p></div>';
		return false;
	}
}

ACF_Options_Importer::instance();

endif;
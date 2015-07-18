<?php
/*
Plugin Name: WP Taxonomy Import
Version: 1.0.1
Author: Nakashima Masahiro
Author URI: http://www.kigurumi.asia/
Description: This is a plug-in allowing the user to create large amount of taxonomies. This plugin based on "Batch categories import" https://wordpress.org/plugins/batch-category-import/.
Text Domain: wti
Domain Path: /languages/
*/

if ( !class_exists( "WPTaxonomyImport" ) ) {
	class WPTaxonomyImport {
		protected $textdomain = 'wti';

		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_taxonomy_import_menu' ) );
			load_plugin_textdomain( $this->textdomain, false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		function admin_taxonomy_import_menu() {
			require_once ABSPATH . '/wp-admin/includes/admin.php';
			add_options_page(
				'WP Taxonomy Import', //ページのタイトル
				'Taxonomy Import', //管理画面のメニュー
				'manage_options', //ユーザーレベル
				__FILE__, //URLに入る名前
				array( $this, 'form' ) //機能を提供する関数
			);
		}

		private function create_taxonomy( $line, $delimiter, $target_taxonomy = 'category' ) {

			$created_categories = array();
			$parent_id = 0;
			$category_tree = explode( '->', $line );

			foreach ( $category_tree as $category ) {
				if ( strlen( trim( $category ) ) == 0 )
					break;

				if ( strpos( $category, $delimiter ) !== false ) {
					$category = explode( $delimiter, $category );
					$category_name = $category[0];
					$category_slug = $category[1];
					$category_description = ( isset( $category[2] ) ? substr( $category[2], 1, -1 ) : '' );
				}
				else {
					$category_name = $category;
					$category_slug = $category;
					$category_description = '';
				}

				$existing_category = term_exists( $category_name, $target_taxonomy );

				if ( is_array( $existing_category ) ) {
					$parent_id = ( (int) $existing_category['term_id'] );
				}
				else if ( $existing_category ) {
						$parent_id = $existing_category;
					}
				else if ( $existing_category == false ) {
						$category_params = array(
							'description' => $category_description,
							'slug'    => $category_slug,
							'parent'   => ( isset( $parent_id ) ? $parent_id : 0 )
						);

						$result = wp_insert_term( $category_name, $target_taxonomy, $category_params );

						if ( is_wp_error( $result ) ) {
							return die( "$catname produced this -> ".$result->get_error_message() );
						}
						$created_categories[] = $category_name;
					}

			}

			return $created_categories;
		}

		public function form() {

			if ( isset( $_POST['submit'] ) ) {
				$delimiter = strlen( trim( $_POST['delimiter'] ) ) != 0 ? $_POST['delimiter'] : "$";
				$target_taxonomy = esc_html( $_POST['target_taxonomy'] );
				$textarea = explode( PHP_EOL, $_POST['bulkCategoryList'] );

				foreach ( $textarea as $line ) {
					$result[] = $this->create_taxonomy( $line , $delimiter, $target_taxonomy );
				}
				echo "<div id='message' class='updated fade'><p><strong>Request returned with the following result: </strong><br />";
				$response = "";
				foreach ( $result as $line => $categories_created ) {
					if ( !$categories_created || empty( $categories_created ) )
						$response .= "#$line Could not create category.<br />";
					else {
						$response .= "#$line Created: ";
						foreach ( $categories_created as $category ) {
							$response .= "$category ";
						}
						$response .= "<br />";
					}
				}
				echo $response;
				echo "</p></div>";
			}

			wp_enqueue_script( 'jquery' );
?>
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/wp-taxonomy-import/css/style.css" type="text/css"/>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/wp-taxonomy-import/treeview.js"></script>

	<div id="formLayer">
		<h2>Taxonomy Import</h2>
		<form name="bulk_categories" action="" method="post">
			<span class=""><?php _e('Enter the taxonomy you want to add.', $this->textdomain); ?></span><br>
			<br/>

			<span class=""><?php _e('If you want to make a hierarchy, put a -> between the taxonomy and the sub-taxonomy in one line.', $this->textdomain); ?></span>
			<br/>

			<?php _e('Example', $this->textdomain); ?> : <b class="">Level A -> Level B -> Level C</b>
			<br/><br/>

			<span class=""><?php _e('Define a delimiter here to split the taxonomy name and slug. (default: $)', $this->textdomain); ?></span><input type="text" id="delimiter" name="delimiter" maxlength="2" size="2" onchange="validation(this);" placeholder="$" />
			<br/>
			<?php _e('Example', $this->textdomain); ?> : <b class="">Level A -> Level B$level-b1 -> Level C$level-c1</b>

<hr>
<b class=""><?php _e('Secelet Target Taxonomy', $this->textdomain); ?> : </b>
<select name="target_taxonomy" >
<?php
//タクソノミーを取得
$custom_taxonomies = get_taxonomies( array(), "objects" );
foreach ( $custom_taxonomies as $key => $taxonomy ) :
?>
<<option value="<?php echo $taxonomy->name ?>"><?php echo $taxonomy->name ?></option>
<?php endforeach; ?>
</select><br>
			<textarea id="bulkCategoryList" name="bulkCategoryList" rows="20" style="width: 100%;"><?php echo isset( $_POST['bulkCategoryList'] ) ? $_POST['bulkCategoryList'] : ''; ?></textarea>
			<br/>

			<div id="displayTreeView" name="displayTreeView" style="display:none;">
				<ul id="treeView" name="treeView" class="tree"></ul>
			</div>

			<p class="submit">
				<input type="button" id="preview" name="preview" value="<?php _e('Preview', $this->textdomain); ?>" onclick="treeView();"/>
				<input type="button" id="closePreview" name="closePreview" value="<?php _e('Close Preview', $this->textdomain); ?>" style="display:none;" onclick="hideTreeView();"/>
				<input type="submit" id="submit" name="submit" value="<?php _e('Add Taxonomies', $this->textdomain); ?>"/>
			</p>
		</form>
	</div>
<?php
		}
	}
	$WPTaxonomyImport = new WPTaxonomyImport();
}

<?php

/*
Plugin Name: Word Filter
Description: A plugin to filter unwanted the words from posts
Version: 1.0
Author: Hovhannes Verdyan
*/

if ( ! defined( 'ABSPATH' ) ) exit; //Exit of accessed directly

class WordFilter {

	public function __construct()
	{
		add_action('admin_menu', array($this, 'ourMenu'));
	}

	public function ourMenu()
	{
		$mainPageHook = add_menu_page('Words to filter', 'Word Filter', 'manage_options', 'wordfilter', array($this, 'wordFilterPage'), 'dashicons-megaphone', 4);
        add_submenu_page('wordfilter', 'Words to filter', 'Words List', 'manage_options', 'wordfilter', array($this, 'wordFilterPage'));
        add_submenu_page('wordfilter', 'Word Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'wordFilterOptions'));
        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
	}

    public function mainPageAssets()
    {
        wp_enqueue_style('filterAdminCSS', plugin_dir_url(__FILE__) . 'style.css');
    }

	public function wordFilterPage()
	{ ?>
		<div class="wrap">
            <h1>Word Filter</h1>
            <?php if ( $_POST['justsubmitted'] == "true" ) $this->handleForm() ?>
            <form method="POST">
                <input type="hidden" name="justsubmitted" value="true">
                <?php  wp_nonce_field('saveFilterWord', 'saveFilter') ?>
                <label for="plugin_words_to_filter"><p>Enter comma separated words to filter</p></label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" placeholder="bed, pit, slow, fast...">
                        <?php echo trim(esc_textarea(get_option('plugin_words_to_filter'))) ?>
                    </textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>

	<?php }

    public function handleForm()
    {
        if ( wp_verify_nonce($_POST['saveFilter'], 'saveFilter') AND current_user_can('manage_options'))
        {
	        update_option( 'plugin_words_to_filter', htmlspecialchars(strip_tags($_POST['plugin_words_to_filter'] ))); ?>
            <div class="updated">Your filter words were saved</div>
        <?php } else
        { ?>
            <div class="error">You don't have permmissiona to perform that action</div>
        <?php }
    }

    public function wordFilterOptions()
	{ ?>
		Hello World Second TIme!

	<?php }

}

$wordFilter = new WordFilter();
<?php
class Mobi2GoAdminPage {
    private $options;

    public function __construct() {
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    public function add_page() {
        add_menu_page(
            'Mobi2Go Settings',
            'Mobi2Go Settings',
            'administrator',
            'mobi2go',
            array($this, 'page'),
            plugin_dir_url(__FILE__) . 'images/mobi2go.png'
        );
    }

    public function page_init() {
        register_setting(
            'mobi2go',
            'mobi2go-settings',
            array($this, 'sanitize')
        );

        add_settings_section(
            'mobi2go-settings-section',
            '',
            array($this, 'print_section_info'),
            'mobi2go'
        );

        add_settings_field(
            'site',
            'Site Name',
            array($this, 'sitename_callback'),
            'mobi2go',
            'mobi2go-settings-section'
        );

        add_settings_field(
            'container',
            'Container',
            array($this, 'container_callback'),
            'mobi2go',
            'mobi2go-settings-section'
        );
    }

    public function page() {
        $this->options = get_option('mobi2go-settings');
        ?>
        <div class="wrap">
            <img src="<?php echo plugin_dir_url(__FILE__) . 'images/Mobi2Go-banner.png' ?>" />
            <h2>Mobi2Go Settings</h2>
            <form method="post" action="options.php">
                <?php
                    settings_fields('mobi2go');
                    do_settings_sections('mobi2go');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function print_section_info() {
        echo '<p class="description">';
        echo 'To use this plugin you will first need to set up an account for Mobi2go,';
        echo '<a href="http://www.mobi2go.com/signup?utm_source=wordpress&utm_medium=installation&utm_campaign=wordpress-plugin" target="_blank">here</a>.<br />';
        echo 'Then enter in your site name (eg. examplesite).';
        echo '</p>';
        echo '<p class="description">';
        echo 'Create a new page and add this tag [mobi2go] to the content and publish the page.<br />';
        echo 'Mobi2Go will now be on that page.';
        echo '</p>';
    }

    public function sitename_callback() {
        printf(
            '<input type="text" id="site" name="mobi2go-settings[site]" value="%s" />',
            empty($this->options['site']) ? '' : $this->options['site']
        );
        echo '<p class="description">
            Site name from console.
        </p>';
    }

    public function container_callback() {
        printf(
            '<input type="text" id="container" name="mobi2go-settings[container]" value="%s" />',
            empty($this->options['container']) ? 'mobi2go-ordering' : $this->options['container']
        );

        echo '<p class="description">
            ID of div to insert mobi2go into (The div will be created by the plugin).
        </p>';
    }

    public function sanitize($input) {
        $clean = array();

        foreach ($input as $key => $value) {
            $clean[$key] = (string) strip_tags($value);
        }

        return $clean;
    }
}

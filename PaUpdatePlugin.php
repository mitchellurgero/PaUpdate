<?php
/*
Plugin to allow users to export and import their following list.
Looks at ALL local posts that come in the moment they come in and adds a Image URL to the end of it. (Eventually it will actually attach an image to the notice.)
Built by: Mitchell Urgero (@loki@urgero.org) <info@urgero.org>
*/

if (!defined('STATUSNET')) {
    exit(1);
}
class PaUpdatePlugin extends Plugin
{
	public function initialize()
    {
    	return true;
    }
    static function settings($setting)
	{
		// config.php settings override the settings in this file
		$configphpsettings = common_config('site','paupdate') ?: array();
		foreach($configphpsettings as $configphpsetting=>$value) {
			$settings[$configphpsetting] = $value;
		}
		if(isset($settings[$setting])) {
			return $settings[$setting];
		}
		else {
			return false;
		}
	}
	public function onPluginVersion(array &$versions)
    {
        $versions[] = array('name' => 'paUpdate',
            'version' => '1.0',
            'author' => 'Mitchell Urgero <info@urgero.org>',
            'homepage' => 'https://github.com/mitchellurgero/PaUpdate',
            'rawdescription' => _m('Admin front end to check pa git for updates.'), );

        return true;
    }
    public function onRouterInitialized($m)
    {
    	 $m->connect('panel/paupdate',
            array('action' => 'paupdatesettings'));	
    }
    public function onEndAdminPanelNav($action)
    {
        $action_name = $action->trimmed('action');

        $action->out->menuItem(common_local_url('paupdatesettings'),
            // TRANS: Poll plugin menu item on user settings page.
            _m('MENU', 'pA Updates'),
            // TRANS: Poll plugin tooltip for user settings menu item.
            _m('pA Update Checker'),
            $action_name === 'paupdatesettings');

        return true;
    }
}
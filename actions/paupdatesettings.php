<?php

if (!defined('STATUSNET') && !defined('LACONICA')) {
    exit(1);
}

class PaUpdateSettingsAction extends SettingsAction
{
    /**
     * Title of the page.
     *
     * @return string Page title
     */
    public function title()
    {
        // TRANS: Page title.
        return _m('postActiv Update Checker');
    }

    /**
     * Instructions for use.
     *
     * @return string Instructions for use
     */
    public function getInstructions()
    {
        // TRANS: Page instructions.
        return _m('Check to see if you need to update postActive. (Note: This plugin does not actually do the update due to the possible complexity of future updates.)');
    }

    /**
     * Show the form for FollowExport.
     */
    public function showContent()
    {
        $user = common_current_user();
        $form = new PaUpdateForm($this);
        $form->show();
    }

    /**
     * Handler method.
     *
     * @param array $argarray is ignored since it's now passed in in prepare()
     */
    public function handlePost()
    {
        $user = common_current_user();
        // TRANS: Confirmation shown when user profile settings are saved.
        $this->showForm(_('Checked updates!'), true);

        return;
    }
}

class PaUpdateForm extends Form
{
	public $profiles = null;
	public $users_stripped = null;
    public function __construct($out)
    {
        parent::__construct($out);
        
    }
    /**
     * Visible or invisible data elements.
     *
     * Display the form fields that make up the data of the form.
     * Sub-classes should overload this to show their data.
     */
    public function formData()
    {
    	$note = null;
    	$c = htmlspecialchars(shell_exec("git fetch && git status"));
    	$c3 = htmlspecialchars(shell_exec("git config --get remote.origin.url"));
		if (strpos($c, 'no changes added to commit') !== false) {
			$note = '<p>Seems you have made changes to this installation. So we cannot check for updates.</p>';
		}elseif (strpos($c, 'behind') !== false) {
			$note = "<p>There is a new update! Please check <a href=\"".$c3."\">here for update info.</a></p>";
			$c2 = htmlspecialchars(shell_exec("git fetch && git diff origin/master"));
			$note .= "<p>Otherwise, here are current changes made:<br/><textarea style=\"width:100%;\" readonly>".$c2."</textarea></p>";
		} elseif (strpos($c, 'up-to-date') !== false) {
			$note =  '<p><b>postActiv is up-to-date!</b></p>';
		} else {
			$note =  '<p> There was an error processing the update request:<p>';
			$note .=  '<pre>'.$c.'</pre>';
		}
		$this->elementStart('fieldset');
        $this->elementStart('ul', 'form_data');
        $this->raw($note);
        $this->elementEnd('ul');
        $this->elementEnd('fieldset');
        $this->elementStart('fieldset');
        $this->elementStart('ul', 'form_data');
        $this->raw("
        	<b>Please note:</b>
        	<p>This plugin can only <i>check</i> for updates via git. It will NOT install them for you.</p>
			<p>This plugin will use git to check the same branch used during you initial install. If git does not work for some reason, you will get an error message about it.</p>
        ");
        $this->elementEnd('ul');
        $this->elementEnd('fieldset');
    }

    /**
     * Buttons for form actions.
     *
     * Submit and cancel buttons (or whatever)
     * Sub-classes should overload this to show their own buttons.
     */
    public function formActions()
    {
        $this->submit('check_updates',_('Check for Updates'));
    }

    /**
     * ID of the form.
     *
     * Should be unique on the page. Sub-classes should overload this
     * to show their own IDs.
     *
     * @return int ID of the form
     */
    public function id()
    {
        return 'paupdates_form';
    }

    /**
     * Action of the form.
     *
     * URL to post to. Should be overloaded by subclasses to give
     * somewhere to post to.
     *
     * @return string URL to post to
     */
    public function action()
    {
        return common_local_url('paupdatesettings');
    }

    /**
     * Class of the form. May include space-separated list of multiple classes.
     *
     * @return string the form's class
     */
    public function formClass()
    {
        return 'form_settings';
    }
    /**
     * Get profiles.
     *
     * @return array Profiles
     */
}

<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 11/22/13
 * @Purpose: Logout controller
 * @Version: 1.0
 */

/**
 * @Purpose: Logout Controller
 */
class timeclock_logout extends controller {
    /**
     * @Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->system_di->config->timeclock_subdirectories . '_' . $dependency);
        }
    }
    
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->model_loggedIn->status();
    }

    /**
     * @Purpose: Default function to be run when class is called
     */
    public function index() {
        $this->load_dependencies(array('renderPage', 'loggedIn'));
        $this->system_di->template->login_failed = '';

        if ($this->is_logged_in()) {
            $this->model_loggedIn->logout();
        }

        $this->system_di->template->title = 'TimeClock | Sign In';
        $parse = 'login';
        $full_page = False;

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
}

//End File

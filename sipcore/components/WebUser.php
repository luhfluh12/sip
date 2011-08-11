<?php

class WebUser extends CWebUser {

    protected $_access = array();
    protected $_model = null;

    /**
     * Checks if the logged in account has access to the requestied action.
     * To add a param (value in {@link Authorization}), use ":". 
     * Note that, "addMark:0" is the same as "addMark". 
     * @example viewMarks:3
     * @example admin (same as "admin:0")
     * @param string $operation The action(s) to check
     */
    public function checkAccess($operation, $params=array(), $allowCache=true) {
        // not available for guests
        if ($this->getIsGuest() === true)
            return false;
        // get $action and $value
        if (strpos($operation, ':') !== false) {
            list($action, $value) = explode(':', $operation);
        } else {
            $action = $operation;
            $value = 0;
        }
        // look for caches
        if (isset($this->_access[$action][$value]))
            return $this->_access[$action][$value];
        if ($action != 0 && isset($this->_access[$action][0]))
            return $this->_access[$action][0];
        // if no cache found, make the check and cache and return the result:
        return $this->_access[$action][$value] = Authorization::model()->authExists($this->getId(), $action, $value);
    }
    
    /**
     * Get the current user's Account model. Cached for the request.
     * @return Account The logged in account model. 
     */
    public function model() {
        if ($this->_model !== null)
            return $this->_model;
        return $this->_model = Account::model()->findByPk($this->getId());
    }

}


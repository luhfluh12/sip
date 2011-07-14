<?php

class WebUser extends CWebUser {
    protected $_access=array();
    protected $_model=null;
    /**
     * Checks if the logged in account has access to the requestied action. 
     * To add more actions, with "AND" condition separate them with "&". 
     * To add more actions, with "OR" condition separate them with "|". 
     * "OR" conditions are parsed first (have priority). 
     * To add a param (value in {@link Authorization}), use ":". 
     * Note that, "addMark:0" is the same as "addMark". 
     * @example viewMarks:3
     * @example addMarkClass:9&addMarkSubject:4|addMark:8
     * @param string $operation The action(s) to check
     */
    public function checkAccess($operation, $params=array(), $allowCache=true) {
        // not available for guests
        if ($this->getIsGuest()===true)
                return false;
        // make the actual check
        $actions = explode("&", $operation);
        foreach ($actions as $action) {
            $result = $this->checkAction($action);
            if ($result === false)
                return false;
        }
        return $result;
    }

    protected function checkAction($operation) {
        if (strpos($operation, '|') !== false) {
            $actions = explode('|', $operation);
            foreach ($actions as $action) {
                $result = $this->checkAction($action);
                if ($result === true)
                    return true;
            }
            return $result;
        } else {
            if (strpos($operation, ':') !== false) {
                list($action, $value) = explode(':', $operation);
            } else {
                $action = $operation;
                $value = 0;
            }
            if (isset($this->_access[$action][$value]))
               return $this->_access[$action][$value];
            if ($action!=0 && isset($this->_access[$action][0]))
               return $this->_access[$action][0];
            // if no cache found, make the check and return the result:
            return $this->_access[$action][$value] = Authorization::model()->authExists($this->getId(), $action, $value);
       }
    }
    public function model() {
        if ($this->_model!==null)
                return $this->_model;
        return $this->_model = Account::model()->findByPk($this->getId());
    }

}


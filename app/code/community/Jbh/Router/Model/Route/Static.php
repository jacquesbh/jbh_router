<?php
/**
 * This file is part of Jbh_Router for Magento.
 *
 * @license MIT (https://raw.github.com/jacquesbh/jbh_router/master/LICENSE)
 * @category Jbh
 * @package Jbh_Router
 * @copyright Copyright (c) 2012 Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 */

/**
 * Static router
 *
 * @category    Jbh
 * @package     Jbh_Router
 * @author      Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 *
 * @see Zend_Controller_Router_Route_Static
 */
class Jbh_Router_Model_Route_Static extends Jbh_Router_Model_Route_Abstract
                                    implements Jbh_Router_Model_Route_Interface
{
    /**
     * Configure the router with data used for matching path
     * @return Jbh_Router_Model_Route_Abstract
     */
    public function configure()
    {
        if ($this->_routerObject === null) {
            $this->_routerObject = new Zend_Controller_Router_Route_Static($this->getRoute(), $this->getDefaults());
        }
        return $this;
    }
}

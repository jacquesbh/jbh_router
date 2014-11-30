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
 * Regex Route.
 *
 * @category    Jbh
 * @package     Jbh_Router
 * @author      Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 */
class Jbh_Router_Controller_Router_Route_Regex extends Zend_Controller_Router_Route_Regex
{

    /**
     * Set the reverse
     * @param string $reverse
     * @return Jbh_Router_Controller_Router_Route_Regex
     */
    public function setReverse($reverse)
    {
        $this->_reverse = $reverse;
        return $this;
    }

}

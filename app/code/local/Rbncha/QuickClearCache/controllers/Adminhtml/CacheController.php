<?php

/**
 * Cache Adminhtml Controller
 *
 * @category   Rbncha
 * @package    Rbncha_QuickClearCache
 * @author     Rubin Shrestha <rubin.sth@gmail.com>
 */

require_once('Mage'.DS.'Adminhtml'.DS.'controllers'.DS.'CacheController.php');

class Rbncha_QuickClearCache_Adminhtml_CacheController extends Mage_Adminhtml_CacheController
{

	/**
	 * Clears all types of cache data
	 */
	public function qcc1Action()
	{
		$types = array('config', 'layout', 'block_html', 'translate', 'collections', 'eav', 'config_api', 'config_api2');

		$updatedTypes = 0;
        if (!empty($types)) {
            foreach ($types as $type) {
                $tags = Mage::app()->getCacheInstance()->cleanType($type);
                Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $type));
                $updatedTypes++;
            }
        }

        //Clear Full Page Cache
        if (Mage::helper('pagecache')->isEnabled()) {
            Mage::helper('pagecache')->getCacheControlInstance()->clean();
        }



        if ($updatedTypes > 0) {
            $this->_getSession()->addSuccess(Mage::helper('adminhtml')->__("All caches cleared including FPC, Configuration, Layouts, Blocks HTML output, Translations, Collections Data, EAV types and attributes, Web Services Configuration and Web Services Configuration."));
        }

        $this->_redirectReferer();
	}

	/**
	 * Clears Magento Storage cache data
	 */
	public function qcc2Action()
	{
		Mage::app()->cleanCache();
        Mage::dispatchEvent('adminhtml_cache_flush_system');
        $this->_getSession()->addSuccess(Mage::helper('adminhtml')->__("The Magento cache storage has been flushed."));

        $this->_redirectReferer();
	}

	/**
	 * Clears cache storage data
	 */
	public function qcc3Action()
	{
		Mage::dispatchEvent('adminhtml_cache_flush_all');
        Mage::app()->getCacheInstance()->flush();
        $this->_getSession()->addSuccess(Mage::helper('adminhtml')->__("The cache storage has been flushed."));

        $this->_redirectReferer();
	}
}
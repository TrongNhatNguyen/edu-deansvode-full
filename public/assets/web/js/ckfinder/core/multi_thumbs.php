<?php

/**
 * Mode create muiltiple thumbs
 * 
 * @author Phước Đại
 * @since 10/2014
 */

class Ckfinder_BigB_Thumbs
{
	private static $instance = null;
	private $baseUrl = '';
	private $configs = array();
	private $_currentFolder = null;
	
	private function __construct()
	{
		if (isset($GLOBALS['config']['multi_thumbs_path']))
		{
			$this->baseUrl = CKFinder_Connector_Utils_FileSystem::combinePaths($_SERVER['DOCUMENT_ROOT'], $GLOBALS['config']['multi_thumbs_path']);
		}
		
		$this->_getConfigs();
		$this->_currentFolder = & CKFinder_Connector_Core_Factory::getInstance("Core_FolderHandler");
	}
	
	
	public static function getInstance()
	{
		if (self::$instance === null)
			self::$instance = new static();
		
		return self::$instance;
	}
	
	
	/**
	 * Create multi thumbs form path
	 * 
	 * @param string $path Path of image upload
	 */
	public function createThumbs($path)
	{
		if (empty($this->configs) || empty($this->baseUrl))
			return;
		
		$fileName = $this->_getFileName($path);
		
		if (empty($fileName))
			return;
		
		//resize image if required
		require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/Thumbnail.php";
		
		require_once dirname(__FILE__) . '/php_image_magician/php_image_magician.php';
		
		$magicianObj = new imageLib($path);
		$flag = false;

		foreach ($this->configs as $key => $val)
		{
			// folder to upload
			$pathThumb = $this->baseUrl . $key . '/' . $this->_currentFolder->getResourceTypeName();
			
			// create if not exitst
			if (! file_exists($pathThumb))
			{
				mkdir($pathThumb, $GLOBALS['config']['ChmodFolders']);
			}
			
			if (isset($_GET["currentFolder"]))
			{
				$currentFolder = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding((string)$_GET["currentFolder"]);
				$currentFolder = explode('/', $currentFolder);
				
				foreach ($currentFolder as $folder)
				{
					if (empty($folder))
						continue;

					$pathThumb = CKFinder_Connector_Utils_FileSystem::combinePaths($pathThumb, $folder);
	
					if (! file_exists($pathThumb))
					{
						mkdir($pathThumb, $GLOBALS['config']['ChmodFolders']);
					}
				}
			}
			
			// path of thumb image
			$pathThumb = CKFinder_Connector_Utils_FileSystem::combinePaths($pathThumb, $fileName);
			
			if (file_exists($pathThumb))
				continue;
				
			try{
				// Create thumb
				if ($flag) {
					$magicianObj->reset();
				}
				$magicianObj->setForceStretch(false);
				$magicianObj->resizeImage($val['maxWidth'], $val['maxHeight'], $this->_getOptionImage($val));
				$magicianObj->saveImage($pathThumb, $val['quality']);
			}
			catch(Exception $ex)
			{
				// Create thumbnail
				CKFinder_Connector_CommandHandler_Thumbnail::createThumb($path, $pathThumb, $val['maxWidth'], $val['maxHeight'], $val['quality'], true);
			}
			$flag = true;
		}
	}
	
	
	public function deleteThumbs($path)
	{
		if (empty($this->configs) || empty($this->baseUrl))
			return;
		
		$fileName = $this->_getFileName($path);
		
		if (empty($fileName))
			return;
		
		foreach ($this->configs as $key => $val)
		{
			// folder to upload
			$pathThumb = $this->baseUrl . $key . '/' . $this->_currentFolder->getResourceTypeName();
			
			if (isset($_GET["currentFolder"]))
			{
				$currentFolder = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding((string)$_GET["currentFolder"]);
				$pathThumb = CKFinder_Connector_Utils_FileSystem::combinePaths($pathThumb, $currentFolder);
			}
			
			$pathThumb =  CKFinder_Connector_Utils_FileSystem::combinePaths($pathThumb, $fileName);
			
			if (! file_exists($pathThumb))
			{
				continue;
			}
			
			@unlink($pathThumb);
		}
	}
	
	
	public function deleteFolder($path)
	{
		if (empty($this->configs) || empty($this->baseUrl))
			return;
	
		$fileName = $this->_getFileName(substr($path, 0, -1));
	
		if (empty($fileName))
			return;
	
		foreach ($this->configs as $key => $val)
		{
			// folder to upload
			$pathThumb = $this->baseUrl . $key . '/' . $this->_currentFolder->getResourceTypeName();
				
			if (isset($_GET["currentFolder"]))
			{
				$currentFolder = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding((string)$_GET["currentFolder"]);
			}
			else
			{
				$currentFolder = $fileName;
			}
				
			$pathThumb =  CKFinder_Connector_Utils_FileSystem::combinePaths($pathThumb, $currentFolder);
				
			if (! file_exists($pathThumb))
			{
				continue;
			}
				
			CKFinder_Connector_Utils_FileSystem::unlink($pathThumb);
		}
	}
	
	
	private function _getConfigs()
	{
		if (isset($GLOBALS['config']['multi_thumbs']) && is_array($GLOBALS['config']['multi_thumbs']))
		{
			$this->configs = $GLOBALS['config']['multi_thumbs'];

			foreach ($this->configs as $key => $val)
			{
				$path = $this->baseUrl . $key;
				
				if (! file_exists($path))
				{
					mkdir($path, $GLOBALS['config']['ChmodFolders']);
				}
				
				
			}
			
		}
	}
	
	
	private function _getFileName($path)
	{
		$explode = explode('/', str_replace("\\", "/", $path));
		return end($explode);
	}
	
	
	private function _getOptionImage($configs)
	{
		if (! isset($configs['option']))
			return 'auto';
	
		$arrayDefined = array('exact', 'portrait', 'landscape', 'auto', 'crop');
	
		if (in_array($configs['option'], $arrayDefined))
			return $configs['option'];
	
		return 'auto';
	}
}


/************** CAU HINH ******************
 * 
 * 1. core/connector/php/php5/CommandHandler/GetFiles.php - Đặt lệnh:
 * 
 * 			Ckfinder_BigB_Thumbs::getInstance()->createThumbs($_sServerDir . $file);
 * 
 * 	  ở cuối dòng: foreach ($files as $file)
 * 
 * 
 * 2. core/connector/php/php5/CommandHandler/FileUpload.php - Đặt lệnh:
 * 
 *			Ckfinder_BigB_Thumbs::getInstance()->createThumbs($sFilePath); 			
 * 
 * 	  ở dưới dòng: require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/Thumbnail.php";
 * 
 * 
 * 3. core/connector/php/php5/CommandHandler/DeleteFiles.php - Đặt lệnh:
 * 
 * 			Ckfinder_BigB_Thumbs::getInstance()->deleteThumbs($thumbPath);
 * 
 * 	  ở dưới dòng: @unlink($thumbPath);
 * 
 * 4. core/connector/php/php5/CommandHandler/DeleteFolder.php - Đặt lệnh:
 * 
 * 			Ckfinder_BigB_Thumbs::getInstance()->deleteFolder($folderServerPath);
 * 
 * 	  ở trên dòng: if (!CKFinder_Connector_Utils_FileSystem::unlink($folderServerPath)) {}
 */
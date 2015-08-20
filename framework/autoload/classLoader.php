<?php

class ClassLoader
{
    private $_fileExtension = '.php';
    private $_namespace = "Lp";
    private $_basePath;
    private $_namespaceSeparator = '\\';
    private $_name;
    private $_devDirRoot ="lp";

    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     * 
     * @param string $ns The namespace to use.
     */
    public function __construct($basePath = null)
    {
        $this->_basePath = $basePath;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * @return void
     */
    public function loadClass($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, lcfirst($namespace)) . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) .  $this->_fileExtension;
        $path = dirname($this->_basePath);
        $fileNameArray = explode("/", $fileName);
        $fileNameArrayLC = array();
        foreach ($fileNameArray as $v) {
            $fileNameArrayLC[] = lcfirst($v);
        }
        $fileNameArrayLC[0] = str_replace($this->_devDirRoot, ENVIRONMENT_DIR, $fileNameArrayLC[0]);
        $fileName = implode("/", $fileNameArrayLC);
        $name = $path."/".$fileName;
        if (file_exists($name)) {
            require_once($name);
        } else {
            $this->fallbackLoaderForControllers($name);
        }
    }

    private function fallbackLoaderForControllers($name) 
    {   
        $controllerName =  str_replace(".php", "Controller.php",$name);
        if(file_exists($controllerName)) {
            require_once($controllerName);
            return true;
        } else {
            throw new Exception("Internal Server Error", 500);
        }
        
        
    }
     /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     * 
     * @param string $sep The separator to use.
     */
    public function setNamespaceSeparator($sep)
    {
        $this->_namespaceSeparator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return void
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     * 
     * @param string $includePath
     */
    public function setIncludePath($includePath)
    {
        $this->_includePath = $includePath;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath
     */
    public function getIncludePath()
    {
        return $this->_includePath;
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     * 
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->_fileExtension = $fileExtension;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public function getFileExtension()
    {
        return $this->_fileExtension;
    }

}
?>
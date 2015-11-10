<?php

/**
 * This class translates the namespace into
 * file path.
 * Class ClassLoader
 * @author Prateek Nayak <prateek.1708@gmail.com>
 * @package Framework\AutoloadS
 */
class ClassLoader
{
    /**
     * File extension
     * @var string
     */
    private $_fileExtension = '.php';

    /**
     * Path to the root folder.
     * @var null|string
     */
    private $_basePath;

    /**
     * @var string
     */
    private $_namespaceSeparator = '\\';

    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     * 
     * @param string $basePath The namespace to use.
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
    public function unRegister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * @return void
     * @throws Exception
     */
    public function loadClass($className)
    {
        $className = ltrim($className, $this->_namespaceSeparator);
        if ($lastNsPos = strrpos($className, $this->_namespaceSeparator)) {
            $fileName  = substr($className, $lastNsPos + 1) .  $this->_fileExtension;
            $camelCaseDir =dirname($this->_basePath).DIRECTORY_SEPARATOR;
            foreach(explode($this->_namespaceSeparator,substr($className, 0, $lastNsPos)) as $part) {
                $camelCaseDir .= lcfirst($part).DIRECTORY_SEPARATOR;
            }
            $fullFileName = $this->iFileExists($camelCaseDir, $fileName);
            if (!is_null($fullFileName)) {
                require_once($fullFileName);
            } else {
                throw new \Exception("File Not found");
            }
        } else {
            throw new \Exception("Illegal namespace detected");
        }
    }

    /**
     * Check if file exists
     *
     * @param $dir
     * @param $fileName
     * @return null|string
     */
    private function iFileExists($dir, $fileName)
    {
        if (file_exists($dir.$fileName)) {
           return $dir.$fileName;
        } elseif (file_exists($dir.lcfirst($fileName))) {
           return $dir.lcfirst($fileName);
        }
        return null;
    }
}

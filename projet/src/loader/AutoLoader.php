<?php
namespace iutnc\deefy\loader;

class Autoloader
{
    protected $prefix;
    protected $baseDir;

    public function __construct($prefix, $baseDir)
    {
        $this->prefix = $prefix;
        $this->baseDir = $baseDir;
        
    }

    public function loadClass($className)
    {
        // Vérifie si la classe appartient au namespace spécifié (prefix)
        if (strpos($className, $this->prefix) === 0) {
            // Remplace le préfixe par le chemin de base et les séparateurs de classe par des séparateurs de fichiers
            $relativeClass = substr($className, strlen($this->prefix));
            $file = $this->baseDir .'/'. str_replace('\\', '/', $relativeClass). '.php';

            // Vérifie si le fichier existe et le charge
            if (is_file($file)) {
                require_once $file;
            }
        }
    }

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }
}
?>
<?php

namespace app\core;

use \PDO;



class Database
{
    public PDO $pdo;
    public array $migrations = [];
    public function __construct($config){
        $dsn = $config['dsn'];
        $user = $config['user'];
        $password = $config['password'];
        $this->pdo = new \PDO($dsn,$user,$password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    public function applyMigration(){
        $this->createMigrationTable();
        $appliedMigrations = $this->appliedMigration();
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files,$appliedMigrations);
        foreach ($toApplyMigrations as $migration){
            if($migration == '.' || $migration == '..'){
                continue;
            }
            $this->migrations[] = $migration;
            require Application::$ROOT_DIR . "/migrations/".$migration;
            $className = pathinfo($migration)['filename'];
            $instance = new $className();

            $this->log("Applying migration $migration".PHP_EOL);
            $instance->up();
            $this->log("Applied migration $migration".PHP_EOL);

        }
        if(!empty($this->migrations)){
            $this->saveMigration($this->migrations);
        }else{
            $this->log("All migrations are up-to-date");
        }
    }
    public function createMigrationTable(){
        $stmt = $this->pdo->prepare("CREATE TABLE IF NOT EXISTS migrations(
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            migration VARCHAR(255),
                            CREATED_AT  TIMESTAMP DEFAULT   CURRENT_TIMESTAMP 
                            )");
        $stmt->execute();
    }
    public function appliedMigration(){
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function saveMigration($migrations){
        $migrations = implode(",",array_map(fn($mig) => "('$mig')",$migrations));
        $stmt = $this->pdo->prepare("INSERT INTO migrations(migration)
                    VALUES $migrations");
        $stmt->execute();
    }

    public function log($message):void{
        echo $message;
    }
}
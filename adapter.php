<?php 

    class PrixEssence {
        /**
         * Some other stuff
        */
        public function persist(DataTimeInterface $now, callable $persister){
            $this->updatedAt = $now;
            $persister(json_encode($this)); // $data
        }
    }
    interface DataTimeInterface {
        
    }
    interface DriverInterface {
        public function doSave($param);
    }
    class DriverDatabaseExecutor implements DriverInterface{
        public function __construct(
            private Database $db,
            private $defaultSecondParam
        ){}
        public function doSave($param){
            return $this->db->doSave($param, $this->defaultSecondParam);
        }
    }

    class PrixEssenceRepository { // l'adapté
        public function __construct(
            private DataTimeInterface $now,
            private DriverInterface $driver // adapter à créer
        ){}

        public function save(PrixEssence $prixEssence){
            $driver = $this->driver;

            $prixEssence->persist(
                $this->now,
                function ($data) use ($driver){
                    $driver->doSave($data);
                }
            );
        }
    }


    class Database { // Client
        public function doSave(string $sqlQuery, PDO $connexion){
            $stmt = $connexion->createStatement($sqlQuery);
            $stmt->execute();
        }
    }

    // Créer un adapter entre PrixEssenceRepository et Database
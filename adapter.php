<?php 

    /**
     * Mise en place 
     *      1- 
     * 
     * 
     */

    class PrixEssence {
        /**
         * Some other stuff
        */
        public function __construct(
            private $updatedAt
        )
        {}
        public function persist(DataTimeInterface $now, callable $persister){
            $this->updatedAt = $now;
            $persister(json_encode($this)); // $data
        }
    }
    interface DataTimeInterface {
        
    }
    // interface DriverInterface {
    //     public function doSave($param);
    // }
    // class DriverDatabaseExecutor implements DriverInterface{
    //     public function __construct(
    //         private Database $db,
    //         private $defaultSecondParam
    //     ){}
    //     public function doSave($param){
    //         return $this->db->doSave($param, $this->defaultSecondParam);
    //     }
    // }

    interface DriverJSONInterface {
        public function JSONEncodeSave($data, $path);
    }
    class JSONPrixEssencePersister {
        public function __construct(
            private PrixEssence $prixEssence,
            private $path
        ){}
        public function JSONEncodeSave($prixEssence, $path){
            // $prix = json_encode($prixEssence);
            file_put_contents($path, $prixEssence);
        }
    }
    class PrixEssenceRepository { 
        public function __construct(
            private DataTimeInterface $now,
            private DriverJSONInterface $driver
        ){}

        public function save(PrixEssence $prixEssence){
            $driver = $this->driver;

            $prixEssence->persist(
                $this->now,
                function ($data) use ($driver){
                    $driver->JSONEncodeSave($data, $this->driver->path);
                }
            );
        }
    }


    // class Database { 
    //     public function doSave(string $sqlQuery, PDO $connexion){
    //         $stmt = $connexion->createStatement($sqlQuery);
    //         $stmt->execute();
    //     }
    // }

    $prixEssence = new PrixEssence(date('d m Y'));
    $prixEssenceRepository = new PrixEssenceRepository(date('d m Y'), $JSONPRixEssencePersister = new DriverJSONInterface());
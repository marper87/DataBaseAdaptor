<?php


/**
 * Classe para ligação a uma base de dados
 * PDO _ PHP
 *
 * @author Marcelo Pereira
 */


class DataBase
{
    const CONNECT_TIMEOUT = 5; // in seconds


    /**
     * Array of reserved words
     *
     * @var array
     */
    protected $reservedWords = ['where', 'tablename', 'orderby', 'groupby', 'selectfields']; //allways lowercase

    /**
     * Variável caminho BD
     * 
     * @var string
     * 
     */
    private $dsn;

    /**
     * Username BD
     * 
     * @var string
     */
    private $username;

    /**
     * Password BD
     * 
     * @var string
     */
    private $password;


    /**
     * Ligação BD
     * 
     * @var object
     * 
     */
    private $connection;

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $pdoOptions;





    /**
     * Construtor da classe
     *
     * @param string $dsn Host de ligação
     * @param string $username Username da BD
     * @param string $password Password da BD
     * 
     * @return void
     */
    public function __construct($dsn, $username, $password)
    {

        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_TIMEOUT => self::CONNECT_TIMEOUT
        ];

        $this->makeConnection();

    }

    /**
     * Criar ligação
     *
     * @return object
     */
    protected function makeConnection()
    {

        $this->connection = new PDO($this->dsn, $this->username, $this->password, $this->pdoOptions);

        return $this->connection;

    }





    /**
     * Verifica a ligação à BD
     *
     * @return boolean
     */
    public function isConnected()
    {
        return (boolean)$this->connection;

    }

    protected function unsetReservedWords(array $args)
    {

        $ret = [];

        foreach ($args as $k => $v) {

            if (!in_array(strtolower($k), $this->reservedWords)) {

                $ret[$k] = $v;

            }
        }

        return $ret;
    }


    public function insertQuery($args)
    {

        // insert into exemplo_pdo (campo1, campo2, campo3) VALUES ()
        $table = $args['tablename'];

        $query = "INSERT INTO " . $table;

        $fields = $this->unsetReservedWords($args);

        $fieldCount = 0;

        foreach ($fields as $key => $value) {

            if ($fieldCount === 0) {

                $query .= " (" . $key;

            } else {

                $query .= ", " . $key;

            }

            $fieldCount++;

        }




        $query .= ") VALUES (";

        $fieldCount = 0;

        foreach ($fields as $key => $value) {

            if ($fieldCount !== 0) {

                $query .= ", ";

            }

            $query .= "'" . $value . "'";
            $fieldCount++;
        }

        $query .= ")";

        return $query;
    }



    public function updateQuery($args)
    {

        // update exemplo_pdo SET campo1 ="XXXX", campo2 = "XXXX", campo3 = "XXXX"
        // Where 1=1 AND id = XX AND campo

        $tablename = $args['tablename'];

        $fields = $this->unsetReservedWords($args);

        $query = "UPDATE " . $tablename . " SET ";

        $fieldCount = 0;

        foreach ($fields as $key => $value) {

            if ($fieldCount === 0) {

                $query .= $key . "= '" . $value . "' ";

            } else {

                $query .= "," . $key . "= '" . $value . "' ";

            }

            $fieldCount++;
        }


        $argsWhere = $args['where'];

        if (count($args['where']) > 0) {

            $query .= " WHERE 1=1";

            foreach ($argsWhere as $key => $value) {

                $query .= " AND " . $key . "= '" . $value . "'";
            }

        } else {

            return false;
        }

        return $query;

    }

    public function deleteQuery($args)
    {
    //Delete from table where ....

        $table = $args['tablename'];

        $query = "DELETE FROM " . $table . " ";


        $argsWhere = $args['where'];

        if (count($args['where']) > 0) {

            $query .= " WHERE 1=1";

            foreach ($argsWhere as $key => $value) {

                $query .= " AND " . $key . "= '" . $value . "'";
            }

        } else {

            return false;
        }

        return $query;

    }

    public function selectQuery($args)
    {
        //SELECT campo1, campo2, campo3 FROM TABLE WHERE ID = XXX GROUP BY XXX ORDER BY XXX ASC

        $table = $args['tablename'];

        $selectFields = $args['selectFields'];

        $query = "SELECT ";

        $fieldCount = 0;

        foreach ($selectFields as $value) {

            if ($fieldCount === 0) {

                $query .= $value;
            } else {

                $query .= ", " . $value;

            }

            $fieldCount++;
        }

        $query .= " FROM " . $table . " ";


        $argsWhere = $args['where'];

        if (count($args['where']) > 0) {

            $query .= "Where 1=1 ";

            foreach ($argsWhere as $key => $value) {
                $query .= " AND " . $key . " = '" . $value . "'";
            }

        }

        $argsGroupBy = $args['groupby'];

        if (count($argsGroupBy) > 0) {

            $query .= " GROUP BY ";



            foreach ($argsGroupBy as $key => $value) {
                if ($key === 0) {
                    $query .= $value;
                } else {

                    $query .= ", " . $value;
                }


            }



        }



        return $query;

    }

}
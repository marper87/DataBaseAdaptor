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
     * Undocumented function
     *
     * @return boolean
     */
    public function isConnected()
    {
        return (boolean) $this->connection;

    }



}
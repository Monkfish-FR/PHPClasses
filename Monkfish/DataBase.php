<?php
	/**
	 * DataBase class - design pattern singleton
	 *
	 * Usage :
	 * <pre>
	 *     private $cnx; // connexion variable
	 *     $db = DataBase::getInstance();
	 *     $this->cnx = $db->getPDO();
	 * </pre>
	 *
	 * @link http://php.net/manual/en/book.pdo.php PDO class
	 */
	namespace Monkfish;
	use PDO;

	class DataBase {

		/**
		 * DataBase class instance
		 * @access private
		 * @static
		 * @var connexion
		 * @see getInstance
		 */
		private static $instance = NULL;

		/**
		 * Database type
		 * @access private
		 * @var string
		 * @see __construct
		 */
		private $db_type = DB_TYPE;

		/**
		 * Database host
		 * @access private
		 * @var string
		 * @see __construct
		 */
		private $db_host = DB_HOST;

		/**
		 * Database name
		 * @access private
		 * @var string
		 * @see __construct
		 */
		private $db_name = DB_BASE;

		/**
		 * Database username
		 * @access private
		 * @var string
		 * @see __construct
		 */
		private $db_user = DB_USER;

		/**
		 * Database user password
		 * @access private
		 * @var string
		 * @see __construct
		 */
		private $db_pwd = DB_PASS;

		/**
		  * PDO object
		  * @var PDO object
		  * @see __construct
		  */
		private $pdo;

		/**
		 * DataBase class constructor
		 *
		 * Run database connexion and stock it in the $pdo variable
		 * @access private
		 * @param void
		 */
		private function __construct() {
			try {
				$this->pdo = new PDO(
					$this->db_type . ':host=' . $this->db_host . '; dbname=' . $this->db_name,
					$this->db_user,
					$this->db_pwd,
					array(
						// PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
						PDO::ATTR_PERSISTENT => true
					)
				);

				$set = 'SET NAMES UTF8';
				$result = $this->pdo->prepare($set);
				$result->execute();
			}
			catch (PDOException $e) {
				echo '<div class="error">Erreur !: ' . $e->getMessage() . '</div>';
				die();
			}
		}

		/**
		 * If an instance exists, return it ; else create a new one
		 * @access public
		 * @static
		 * @param void
		 * @return $instance
		 */
		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Get the PDO object to manipulate the database
		 * @access public
		 * @param void
		 * @return $pdo
		 */
		public function getPDO() {
			return $this->pdo;
		}

	}
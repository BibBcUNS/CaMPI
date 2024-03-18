<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\rbac;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\caching\CacheInterface;
use yii\db\Connection;
use yii\db\Expression;
use yii\db\Query;
use yii\di\Instance;

use yii\rbac\Assignment;

/**
 * DbManager represents an authorization manager that stores authorization information in database.
 *
 * The database connection is specified by [[db]]. The database schema could be initialized by applying migration:
 *
 * ```
 * yii migrate --migrationPath=@yii/rbac/migrations/
 * ```
 *
 * If you don't want to use migration and need SQL instead, files for all databases are in migrations directory.
 *
 * You may change the names of the tables used to store the authorization and rule data by setting [[itemTable]],
 * [[itemChildTable]], [[assignmentTable]] and [[ruleTable]].
 *
 * For more details and usage information on DbManager, see the [guide article on security authorization](guide:security-authorization).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class LibraryDbManager extends \yii\rbac\DbManager
{
    const NO_LIBRARY = 0;

    public $library_id;

    protected $_checkAccessAssignments = [];

    public function __construct() {
        parent::__construct();
        if ($library = Yii::$app->session->get('library'))
            $this->library_id = $library->id;
        else 
            $this->library_id =  self::NO_LIBRARY;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssignments($userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return [];
        }

        $query = (new Query())
            ->from($this->assignmentTable)
            ->where([
                'user_id' => (string) $userId,
                'library_id'=> [$this->library_id,self::NO_LIBRARY]
            ]);

        $assignments = [];
        //var_dump($query->all($this->db));exit;
        foreach ($query->all($this->db) as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
            ]);
        }

        return $assignments;
    }

    /**
     * {@inheritdoc}
     */
    public function assign($role, $userId)
    {

        $assignment = new Assignment([
            'userId' => $userId,
            'roleName' => $role->name,
            'createdAt' => time(),
        ]);

        $this->db->createCommand()
            ->insert($this->assignmentTable, [
                'user_id' => $assignment->userId,
                'library_id' => $this->library_id,
                'item_name' => $assignment->roleName,
                'created_at' => $assignment->createdAt,
            ])->execute();

        unset($this->_checkAccessAssignments[(string) $userId]);
        return $assignment;
    }

    public function revoke($role, $userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return false;
        }
        
        unset($this->_checkAccessAssignments[(string) $userId]);
        return $this->db->createCommand()
            ->delete($this->assignmentTable, [
                'user_id'    => (string) $userId,
                'library_id' => $this->library_id,
                'item_name'  => $role->name])
            ->execute() > 0;
    }


    protected function isEmptyUserId($userId)
    {
        return !isset($userId) || $userId === '';
    }

    public static function userLibraries($user_id) {
        $all_libraries_permission = (new Query())
            ->from('auth_assignment')
            ->where([
                'user_id' => (string) $user_id,
                'library_id'=> self::NO_LIBRARY // Esto representa un permiso global (No está definido para una bibliteca específica)
            ])
            ->groupBy('library_id')
            ->count();

        // si tiene permiso para para todas las bibliotecas...
        if ($all_libraries_permission) {
            return (new Query())
            ->from('biblioteca')
            ->select('id as library_id')
            ->column();
        }

        return (new Query())
            ->from('auth_assignment')
            ->select('library_id')
            ->where([
                'user_id' => (string) $user_id,
            ])
            ->groupBy('library_id')
            ->column();
    }
}

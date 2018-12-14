<?php
/**
 * ILS Driver for Campi
 *
 * PHP version 5
 *
 * Copyright (C) Verus Solutions Pvt.Ltd 2010.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  ILS_Drivers
 * @author   Verus Solutions <info@verussolutions.biz>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:ils_drivers Wiki
 */
//namespace VuFind\ILS\Driver;
namespace campi\ILS\Driver;
use PDO, PDOException, VuFind\Exception\ILS as ILSException,VuFind\I18n\Translator\TranslatorAwareInterface;

/**
 * ILS Driver for Campi
 *
 * @category VuFind
 * @package  ILS_Drivers
 * @author   Verus Solutions <info@verussolutions.biz>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:ils_drivers Wiki
 */
class Campi extends \VuFind\ILS\Driver\AbstractBase  implements TranslatorAwareInterface
{
    use \VuFind\I18n\Translator\TranslatorAwareTrait;

    /**
     * Database connection
     *
     * @var PDO
     */
    protected $db;

    /**
     * Record loader
     *
     * @var \VuFind\Record\Loader
     */
    protected $recordLoader;

    /**
     * Constructor
     *
     * @param \VuFind\Record\Loader $loader Record loader
     */
    public function __construct(\VuFind\Record\Loader $loader)
    {
        $this->recordLoader = $loader;
    }

    /**
     * Initialize the driver.
     *
     * Validate configuration and perform all resource-intensive tasks needed to
     * make the driver active.
     *
     * @throws ILSException
     * @return void
     */
    public function init()
    {
        /*if (empty($this->config)) {
            throw new ILSException('Configuration needs to be set.');
        }

        try {
            $connectStr = 'mysql:host=' . $this->config['Catalog']['hostname'] .
                ' user=' . $this->config['Catalog']['user'] .
                ' dbname=' . $this->config['Catalog']['database'] .
                ' password=' . $this->config['Catalog']['password'].
                ' port=' . $this->config['Catalog']['port'];
	   */
	    

        $dsn='mysql:host=localhost;dbname=campi;charset=utf8';
        $usuario='campi';
        $pass='campi'; 

	    //$connectStr= 'mysql:host=localhost;dbname=campi;campi;campi';
            $this->db = new PDO($dsn, $usuario, $pass);
        }/* catch (PDOException $e) {
            throw $e;
        }
    }*/

    public function getConfig($function, $params = null)
    {
        return isset($this->config[$function]) ? $this->config[$function] : false;
    }

    /**
     * Get Holding
     *
     * This is responsible for retrieving the holding information of a certain
     * record.
     *
     * @param string $RecordID The record id to retrieve the holdings for
     * @param array  $patron   Patron data
     *
     * @throws \VuFind\Exception\Date
     * @throws ILSException
     * @return array           On success, an associative array with the following
     * keys: id, availability (boolean), status, location, reserve, callnumber,
     * duedate, number, barcode.
     */
     
    public function getHolding($id, array $patron = null)
    {
            // Retrieve record from index:
            $recordDriver = $this->getSolrRecord($id);
            return $this->getFormattedMarcDetails($recordDriver, 'MarcHoldings');
    }

    /**
     * Get a Solr record.
     *
     * @param string $id ID of record to retrieve
     *
     * @return \VuFind\RecordDriver\AbstractBase
     */
    protected function getSolrRecord($id)
    {
        // Add idPrefix condition
        $idPrefix = $this->getIdPrefix();
        return $this->recordLoader->load(strlen($idPrefix) ? $idPrefix . $id : $id);
    }

    /**
     * Get the ID prefix from the configuration, if set.
     *
     * @return string
     */
    protected function getIdPrefix()
    {
        return isset($this->config['settings']['idPrefix'])
            ? $this->config['settings']['idPrefix'] : null;
    }

    /**
     * This is responsible for retrieving the status or holdings information of a
     * certain record from a Marc Record.
     *
     * @param object $recordDriver  A RecordDriver Object
     * @param string $configSection Section of driver config containing data
     * on how to extract details from MARC.
     *
     * @return array An Array of Holdings Information
     */
    protected function getFormattedMarcDetails($recordDriver, $configSection)
    {
        $marcStatus = isset($this->config[$configSection])
            ? $this->config[$configSection] : false;
        if ($marcStatus) {
            $field = $marcStatus['marcField'];
            unset($marcStatus['marcField']);
            $result = $recordDriver->tryMethod(
                'getFormattedMarcDetails', [$field, $marcStatus]
            );
            // If the details coming back from the record driver include the
            // ID prefix, strip it off!
            $idPrefix = $this->getIdPrefix();
            if (isset($result[0]['id']) && strlen($idPrefix)
                && $idPrefix === substr($result[0]['id'], 0, strlen($idPrefix))
            ) {
                $result[0]['id'] = substr($result[0]['id'], strlen($idPrefix));
            }
            return empty($result) ? [] : $result;
        }
        return [];
    }

    /**
     * Get Patron Transactions
     *
     * This is responsible for retrieving all transactions (i.e. checked out items)
     * by a specific patron.
     *
     * @param array $patron The patron array from patronLogin
     *
     * @throws \VuFind\Exception\Date
     * @throws ILSException
     * @return array        Array of the patron's transactions on success.
     */
    public function getMyTransactions($patron)
    {
        $transactions = [];
        $PatId = $patron['cat_username'];
        $mainsql = "select c.due_date as due_date, c.status as status, c.ta_id " .
            "as ta_id, c.library_id as library_id, c.accession_number as " .
            "accession_number, v.cataloguerecordid as cataloguerecordid, " .
            "v.owner_library_id as owner_library_id, c.patron_id as " .
            "patron_id from document d,cat_volume v,cir_transaction c where " .
            "d.volume_id=v.volume_id and v.owner_library_id='1' and " .
            "c.accession_number=d.accession_number and " .
            "c.document_library_id=d.library_id and c.patron_id='" .
            $PatId . "' and c.status in('A','C')";
        try {
            $sqlStmt = $this->db->prepare($mainsql);
            $sqlStmt->execute();
        } catch (PDOException $e) {
            throw new ILSException($e->getMessage());
        }
        while ($row = $sqlStmt->fetch(PDO::FETCH_ASSOC)) {
            $countql = "select count(*) as total from cir_transaction c, " .
                "cir_transaction_renewal r where r.ta_id='" . $row['ta_id'] .
                "' and r.library_id='" . $row['library_id'] .
                "' and c.status='A'";
            try {
                $sql = $this->db->prepare($countql);
                $sql->execute();
            } catch (PDOException $e) {
                throw new ILSException($e->getMessage());
            }
            $RecordId = $row['cataloguerecordid'] . "_" . $row['owner_library_id'];
            $count = "";
            while ($srow = $sql->fetch(PDO::FETCH_ASSOC)) {
                $count = "Renewed = " . $srow['total'];
            }
            $transactions[] = ['duedate' => $row['due_date'] . " " . $count,
                'id' => $RecordId,
                'barcode' => $row['accession_number'],
                'renew' => $count,
                'reqnum' => null];
        }
        return $transactions;
    }

    /**
     * Get Status
     *
     * This is responsible for retrieving the status information of a certain
     * record.
     *
     * @param string $RecordID The record id to retrieve the holdings for
     *
     * @throws ILSException
     * @return mixed           On success, an associative array with the following
     * keys: id, availability (boolean), status, location, reserve, callnumber.
     */
    public function getStatus($RecordID)
    {
        $status = $this->getItemStatus($RecordID);
        if (!is_array($status)) {
            return $status;
        }
        // remove not needed entries within the items within the result array
        for ($i = 0; $i < count($status); $i++) {
            unset($status[$i]['number']);
            unset($status[$i]['barcode']);
            unset($status[$i]['library_id']);
        }
        return $status;
    }

    /**
     * Get Statuses
     *
     * This is responsible for retrieving the status information for a
     * collection of records.
     *
     * @param array $StatusResult The array of record ids to retrieve the status for
     *
     * @throws ILSException
     * @return array              An array of getStatus() return values on success.
     */
    public function getStatuses($StatusResult)
    {
        $status = [];
        foreach ($StatusResult as $id) {
            $status[] = $this->getStatus($id);
        }
        return $status;
    }

    /**
     * Patron Login
     *
     * This is responsible for authenticating a patron against the catalog.
     *
     * @param string $username The patron username
     * @param string $password The patron's password
     *
     * @throws ILSException
     * @return mixed          Associative array of patron info on successful login,
     * null on unsuccessful login.
     */
    public function patronLogin($username, $password)
    {
        $sql = "select p.id as patron_id, p.nombre as fname, p.apellido as lname, p.password_hash as user_password, p.email as email from persona p where p.username=:username and p.password_hash=sha1(:password)";

        try {
            $sqlStmt = $this->db->prepare($sql);
            $sqlStmt->execute([':username' => $username, ':password' => $password]);
        } catch (PDOException $e) {
            throw new ILSException($e->getMessage());
        }
        $row = $sqlStmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return [
            "id" => $row['patron_id'],
            "firstname" => $row['fname'],
            'lastname' => $row['lname'],
            'cat_username' => $username,
            'cat_password' => $password,
            'email' => $row['email'],
            'major' => null,
            'college' => null
        ];
    }

    /**
     * Patron Profile Update
     *
     * This is responsible for update user from patron.
     *
     * @param string $username The patron username
     * @param string $email The patron's email
     *
     * @throws ILSException
     * @return mixed          Associative array of patron info on successful login,
     * null on unsuccessful login.
     */
    public function getPatronByUsername($username)
    {
	$sql = "select p.id as patron_id, p.nombre as fname, p.apellido as lname, p.password_hash as user_password, p.email as email from persona p where p.username=:username";

        try {
            $sqlStmt = $this->db->prepare($sql);
            $sqlStmt->execute([':username' => $username]);
        } catch (PDOException $e) {
            throw new ILSException($e->getMessage());
        }
        $row = $sqlStmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return [
            //"id" => $row['patron_id'],
            "firstname" => $row['fname'],
            'lastname' => $row['lname'],
            'cat_username' => $username,
            'cat_password' => '',
            'email' => $row['email'],
            'major' => null,
            'college' => null
        ];
    }

    /**
     * Get New Items
     *
     * Retrieve the IDs of items recently added to the catalog.
     *
     * @param int $page    Page number of results to retrieve (counting starts at 1)
     * @param int $limit   The size of each page of results to retrieve
     * @param int $daysOld The maximum age of records to retrieve in days (max. 30)
     * @param int $fundId  optional fund ID to use for limiting results (use a value
     * returned by getFunds, or exclude for no limit); note that "fund" may be a
     * misnomer - if funds are not an appropriate way to limit your new item
     * results, you can return a different set of values from getFunds. The
     * important thing is that this parameter supports an ID returned by getFunds,
     * whatever that may mean.
     *
     * @throws ILSException
     * @return array       Associative array with 'count' and 'results' keys
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getNewItems($page, $limit, $daysOld, $fundId = null)
    {
        // Do some initial work in solr so we aren't repeating it inside this loop.
        $retVal[][] = [];

        $offset = ($page - 1) * $limit;
        $sql = "select cataloguerecordid,owner_library_id from cataloguerecord " .
            "where created_on + interval '$daysOld days' >= " .
            "current_timestamp offset $offset limit $limit";
        try {
            $sqlStmt = $this->db->prepare($sql);
            $sqlStmt->execute();
        }
        catch (PDOException $e) {
            throw new ILSException($e->getMessage());
        }

        $results = [];
        while ($row = $sqlStmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['cataloguerecordid'] . "_" . $row['owner_library_id'];
            $results[] = $id;
        }
        $retVal = ['count' => count($results), 'results' => []];
        foreach ($results as $result) {
            $retVal['results'][] = ['id' => $result];
        }
        return $retVal;
    }

    /**
     * Get Purchase History
     *
     * This is responsible for retrieving the acquisitions history data for the
     * specific record (usually recently received issues of a serial).
     *
     * @param string $id The record id to retrieve the info for
     *
     * @throws ILSException
     * @return array     An array with the acquisitions data on success.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getPurchaseHistory($id)
    {
        // TODO
        return [];
    }

    /**
     * Support method to get information about the items attached to a record
     *
     * @param string $RecordID Record ID
     *
     * @return array
     */
     
    protected function getItemStatus($RecordID)
    {
        $StatusResult = [];
    	return [];
        $pieces = explode("_", $RecordID);
        $CatId = $pieces[0];
        $LibId = $pieces[1];
        //SQL Statement
        $mainsql = "select d.status as status, d.location_id as location_id, " .
            "d.call_number as call_number, d.accession_number as accession_number," .
            " d.barcode as barcode, d.library_id as library_id from " .
            "document d,cat_volume v where d.volume_id=v.volume_id and " .
            "v.cataloguerecordid='" . $CatId . "' and v.owner_library_id=" . $LibId;

        try {
            $sqlSmt = $this->db->prepare($mainsql);
            $sqlSmt->execute();
        } catch (PDOException $e) {
            throw new ILSException($e->getMessage());
        }
        $reserve = 'N';
        while ($row = $sqlSmt->fetch(PDO::FETCH_ASSOC)) {
            switch ($row['status']) {
            case 'B':
                $status = "Available";
                $available = true;
                $reserve = 'N';
                break;
            case 'A':
                // Instead of relying on status = 'On holds shelf',
                // I might want to see if:
                // action.hold_request.current_copy = asset.copy.id
                // and action.hold_request.capture_time is not null
                // and I think action.hold_request.fulfillment_time is null
                $status = "Checked Out";
                $available = false;
                $reserve = 'N';
                break;
            default:
                $status = "Not Available";
                $available = false;
                $reserve = 'N';
                break;
            }
            $locationsql = "select location from location where location_id='" .
                $row['location_id'] . "' and library_id=" . $row['library_id'];
            try {
                $sqlSmt1 = $this->db->prepare($locationsql);
                $sqlSmt1->execute();
            } catch (PDOException $e1) {
                throw new ILSException($e1->getMessage());
            }
            $location = "";
            while ($rowLoc = $sqlSmt1->fetch(PDO::FETCH_ASSOC)) {
                $location = $rowLoc['location'];
            }
            $StatusResult[] = ['id' => $RecordID,
                'status' => $status,
                'location' => $location,
                'reserve' => $reserve,
                'callnumber' => $row['call_number'],
                'availability' => $available,
                'number' => $row['accession_number'],
                'barcode' => $row['barcode'],
                'library_id' => $row['library_id']];
        }
        return $StatusResult;
    }
    
        public function changePassword($details)
        {
            $username = $details['patron']['id'];
            $newPassword = $details['newPassword'];
            $sql = "UPDATE  persona SET  password_hash = SHA1(:newpassword) WHERE username=:username";
            try {
                $sqlStmt = $this->db->prepare($sql);
                $sqlStmt->execute([':username' => $username,':newpassword'=>$newPassword]);
            } catch (PDOException $e) {
                throw new ILSException($e->getMessage());
            }
            
            return ['success'=>true];
        }

        public function activarCuenta($details)
        {
            $this->checkIntermittentFailure();
                if (!$this->isFailing(__METHOD__, 33)) {
                    return ['success' => true, 'status' => 'change_password_ok'];
                }
                return [
                    'success' => false,
                    'status' => 'An error has occurred',
                    'sysMessage' => 'Demonstrating failure; keep trying and it will work eventually.'
                ];
        }
}

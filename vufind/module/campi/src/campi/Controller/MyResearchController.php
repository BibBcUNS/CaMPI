<?php
/**
 * MyResearch Controller
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2010.
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
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Site
 */
namespace campi\Controller;

use VuFind\Exception\Auth as AuthException,
    VuFind\Exception\Forbidden as ForbiddenException,
    VuFind\Exception\ILS as ILSException,
    VuFind\Exception\Mail as MailException,
    VuFind\Exception\ListPermission as ListPermissionException,
    VuFind\Exception\RecordMissing as RecordMissingException,
    VuFind\Search\RecommendListener, Zend\Stdlib\Parameters,
    Zend\View\Model\ViewModel;

/**
 * Controller for the user account area.
 *
 * @category VuFind
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Site
 */
class MyResearchController extends \VuFind\Controller\MyResearchController
{

    /**
     * Send account recovery email
     *
     * @return View object
     */
    public function activarAction()
    {
        // Make sure we're configured to do this
        $this->setUpAuthenticationFromRequest();
        if (!$this->getAuthManager()->supportsRecovery()) {
            $this->flashMessenger()->addMessage('recovery_disabled', 'error');
            return $this->redirect()->toRoute('myresearch-home');
        }
        if ($this->getUser()) {
            return $this->redirect()->toRoute('myresearch-home');
        }
        // Database

        // acá lo primero que tengo que hacer es actualizar la tabla de usuario de VuFind con los datos de usuarios campi.
        // De la misma forma que con "processILSUser($info)" en Auth/ILS.php

        $catalog  = $this->getILS();
        if ($username = strtoupper($this->params()->fromPost('username'))) {
                
            if ($patron = $catalog->getPatronByUsername($username)) {
                $auth = $this->getAuthManager();
                // para poder utilizar la función procesILSUser implementada en ILS.php tuve que definir una función en
                // Manager.php y hacer "public" processILSUser en ILS.php (feo)
                // No encontré una forma directa
                $auth->updateUserFromPatron($patron);
            }
            else {
                $this->flashMessenger()->addMessage('recovery_user_not_found', 'error');
            }
        }

        $table = $this->getTable('User');
        $user = false;
        // Check if we have a submitted form, and use the information
        // to get the user's information
        if ($username = strtoupper($this->params()->fromPost('username'))) {
            $user = $table->getByUsername($username, false);
        }
        $view = $this->createViewModel();
        $view->useRecaptcha = $this->recaptcha()->active('passwordRecovery');
        // If we have a submitted form
        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {
            if ($user) {
                $this->sendEmail($user, $this->getConfig(), 'activar-cuenta.phtml','activar_email_subject','activar_email_sent');
            } else {
                $this->flashMessenger()->addMessage('recovery_user_not_found', 'error');
            }
        }
        return $view;
    }

    /**
     * Send account recovery email
     *
     * @return View object
     */
    public function recoverAction()
    {
        // Make sure we're configured to do this
        $this->setUpAuthenticationFromRequest();
        if (!$this->getAuthManager()->supportsRecovery()) {
            $this->flashMessenger()->addMessage('recovery_disabled', 'error');
            return $this->redirect()->toRoute('myresearch-home');
        }
        if ($this->getUser()) {
            return $this->redirect()->toRoute('myresearch-home');
        }
        // Database
        $table = $this->getTable('User');
        $user = false;
        // Check if we have a submitted form, and use the information
        // to get the user's information
        if ($email = $this->params()->fromPost('email')) {
            $user = $table->getByEmail($email);
        } elseif ($username = $this->params()->fromPost('username')) {
            $user = $table->getByUsername($username, false);
        }
        $view = $this->createViewModel();
        $view->useRecaptcha = $this->recaptcha()->active('passwordRecovery');
        // If we have a submitted form
        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {
            if ($user) {
                $this->sendEmail($user, $this->getConfig(),'recover-password.phtml','recovery_email_subject','recovery_email_sent');
            } else {
                $this->flashMessenger()
                    ->addMessage('recovery_user_not_found', 'error');
            }
        }
        return $view;
    }
    
    private function maskEmail($email) {
	function mask($str, $first, $last) {
	    $len = strlen($str);
	    $toShow = $first + $last;
	    return substr($str, 0, $len <= $toShow ? 0 : $first).str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)).substr($str, $len - $last, $len <= $toShow ? 0 : $last);
	}

        $mail_parts = explode("@", $email);
        $domain_parts = explode('.', $mail_parts[1]);

        $mail_parts[0] = mask($mail_parts[0], 2, 1); // show first 2 letters and last 1 letter
        $domain_parts[0] = mask($domain_parts[0], 2, 1); // same here
	$mail_parts[1] = implode('.', $domain_parts);

        return implode("@", $mail_parts);
    }


    /**
     * Helper function for recoverAction
     *
     * @param \VuFind\Db\Row\User $user   User object we're recovering
     * @param \VuFind\Config      $config Configuration object
     * @param String              $template Contenido del mensaje enviado por mail
     * @param String              $message Mensaje que se muestra al enviar el mail
     *
     * @return void (sends email or adds error message)
     */
    protected function sendEmail($user, $config, $email_template, $email_subject, $flash_message)
    {
        // If we can't find a user
        if (null == $user) {
            $this->flashMessenger()->addMessage('recovery_user_not_found', 'error');
        } else {
            // Make sure we've waiting long enough
            $hashtime = $this->getHashAge($user->verify_hash);
            $recoveryInterval = isset($config->Authentication->recover_interval)
                ? $config->Authentication->recover_interval
                : 60;
            if (time() - $hashtime < $recoveryInterval) {
                $this->flashMessenger()->addMessage('recovery_too_soon', 'error');
            } else {
                // Attempt to send the email
                try {
                    // Create a fresh hash
                    $user->updateHash();
                    $config = $this->getConfig();
                    $renderer = $this->getViewRenderer();
                    $method = $this->getAuthManager()->getAuthMethod();
                    // Custom template for emails (text-only)
                    $message = $renderer->render(
                        'Email/'.$email_template,
                        [
                            'library' => $config->Site->title,
                            'url' => $this->getServerUrl('myresearch-verify')
                                . '?hash='
                                . $user->verify_hash . '&auth_method=' . $method,
                        ]
                    );
                    $this->serviceLocator->get('VuFind\Mailer')->send(
                        $user->email,
                        $from_email = (isset($config->Mail->default_from))?$config->Mail->default_from:$config->Site->email,
                        $this->translate($email_subject),
                        $message
                    );
                    $this->flashMessenger()
                        ->addMessage($this->translate($flash_message,['%%mask_email%%'=>self::maskEmail($user->email)]), 'success');
                } catch (MailException $e) {
                    $this->flashMessenger()->addMessage($e->getMessage(), 'error');
                }
            }
        }
    }

    public function ecuAction()
    {
        
        //file_put_contents('jero.txt',"------------------\n", FILE_APPEND);
        
        $ecus = [];
        $user = $this->getUser();
        if ($user == false) {
            return $this->forceLogin();
        }

        //$this->log_time();
        $json_ecu = file_get_contents("http://".$this->urlCampi()."/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/consulta-ecu-json.xis&expresion=".strtoupper($user->username));
        //echo $json_ecu;exit;
        //$this->log_time();

        $json_ecu = utf8_encode($json_ecu);
        $ecu = json_decode($json_ecu);
        //echo "<pre>";var_dump($ecu);echo "</pre>";exit;

        $ecus['bc'] = $ecu;
        $view = $this->createViewModel(['usuario'=>$user,'estado'=>$ecu]);
        return $view;
    }

    public function renovarAction()
    {
        $user = $this->getUser();
        if ($user == false) {
            return $this->forceLogin();
        }

        $params = $this->params();
        
    
        if ($inventarios = $params->fromPost('inventarios')) {
            $invs_parametros = '';
            foreach($inventarios as $inventario) {
                //$invs_parametros='inventarios='.implode($inventarios,'&inventarios=');
                $invs_parametros .= '&inventarios='.$inventario;
            }
            $url_renovar = "http://".$this->urlCampi()."/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/auto-renovacion.xis{$invs_parametros}&documento=".strtoupper($user->username);            //echo $url_renovar;exit;
            
            //echo $url_renovar;exit;
            $renovaciones_json = file_get_contents($url_renovar);
            //echo $renovaciones_json;exit;
            $renovaciones=json_decode($renovaciones_json);
            //var_dump($renovaciones);exit;
            
            foreach ($renovaciones as $renovacion) {
                //echo $renovacion->estado.': '.$renovacion->mensaje;
                if ($renovacion->estado=='ok') {
                    $this->flashMessenger()->addSuccessMessage($renovacion->mensaje);
                }
                else
                    $this->flashMessenger()->addErrorMessage($renovacion->mensaje);
            }
        }
        else {
                $this->flashMessenger()->addMessage('Debe seleccionar algún elemento a renovar', 'error');
        }
    
        $this->redirect()->toUrl('ecu');
        return true;
    }

    private function normaliza ($cadena){
        setlocale(LC_ALL, 'en_US.UTF8');
        //$cadena= preg_replace("/[^A-Za-z0-9. ]/", '',iconv('UTF-8', 'ASCII//TRANSLIT', $cadena));
        return urlencode($cadena);
    }


    public function reservarAction()
    {
        $view = $this->createViewModel();
        $user = $this->getUser();
        if ($user == false) {
            return $this->forceLogin();
        }

        // Aca pasa solo si el usuario está logueado correctamente.
        $params = $this->params();
        $library_cod = $params->fromQuery('library_cod');
        $control_number = rawurlencode($params->fromQuery('control_number'));
        $libraryTable = $this->getTable('library');

        if ($library = $libraryTable->getByCode("EUN")) {
            //echo "<b>$user->username</b><br>$library_cod<br>$control_number<br>$library->name<br>$library->campi_url<br>";
            $url_reservar = "http://$library->campi_url/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=%2Fcirculacion%2Fauto-reserva.xis&usuario_id=$user->username&partes_id=^bMARC^c{$control_number}";
            //echo $url_reservar;exit;
            $reservar_json = utf8_encode(file_get_contents($url_reservar));
            //$reservar_json = file_get_contents($url_reservar);
            //echo $reservar_json;exit;
            $resultado = json_decode($reservar_json);
            //var_dump($resultado);
            $filter = new \Zend\Filter\StripTags();  // Es para eliminar los tags que generan los errores en campi ej: <li></li>
            if ($resultado->estado=='ok') {
                echo $filter->filter($resultado->mensaje);
                //$this->flashMessenger()->addSuccessMessage($filter->filter($resultado->mensaje));
            }
            else {
                echo $filter->filter($resultado->mensaje);
                //$this->flashMessenger()->addErrorMessage($filter->filter($resultado->mensaje));
            }
        }
        // VER QUE HAGO DESPUÉS DE RESERVAR
        //$this->redirect()->toUrl('ecu');
        
        $view->setTerminal(true);

        return $view;

    }
    public function reservaEliminarAction()
    {
        $user = $this->getUser();
        if ($user == false) {
            return $this->forceLogin();
        }

        $params = $this->params();
        
    
        if ($parte_id = $params->fromPost('parte_id')) {
            $url_eliminar = "http://".$this->urlCampi()."/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/auto-eliminar-reserva.xis&parte_id=".rawurlencode($parte_id)."&usuario_id=".strtoupper($user->username);
            
            //echo $url_eliminar;exit;
            $resultado_eliminar_json = file_get_contents($url_eliminar);
            //echo $renovaciones_json;exit;
            $resultado_eliminar=json_decode($resultado_eliminar_json);
            //var_dump($renovaciones);exit;
            
            if ($resultado_eliminar) {
                //echo $renovacion->estado.': '.$renovacion->mensaje;
                if ($resultado_eliminar->estado=='ok') {
                    $this->flashMessenger()->addSuccessMessage($resultado_eliminar->mensaje);
                }
                else
                    $this->flashMessenger()->addErrorMessage($resultado_eliminar->mensaje);
            }
            else  // Esto ocurre si el resultado del wxis no es un JSON
                $this->flashMessenger()->addErrorMessage("No fue posible realizar la operación. Por favor de aviso a la institución. Gracias!");
        }
        else {
                $this->flashMessenger()->addMessage('Falta indicar el objeto a procesar', 'error');
        }
    
        $this->redirect()->toUrl('ecu');
        return true;

    }
}

<?php
namespace bo\components\controller;

use bo\components\classes\helper\Logger;
use bo\components\classes\User;
use bo\components\classes\Invitation;
use bo\components\classes\helper\Query;

/**
 * Klasse loginController
 *
 * @author Manuel Sagorski
 *        
 */
class LoginController
{

    private const DEFAULT_VIEW = 'views/login.view.php';

    private const PW_RESET_VIEW = 'views/pwReset.view.php';

    private const REGISTER_VIEW = 'views/register.view.php';

    public function __construct()
    {
        if (isset($_SESSION['user']) && ! isset($_GET['logout'])) {
            header('Location: ' . PUBLIC_PATH . User::$defaultPage[$_SESSION['userLevel']] . '.php');
        }
    }

    /**
     * Führt je nach Aufrufart der index.php die entsprechenden Aktionen durch
     *
     * @return Array [optional Message | zu ladende View]
     */
    public function start()
    {
        if (isset($_GET['logout'])) {
            session_unset();
            session_destroy();
            session_start();
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case ('POST'):
                if (isset($_POST['registerKey'])) {
                    $msg['info'] = $this->registerNewUser();
                }
                
                if (isset($_POST['username'])) {
                    $msg = $this->login();
                    if ($msg === true) {
                        header('Location: ' . PUBLIC_PATH . User::$defaultPage[$_SESSION['userLevel']] . '.php');
                    }
                }

                if (isset($_POST['formData']['secretNew1']) && isset($_POST['formData']['secretNew2'])) {
                    $msg['info'] = $this->changePassword($_REQUEST);
                }

                if (isset($_POST['formData']['usernameReset'])) {
                    echo $this->forgotPassword($_REQUEST);
                } else {
                    return [
                        "message" => $msg,
                        "view" => self::DEFAULT_VIEW
                    ];
                }
                break;

            case ('GET'):
                if (isset($_GET['id']) && isset($_GET['code'])) {
                    if ($this->pwReset($_REQUEST)) {
                        return [
                            "view" => self::PW_RESET_VIEW
                        ];
                    } else {
                        $msg['error'] = "Der verwendete Link zum Zurücksetzen des Passwortes ist ungültig.";
                        return [
                            "message" => $msg,
                            "view" => self::DEFAULT_VIEW
                        ];
                    }
                }

                if (isset($_GET['register']) && isset($_GET['code'])) {
                    $id = $this->checkRegisterCode();

                    echo $id;

                    if ($id) {
                        return [
                            "view" => self::REGISTER_VIEW
                        ];
                    } else {
                        $msg['error'] = "Der verwendete Link zur Registrierung eines neuen Benutzers ist ungültig.";
                        return [
                            "message" => $msg,
                            "view" => self::DEFAULT_VIEW
                        ];
                    }
                }

                return [
                    "view" => self::DEFAULT_VIEW
                ];
                break;
        }
    }

    /**
     * Login eines Benutzers
     *
     * @param Array $request
     * @return string[]
     */
    public function login()
    {
        $username = $_POST['username'];
        $passwort = $_POST['secret'];

        $user = User::getSingleObjectByCondition(Array(
            "username" => $username,
            "inactive" => 0
        ));

        If (empty($user)) {
            Logger::writeLogError('login', 'Loginversuch mit unbekanntem Benutzername: ' . $username);
            return array(
                'error' => 'Benutzername nicht bekannt'
            );
        } else {
            If ($user->getLevel() >= 2) {
                If (password_verify($passwort, $user->getSecret())) {
                    $_SESSION['user'] = $user->getId();
                    $_SESSION['userLevel'] = $user->getLevel();
                    $_SESSION['project'] = $user->getProjectId();
                    Logger::writeLogInfo('login', 'Login erfolgreich');
                    return true;
                } else {
                    Logger::writeLogError('login', 'Loginversuch mit verkehrtem Passwort. Benutzername: ' . $username);
                    return array(
                        'error' => 'Falsches Passwort'
                    );
                }
            }
            else {
                return Array('error' => 'Dein Benutzer ist noch nicht für ein Login freigeschaltet. Bitte wende dich an den Koordinator der Hafengruppe.');
            }
        }
    }

    /**
     * Nutzer hat Passwort vergessen
     *
     * @param Array $request
     */
    public function forgotPassword($request)
    {
        $user = User::getSingleObjectByCondition(Array(
            "username" => $request['formData']['usernameReset']
        ));

        if (! empty($user)) {
            $user->sendResetPasswordLink();
        }

        return 'Sofern der eingegebene Benutzername existiert, erhälst du eine Email mit weiteren Hinweisen zum Zurücksetzen des Passwortes.';
    }

    /**
     * Prüft ob ein Nutzer berechtigt ist sein Passwort zu ändern.
     *
     * @param String $id
     */
    public function pwReset($request)
    {
        $resetUser = User::getSingleObjectByID($request['id']);

        if (password_verify($request['code'], $resetUser->getPasswordCode()) && strtotime($resetUser->getPasswordCodeTime()) > (time() - 24 * 3600)) {
            return true;
        } else {
            Logger::writeLogError('user', "Versuchtes Zurücksetzen des Passwortes mit einem verkehrten Link. Benutzer: " . User::getUserFullName($resetUser->getId()));
            return false;
        }
    }

    /**
     * Ändert das Passwort eines Nutzers
     *
     * @param Array $request
     */
    public function changePassword($request)
    {
        $resetUser = User::getSingleObjectByID($request['formData']['userID']);

        if (password_verify($request['formData']['userCode'], $resetUser->getPasswordCode()) && strtotime($resetUser->getPasswordCodeTime()) > (time() - 24 * 3600)) {
            $resetUser->setNewPassword($request['formData']['secretNew1']);
            return "Du kannst dich jetzt mit dem neuen Passwort anmelden";
        }
    }

    public function checkRegisterCode()
    {
        $invitation = Invitation::getSingleObjectByCondition([
            "invitation_key" => $_GET['code']
        ]);

        if (! empty($invitation)) {
            return $invitation->getID();
        } else {
            return false;
        }
    }

    public function checkSelectedUsername()
    {
        if (empty(User::getSingleObjectByCondition([
            "username" => $_POST['username']
        ], null, 0))) {
            echo false;
        } else {
            echo true;
        }
    }

    public function registerNewUser()
    {
        $invitation = Invitation::getSingleObjectByCondition([
            "invitation_key" => $_POST['registerKey']
        ]);

        if (! empty($invitation)) {
            if (empty(User::getSingleObjectByCondition([
                "username" => $_POST['userUsername']
            ], null, 0))) {
                $_POST['projectID'] = $invitation->getProjectID();
                (new User($_POST))->addUser(true);
                
                (new Query("delete"))
                    ->table(Invitation::TABLE_NAME)
                    ->condition(["id" => $invitation->getID()])
                    ->execute();
                
                return "Die Registrierung war erfolgreich. Sobald dein Konto vom Koordinator freigeschaltet wurde, kannst du dich anmelden.";
            }
        }
    }
}

?>
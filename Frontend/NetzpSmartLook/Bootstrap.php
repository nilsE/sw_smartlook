<?php
/**
 * Autor: netzperfekt, Nils Ehnert <ehnert@netzperfekt.de>
 *
 * Historie:
 *     26.02.2017   1.0.0	Initiale Version
*/

class Shopware_Plugins_Frontend_NetzpSmartLook_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    const STATE_COMPLETED = 2; // s_core_states.completed

    public function getVersion() {
        return '1.0.0';
    }
 
    public function getLabel() {
        return 'Smartlook/Smartsupp Integration';
    }
 
    public function install()
    {
        if (!$this->assertVersionGreaterThen("5.1.0")) {
            throw new Exception("Dieses Plugin benötigt Shopware ab Version 5.1.0");
        }

        try {
            $this->subscribeEvents();
            $this->createConfiguration();
        } 
        catch (Exception $e) {
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }

        return $this->successfulInstallation();
    }

    private function successfulInstallation() 
    {
        return array(
            'success' => true,
            'invalidateCache' => array(
                'theme',
            )
        );
    }

    public function uninstall()
    {
        return true;
    }

    public function update($oldVersion)
    {
        if (version_compare($oldVersion, '1.0.1', '<=')) {
            // next upcoming release
        }
        if (version_compare($oldVersion, '1.0.2', '<=')) {
            // next upcoming release
        }

        return $this->successfulInstallation();
    }

    private function createConfiguration() {

        $form = $this->Form();

        $form->setElement('select', 'netzp_smartlook_active', array(
            'label'     => 'SmartLOOK aktivieren',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Dukannst Smartlook pro Subshop einzeln aktivieren.',
            'value'     => 1,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

        $form->setElement('text', 'netzp_smartlook_key', array(
         'label'         => 'SmartLOOK Key',
         'description'   => 'Falls Du SmartLOOK nutzen willst, trage hier bitte den 40stelligen Smartlook-Key ein. Du findest diesen bei Smartlook unter "Konto" / "Tracking Code" in der Zeile "init..."',
         'value'         => ''
        ));

        $form->setElement('select', 'netzp_smartlook_visitordata', array(
            'label'     => 'Smartlook: Angemeldete Benutzer anzeigen',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Angemeldete Shopware-Nutzer können mit Namen und E-Mailadresse in Smartlook angezeigt werden. Achtung: weise bitte ggf. in Deinen Datenschutzbestimmungen darauf hin!',
            'value'     => 0,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

        $form->setElement('select', 'netzp_smartsupp_active', array(
            'label'     => 'SmartSUPP aktivieren',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Dukannst Smartsupp pro Subshop einzeln aktivieren.',
            'value'     => 1,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

        $form->setElement('text', 'netzp_smartsupp_key', array(
         'label'         => 'SmartSUPP Key',
         'description'   => 'Falls du SmartSUPP nutzen willst, trage hier bitte den 40stelligen Smartsupp-Key ein. Du findest diesen bei Smartsupp unter "Chat Code" ganz unten',
         'value'         => ''
        ));

        $form->setElement('select', 'netzp_smartsupp_hidemobilewidget', array(
            'label'     => 'Smartsupp: Chatbox auf mobilen Endgeräten ausblenden',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Die Chatbox kann optional auf mobilen Endgeräten ausgeblendet werden.',
            'value'     => 0,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

        $form->setElement('select', 'netzp_smartsupp_sendtranscript', array(
            'label'     => 'Smartsupp: E-Mailtranscript nach Chat-Ende anforderbar',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Der Nutzer kann optional nach dem Ende eine Zusammenfassung des Chats per E-Mail anfordern.',
            'value'     => 1,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

        $form->setElement('select', 'netzp_smartsupp_ratingenabled', array(
            'label'     => 'Smartsupp: Bewertung des Supports ermöglichen',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Nach Chat-Ende wird eine Bewertungsmöglichkeit angezeigt (negativ - neutral - positiv).',
            'value'     => 0,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

        $form->setElement('select', 'netzp_smartsupp_ratingcomment', array(
            'label'     => 'Smartsupp: Bewertungskommentar hinterlassen',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Zusätzlich kann der Nutzen einen freien Kommentar zur Bewertung hinterlassen.',
            'value'     => 0,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

        $form->setElement('select', 'netzp_smartsupp_visitordata', array(
            'label'     => 'Smartsupp: zusätzliche Kundendaten anzeigen',
            'store'     => array(
                            array(1, 'Ja'),
                            array(0, 'Nein'),
                           ),
            'description'   => 'Es werden zusätzliche Nutzerdaten in Smartsupp angezeigt (Name, E-Mail, Kundennummer, Umsatz).',
            'value'     => 1,
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP
        ));

    }

    private function subscribeEvents()
    {
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend', 'onFrontendPostDispatch');
    }

    public function onFrontendPostDispatch(Enlight_Event_EventArgs $args)
    {
        $request = $args->getSubject()->Request();
        $response = $args->getSubject()->Response();
        $view = $args->getSubject()->View();

        if(!$request->isDispatched() || $response->isException() || !$view->hasTemplate() || $request->getModuleName() != 'frontend') {
            return;
        }

        Shopware()->Template()->addTemplateDir($this->Path() . 'Views/');
        if ( ! Shopware()->Session()->Bot) {
            $this->addUserInfo($view);
        }
    }

    private function addUserInfo($view) {

        $config = Shopware()->Plugins()->Frontend()->NetzpSmartLook()->Config();

        $userId = 0;
        $userMail = '';
        $userName = '';
        $userTitle = '';
        $userNumber = '';
        $userTurnover = 0;
        $userComment = '';

        $shopName = Shopware()->Shop()->getName();
        $shopCurrency = Shopware()->Shop()->getCurrency()->getSymbol();
        $shopLocale = Shopware()->Shop()->getLocale()->getLocale();
        $p = strpos($shopLocale, '_');
        if($p !== false) {
            $shopLocale = strtolower(substr($shopLocale, 0, $p));
        }

        if($config->netzp_smartlook_visitordata == 1 || $config->netzp_smartsupp_visitordata == 1) {

            if (Shopware()->Modules()->Admin()->sCheckUser()) {
                $userId = Shopware()->Session()->offsetGet('sUserId');
                $userMail = Shopware()->Session()->offsetGet('sUserMail');
                
                $tmpUser = Shopware()->Db()->fetchRow('SELECT firstname, lastname, salutation, title, customernumber, internalcomment
                                                       FROM s_user WHERE id = ?', 
                                                       array($userId)) ? : array();

                $userName = ($tmpUser ? $tmpUser['firstname'] . ' ' . $tmpUser['lastname'] : '');
                $userTitle = ($tmpUser ? $tmpUser['salutation'] . ' ' . $tmpUser['title'] : '');
                $userNumber = ($tmpUser ? $tmpUser['customernumber'] : '');
                $userComment = ($tmpUser ? $tmpUser['internalcomment'] : '');

                $sqlTurnover = 'SELECT round(sum(`invoice_amount`), 0) as turnover 
                                FROM   s_order 
                                WHERE  userID = ? AND status = ?';
                $tmpTurnover = Shopware()->Db()->fetchRow($sqlTurnover, array($userId, self::STATE_COMPLETED));
                if($tmpTurnover) {
                    $userTurnover = $tmpTurnover['turnover'];
                }
            }
        }

        $view->assign('netzp_locale', $shopLocale);
        $view->assign('netzp_shopname', $shopName);
        $view->assign('netzp_shopcurrency', $shopCurrency);

        $view->assign('netzp_user_email', $userMail);
        $view->assign('netzp_user_name', $userName);
        $view->assign('netzp_user_title', $userTitle);
        $view->assign('netzp_user_number', $userNumber);
        $view->assign('netzp_user_comment', $userComment);
        $view->assign('netzp_user_turnover', $userTurnover);
    }

    public function getCapabilities() {
 
        return array(
            'install'   => true,
            'enable'    => true,
            'update'    => true
        );
    }
 
    public function getInfo() {

         return array(
            'version'       => $this->getVersion(),
            'label'         => $this->getLabel(),
            'author'        => 'netzperfekt',
            'copyright'     => 'netzperfekt',
            'description'   => '',
            'support'       => 'http://netzperfekt.de/support',
            'link'          => 'http://netzperfekt.de',
        );
    }
}
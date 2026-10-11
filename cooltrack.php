<?php
/**
 * CoolTrack - Suivi e-commerce pour Matomo, pour PrestaShop
 *
 * @author    ZM40 — Nicolas Michaud (Magic Garden)
 * @copyright 2026 Nicolas Michaud — ZM40 / Magic Garden
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/lib/zm40/Zm40Common.php';

class CoolTrack extends Module
{
    public function __construct()
    {
        $this->name = 'cooltrack';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'ZM40';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = 'CoolTrack';
        $this->description = $this->l('Suivi e-commerce pour Matomo : pages vues, vues produit et catégorie, paniers et commandes, vers votre Matomo auto-hébergé.');
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('displayHeader')) {
            return false;
        }
        Configuration::updateValue('ZM40_NET_ENABLED', 1);

        return true;
    }

    public function uninstall()
    {
        foreach (['COOLTRACK_URL', 'COOLTRACK_SITE_ID', 'ZM40_LASTCHECK_COOLTRACK', 'ZM40_LATEST_COOLTRACK'] as $cle) {
            Configuration::deleteByName($cle);
        }

        return parent::uninstall();
    }

    // -- Page du module --------------------------------------------------------------------

    public function getContent()
    {
        $message = '';
        if (Tools::isSubmit('submitCoolTrackConfig')) {
            $url = rtrim(trim((string) Tools::getValue('COOLTRACK_URL')), '/') . '/';
            $siteId = (int) Tools::getValue('COOLTRACK_SITE_ID');
            if (strpos($url, 'https://') !== 0 || !Validate::isAbsoluteUrl($url) || $siteId < 1) {
                $message = $this->displayError($this->l('Une adresse en https et un numéro de site valides sont requis.'));
            } else {
                Configuration::updateValue('COOLTRACK_URL', $url);
                Configuration::updateValue('COOLTRACK_SITE_ID', $siteId);
                $message = $this->displayConfirmation($this->l('Réglages enregistrés.'));
            }
        } elseif (Tools::isSubmit('submitCoolTrackNet')) {
            Configuration::updateValue('ZM40_NET_ENABLED', (int) Tools::getValue('ZM40_NET_ENABLED'));
            Zm40CommonCtk::clearFeedCache();
            $message = $this->displayConfirmation($this->l('Réglages enregistrés.'));
        }

        $this->context->smarty->assign([
            'module_dir' => $this->_path,
            'module_version' => $this->version,
            'zm40_ah_name' => 'CoolTrack',
            'zm40_ah_sub' => $this->l('Suivi e-commerce pour Matomo'),
            'zm40_ah_version' => $this->version,
            'zm40_ah_shop' => Configuration::get('PS_SHOP_NAME'),
            'ct_message' => $message,
            'ct_form_config' => $this->formulaireConfiguration(),
            'ct_form_ecosystem' => $this->formulaireEcosysteme(),
            'zm40_net_enabled' => Zm40CommonCtk::isNetEnabled() ? 1 : 0,
            'zm40_footer_html' => Zm40CommonCtk::footer('CoolTrack', $this->version, 'cooltrack'),
            'zm40_update' => Zm40CommonCtk::checkUpdate('cooltrack', $this->version),
            'zm40_modules' => Zm40CommonCtk::modulesFeed('cooltrack'),
            'zm40_about_name' => 'CoolTrack',
            'zm40_about_license' => 'OSL 3.0',
            'zm40_about_github' => Zm40CommonCtk::githubUrl('cooltrack'),
            'zm40_about_site' => Zm40CommonCtk::siteUrl('cooltrack', 'panel', '/contact'),
            'zm40_about_modules' => Zm40CommonCtk::siteUrl('cooltrack', 'panel', '/'),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    private function formulaireConfiguration()
    {
        return $this->helper([['form' => [
            'legend' => ['title' => $this->l('Connexion à Matomo'), 'icon' => 'icon-cogs'],
            'input' => [
                ['type' => 'text', 'name' => 'COOLTRACK_URL', 'label' => $this->l('Adresse de Matomo'), 'required' => true,
                    'desc' => $this->l('L\'adresse de votre Matomo, en https. Exemple : https://stats.votre-domaine.fr/')],
                ['type' => 'text', 'name' => 'COOLTRACK_SITE_ID', 'label' => $this->l('Numéro du site'), 'class' => 'fixed-width-sm', 'required' => true,
                    'desc' => $this->l('Dans Matomo : Administration, Sites web, Gérer. Le numéro est dans la colonne ID.')],
            ],
            'submit' => ['title' => $this->l('Enregistrer'), 'name' => 'submitCoolTrackConfig'],
        ]]], [
            'COOLTRACK_URL' => (string) Configuration::get('COOLTRACK_URL'),
            'COOLTRACK_SITE_ID' => Configuration::get('COOLTRACK_SITE_ID') ?: '',
        ], 'submitCoolTrackConfig');
    }

    private function formulaireEcosysteme()
    {
        $oui = [['id' => 'on', 'value' => 1, 'label' => $this->l('Oui')], ['id' => 'off', 'value' => 0, 'label' => $this->l('Non')]];

        return $this->helper([['form' => [
            'legend' => ['title' => $this->l('Mises à jour et modules ZM40'), 'icon' => 'icon-globe'],
            'input' => [[
                'type' => 'switch', 'name' => 'ZM40_NET_ENABLED', 'is_bool' => true, 'values' => $oui,
                'label' => $this->l('Vérifier les mises à jour et actualiser la liste des modules ZM40'),
                'desc' => $this->l('Une fois par jour au plus, une requête anonyme vers zm40.com met la liste à jour et GitHub donne la dernière version. Désactivé, la liste reste affichée telle quelle. Aucune donnée de la boutique n\'est transmise.'),
            ]],
            'submit' => ['title' => $this->l('Enregistrer'), 'name' => 'submitCoolTrackNet'],
        ]]], ['ZM40_NET_ENABLED' => Zm40CommonCtk::isNetEnabled() ? 1 : 0], 'submitCoolTrackNet');
    }

    private function helper(array $formulaires, array $valeurs, $submit)
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->languages = $this->context->controller->getLanguages();
        $helper->show_toolbar = false;
        $helper->submit_action = $submit;
        $helper->tpl_vars = ['fields_value' => $valeurs, 'languages' => $helper->languages, 'id_language' => $this->context->language->id];

        return $helper->generateForm($formulaires);
    }

    // -- Vitrine ---------------------------------------------------------------------------

    public function hookDisplayHeader()
    {
        $url = Configuration::get('COOLTRACK_URL');
        $siteId = (int) Configuration::get('COOLTRACK_SITE_ID');
        if (!$url || !$siteId) {
            return '';
        }

        return self::buildScript($url, $siteId, $this->ecommerceCommands());
    }

    /**
     * Commandes _paq e-commerce de la page courante. SKU = id_product partout,
     * pour que vues, paniers et commandes se recoupent dans Matomo.
     */
    private function ecommerceCommands()
    {
        $idLang = (int) $this->context->language->id;

        switch ($this->context->controller->php_self) {
            case 'product':
                $product = new Product((int) Tools::getValue('id_product'), false, $idLang);
                if (!Validate::isLoadedObject($product)) {
                    return [];
                }
                $category = new Category((int) $product->id_category_default, $idLang);

                return [['setEcommerceView', (string) $product->id, $product->name, (string) $category->name, round((float) Product::getPriceStatic($product->id), 2)]];

            case 'category':
                $category = new Category((int) Tools::getValue('id_category'), $idLang);

                return Validate::isLoadedObject($category) ? [['setEcommerceView', false, false, $category->name]] : [];

            // ponytail: panier suivi seulement sur les pages panier/tunnel, pas à chaque ajout AJAX — suffit pour les paniers abandonnés
            case 'cart':
            case 'order':
                $cart = $this->context->cart;
                $products = $cart && $cart->id ? $cart->getProducts() : [];
                if (!$products) {
                    return [['clearEcommerceCart'], ['trackEcommerceCartUpdate', 0]];
                }
                $commands = [];
                foreach ($products as $p) {
                    $commands[] = ['addEcommerceItem', (string) $p['id_product'], $p['name'], false, round((float) $p['price_wt'], 2), (int) $p['cart_quantity']];
                }
                $commands[] = ['trackEcommerceCartUpdate', round((float) $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS), 2)];

                return $commands;

            case 'order-confirmation':
                $order = new Order((int) Tools::getValue('id_order'));
                // Même contrôle que PrestaShop : la clé sécurisée de l'URL doit correspondre (fonctionne aussi en invité)
                if (!Validate::isLoadedObject($order) || Tools::getValue('key') !== $order->secure_key) {
                    return [];
                }
                // Un panier multi-transporteurs donne plusieurs commandes de même référence : on les cumule
                $commands = [];
                $paid = $paidExcl = $subtotal = $shipping = $discounts = 0;
                foreach (Order::getByReference($order->reference) as $o) {
                    $paid += $o->total_paid_tax_incl;
                    $paidExcl += $o->total_paid_tax_excl;
                    $subtotal += $o->total_products_wt;
                    $shipping += $o->total_shipping_tax_incl;
                    $discounts += $o->total_discounts_tax_incl;
                    foreach ($o->getProducts() as $p) {
                        $commands[] = ['addEcommerceItem', (string) $p['product_id'], $p['product_name'], false, round((float) $p['unit_price_tax_incl'], 2), (int) $p['product_quantity']];
                    }
                }
                $commands[] = ['trackEcommerceOrder', self::orderId($order->reference), round($paid, 2), round($subtotal, 2), round($paid - $paidExcl, 2), round($shipping, 2), round($discounts, 2)];

                return $commands;
        }

        return [];
    }

    /**
     * Identifiant de commande non réversible : Matomo dédoublonne les rechargements
     * sans pouvoir relier la visite à une commande PrestaShop (exemption CNIL).
     */
    public static function orderId($reference)
    {
        return substr(hash_hmac('sha256', $reference, _COOKIE_KEY_), 0, 32);
    }

    public static function buildScript($url, $siteId, array $commands)
    {
        // JSON_HEX_TAG empêche un nom de produit contenant </script> de casser la page
        $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
        $calls = array_merge(
            [['setTrackerUrl', $url . 'matomo.php'], ['setSiteId', (string) $siteId]],
            $commands,
            [['trackPageView'], ['enableLinkTracking']]
        );

        $js = "var _paq = window._paq = window._paq || [];\n";
        foreach ($calls as $call) {
            $js .= '_paq.push(' . json_encode($call, $flags) . ");\n";
        }
        $js .= "(function(){var d=document,g=d.createElement('script'),s=d.getElementsByTagName('script')[0];"
            . 'g.async=true;g.src=' . json_encode($url . 'matomo.js', $flags) . ";s.parentNode.insertBefore(g,s);})();\n";

        return "<script>\n" . $js . "</script>\n";
    }
}

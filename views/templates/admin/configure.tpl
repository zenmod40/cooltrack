{*
 * CoolTrack - Suivi e-commerce pour Matomo, pour PrestaShop
 *
 * @author    ZM40 — Nicolas Michaud (Magic Garden)
 * @copyright 2026 Nicolas Michaud — ZM40 / Magic Garden
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License version 3.0
 *}
<link rel="stylesheet" href="{$module_dir|escape:'html':'UTF-8'}views/css/zm40-common.css?v={$module_version|escape:'url'}">
<link rel="stylesheet" href="{$module_dir|escape:'html':'UTF-8'}views/css/cooltrack-admin.css?v={$module_version|escape:'url'}">

{* ===== Header de marque ===== *}
<div class="zm40-ah">
    <div class="zm40-ah-brand">
        <div>
            <h2>{$zm40_ah_name|escape:'html':'UTF-8'}</h2>
            <div class="zm40-ah-sub">{$zm40_ah_sub|escape:'html':'UTF-8'} &middot; v{$zm40_ah_version|escape:'html':'UTF-8'}</div>
        </div>
    </div>
    {if $zm40_ah_shop}<span class="zm40-ah-badge">{$zm40_ah_shop|escape:'html':'UTF-8'}</span>{/if}
</div>

{include file="module:cooltrack/views/templates/admin/_partials/zm40_update.tpl"}

{$ct_message nofilter}

{* ===== Onglets ===== *}
<ul class="zm40-tabs" id="zm40-tabs">
    <li class="is-active" data-tab="config"><i class="icon icon-cogs"></i>{l s='Configuration' mod='cooltrack'}</li>
    <li data-tab="guide"><i class="icon icon-book"></i>{l s='Guide' mod='cooltrack'}</li>
    <li data-tab="ecosystem"><i class="icon icon-th-large"></i>{l s='Modules ZM40' mod='cooltrack'}</li>
</ul>

{* ===== Onglet 1 : CONFIGURATION ===== *}
<div class="zm40-tab-content is-active" data-content="config">
    {$ct_form_config nofilter}
</div>

{* ===== Onglet 2 : GUIDE ===== *}
<div class="zm40-tab-content" data-content="guide">
    <div class="panel">
        <h3><i class="icon icon-book"></i> {l s='Guide' mod='cooltrack'} <small>CoolTrack v{$module_version|escape:'html':'UTF-8'}</small></h3>
        <p>{l s='CoolTrack envoie à votre Matomo les visites de la boutique et leur suivi e-commerce. Il ne dépose rien d\'autre sur la vitrine que le script de suivi de Matomo.' mod='cooltrack'}</p>
        <h4>{l s='Ce qui est suivi' mod='cooltrack'}</h4>
        <ul>
            <li>{l s='Toutes les pages : une page vue.' mod='cooltrack'}</li>
            <li>{l s='Fiche produit et catégorie : une vue e-commerce, avec le nom, la catégorie et le prix.' mod='cooltrack'}</li>
            <li>{l s='Panier et tunnel de commande : le contenu du panier, ce qui alimente les paniers abandonnés de Matomo.' mod='cooltrack'}</li>
            <li>{l s='Confirmation de commande : la commande, ses produits, les frais de port, les taxes et les remises.' mod='cooltrack'}</li>
        </ul>
        <h4>{l s='Avant de commencer' mod='cooltrack'}</h4>
        <p>{l s='Dans Matomo, activez le commerce électronique pour le site (Administration, Sites web, Gérer, puis Modifier sur le site). Sans cela, Matomo reçoit les commandes mais ne les affiche pas.' mod='cooltrack'}</p>
        <h4>{l s='Vie privée' mod='cooltrack'}</h4>
        <p>{l s='Les commandes partent avec un identifiant brouillé : Matomo ne peut pas relier une visite à une commande de la boutique. Aucun nom, aucune adresse e-mail, aucun identifiant client n\'est transmis.' mod='cooltrack'}</p>
        <p>{l s='L\'exemption de consentement de la CNIL dépend aussi des réglages de votre Matomo (anonymisation des adresses IP, durée de conservation). Vérifiez-les avant de retirer Matomo de votre bandeau de consentement.' mod='cooltrack'}</p>
    </div>
</div>

{* ===== Onglet 3 : MODULES ZM40 ===== *}
<div class="zm40-tab-content" data-content="ecosystem">
    {include file="module:cooltrack/views/templates/admin/_partials/zm40_modules.tpl"}
    {if !isset($zm40_modules) || !$zm40_modules|@count}<div class="panel"><p style="margin:0">{l s='Aucun module à afficher pour le moment.' mod='cooltrack'}</p></div>{/if}
    {$ct_form_ecosystem nofilter}
</div>

{include file="module:cooltrack/views/templates/admin/_partials/zm40_panel.tpl"}
{include file="module:cooltrack/views/templates/admin/_partials/zm40_footer.tpl"}

<script>
(function () {
    var tabs = document.querySelectorAll('#zm40-tabs li');
    var contents = document.querySelectorAll('.zm40-tab-content');
    function ouvrir(cible) {
        var li = document.querySelector('#zm40-tabs li[data-tab="' + cible + '"]');
        var pane = document.querySelector('.zm40-tab-content[data-content="' + cible + '"]');
        if (!li || !pane) { return; }
        tabs.forEach(function (x) { x.classList.remove('is-active'); });
        contents.forEach(function (x) { x.classList.remove('is-active'); });
        li.classList.add('is-active');
        pane.classList.add('is-active');
        try { localStorage.setItem('zm40_cooltrack_tab', cible); } catch (e) {}
    }
    tabs.forEach(function (li) {
        li.addEventListener('click', function () { ouvrir(li.getAttribute('data-tab')); });
    });
    try {
        ouvrir(localStorage.getItem('zm40_cooltrack_tab') || 'config');
    } catch (e) {}
})();
</script>

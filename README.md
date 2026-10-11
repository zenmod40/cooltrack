# CoolTrack — Suivi e-commerce pour Matomo, pour PrestaShop

> Les visites, les paniers et les commandes de votre boutique PrestaShop dans votre propre Matomo, sans Google Analytics.

*E-commerce tracking for Matomo, for PrestaShop. Free & open source (OSL 3.0).*

![PrestaShop 1.7 → 9](https://img.shields.io/badge/PrestaShop-1.7%20%E2%86%92%209-blue) [![Version](https://img.shields.io/github/v/release/zenmod40/cooltrack)](https://github.com/zenmod40/cooltrack/releases/latest)
![License: OSL 3.0](https://img.shields.io/badge/License-OSL--3.0-blue)

## Ce que fait CoolTrack

- **Pages vues** sur toute la vitrine.
- **Vues e-commerce** : fiche produit (nom, catégorie, prix) et catégorie.
- **Paniers** : le contenu du panier sur la page panier et dans le tunnel de commande, pour les paniers abandonnés de Matomo.
- **Commandes** à la confirmation : produits, total, sous-total, taxes, frais de port et remises. Un panier livré par plusieurs transporteurs donne plusieurs commandes dans PrestaShop : CoolTrack les cumule en une seule.

Le produit est identifié par son numéro PrestaShop partout, pour que vues, paniers et commandes se recoupent dans les rapports de Matomo.

## Vie privée

- Les commandes partent avec un **identifiant brouillé** : Matomo dédoublonne un rechargement de la page de confirmation, mais ne peut pas relier une visite à une commande de la boutique.
- **Aucun nom, aucune adresse e-mail, aucun identifiant client** n'est transmis.
- L'exemption de consentement de la CNIL dépend aussi des réglages de votre Matomo (anonymisation des adresses IP, durée de conservation). Vérifiez-les avant de retirer Matomo de votre bandeau de consentement.

## Compatibilité

PrestaShop **1.7 → 9.x** · PHP **7.1+** · Aucune dépendance Composer. Matomo auto-hébergé, en https.

## Installation

1. Télécharger la dernière release (`cooltrack_vX.Y.Z.zip`, dans les *Assets*).
2. Back-office PrestaShop → **Modules** → **Téléverser un module**.
3. Installer, puis ouvrir la configuration de **CoolTrack**.

## Configuration

- **Adresse de Matomo** : par exemple `https://stats.votre-domaine.fr/`.
- **Numéro du site** : dans Matomo, *Administration → Sites web → Gérer*, colonne ID.

Dans Matomo, activez le **commerce électronique** pour le site (*Modifier* sur la ligne du site). Sans cela, Matomo reçoit les commandes mais ne les affiche pas.

## Limite connue

Le panier est envoyé quand le visiteur ouvre la page panier ou le tunnel de commande, pas à chaque ajout depuis une fiche produit. Cela suffit pour les paniers abandonnés.

## Confidentialité du module

CoolTrack vérifie périodiquement (au maximum 1×/jour) si une nouvelle version est disponible via l'**API publique de GitHub**, et récupère la liste des autres modules ZM40 depuis **zm40.com**. Ces requêtes sont **anonymes** : **aucune donnée de votre boutique n'est transmise** (seule l'adresse IP de votre serveur est visible, comme pour toute requête HTTP). Vous pouvez **tout désactiver** dans la configuration du module (onglet **Modules ZM40**).

## Support & services

CoolTrack est **offert à la communauté, sans support garanti**. Les issues GitHub sont les bienvenues pour les **bugs** et les **idées**.

Besoin d'aide à l'installation, d'une adaptation sur mesure, d'un connecteur (ERP, marketplace, API), de débogage ou de maintenance ? → **[zm40.com](https://zm40.com)** (c'est Nicolas qui répond).

Une version compatible ThirtyBees / PrestaShop 1.6 est disponible sur demande.

## Contribuer

Les PR sont bienvenues. Merci de garder le style du code et d'ouvrir une issue avant les gros changements.

## Licence

**OSL 3.0** © 2026 Nicolas Michaud — ZM40 / Magic Garden · [zm40.com](https://zm40.com)

Matomo est une marque de Matomo / InnoCraft. CoolTrack est un module indépendant, ni édité ni approuvé par Matomo.

Voir [LICENSE](LICENSE) pour le texte complet.

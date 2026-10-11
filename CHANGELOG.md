# Changelog CoolTrack

Toutes les évolutions notables du module sont listées ici.
Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/), versions [SemVer](https://semver.org/lang/fr/).

## [1.0.0] — 2026-10-11

Première publication open source.

### Ajouté

- **Suivi des pages vues** vers un Matomo auto-hébergé : il suffit de son adresse et du numéro du site.
- **Vues e-commerce** sur les fiches produit (nom, catégorie, prix) et les catégories.
- **Paniers** : le contenu du panier est envoyé sur la page panier et dans le tunnel de commande, ce qui alimente les paniers abandonnés de Matomo.
- **Commandes** à la confirmation : produits, total, sous-total, taxes, frais de port et remises. Les commandes d'un panier multi-transporteurs sont cumulées.
- **Identifiant de commande brouillé** : Matomo dédoublonne les rechargements de la page de confirmation sans pouvoir relier une visite à une commande de la boutique.
- Composant commun ZM40 : notification de mise à jour, autres modules ZM40, opt-out réseau.

# WP Punk API

WordPress Plugin that connects to the Punk API, and imports the data into a Custom Post Type called Beers

## Installation

1. Download the plugin
2. Upload the plugin to the `/wp-content/plugins/` directory
3. Run `composer install` in the plugin directory to install the Composer dependencies
4. Then run `npm install` in the plugin directory to install the Node dependencies
5. Finally run `npm run build` in the plugin directory to build the assets
6. Rename the `.env.example` file to `.env`.
7. Activate the plugin through the 'Plugins' menu in WordPress
8. The plugin will create a new Custom Post Type called Beers and import the data from the Punk API

## CLI Command

* `wp punk-api import` - Imports the data from the Punk API into the database
* `wp punk-api delete` - Deletes the data from the database

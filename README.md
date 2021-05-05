# Loop Demo Plugin

Simple plugin that adds **Events** and **Event tags** and allows importing, displaying and exporting events.

## Installation

Download the repository and copy **loop-demo-main/plugin/loop-demo** folder into /wp-content/plugins folder of Wordpress installation and activate Loop Demo plugin.

## Setup

### Creating Events listing page

Create a page and add following shortcode into the content **[events_list]**.

### Creating Export Events page

Create a page and add following shortcode into the content **[export_events]**.

## Importing events

Events data is located in **data.json** file inside plugin's folder. To import it into the website, run **wp import_events** CLI command.

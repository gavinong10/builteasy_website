# ReachEdge WordPress 4 Plugin
A WordPress 4 plugin to install the [ReachEdge](http://go.reachlocal.com/contact-us-edge.html) tracking code.

## Features

* Enables the [ReachEdge](http://go.reachlocal.com/contact-us-edge.html) tracking functionality on WordPress sites.

## Installation

1. Download the [latest stable
   release](http://github.com/reachlocal/reachedge-wordpress-4x-tracking-plugin/releases/latest).
2. In the WordPress dashboard, navigate to the *Plugins* page and click *Add New*, then click *Upload Plugin*.
3. Browse to the 'reachlocal_wordpress_4x_tracking_plugin_v1.0.0.zip' file, then select *Open* and click *Install Now*.
4. When the upload and installation completes successfully, click on *Activate Plugin*.

### Entering your tracking code ID

1. In the WordPress dashboard, navigate to the *Settings* menu.
3. Select the *ReachLocal Tracking Code* option from the menu.
2. Enter your tracking code ID into the ID field, and click the *Save Changes* button.

## Prep a Release

To prep a release of this plugin you need to perform the following steps.

1. Update the `reachedge-tracking-plugin.php` file's `Version: X.Y.Z`
   declaration in the header comment to have the new version. We follow
   [semantic versioning](http://semver.org).
2. Update the `README.txt` with a proper entry in the *Changelog* section &
   the `Stable tag: X.Y.Z` entry with the new version.
3. Commit the above changes with a message saying "Bump version to vX.Y.Z"
   where vX.Y.Z is the new version.
4. Tag the "Bump version to vX.Y.Z" commit with the new version, ex: `git tag
   vX.Y.Z`
5. Push changes up including tags, ex: `git push && git push --tags`
6. Verify GitHub properly created your release for you on the [releases page](https://github.com/reachlocal/reachedge-wordpress-4x-tracking-plugin/releases).
7. Update that releases notes with the content you included in the *Changelog* section of the `README.txt` file by going to the [tags page](http://github.com/reachlocal/reachedge-wordpress-4x-tracking-plugin/tags) and clicking 'Add release notes' and putting your release notes in the description.

## License

The ReachLocal Tracking Plugin is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the plugin at `reachlocal-tracking/src/LICENSE.txt`.

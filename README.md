# Setup: 
**Make sure all dependencies have been installed before moving on:**

- [Virtualbox](https://www.virtualbox.org/wiki/Downloads "Download Virtualbox") >= 4.3.10
- [Vagrant](https://www.vagrantup.com/downloads.html "Download Vagrant") >= 2.0.1
- [Node](https://nodejs.org/en/download/ "Download Node") = latest
- $ npm i -g "github:gulpjs/gulp#4.0" (might need git to run this)

### **Open your favorite Terminal and run these commands:** 

*navigate to the trellis folder (change project_path in to your local path)*

	$ cd project_path/trellis

*greate a local machine for development (grab a coffee...)*

	$ vagrant up

*navigate to the theme folder (change project_path in to your local path and Theme_name into ... the theme name)*

	$ cd project_path/site/web/app/themes/Theme_name

*Run the node package manager installer*

	$ npm install

*Build bootstrap.js for the first time only*

	$ gulp make-bootstrap-js

### **Open a browser and checkout the site {project_name}.test**

*Got a "Connection is not private" error?*

**(MAC)**: open Keychain Access and drag cert file from the urlbar into System under Keychains.
Once it is imported, right click the imported certificate and click Get Info. Change first dropdown to Always Trust.

**(WINDOWS)**: You'r screwed (not going to find out now, if you do please update ;) )

# Starting work: 
### **Open your favorite Terminal and run these commands:** 

*navigate to the trellis folder*

	$ cd project_path/trellis

*start a local machine for development*

	$ vagrant up

*open a new tab and navigate to the theme folder*

	$ cd project_path/site/web/app/themes/Theme_name

*Run gulp when you start coding(this starts watching the assets/src folder for changes)*

	$ gulp

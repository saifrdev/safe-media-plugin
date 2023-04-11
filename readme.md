# Safe Media Delete

## Installation

1. Download the plugin ZIP file from the plugin repository.
2. Extract the ZIP file to your local machine.
3. Upload the extracted plugin folder to the `/wp-content/plugins/` directory of your WordPress installation.
4. Go to your WordPress admin dashboard and navigate to "Plugins" > "Installed Plugins".
5. Find the "WordPress Plugin Testing" plugin in the list and click the "Activate" button to activate the plugin.

## PHPUnit Installation and Test Execution

To run PHPUnit tests for your WordPress plugin, follow these steps:

1. Install PHPUnit: PHPUnit is a popular testing framework for PHP, and it is commonly used for writing and executing tests for WordPress plugins. You can install PHPUnit using Composer, a dependency management tool for PHP, or by downloading and installing it manually. Here are the steps to install PHPUnit using Composer:

    a. Install Composer: If you don't have Composer installed already, you can download and install it from the official Composer website: https://getcomposer.org/

    b. Navigate to your plugin's root directory: Open a command line interface (CLI) or terminal, and navigate to the root directory of your WordPress plugin where the `composer.json` file is located.

    c. Run Composer to install PHPUnit: Run the following command to install PHPUnit as a development dependency:

        ```
        composer require --dev phpunit/phpunit
        ```
    d. Run Composer to install PHPUnit: Run the following command to install PHPUnit as a development dependency:
    
        ```
        composer require --dev yoast/phpunit-polyfills
        ```
        
    e. Install dependencies
    
        ```
        composer install
        ```

2. Configure PHPUnit: Create a `phpunit.xml` file in the `tests` directory to configure PHPUnit for your plugin. You can specify the test suite, test case files, and other configurations in this file. Here's an example configuration:

    ```xml
    <phpunit
	    bootstrap="tests/bootstrap.php"
	    backupGlobals="false"
	    colors="true"
	    convertErrorsToExceptions="true"
	    convertNoticesToExceptions="true"
	    convertWarningsToExceptions="true"
	>
    	<testsuites>
    		<testsuite name="testing">
    			<directory prefix="test-" suffix=".php">./tests/</directory>
    			<exclude>./tests/test-sample.php</exclude>
    		</testsuite>
    	</testsuites>
    </phpunit>
    ```

3. Run PHPUnit tests: To execute PHPUnit tests for your plugin, run the following command in the root directory of your plugin:

    ```
    ./vendor/bin/phpunit
    ```

    PHPUnit will automatically discover and run the tests in the `tests` directory based on the configuration in the `phpunit.xml` file.

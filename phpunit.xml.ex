<phpunit
        bootstrap="vendor/autoload.php"
        colors="true"
        convertErrorsToExceptions="true"
        convertNoticesToExceptions="true"
        convertWarningsToExceptions="true"
        processIsolation="false"
        stopOnFailure="false">
    <testsuites>
        <testsuite name="tiger">
            <directory suffix="Test.php">tests</directory>
            <exclude>tests/TestCase.php</exclude>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">./src</directory>
            <exclude>
                <directory suffix=".php">./vendor</directory>
            </exclude>
        </whitelist>
    </filter>
    <php>
        <env name="API_KEY" value="key" force="false"/>
        <env name="API_SECRET" value="secret" force="false"/>
        <env name="API_PASSPHRASE" value="passphrase" force="false"/>
        <env name="API_BASE_URI" value="" force="false"/>
        <env name="API_SKIP_VERIFY_TLS" value="0" force="false"/>
    </php>
</phpunit>

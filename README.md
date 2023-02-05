<table align="center">
    <tr style="text-align: center;">
        <td align="center" width="9999">
            <img src="./doc/behat.png" width="150" alt="Project icon" style="margin: 25px auto; display: inline-block">
            <img src="./doc/laravel.png" width="150" alt="Project icon" style="margin: 25px auto; display: inline-block">

 <h1 style="color: black;">Laravel Behat Extension</h1>

<p style="color: black">A powerfully extension to integrate laravel with behat from scratch and start writing great feature histories.</p>
</td>
</tr>
</table>

# 1. Install Dependencies

As always, we need to pull in some dependencies through Composer.

```shell
composer require behat/behat behat/mink friends-of-behat/mink-extension soulcodex/laravel-behat --dev
```

This will give us access to Behat, Mink, and, of course, the Laravel extension.

# 2. Create the Behat.yml Configuration File

Next, within your project root, create a `behat.yml` file, and add:

```yaml
default:
    extensions:
        Soulcodex\Behat:
            kernel: # Default values
                bootstrap_path: '/bootstrap/app.php'
                environment_path: '.env.behat'
        Behat\MinkExtension: # Default mink extension configuration
            default_session: laravel
            laravel: ~
    
    # Your test suites here
    suites:
        user:
            paths: [ '%paths.base%/path/to/your/features/tests/files' ]
            # The context needed by your features tests
            contexts: ~
```

Here, is where we reference the Laravel extension, and tell Behat to use it as our default session. You may pass an
optional parameter, `env_path` (currently commented out above) to specify the name of the environment file that should
be referenced from your tests. By default, it'll look for a `.env.behat` file.

This file should, like the standard `.env` file in your project root, contain any special environment variables
for your tests (such as a special acceptance test-specific database).

# 3. Setting up FeatureContext

Run, from the root of your app

~~~
vendor/bin/behat --init 
~~~

More documentation ...

## FAQ

### I'm getting a "PHP Fatal error: Maximum function nesting level of '100' reached, aborting!" error.

Sounds like you're using Xdebug. [Increase the max nesting level](http://xdebug.org/docs/all_settings#max_nesting_level)
.

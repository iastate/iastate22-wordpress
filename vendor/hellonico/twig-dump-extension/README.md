# Twig Dump Extension

Standalone Symfony Var Dumper Twig extension.

## Installation

```bash
composer require hellonico/twig-dump-extension
```

## Usage

```php
$twig = new Twig_Environment($loader, $options);
$twig->addExtension(new HelloNico\Twig\DumpExtension());
```

In Twig templates:

```twig
{{ dump(foo) }}
{% dump foo %}
{% dump foo, bar %}
```

# Extra

Set some dumper options with the `hellonico/var-dumper-configurator` package:

```bash
composer req hellonico/var-dumper-configurator --dev
```

See https://github.com/nlemoine/var-dumper-configurator for configuration.

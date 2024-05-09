<?php

namespace HelloNico\Twig;

use HelloNico\Twig\DumpTokenParser;
use Symfony\Component\VarDumper\Cloner\ClonerInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;
use Twig\TwigFunction;
use HelloNico\VarDumperConfigurator\VarDumperConfigurator;

/**
 * Provides integration of the dump() function with Twig.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class DumpExtension extends AbstractExtension
{
    private $cloner;
    private $dumper;

    public function __construct(?ClonerInterface $cloner = null, ?HtmlDumper $dumper = null)
    {
        $this->cloner = $cloner ?? new VarCloner();
        if (!$dumper && class_exists(VarDumperConfigurator::class)) {
            $this->dumper = VarDumperConfigurator::getDumper();
        }
        if (!$this->dumper) {
            $this->dumper = new HtmlDumper();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('dump', [$this, 'dump'], ['is_safe' => ['html'], 'needs_context' => true, 'needs_environment' => true]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers(): array
    {
        return [new DumpTokenParser()];
    }

    public function dump(Environment $env, array $context): ?string
    {
        if (!$env->isDebug()) {
            return null;
        }

        if (2 === \func_num_args()) {
            $vars = [];
            foreach ($context as $key => $value) {
                if (!$value instanceof Template) {
                    $vars[$key] = $value;
                }
            }

            $vars = [$vars];
        } else {
            $vars = \func_get_args();
            unset($vars[0], $vars[1]);
        }

        $dump = fopen('php://memory', 'r+');
        $this->dumper = $this->dumper ?? new HtmlDumper();
        $this->dumper->setCharset($env->getCharset());

        foreach ($vars as $value) {
            $this->dumper->dump($this->cloner->cloneVar($value), $dump);
        }

        return stream_get_contents($dump, -1, 0);
    }
}

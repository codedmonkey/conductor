<?php

namespace CodedMonkey\Conductor\Composer;

use CodedMonkey\Conductor\Doctrine\Entity\Package;
use CodedMonkey\Conductor\Doctrine\Entity\Registry;
use Composer\Config;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Repository\ComposerRepository;
use Composer\Repository\RepositoryInterface;
use Composer\Repository\VcsRepository;
use Composer\Util\HttpDownloader;

class ComposerClient
{
    public function createComposerRepository(Package|Registry $registry, ?IOInterface $io = null, ?Config $config = null): RepositoryInterface
    {
        $registry = $registry instanceof Package ? $registry->getMirrorRegistry() : $registry;

        $config ??= ConfigFactory::createForRegistry($registry);
        if (!$io) {
            $io = new NullIO();
            $io->loadConfiguration($config);
        }
        $httpDownloader = $this->createHttpDownloader($io, $config);

        return new ComposerRepository(['url' => $registry->getUrl()], $io, $config, $httpDownloader);
    }

    public function createVcsRepository(Package $package, ?IOInterface $io = null, ?Config $config = null): RepositoryInterface
    {
        $repoUrl = $package->getRepositoryUrl();

        $config ??= ConfigFactory::createForVcsRepository($repoUrl);
        if (!$io) {
            $io = new NullIO();
            $io->loadConfiguration($config);
        }
        $httpDownloader = $this->createHttpDownloader($io, $config);

        return new VcsRepository(['url' => $repoUrl], $io, $config, $httpDownloader);
    }

    public function createHttpDownloader(?IOInterface $io = null, ?Config $config = null): HttpDownloader
    {
        $config ??= Factory::createConfig();
        if (!$io) {
            $io = new NullIO();
            $io->loadConfiguration($config);
        }

        return new HttpDownloader($io, $config, self::getHttpDownloaderOptions());
    }

    public static function getHttpDownloaderOptions(): array
    {
        $options['http']['header'][] = 'User-Agent: Conductor (https://github.com/codedmonkey/conductor)';
        $options['max_file_size'] = 128_000_000;

        return $options;
    }
}

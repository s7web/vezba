<?php
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once 'config/site_config.php';
require_once SITE_PATH .'/vendor/autoload.php';

$console = new Application();

$console
    ->register('assets')
    ->setDescription('Install assets from src to public')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $s7dir = __DIR__ . '/../src/s7designcreative';
        $public = __DIR__ . '/../public';
        $packages = scandir($s7dir);
        $packages = array_diff($packages, array('.', '..'));
        shell_exec("rm -rf $public/s7designcreative/");
        mkdir($public . '/s7designcreative');
        foreach($packages as $package){
            shell_exec("cp -r $s7dir/$package/public $public/s7designcreative/$package");
            $output->writeln(sprintf('Package <info>%s</info> assets installed', $package));
        }
    });

// TODO this command is for crawler project and should be moved to it after logic for registering commands in project have been implemented
$console
    ->register('crawl')
    ->setDescription('Crawl site from database.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        require_once 'init.php';
        $app = new App();
        $crawl = new \s7designcreative\crawler\Command\CrawlSite();
        $crawl->run($app->entityManager);
    });

$console->run();
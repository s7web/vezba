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
        $S7Dir = __DIR__ . '/../src/S7D/APP';
        $public = __DIR__ . '/../public';
        $packages = scandir($S7Dir);
        $packages = array_diff($packages, array('.', '..'));
        shell_exec("rm -rf $public/S7Designcreative/");
        mkdir($public . '/S7Designcreative');
        foreach($packages as $package){
            shell_exec("ln -s $S7Dir/$package/public $public/S7Designcreative/$package");
            $output->writeln(sprintf('Package <info>%s</info> assets installed', $package));
        }
    });

// TODO this command is for crawler project and should be moved to it after logic for registering commands in project have been implemented
$console
    ->register('crawl')
    ->setDescription('Crawl site from database.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        require_once APP_PATH . 'core/App.php';
        $app = new App();
        $crawl = new \S7D\App\Dibz\Command\CrawlSite();
        $crawl->run($app->entityManager);
    });

$console
    ->register('google')
    ->setDescription('Get Google results from term(s).')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        require_once APP_PATH . 'core/App.php';
        $app = new App();
        $google = new \S7D\App\Dibz\Command\Google();
        $google->run($app->entityManager);
    });

$console->run();
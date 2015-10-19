<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use S7D\Core\Routing\Application as App;

$console = new Application();
$app = new App(__DIR__ . '/..');

$console
    ->register('assets')
    ->setDescription('Install assets from src to public')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $S7Dir = __DIR__ . '/../src/S7D/App';
        $public = __DIR__ . '/../public';
        $packages = scandir($S7Dir);
        $packages = array_diff($packages, array('.', '..'));
        shell_exec("rm -rf $public/s7d/");
        mkdir($public . '/s7d');
        foreach($packages as $package){
            shell_exec("ln -s $S7Dir/$package/public $public/s7d/$package");
            $output->writeln(sprintf('Package <info>%s</info> assets installed', $package));
        }
    }
);

foreach($app->parameters->get('commands') as $command => $arr) {
	$console
		->register($command)
		->setDescription($arr['description'])
		->setCode(function (InputInterface $input, OutputInterface $output) use ($app, $arr) {
			$c = new $arr['class'];
			$c->run($app->em, $app->parameters, $app->root . '/log');
		}
	);
}

$console->run();
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
/** @var \Doctrine\ORM\EntityManager $em */
$em = $app->container->em;
$console
    ->register('insert:user')
    ->setDescription('Insert admin user admin@admin.com:admin123')
    ->setCode(function (InputInterface $input, OutputInterface $output) use($em) {

		$adminRole = new \S7D\Core\Auth\Entity\Role();
		$adminRole->name = 'ADMIN';
		$em->persist($adminRole);
		$role = new \S7D\Core\Auth\Entity\Role();
		$role->name = 'USER';
		$em->persist($role);
		$em->flush();

		/** @var \S7D\Core\Auth\Repository\UserRepository $userRepo */
        $userRepo = $em->getRepository('S7D\Core\Auth\Entity\User');
		$userRepo->insert('admin@admin.com', 'admin123', [$adminRole], [], 1);
    }
);

$console
    ->register('insert:blogData')
    ->setDescription('Insert blog data.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use($em) {

		$postStatus = new \S7D\Vendor\Blog\Entity\PostStatus();
		$postStatus->setName('public');
		$em->persist($postStatus);
		$postStatus = new \S7D\Vendor\Blog\Entity\PostStatus();
		$postStatus->setName('private');
		$em->persist($postStatus);
		$em->flush();
    }
);

$console
    ->register('update:blogData')
    ->setDescription('Insert blog data.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use($em) {

		/** @var \S7D\Vendor\Blog\Entity\Post[] $posts */
		$posts = $em->getRepository('S7D\Vendor\Blog\Entity\Post')->findAll();
		foreach($posts as $post) {
			$slugger = new \S7D\App\News\Utils\Slugger();
			$post->setSlug($slugger->slugify($post->getTitle()));
			$em->persist($post);
		}
		$em->flush();
    }
);

$console
    ->register('update:blog:category:slug')
    ->setDescription('Update categories slug.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use($em) {

		/** @var \S7D\Vendor\Blog\Entity\Category[] $categories */
		$categories = $em->getRepository('S7D\Vendor\Blog\Entity\Category')->findAll();
		foreach($categories as $category) {
			$slugger = new \S7D\App\News\Utils\Slugger();
			$category->setSlug($slugger->slugify($category->getName()));
			$em->persist($category);
		}
		$em->flush();
    }
);

$console
    ->register('insert:siteOption')
    ->setDescription('Insert site option.')
	->addArgument('key', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'key')
	->addArgument('value', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'value')
    ->setCode(function (InputInterface $input, OutputInterface $output) use($em) {

		/** @var \S7D\Core\Helpers\Repository\SiteOptionRepository $optionRepo */
		$optionRepo =  $em->getRepository('S7D\Core\Helpers\Entity\SiteOption');
		$optionRepo->set($input->getArgument('key'), $input->getArgument('value'));
    }
);

$finder = new \Symfony\Component\Finder\Finder();
$finder->files()->name('*Command.php')->in(__DIR__ . '/../src/S7D/App/' . $app->parameters->get('app'));

$ns = 'S7D\App\\' . $app->parameters->get('app');

foreach($finder as $file) {
	$class = $ns . '\Command\\' . $file->getBasename('.php');
	$r = new \ReflectionClass($class);
	$console->add($r->newInstance($app->container->em));
}

$helperSet = \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);
$console->setHelperSet($helperSet);
\Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($console);

$console->run();

<?php
namespace S7D\Vendor\Blog\Twig;

class TextTransition extends \Twig_Extension {

	public $sr = [
		'cyrillic' => [
			'А','Б','В','В','Y','Г','Ђ','Џ','Џ','Д','Ж','Е','З','Њ','Њ','Љ','Љ',
			'И','Ј','К','Л','М','Н','О','П','Р','С','Т','Ћ','У','Ф','Х','Ц','Ч','Ш',
			'а','б','в','в','y','г','ђ','џ','д','ж','е','з','њ','љ',
			'и','ј','к','л','м','н','о','п','р','с','т','ћ','у','ф','х','ц','ч','ш'
		],
		'latin' => [
			'A','B','V','W','Y','G','Đ','DŽ','Dž','D','Ž','E','Z','NJ','Nj','LJ','Lj',
			'I','J','K','L','M','N','O','P','R','S','T','Ć','U','F','H','C','Č','Š',
			'a','b','v','w','y','g','đ','dž','d','ž','e','z','nj','lj',
			'i','j','k','l','m','n','o','p','r','s','t','ć','u','f','h','c','č','š'
		],
	];

	private $textScript;

	function __construct( $textScript ) {
		$this->textScript = $textScript;
	}


	public function getFilters()
	{
		return [
			'transit' => new \Twig_SimpleFilter( 'transit', [$this, 'transit'] ),
		];
	}

	public function transit( $string )
	{
		$search = $this->sr['cyrillic'];
		$replacement = $this->sr['latin'];
		if($this->textScript === 'cyrillic') {
			$search = $this->sr['latin'];
			$replacement = $this->sr['cyrillic'];
		}
		return str_replace($search, $replacement, $string);
	}

	public function getName() {
		return 'textTransition';
	}

}

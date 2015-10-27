<?php
namespace S7D\Core\Helpers;


/**
 * Class LanguageExtension
 * @package Helpers
 *
 * @version 4-1-2015
 * @author  S7Designcreative
 */
class LanguageExtension extends \Twig_Extension
{
    private $translations;

    /**
     * Set up params
     */
    public function __construct(Parameter $translations) {

		$this->translations = $translations;
    }

    /**
     * Get twig filters
     * @return array
     */
    public function getFilters()
    {
        return [
            'trans' => new \Twig_SimpleFilter( 'trans', [$this, 'trans'] ),
        ];
    }

    /**
     * Execute filter
     *
     * @param $term
     *
     * @return string
     */
    public function trans( $term )
    {
        return $this->translations->get($term, $term);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'lang';
    }
}

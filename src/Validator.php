<?php
namespace Germey\Support;

use Illuminate\Contracts\Container\Container;
use Illuminate\Validation\Factory;
use Slim\Http\Response;
use Symfony\Component\Translation\TranslatorInterface;


class Validator extends Factory
{
	/**
	 * Validator constructor.
	 *
	 * @param TranslatorInterface $translator
	 * @param Container|null $container
	 */
	public function __construct(TranslatorInterface $translator, Container $container = null)
	{
		parent::__construct($translator, $container);
	}

	/**
	 * @param $array
	 * @param $rules
	 * @param $attributes
	 * @param Response $response
	 * @return static
	 */
	public function attempt($array, $rules, $attributes, Response $response)
	{
		$validator = $this->make($array, $rules, [], $attributes);
		if ($validator->fails()) {
			return $response->withJson($validator->errors(), 401)->withHeader('Access-Control-Allow-Origin', '*');;
		}
		return false;
	}
}

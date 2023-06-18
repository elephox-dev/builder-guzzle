<?php
declare(strict_types=1);

namespace Elephox\Builder\Guzzle;

use Elephox\DI\Contract\ServiceCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

trait AddsGuzzle {
	abstract public function getServices(): ServiceCollection;

	public function addGuzzle(
		bool $registerPsr18client = true,
		bool $registerPsr17requestFactory = true,
		bool $registerPsr17serverRequestFactory = true,
		bool $registerPsr17streamFactory = true,
		bool $registerPsr17uploadedFileFactory = true,
		bool $registerPsr17uriFactory = true
	): void {
		$this->getServices()->addSingleton(Client::class, Client::class);
		if ($registerPsr18client) {
			$this->getServices()->addSingleton(ClientInterface::class, factory: fn (Client $client) => $client);
		}

		if (
			!$registerPsr17requestFactory &&
			!$registerPsr17serverRequestFactory &&
			!$registerPsr17streamFactory &&
			!$registerPsr17uploadedFileFactory &&
			!$registerPsr17uriFactory
		) {
			return;
		}

		$this->getServices()->addSingleton(HttpFactory::class, HttpFactory::class);

		if ($registerPsr17requestFactory) {
			$this->getServices()->addSingleton(RequestFactoryInterface::class, factory: fn (HttpFactory $httpFactory) => $httpFactory);
		}

		if ($registerPsr17serverRequestFactory) {
			$this->getServices()->addSingleton(ServerRequestFactoryInterface::class, factory: fn (HttpFactory $httpFactory) => $httpFactory);
		}

		if ($registerPsr17streamFactory) {
			$this->getServices()->addSingleton(StreamFactoryInterface::class, factory: fn (HttpFactory $httpFactory) => $httpFactory);
		}

		if ($registerPsr17uploadedFileFactory) {
			$this->getServices()->addSingleton(UploadedFileFactoryInterface::class, factory: fn (HttpFactory $httpFactory) => $httpFactory);
		}

		if ($registerPsr17uriFactory) {
			$this->getServices()->addSingleton(UriFactoryInterface::class, factory: fn (HttpFactory $httpFactory) => $httpFactory);
		}
	}
}

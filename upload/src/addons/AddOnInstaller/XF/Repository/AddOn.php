<?php

namespace AddOnInstaller\XF\Repository;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Extends \XF\Repository\AddOn
 */
class AddOn extends XFCP_AddOn
{
	public function checkForUpdate(\XF\AddOn\AddOn $addOn)
	{
		// todo: tighten this up to check for an xf.com resource url across entire json instead
		$supportUrl = $addOn->getJson()['support_url'] ?? null;

		if (empty($supportUrl))
		{
			return false;
		}

		try
		{
			$client = $this->app()->http()->client();

			$response = $client->get($supportUrl, [
				'headers' => [
					'User-Agent' => 'AddOnInstaller_11388'
				]
			])->getBody()->getContents();

			$crawler = new Crawler($response);
			$version = $crawler->filter('h1 .u-muted');

			if (!$version->count())
			{
				return false;
			}

			$versionText = $version->extract(['_text'])[0];

			return $this->versionRequiresUpdate($versionText, $addOn->json_version_string);
		}
		catch (\Exception $e)
		{
			\XF::logException($e, false, 'AddOn Install & Upgrade error: ');
			return true;
		}
	}

	public function versionRequiresUpdate($version1, $version2)
	{
		if ($version1 == $version2)
		{
			return false;
		}

		if (!\XF::options()->addoninstaller_exact_check)
		{
			$version1 = preg_replace('/\s+/u', ' ', utf8_strtolower($version1));
			$version2 = preg_replace('/\s+/u', ' ', utf8_strtolower($version2));
			if ($version1 == $version2)
			{
				return false;
			}
			return version_compare($version1, $version2) > 0;
		}

		return true;
	}
}
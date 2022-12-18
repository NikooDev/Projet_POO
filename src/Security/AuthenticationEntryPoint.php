<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Classe permettant de transmettre un message flash en cas de tentative d'accès à une page protégée
 * Retourne une réponse HTTP effectuant une redirection
 * @return RedirectResponse
 */
class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
	private $urlGenerator;

	public function __construct(UrlGeneratorInterface $urlGenerator)
	{
		$this->urlGenerator = $urlGenerator;
	}

	public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
	{
		$request->getSession()->getFlashBag()->add('error', 'Vous devez être connecté pour accéder à cette page');

		return new RedirectResponse($this->urlGenerator->generate('auth_login'));
	}
}
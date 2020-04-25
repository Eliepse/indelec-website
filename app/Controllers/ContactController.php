<?php

namespace App\Controllers;

use App\Mails\ContactFromVisitorMail;
use App\RedirectResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\SendmailTransport;

class ContactController
{
	/**
	 * @param Request $request
	 *
	 * @return Response
	 * @throws TransportExceptionInterface
	 * @noinspection PhpUnused
	 */
	public function sendMail(Request $request): Response
	{
		$transport = new SendmailTransport(env("SENDMAIL_PATH") . " -bs");
		$mailer = new Mailer($transport);
		$mail = new ContactFromVisitorMail($request->getParsedBody());
		$mailer->send($mail);
		return new RedirectResponse("/message-sent");
	}


	/**
	 * @return Response
	 * @noinspection PhpUnused
	 */
	public function showSuccess(): Response
	{
		return view("contact-thanks", ["page" => null]);
	}
}
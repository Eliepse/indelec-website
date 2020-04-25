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
	private Request $request;


	public function __construct(Request $request)
	{
		$this->request = $request;
	}


	/**
	 * @return Response
	 * @throws TransportExceptionInterface
	 * @noinspection PhpUnused
	 */
	public function sendMail(): Response
	{
		$transport = new SendmailTransport(env("SENDMAIL_PATH") . " -bs");
		$mailer = new Mailer($transport);
		$mail = new ContactFromVisitorMail($this->request->getParsedBody());
		$mailer->send($mail);
		return new RedirectResponse("/message-sent");
	}


	/**
	 * @return Response
	 * @noinspection PhpUnused
	 */
	public function showSuccess(): Response
	{
		return view("contact-thanks", [
			"name" => env("META_TITLE"),
			"page" => null,
		]);
	}
}
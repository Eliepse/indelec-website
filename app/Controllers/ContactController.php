<?php

namespace App\Controllers;

use App\Mails\ContactFromVisitorMail;
use App\RedirectResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

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
		$transport = $this->getTransport();
		$mailer = new Mailer($transport);
		$mailer->send(new ContactFromVisitorMail($request->getParsedBody()));
		return new RedirectResponse("/message-sent");
	}


	/**
	 * @return AbstractTransport
	 * @throws \ErrorException
	 */
	private function getTransport(): AbstractTransport
	{
		if ("sendmail" === env("MAIL_DRIVER")) {
			return new SendmailTransport(env("SENDMAIL_PATH") . " -bs");
		}

		if ("smtp" === env("MAIL_DRIVER")) {
			$transport = new EsmtpTransport(env("MAIL_SERVER"), intval(env("MAIL_PORT")), env("MAIL_TLS"));
			$transport->setUsername(env("MAIL_USERNAME", ""));
			$transport->setPassword(env("MAIL_PASSWORD", ""));
			return $transport;
		}
		throw new \ErrorException("Mail driver (MAIL_DRIVER in .env) not specified correctly or unsupported");
	}


	/**
	 * @return Response
	 * @noinspection PhpUnused
	 */
	public function showSuccess(): Response
	{
		return view("contact-thanks", ["pageTitle" => " | Message envoyé", "page" => null]);
	}
}
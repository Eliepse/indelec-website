<?php

namespace App\Mails;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ContactFromVisitorMail extends Email
{
	private array $data = [];


	public function __construct(array $data)
	{
		parent::__construct(null, null);

		$this->data = $data;

		$this->priority(Email::PRIORITY_NORMAL);
		$this->from(new Address(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME")));
		$this->replyTo($this->data["email"]);
		$this->to(env("CONTACT_TARGET_MAIL"));
		$this->subject(env("MAIL_SUBJECTS_PREFFIX") . "Nouveau message d'un visiteur");

		$this->data = array_merge($data, [
			"created_at" => date("d/m/Y à H:i"),
			"subject" => $this->getSubject(),
		]);

		$this->text((string)view("mails/contactFromVisitor-txt", $this->data)->getBody());
		$this->html((string)view("mails/contactFromVisitor-html", $this->data)->getBody());
	}
}
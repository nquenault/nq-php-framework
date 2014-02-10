<?php if(!defined('BASEPATH')) die('No direct script access allowed');

class hMailServer
{
	private $com; // COM Instance
	private $authDone; // Authenticated flag

	public function __construct($login, $passwd)
	{
		$this->com = new COM('hMailServer.Application');
		$this->authDone = $this->com->Authenticate($login, $passwd);
	}

	public function getDomain($domain)
	{
		$domains = $this->com->Domains;
		return $domains->ItemByName($domain);
	}

	public function createAccount($domain, $username, $password, $active = true, $size = 10)
	{
		try
		{
			$obj = $this->getDomain($domain)->Accounts->Add();
			$obj->Address = $username.'@'.$domain;
			$obj->Password = $password;
			$obj->Active = $active;
			$obj->MaxSize = $size;
			$obj->Save();

			return true;
		}
		catch(Exception $ex) { return false; }
	}

	public function getAccount($domain, $username)
	{
		
		try
		{
			$obj = $this->getDomain($domain)->Accounts;
			return $obj->ItemByAddress($username.'@'.$domain);
		}
		catch(Exception $ex) { return null; }
	}

	public function accountExists($domain, $username)
	{
		return $this->getAccount($domain, $username) !== null;
	}

	public function deleteAccount($domain, $username)
	{
		if(!$this->accountExists($domain, $username))
			return true;

		try
		{
			$obj = $this->getAccount($domain, $username);
			$obj->Delete();
			return true;
		}
		catch(Exception $ex) { return false; }
	}

	public function createRedirection($domain, $username, $to, $active = true)
	{
		try
		{
			$obj = $this->getDomain($domain)->Aliases->Add();
			$obj->Name = $username.'@'.$domain;
			$obj->Value = $to;
			$obj->Active = $active;
			$obj->Save();

			return true;
		}
		catch(Exception $ex) { return false; }
	}

	public function getRedirection($domain, $username)
	{
		try
		{
			$obj = $this->getDomain($domain)->Aliases;
			return $obj->ItemByName($username.'@'.$domain);
		}
		catch(Exception $ex) { return null; }
	}

	public function redirectionExists($domain, $username)
	{
		return $this->getRedirection($domain, $username) !== null;
	}

	public function deleteRedirection($domain, $username)
	{
		if(!$this->redirectionExists($domain, $username))
			return true;

		try
		{
			$obj = $this->getRedirection($domain, $username);
			$obj->Delete();
			return true;
		}
		catch(Exception $ex) { return false; }
	}

	public function send($fromAddress, $toAddress, $subject, $message)
	{
		try
		{
			$obj = new COM('hMailServer.Message');
			$obj->FromAddress = $fromAddress;
			$obj->AddRecipient($toAddress, $toAddress);
			$obj->Subject = utf8_decode($subject);
			//$obj->Body = utf8_decode($message);
			$obj->HTMLBody = utf8_decode($message);
			$obj->From = $fromAddress;
			$obj->Save();

			return true;
		}
		catch(Exception $ex) { return false; }
	}
}

?>
<?php
/**
 *
 * Plugin developpe pour l agglomeration de St Lo
 *
 * @version 1.0
 * @author 
 * @url 
 */
class mail_skin extends rcube_plugin
{
  public $task = 'mail|settings';
  public $link_url = 'http://support-info.saint-lo.fr/logos/agglostlo/style_signature.css';
  public $style_content_file = '/var/www/html/webmail/plugins/mail_skin/skins/larry/mail_skin.css';
  public $style_content = '';
  private $map;

	function init()
	{
		$this->add_hook('message_outgoing_body', array($this, 'replace_body'));
		$this->add_hook('identity_create', array($this, 'replace_identity_create'));
		$this->add_hook('identity_update', array($this, 'replace_identity_create'));

		$rcmail = rcmail::get_instance();

		if ($rcmail->task == 'mail')
		{
		if ($rcmail->action == 'compose')
		{
			$skin_path = $this->local_skin_path();
			$this->include_script('mail_skin.js');

			if(is_file($this->home . "/$skin_path/mail_skin.css"))
			{
				$this->include_stylesheet("$skin_path/mail_skin.css");
			}
		}
		}

		$this->load_signature_css();

	}

	function load_signature_css()
	{
		$fh = fopen($this->style_content_file, 'r');

		if (! $fh)
		{
			$msg = 'Erreur a l ouverture de '.$this->style_content_file;
			write_log('errors', $msg);
		}
		else
		{
			while(!feof($fh))
			{
				if(($tmp_content = fread($fh, 1024)) != false)
				{
					$this->style_content .= $tmp_content;
				}
				else
				{
					$msg = 'Erreur a la lecture de '.$this->style_content_file;
					write_log('errors', $msg);
				}
			}

			fclose($fh);
		}


	}

	function replace_identity_create($args)
	{

		if($args['record']['html_signature'] == '1')
		{
			$cur_record = $args['record'];
			$cur_record['signature'] = '<style type="text/css">'.$this->style_content.$cur_signature.'</style><div id="signature_agglomeration">'.$args['record']['signature'].'</div>';
			return array('record' => $cur_record);
		}

		return null;
	}

	function replace_identity_update($args)
	{

		if($args['record']['html_signature'] == '1')
		{
			$cur_record = $args['record'];
			$cur_record['signature'] = '<style type="text/css">'.$this->style_content.$cur_signature.'</style><div id="signature_agglomeration">'.$args['record']['signature'].'</div>';
			return array('record' => $cur_record);
		}

		return null;
	}

	function replace_body($args)
	{
		$html_body = $args['body'];
		$doc = new DOMDocument();

		if (($args['type'] == 'html') && ($doc->loadHTML($html_body)))
		{
			if (($sig_agglomeration_element = $doc->getElementByID('signature_agglomeration')) != null)
			{
				$signature_agglomeration_parent = $sig_agglomeration_element->parentNode;

				$a_style_element = $doc->createElement('style', $this->style_content);
				$a_type_attr = new DOMAttr('type', 'text/css');
				$a_style_element->setAttributeNode($a_type_attr);

				$a_returned_style = $signature_agglomeration_parent->insertBefore($a_style_element, $sig_agglomeration_element);

				return array('body' => $doc->saveHTML());
			}
		}

		return null;
	}

}
